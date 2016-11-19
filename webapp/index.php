<?php

require($_SERVER['DOCUMENT_ROOT'] . '/app/DB/DB.php');
require($_SERVER['DOCUMENT_ROOT'] . '/app/GCM/main.php');

$gcm = new GCM();
$vessels = array();
$emergency = array();

$sql = "SELECT lng,lat FROM users WHERE status='active' ";

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($vessels, $row['lng']);
            array_push($vessels, $row['lat']);
        }
    }
}


$sql = "SELECT lng,lat FROM users WHERE status='emergency' ";

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($emergency, $row['lng']);
            array_push($emergency, $row['lat']);
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <style>
        #map {
            height: 600px;
            width: 100%;
        }
    </style>
</head>
<body>
<center>
    <h2>MRCC & RLNI Monitoring System</h3></center>
<div id="map"></div>
<script> 
function initMap(){
    var initialLocation = {lat: -6.813111, lng: 39.304491};

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: initialLocation
    });

    var vessels = <?php echo json_encode($vessels); ?>;
        for(i=0; i<vessels.length;i=i+2){
            var lng = parseFloat(vessels[i]);
            var lat = parseFloat(vessels[i+1]);
            createMarker(lat,lng,map);
    }

    var emergency = <?php echo json_encode($emergency); ?>;
        for(i=0; i<emergency.length;i=i+2){
            var lng = parseFloat(emergency[i]);
            var lat = parseFloat(emergency[i+1]);
            createEmergencyMarker(lat,lng,map);
            createEmergencyRadius(lat,lng,map);
        }

    //
}



function createMarker(lng,lat,map){
    var location = {lat: lat, lng: lng};
    var marker = new google.maps.Marker({
        position: location,
        map: map,
        icon: 'icon.png'
    });
}
function createEmergencyMarker(lng,lat,map){
    var location = {lat: lat, lng: lng};
    var marker = new google.maps.Marker({
        position: location,
        map: map,
        icon: 'http://maps.google.com/mapfiles/kml/shapes/caution.png'
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