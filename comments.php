<?php 
require('../vendor/autoload.php');
require('includes.php');
$s = $_SESSION['page_det'];
echo "<html><head><meta http-equiv='Content-Type'  content='text/html;charset=UTF-8'/></head><body>";

$fb = new Facebook\Facebook(array(
    'app_id' => $s['app_id'], 
    'app_secret' => $s['app_secret'],
    'default_app_version' => 'v2.6'
));

function getPost($postId, $fb, $s) {
    $response = $fb->get(
        "/$postId?fields=message,shares,permalink_url",
        $s['page_auth_token']
    );
    $data = $response->getDecodedBody();

    echo "<p><a href='posts.php?limit=10&offset=0'>All posts</a></p>";
    echo "<h3>Post Details &ensp;(# {$data['id']})</h3>";
        if (isset($data['permalink_url']))
        echo "<p><a target='_blank' href='{$data['permalink_url']}'>{$data['permalink_url']}</a></p>";
    if (isset($data['shares']))
        echo "<p>(Shared {$data['shares']['count']} times)</p>";
    echo "<p>{$data['message']}</p>";
}

try {
    $limit = (isset($_GET['limit']) ? $_GET['limit'] : 10); // default limit 250
    $offset = (isset($_GET['offset']) ? $_GET['offset'] : 0); // default offset is 0   
    $order = (isset($_GET['order']) ? $_GET['order'] : "reverse_chronological");
    $postId = $parentId = null;

    if (isset($_GET['post_id'])) { 
        $postId = $_GET['post_id'];
        getPost($postId, $fb, $s);
    }

    if (isset($_GET['see_replies'])) {
        $parentId = $_GET['see_replies'];
    } elseif ($postId) {
        $parentId = $postId;
    }
    $fields = "message,from,created_time,total_count";
    echo $req = "/$parentId/comments?order=$order&limit=$limit&offset=$offset&fields=$fields";
    $response = $fb->get(
        $req,
        $s['page_auth_token']
    );
    $data = $response->getDecodedBody();

    echo "<h3>Comments</h3>";
    if (isset($data['paging'])) {
        echo paging($data['paging'], $offset, $limit, $order, $parentId);
    }

    if (!isset($data['data'])) {
        die("No data");
    }

    $t = "<table border='1'>";
    $t .= "<tr><th width='35%'>Comment</th><th>Username</th><th>FB User ID</th><th>Profile link</th><th>Date</th><th>Time</th></tr>";
    foreach($data['data'] as $p) {
        $t .= "<tr>";
        $t .= "<td>{$p['message']} <br/><a target='_blank' href='?see_replies={$p['id']}'>See replies</a></td>";
        $t .= "<td>{$p['from']['name']}</td>";
        $t .= "<td>{$p['from']['id']}</td>";
        $t .= "<td><a target='_blank' href='http://facebook.com/{$p['from']['id']}'>http://facebook.com/{$p['from']['id']}</a></td>";

        $date = DateTime::createFromFormat('Y-m-d\TH:i:sT', $p['created_time']);
        $t .= "<td>{$date->format('d F y')}</td>";
        $t .= "<td>@{$date->format('H:i')}</td>";
        $t .= "</tr>";
    }
    echo $t .= "</table>";

} catch(Exception $e) {
    echo $e->getMessage();
    die();
}


function paging($paging, $offset, $limit, $order, $postId){
    $nextOffset = $previousOffset = 0;
    if ($offset > 0 && ($offset - $limit) > 0) {
        $previousOffset = $offset - $limit;
    }
    if ($offset >= 0) {
        $nextOffset = $offset + $limit;
    }
    $h = "<p style='background-color: #AEEA12'>";
    $h .= "Showing records ". ($offset + 1) ."-" . ($offset + $limit) ."<br/>";
    if (isset($paging['previous'])) 
        $h .= "<a href='?post_id={$_GET['post_id']}&order=$order&limit=$limit&offset=$previousOffset'><< Previous</a>";
    if (isset($paging['next'])) 
        $h .= "&ensp;<a href='?post_id={$_GET['post_id']}&order=$order&limit=$limit&offset=$nextOffset'>Next >></a>";
    $h .= "</p>";

    $limEl = "Showing <input type='text' size='3' maxLength='3' name='limit' value='$limit'/>comments per page ";
    $offEl = "starting from <input type='text' size='3' maxLength='3' name='offset' value='$offset'/>th comment ";
    $ordEl = "in <select name='order'>";
    $ordEl .= "<option value=''>None</option>";
    $ordEl .= "<option value='reverse_chronological'" . ($order == 'reverse_chronological' ? 'SELECTED' : '') . ">Reverse Chronological</option>";
    $ordEl .= "<option value='chronological'" . ($order == 'chronological' ? 'SELECTED' : '') . ">Chronological</option>";
    $submit = "&ensp;<input type='submit' value='Reset'/>";

    $h .= "<form method='GET'>";
    $h .= "<input type='hidden' name='post_id' value='$postId'/>";
    $h .= $limEl . $offEl . $ordEl . $submit;
    $h .= "</form>";
    return $h;
}

echo "</body></html>";
