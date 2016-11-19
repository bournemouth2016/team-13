<?php

require($_SERVER['DOCUMENT_ROOT'] . '/app/DB/DB.php');
require($_SERVER['DOCUMENT_ROOT'] . '/app/GCM/main.php');

$gcm = new GCM();
$vessels = array();
$emergency = array();

<<<<<<< HEAD
$sql = "SELECT lng,lat,token,name,phone FROM users WHERE status='active' ";
=======
$sql = "SELECT lng,lat,token FROM users WHERE status='active' ";
>>>>>>> origin/master

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($vessels, $row['lng']);
            array_push($vessels, $row['lat']);
            array_push($vessels, $row['token']);
<<<<<<< HEAD
            array_push($vessels, $row['name']);
            array_push($vessels, $row['phone']);
=======
>>>>>>> origin/master
        }
    }
}


<<<<<<< HEAD
$sql = "SELECT lng,lat,token,name,phone FROM users WHERE status='emergency' ";
=======
$sql = "SELECT lng,lat,token FROM users WHERE status='emergency' ";
>>>>>>> origin/master

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($emergency, $row['lng']);
            array_push($emergency, $row['lat']);
            array_push($emergency, $row['token']);
<<<<<<< HEAD
            array_push($emergency, $row['name']);
            array_push($emergency, $row['phone']);
=======
>>>>>>> origin/master
        }
    }
}

function getNumberOfAlerts()
{
    global $conn;
    $sql = "SELECT id FROM alerts WHERE status='new' OR status='closed' ";

    if ($result = $conn->query($sql)) {

        return $result->num_rows;

    } else {
        echo 'Fail';
    }
}

function getNumberOfAlertsTriggered()
{
    global $conn;
    $sql = "SELECT id FROM alerts WHERE status='new'";

    if ($result = $conn->query($sql)) {

        return $result->num_rows;

    } else {
        echo 'Fail';
    }
}

function getNumberOfVesseles()
{
    global $conn;
    $sql = "SELECT id FROM users";

    if ($result = $conn->query($sql)) {

        return $result->num_rows;

    } else {
        echo 'Fail';
    }
}

function getRadii()
{
    global $conn;
    $sql = "SELECT radius FROM settings";

    $result = $conn->query($sql);

	if ($result->num_rows > 0) {
		
		if($row = $result->fetch_assoc()) {
			return $row['radius'];
		}
	} else {
		return 10;
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<<<<<<< HEAD
	<title> Group 13 </title>
=======
>>>>>>> origin/master
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
        #map {
            height: 700px;
            width: 100%;
        }
    </style>
</head>
<body>
<<<<<<< HEAD

	</center>
	<div class="col-md-10">
	
    <h2>MRCC & RLNI Monitoring System</h3>
	<div id="map"></div></div>
	<div class="col-md-2">
	<h1>Menu</h1>
	<hr>
	<a href="/app/GCM/test.php?update=1"><button>Update Vessel Locations</button></a>
        <form action="/app/GCM/test.php" method="post">
        <div id="messageVessel"></div>
        <hr>
        <b>Send Direct Message:</b>
		<br>		
        <span style="color:orange; font-weight: 600" id="markerSelected">Marker Not Selected</span>
		<br><span id="captainInfo"></span>
		<br/>
		<input type="text" name="notify" value="true" hidden>
        <input type="text" name="message" placeholder="Message for the vessel">
        <input type="hidden" value="" name="vessel" id="tokenForMessage" >
        <input value="Send Notification" type="submit" style="margin-top: 5px;">
        </form>
		<br/>
		<br/>
		<h2>Quick Stats</h2>
		<span>Currently Active: <?php echo getNumberOfVesseles(); ?></span><br/>
		<span>On-going Emergencies: <?php echo getNumberOfAlerts(); ?></span><br/>
		<span>Total Emergencies: <?php echo getNumberOfAlertsTriggered(); ?></span>
		<br/>
		<br/>
		<h2>Settings</h2>
		<form method="post" action="/app/GCM/test.php">
		<input type="hidden" name="radius" value="1"/>
		<label> Dispatch Radius (KM) </label>
		<input type="text" value="<?php echo getRadii(); ?>" name="radiusVal" placeholder="Dispatch Radius (KM)"/>
		<input type="submit" style="margin-top: 5px" value="Apply"/>
		</form>
=======
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
>>>>>>> origin/master
	</div>
	</div>
<script> 
function initMap(){
    var emergency = <?php echo json_encode($emergency); ?>;
    var vessels = <?php echo json_encode($vessels); ?>;
    var initialLocation;

<<<<<<< HEAD
    if(emergency[0] != null && emergency[0] != null){
        initialLocation = {lat: parseFloat(emergency[1]), lng: parseFloat(emergency[0])};
        }
    else {
        initialLocation = {lat: -6.769699, lng: 39.320196};
=======
    if((parseFloat(emergency[0]) != null && parseFloat(emergency[0]) != null)){
        initialLocation = {lat: parseFloat(emergency[0]), lng: parseFloat(emergency[1])};
        }
    else {
        initialLocation = {lat: 0, lng: 0};
>>>>>>> origin/master
        }

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 6,
        center: initialLocation
    });

<<<<<<< HEAD
        for(i=0; i<vessels.length;i=i+5){
            var lng = parseFloat(vessels[i]);
            var lat = parseFloat(vessels[i+1]);
            var token = (vessels[i+2]);
            var name = (vessels[i+3]);
            var phone = (vessels[i+4]);
            createMarker(lat,lng,token,name,phone,map);
    }

        for(i=0; i<emergency.length;i=i+5){
            var lng = parseFloat(emergency[i]);
            var lat = parseFloat(emergency[i+1]);
            var token = (emergency[i+2]);
            var name = (emergency[i+3]);
            var phone = (emergency[i+4]);
            createEmergencyMarker(lng,lat,token,name,phone,map);
            createEmergencyRadius(lng,lat,map);
=======
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
>>>>>>> origin/master
        }

    //
}



