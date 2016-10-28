<?php
// Code to extract tweets in real-time which calls the getTweetsWithLocation.php file
require 'getTweetsWithLocation.php';
$valid_keywords = ["love","work","food","travel","trump","dog"];
$arrayForJS = "";
if(isset($_GET["keyword"])){
    $keyword = $_GET["keyword"];
    if(in_array($keyword, $valid_keywords)){
        $valid_keyword = true;
        $geoArray = getTweetsWithLocation($keyword);
        $arrayForJS = json_encode($geoArray);
    }
}
echo $arrayForJS;
?>