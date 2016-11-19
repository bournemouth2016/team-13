<?php
// The API responsible for interfacing with the clients and handling their requests by tunneling them into the
// right handlers.

require($_SERVER['DOCUMENT_ROOT'] . '/app/DB/DB.php'); // Requires the database calls
require($_SERVER['DOCUMENT_ROOT'] . '/app/GCM/main.php'); // Requires the notification calls
require($_SERVER['DOCUMENT_ROOT'] . '/app/Logic.php'); // Requires the main logic calls

$gcm = new GCM();

if (isset($_REQUEST["token"]) && isset($_REQUEST["type"])) {	
	
    $token = $_REQUEST["token"];
	$type = $_REQUEST["type"];

    if ($type == 'register') { // Handle registration requests

        $name = $_REQUEST["name"];
		$phone = $_REQUEST["phone"];	
				
		$sql = "INSERT INTO users (name, phone, token, status, time)
		VALUES ('$name', '$phone', '$token', 'active', now())";

		if (mysqli_query($conn, $sql)) {
			$gcm->requestPos([$token]);
			sleep(1);
			$gcm->requestPos([$token]);
			sleep(1);
			$gcm->requestPos([$token]);
			sleep(1);
			$gcm->requestPos([$token]);
			echo 'ok';
		} else {
			echo 'fail';
		}
   
    } else if($type == 'alert') {	// Handle alert requests			
		
		$ident = $_REQUEST["ident"];
		$status = $_REQUEST["status"];
			
		$sql = "INSERT INTO alerts (token, ident, status, time)
		VALUES ('$token', '$ident', '$status', now())";
		
		if (mysqli_query($conn, $sql)) {		
			
			$sql = "UPDATE users SET status='emergency',time=now() WHERE token='$token'";
			
			if ($conn->query($sql) === TRUE) {
				$gcm->requestPos([$token]);
				onDistressSignalReceived($token);
			} else {
				echo "fail";
			}
			
		} else {
			echo 'fail';
		}
			
		
    } else if($type == 'reportPos') {	// Handle requests to report positions
	
		$acc = $_REQUEST["acc"];
        $lat = $_REQUEST["lat"]; 
		$lng = $_REQUEST["lng"];
		$time = time();
						
		$sql = "UPDATE users SET lat='$lat',lng='$lng',acc='$acc',time=now() WHERE token='$token'";
		
		if ($conn->query($sql) === TRUE) {
			echo "ok";
		} else {
			echo "fail";
		}
		
	} else if($type == 'cancel') {	// Handle cancellation requests
	
		$sql = "UPDATE users SET status='active',time=now() WHERE token='$token'";
		
		if ($conn->query($sql) === TRUE) {
			
			echo "ok";
			
		} else {
			echo "fail";
		}
		
		$sql = "UPDATE alerts SET status='closed',time=now() WHERE token='$token'";
		
			if ($conn->query($sql) === TRUE) {
				echo "ok";
			} else {
				echo "fail";
			}
		
	}				
	
} else { // Disregards requests that do not follow the protocol
	
	echo 'Not a valid request.';

}

return "ok";

