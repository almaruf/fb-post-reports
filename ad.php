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

try {
    $limit = (isset($_GET['limit']) ? $_GET['limit'] : 10); // default limit 10 for posts
    $offset = (isset($_GET['offset']) ? $_GET['offset'] : 0); // default offset is 0 

    echo "<h3>Posts for page</h3>";
    $response = $fb->get(
        "{$s['page_id']}/feed?fields=admin_creator,message,created_time,status_type&limit=$limit",
        $s['page_auth_token']
    );

    $data = $response->getDecodedBody();

    if (!isset($data['data'])) {
        echo "No admin post";
        die();
    }

    $limEl = "Showing latest <input type='text' size='3' maxLength='3' name='limit' value='$limit'/> posts";
    $submit = "<input type='submit' label='Reset'/>";

    $h = "<form method='GET'>";
    $h .= $limEl . $submit;
    echo $h .= "</form>";

    foreach($data['data'] as $p) {
        echo "Created on: {$p['created_time']} ({$p['status_type']}) </br>";
        echo "Post: " . (isset($p['message']) ? substr($p['message'], 0, 100) : 'NO TEXT'). " ...</br>";
        if (isset($p['admin_creator'])) {
            echo "<a href='comments.php?post_id={$p['id']}'>See comments</a>";
        }
        echo "<hr/>";
    }

    echo $h;
} catch(Exception $e) {
    echo $e->getMessage();
    die();
}
echo "</body></html>";
