// updates heatmap with new tweet locations in real time
var geoArray = document.getElementById("data").innerHTML;
window.initMap = function()  {
        var locations = [];
        var geoArrayLength = geoArray.length;
        var marker;
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 0, lng: 0},
          zoom:2,
          draggable: false
        });

        for(var i=0;i<geoArrayLength;i++){
          marker = new google.maps.Marker({
          position: new google.maps.LatLng(geoArray[i]["lat"],geoArray[i]["long"]),
          map: map
          });
        }

        var markers = locations.map(function(location,i) {
          return new google.maps.Marker({
            map: map,
            position: {lat: location.lat, lng: location.lng},
            animation: google.maps.Animation.DROP
          });
        });
}