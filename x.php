<html>
<head>
    <meta charset="UTF-8">
</head>
<?php

$servername = "localhost";
$username = "mo";
$password = "hello321#";

$conn = new mysqli($servername, $username, $password, 'ezy_sondhani');

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
} 

$s = "Select b as Make, c as Model from x group by c, b order by b, c";
$res = $conn->query($s);

$ar = array();
$lastM = 0;
$i = '';

echo "array(<br/>";
while($row = mysqli_fetch_assoc($res)) {
    $make = $row['Make'];
    $model = $row['Model'];
    $b = "&nbsp;&nbsp;&nbsp;&nbsp;";

    if ($lastM != $make) { 
        echo "<br/>$b$b),"; 
        echo "<br/>$b),<br/>"; 
    }
    if (!isset($arr[ $make ])) {
        echo "$b'$make' => array(<br/>";
        $i++;
        echo "$b$b'id' => '".strtolower($make)."',<br/>";
        echo "$b$b'name' => '$make',<br/>";
        echo "$b$b'name_bn' => '$make',<br/>";
        echo "$b$b'show' => false,<br/>";
        echo "$b$b'models' => array(";
    }
    $arr[ $make ] [] = $model;
    echo "<br/>$b$b$b'$model', ";
    
    $lastM = $make;
}
echo ");";
?>
</html>
