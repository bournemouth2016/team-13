<?php

require($_SERVER['DOCUMENT_ROOT'] . '/app/DB/DB.php');

if (isset($_REQUEST["token"]) && isset($_REQUEST["type"])) {

    $token = $_REQUEST["token"];
	$type = $_REQUEST["type"];

    if ($type == 'register') {

        $name = $_REQUEST["name"];
		$phone = $_REQUEST["phone"];	
				
		$sql = "INSERT INTO users (name, phone, token)
		VALUES ('$name', '$phone', '$token')";

		if (mysqli_query($conn, $sql)) {
			echo 'Ok';
		} else {
			echo 'Fail';
		}
   
    } else if($type == 'alert') {
  
		
  
  
    }
} else {
	echo 'Not a valid request.';
}

