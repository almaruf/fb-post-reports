<?php 
$relUrlLog = "/var/www/dev/iampurple.urls";

$handle = fopen($relUrlLog, "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        echo $line;
    }
} else {
    echo "file read error";
} 

/*
$p = xml_parser_create();
xml_parse_into_struct($p, $simple, $vals, $index);
xml_parser_free($p);
echo "Index array\n";
print_r($index);
echo "\nVals array\n";
print_r($vals);
 */
