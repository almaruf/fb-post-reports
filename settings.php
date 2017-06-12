<?php
session_start();
if (isset($_GET['page_id']) && $_GET['page_id'] != ''){
    $_SESSION['page_id'] = $_GET['page_id'];
    echo "<div style='color:red;'>Page ID is set.</div>";
}
if (isset($_GET['page_auth_token']) && $_GET['page_auth_token'] != ''){
    $_SESSION['page_auth_token'] = $_GET['page_auth_token'];
    echo "<div style='color:red;'>Page auth token is set.</div>";
}
if (isset($_GET['app_id']) && $_GET['app_id'] != ''){
    $_SESSION['app_id'] = $_GET['app_id'];
    echo "<div style='color:red;'>App ID is set.</div>";
}
if (isset($_GET['app_secret']) && $_GET['app_secret'] != ''){
    $_SESSION['app_secret'] = $_GET['app_secret'];
    echo "<div style='color:red;'>App secret is set.</div>";
    echo "<a href='index.php'>Go back to reporting home</a><br/>";
}

if (isset($_GET['clear'])) {
    $_SESSION['page_det'] = array();
    echo "<a href='index.php'>Go back to reporting home to start reporting with 'Purple Nija'</a><br/>";
} else {
    echo "<a href='?clear=t'>Clear the settings to use the default 'Purple Nija' details</a><br/>";
    echo "<a href='index.php'>Go back to reporting home to start reporting</a><br/>";
}

echo "<html><body>";
echo "<style>.f-label{ width: 100px; }</style>";

echo "<h4>Fill in the following info to start your session</h4>";
echo "<p>By default it will start using 'Purple Nija' page details and 'I Am Purple' FB app details";
echo "<form method='GET'>";
echo "<div><span class='f-label'>App ID</span><span><input type='text' size='20' name='app_id'/></span></div>";
echo "<div><span class='f-label'>App Secret</span><span><input type='text' size='20' name='app_secret'/></span></div>";
echo "<br/>";
echo "<div><span class='f-label'>Page ID<span></span><input type='text' size='20' name='page_id'/></span></div>";
echo "<div><span class='f-label'>Page Auth Token</span><span><input type='text' size='80' name='page_auth_token'/></span></div>";
echo "<input type='submit' name='submit' value='Start your session'>";
echo "</form>";
echo "</body></html>";
