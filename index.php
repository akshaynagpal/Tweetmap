<?php
require 'getTweetsWithLocation.php';
$valid_keywords = ["love","work","food","travel","trump","dog"];
$arrayForJS = null;
$valid_keyword = false;
?>

<?php
    if(isset($_GET["keyword"])){
      $keyword = $_GET["keyword"];
      if(in_array($keyword, $valid_keywords)){
        $valid_keyword = true;
        $geoArray = getTweetsWithLocation($keyword);
        $arrayForJS = json_encode($geoArray); 
      }
    }
?>
<!DOCTYPE html>
<html>
<head>
	  <title>Tweetmap</title>
	  <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      html, body {
        height: 90%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
      /*#data{
        display: none;
      }*/
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript">
      function deleteMap(){
        var element = document.getElementById("map");
        element.parentNode.removeChild(element);
      }
      function addMap(){
        document.body.innerHTML += '<div id="map"></div>';
        var temp = '<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBi4tEkxYklnWaWHMlLXMHKL8lDpk5TjqY&libraries=visualization&callback=initMap" async defer><\/script>';
        $('head').append(temp);
      }
    </script>
    <script>
        <?php  echo "var geoArray =  ".$arrayForJS.";\n";   ?>
        window.initMap = function() {
            var locations = [];
            var geoArrayLength = geoArray.length;
            var marker;
            var map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: 0, lng: 0},
              zoom:2,
              draggable: true
            });

            for(var i=0;i<geoArrayLength;i++){
              marker = new google.maps.Marker({
              position: new google.maps.LatLng(geoArray[i]["lat"],geoArray[i]["long"]),
              map: map
              });
            }
          }
    </script>
    <script type="text/javascript">

        function updateMap(){
          geoArray =  JSON.parse(document.getElementById("data").innerHTML);
          document.getElementById("numtweets").innerHTML = geoArray.length;
          window.initMap = function()  {
            var locations = [];
            var geoArrayLength = geoArray.length;
            console.log(geoArrayLength);
            var marker;
            var map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: 0, lng: 0},
              zoom:2,
              draggable: true
            });

            for(var i=0;i<geoArrayLength;i++){
              marker = new google.maps.Marker({
              position: new google.maps.LatLng(geoArray[i]["lat"],geoArray[i]["long"]),
              map: map
              });
            }
          }
        }

    </script>

    <script>
        $(document).ready(function(){
            setInterval(function() {
                $("#data").load('realtime.php?keyword='+'<?php echo $keyword ?>');
                deleteMap();
                addMap();
                updateMap();
                console.log("refreshed");
            }, 7000);
        });
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBi4tEkxYklnWaWHMlLXMHKL8lDpk5TjqY&libraries=visualization&callback=initMap"
    async defer></script>
</head>
<body>

  <h1>Tweetmap</h1>
  <div id="data"></div>
  <form id="homeform" action="index.php">
  	<select name="keyword" form="homeform">
  		<option value="NULL">Select Keyword</option>
  		<option value="love">love</option>
  		<option value="work">work</option>
  		<option value="trump">trump</option>
  		<option value="food">food</option>
  		<option value="travel">travel</option>
      <option value="dog">dog</option>
  	</select>	
  	<input type="submit"> 
  </form>
  <?php 
    if($valid_keyword){
      echo "keyword selected: ".$keyword;
    }
    else{
      echo "No valid keyword recieved";
    }
  ?>
  <br>
  <div id = "numtweets"></div>
  <div id="map"></div>
</body>
</html>