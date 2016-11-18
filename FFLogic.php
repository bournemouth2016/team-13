<?php

$host= 'mysql.hostinger.co.uk';
$username = 'u439981189_main';
$password = '041195';
$db= 'u439981189_main';
$conn = mysqli_connect($host, $username, $password, $db);


// Selects the 50 closest ships within a radius of the specifies coordinates in KMs
function determineRescuers($lat, $lng, $radius){
	
	global $conn;
	$shipsWithinRadius = array();
    $sql = 'SELECT id, ( 6371 * acos( cos( radians(' . $lat .') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat .') ) * sin( radians( lat ) ) ) ) AS distance FROM alerts HAVING distance < ' . $radius .' ORDER BY distance LIMIT 0 , 50';

	if ($result = $conn->query($sql)) {
			
			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					array_push($shipsWithinRadius, $row["id"]);
				}
			} else {
				echo "0 results";
			}
			
			
		} else {
			echo 'Fail';
		}

    return $shipsWithinRadius;

}
	

determineRescuers('50.740736', '-1.823260', 5.1);
	