<<<<<<< HEAD
function createMarker(lng,lat,token,name,phone,map){
=======
function createMarker(lng,lat,token,map){
>>>>>>> origin/master
    var location = {lat: lat, lng: lng};
    var marker = new google.maps.Marker({
        position: location,
        map: map,
        title: token,
<<<<<<< HEAD
        secret: 'Reporter: '+ name + ', Phone: ' + phone,
=======
>>>>>>> origin/master
        icon: 'icon.png'
    });
     marker.addListener('click', function() {
              document.getElementById('tokenForMessage').value = '';
              document.getElementById('tokenForMessage').value = marker.title;
<<<<<<< HEAD
              document.getElementById('markerSelected').innerHTML = "Marker Selected";
			  document.getElementById('markerSelected').style.color = "green";
			  document.getElementById('captainInfo').innerHTML = '';
			  document.getElementById('captainInfo').innerHTML = marker.secret;
            });
}
function createEmergencyMarker(lng,lat,token,name,phone,map){
=======
            });
}
function createEmergencyMarker(lng,lat,token,map){
>>>>>>> origin/master
    var location = {lat: lat, lng: lng};
    var marker = new google.maps.Marker({
        position: location,
        map: map,
        title: token,
<<<<<<< HEAD
        secret: 'Reporter: '+ name + ', Phone: ' + phone,
=======
>>>>>>> origin/master
        icon: 'http://maps.google.com/mapfiles/kml/shapes/caution.png'
    });
     marker.addListener('click', function() {
              document.getElementById('tokenForMessage').value = '';
              document.getElementById('tokenForMessage').value = marker.title;
<<<<<<< HEAD
              document.getElementById('markerSelected').innerHTML = "Marker Selected";
			  document.getElementById('markerSelected').style.color = "green";
			  document.getElementById('captainInfo').innerHTML = '';
			  document.getElementById('captainInfo').innerHTML = marker.secret;
=======
>>>>>>> origin/master
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
        radius: (<?php echo getRadii(); ?>*1000)
    });
}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDnVFRzRw2OXGPGhY_JLEIHt1Ym_VLNvk&callback=initMap" async defer></script>
</body>
</html>