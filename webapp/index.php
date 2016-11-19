<?php

require($_SERVER['DOCUMENT_ROOT'] . '/app/DB/DB.php');
require($_SERVER['DOCUMENT_ROOT'] . '/app/GCM/main.php');

$gcm = new GCM();
$vessels = array();

$sql = "SELECT lng,lat FROM users WHERE status='active' ";

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($vessels, $row['lng']);
            array_push($vessels, $row['lat']);
        }
    }
}
print_r($vessels);

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
    <h3>FishermanFriend Status Track</h3></center>
<div id="map"></div>

<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDnVFRzRw2OXGPGhY_JLEIHt1Ym_VLNvk&callback=initMap">
</script>

<script src ="frontEndJS"></script>
</body>
</html>