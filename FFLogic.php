<?php

require($_SERVER['DOCUMENT_ROOT'] . '/app/DB/DB.php');
require($_SERVER['DOCUMENT_ROOT'] . '/app/GCM/main.php');

$gcm = new GCM();

$mrcc = ''; // Token for spcial MRCC Reporting


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
				global $mrcc;
				array_push($shipsWithinRadius, $mrcc);
			} else {
				echo "0 results";
			}
			
			
		} else {
			echo 'Fail';
		}

    return $shipsWithinRadius;

}
	
function captainSignalsDistress($lat, $lng, $radius, $captainName, $peopleInDanger){
    global $gcm;
    $msg = '';
    $shipsWithinRadius = determineRescuers($lat, $lng, $radius);
    $gcm->sendMessage($shipsWithinRadius, $msg);
}

function mrccSignalsStorm($lat, $lng, $radius){
    global $gcm;
    $msg = 'Weather Warning, Go Home!';
    $shipsWithinRadius = determineRescuers($lat, $lng, $radius);
    $gcm->sendMessage($shipsWithinRadius, $msg);
}

function captainSignalsDistressSettled($vesselLost, $casualties, $livesSaved){

}

function rescuerSignalsAbleToHelp($locationOfHelper, $details){

}

function queryWhetherCanHelpOrNot($sailorID){

}

function determineNumberOfAlarmsTriggered()
{

    global $conn;
    $numberOfAlarms = 0;
    $sql = "SELECT id FROM alerts WHERE status='1' OR status='0' ";

    if ($result = $conn->query($sql)) {

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $numberOfAlarms++;
            }
        } else {
            echo "0 results";
        }


    } else {
        echo 'Fail';
    }
    return $numberOfAlarms;
}

function determineNumberOfFalseAlarmsTriggered()
{

    global $conn;
    $numberOfAlarms = 0;
    $sql = "SELECT id FROM alerts WHERE status='-1'";

    if ($result = $conn->query($sql)) {

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $numberOfAlarms++;
            }
        } else {
            echo "0 results";
        }


    } else {
        echo 'Fail';
    }
    return $numberOfAlarms;
}

function produceRLNIReport(){
    $numberOfAlarmsTriggered = determineNumberOfAlarmsTriggered();
    $numberOfFalseAlarmsTriggered = determineNumberOfFalseAlarmsTriggered();
}



	