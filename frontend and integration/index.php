<?php
require 'getTweetsWithLocation.php';
$valid_keywords = ["love","work","food","travel","trump","dog"];  //set of valid keywords
$arrayForJS = null;
$valid_keyword = false;

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
	  <title>Tweet Map </title>
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
      #data{
        display: none;
      }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX&libraries=visualization&callback=initMap" async defer
    ></script>
    <script type="text/javascript">

    </script>
    <script>
        var heatmap,map;
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        var markers = [], markerCluster;
        <?php  echo "var geoArray =  ".$arrayForJS.";\n";   ?>
        window.initMap = function() {
            var locations = [];
            var geoArrayLength = geoArray.length;
            var marker;
            map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: 0, lng: 0},
              zoom:2,
              draggable: true,
              mapTypeId: 'satellite'
            });
            google.maps.event.addListener(map, 'click', function(e){
              var location = e.latLng;
              console.log("user clicked at (latitude,longitude) => ",location.lat(),location.lng());
            });

            for(var i=0;i<geoArrayLength;i++){
              locations.push(new google.maps.LatLng(geoArray[i]["lat"],geoArray[i]["long"]));
            }
            markers = locations.map(function(location, i) {
                  return new google.maps.Marker({
                    position: location,
                    label: labels[i % labels.length]
                  });
            });
            markerCluster = new MarkerClusterer(map, markers,
              {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
            }


        // update map function to plot new marks based on new incoming tweets
        function setMapOnAll(map) {
          for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
          }
        }

        function clearMarkers() {
          setMapOnAll(null);
        }

        function deleteMarkers() {
          clearMarkers();
          markers = [];
        }

        function updateMap(){
          var geoArrayNew = "";
          var geoArrayNewLength = 0;
          var locations2 = [];
          var req = new XMLHttpRequest(); //New request object
          req.onload = function() {
              geoArrayNew = JSON.parse(this.responseText); 
          };
          req.open("get","realtime.php?keyword="+'<?php echo $keyword ?>',false);
          req.send(); 
          geoArrayNewLength = geoArrayNew.length;
          console.log("tweets indexed (max 10K) = "+geoArrayNewLength);
          deleteMarkers();
          //remove clusters
          markerCluster.clearMarkers();
          

          for(var i=0;i<geoArrayNewLength;i++){
              locations2.push(new google.maps.LatLng(geoArrayNew[i]["lat"],geoArrayNew[i]["long"]));
          }
          markers = locations2.map(function(location, i) {
                  return new google.maps.Marker({
                    position: location,
                    label: labels[i % labels.length]
                  });
          });
          markerCluster = new MarkerClusterer(map, markers,
              {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
        }
    </script>

    <script>

    // updates tweets in real time every 60 seconds

        $(document).ready(function(){
            setInterval(function() {
                $("#data").load('realtime.php?keyword='+'<?php echo $keyword ?>');
                
                updateMap();
                // console.log("refreshed");
            }, 10000);
        });
    </script>

    
</head>
<body>

  <h1>Tweetmap (Tweets refresh every 60 seconds)</h1>
  <div id="data" style="display: none;"></div>

  <!-- dropdown menu -->
  
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

  <!-- end of Dropdown menu -->
  
  <?php 
    if($valid_keyword){
      echo "keyword selected: ".$keyword;
    }
    else{
      echo "No valid keyword recieved";
    }
  ?>
  <br>
  <div id="map"></div>
</body>
</html>