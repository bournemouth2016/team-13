<?php

require($_SERVER['DOCUMENT_ROOT'] . '/app/GCM/main.php');
require($_SERVER['DOCUMENT_ROOT'] . '/app/DB/DB.php');


$notify = new GCM();


if(isset($_REQUEST['update'])){
	
	$vessels = array();
	$sql = "SELECT token FROM users";
	if ($result = $conn->query($sql)) {
			
			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					array_push($vessels, $row["token"]);
				}
			} else {
				echo "No boats available to help.";
			}	

	} else {
		
		echo 'Fail';
	}
	
	$notify->requestPos($vessels);

}

if(isset($_REQUEST['notify'])){
	
	$token = $_REQUEST['vessel'];

	$notify->sendNotification([$token], 'SafeSail', $_REQUEST['message']);

}

if(isset($_REQUEST['radius'])){
	
	$radius = $_REQUEST['radiusVal'];
		
	$sql = "UPDATE settings SET radius='$radius'";
	
	if ($conn->query($sql) === TRUE) {
		echo "ok";
	} else {
		echo "fail";
	}
	
}

header("Location: /");