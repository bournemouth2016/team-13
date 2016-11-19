<?php

require($_SERVER['DOCUMENT_ROOT'] . '/app/DB/DB.php');
require($_SERVER['DOCUMENT_ROOT'] . '/app/GCM/main.php');

$gcm = new GCM();
$vessels = array();
$emergency = array();

$sql = "SELECT lng,lat,token FROM users WHERE status='active' ";

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($vessels, $row['lng']);
            array_push($vessels, $row['lat']);
            array_push($vessels, $row['token']);
        }
    }
}


$sql = "SELECT lng,lat,token FROM users WHERE status='emergency' ";

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($emergency, $row['lng']);
            array_push($emergency, $row['lat']);
            array_push($emergency, $row['token']);
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        #map {
            height: 600px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h2>MRCC & RLNI Monitoring System</h3>

	</center>
	<div class="col-md-10">
	<div id="map"></div></div>
	<div class="col-md-2">
	<h1>Menu</h1>
	<a href="/app/GCM/test.php?update=1"><button>Update Vessel Locations</button></a>
        <form action="/app/GCM/test.php" method="post">
        <br>
        <div id="messageVessel"></div>
        <hr>
        Send Direct Message to the selected vessel.
		<input type="text" name="notify" value="true" hidden>
        <input type="text" name="message" placeholder="Message for the vessel">
        <input type="hidden" value="" name="vessel" id="tokenForMessage" >
        <input value="Send" type="submit" style="margin-top: 5px;">
        </form>
	</div>
	</div>
<script> 
function initMap(){
    var emergency = <?php echo json_encode($emergency); ?>;
    var vessels = <?php echo json_encode($vessels); ?>;
    var initialLocation;

    if((parseFloat(emergency[0]) != null && parseFloat(emergency[0]) != null)){
        initialLocation = {lat: parseFloat(emergency[0]), lng: parseFloat(emergency[1])};
        }
    else {
        initialLocation = {lat: 0, lng: 0};
        }

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 6,
        center: initialLocation
    });

        for(i=0; i<vessels.length;i=i+3){
            var lng = parseFloat(vessels[i]);
            var lat = parseFloat(vessels[i+1]);
            var token = (vessels[i+2]);
            createMarker(lat,lng,token,map);
    }

        for(i=0; i<emergency.length;i=i+3){
            var lng = parseFloat(emergency[i]);
            var lat = parseFloat(emergency[i+1]);
            var token = (emergency[i+2]);
            createEmergencyMarker(lng,lat,token,map);
            createEmergencyRadius(lng,lat,token,map);
        }

    //
}



function createMarker(lng,lat,token,map){
    var location = {lat: lat, lng: lng};
    var marker = new google.maps.Marker({
        position: location,
        map: map,
        title: token,
        icon: 'icon.png'
    });
     marker.addListener('click', function() {
              document.getElementById('tokenForMessage').value = '';
              document.getElementById('tokenForMessage').value = marker.title;
            });
}
function createEmergencyMarker(lng,lat,token,map){
    var location = {lat: lat, lng: lng};
    var marker = new google.maps.Marker({
        position: location,
        map: map,
        title: token,
        icon: 'http://maps.google.com/mapfiles/kml/shapes/caution.png'
    });
     marker.addListener('click', function() {
              document.getElementById('tokenForMessage').value = '';
              document.getElementById('tokenForMessage').value = marker.title;
            });
}

function createEmergencyRadius(lng,lat,map){
    var circle = new google.maps.Circle({
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#FF0000',
        fillOpacity: 0.35,
        map: map,
        center: {lat: lat, lng:lng},
        radius: 1300
    });
}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDnVFRzRw2OXGPGhY_JLEIHt1Ym_VLNvk&callback=initMap" async defer></script>
</body>
</html>