<?php

//The main application logic running on the server interfacing between the database and the user facing interfaces.


// Api will calls this function to handle a request to signal distress
function onDistressSignalReceived($sourceToken){
    
	global $gcm;
	global $conn;
	
	$radius = getRadii(); // 200 kms
	
	// SQL Query 
	$sql = "SELECT lat, lng, acc FROM users WHERE token = '$sourceToken'";

	// Handles response
	if ($result = $conn->query($sql)) {
		
			$data = array();
			
			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					$data = [$row["lat"], $row["lng"], $row["acc"]];
				}
			} else {
				echo "No boats available to help.";
			}			
			
		} else {
			
			echo 'Fail';
		}
	
	
	
    $shipsWithinRadius = determineRescuers($data[0], $data[1], $data[2], $radius, $except);
    $gcm->sendNotification($shipsWithinRadius, "SOS Alert", "Help needed at " . $data[0] . ", " . $data[1]);

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

// Selects the 30 closest ships within a radius of the specifies coordinates in KMs
function determineRescuers($lat, $lng, $accuracy, $radius, $except){
	
	global $conn;
	$accuracy = $accuracy/1000;
	$radius = $radius + $accuracy;
	
	$shipsWithinRadius = array();
    $sql = 'SELECT token, ( 6371 * acos( cos( radians(' . $lat .') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat .') ) * sin( radians( lat ) ) ) ) AS distance FROM users HAVING distance < ' . $radius .' ORDER BY distance LIMIT 0 , 30';

	if ($result = $conn->query($sql)) {
			
			if ($result->num_rows > 0) {

				while($row = $result->fetch_assoc()) {
					array_push($shipsWithinRadius, $row["token"]);
				}
			} else {
				echo "No boats available to help.";
			}			
			
		} else {
			
			echo 'Fail';
		}

    return $shipsWithinRadius;

}

// Signals a warning to all ships in a certain area about weather
function mrccSignalsStorm($lat, $lng, $radius){
    global $gcm;
    $msg = 'Weather Warning, Go Home!';
    $shipsWithinRadius = determineRescuers($lat, $lng, $radius);
    $gcm->sendMessage($shipsWithinRadius, $msg);
}


// Queries the database about the total people involved in accidents, used for reporting
function getTotalPAXInvolvedInAccidents(){

    global $conn;
    $sql = "SELECT pax FROM alerts WHERE status='active' OR status='closed' ";

    if ($result = $conn->query($sql)) {

        return $result->num_rows;

    } else {
		
        echo 'Fail';
    }
	
}

// Queries the database about the total number of false alarms triggered, used for reporting

function getNumberOfFalseAlertsTriggered()
{
    global $conn;
    $sql = "SELECT id FROM alerts WHERE status='false_alarm'";

    if ($result = $conn->query($sql)) {

        return $result->num_rows; 

    } else {		
        echo 'Fail';
	}
	
}

// Queries the database about the total number of casualties, used for reporting
function getNumberOfCasualties(){

    global $conn;
    $sql = "SELECT casualties FROM alerts WHERE status='active' OR status='closed'";

    if ($result = $conn->query($sql)) {

        return $result->num_rows;

    } else {
		
        echo 'Fail';
    }
	
}


// Queries the database about the total number of vessels lost, used for reporting
function getNumberOfVesselsLost(){

    global $conn;
    $sql = "SELECT ID FROM alerts WHERE vessel_status='lost'";

    if ($result = $conn->query($sql)) {

        return $result->num_rows;

    } else {
		
        echo 'Fail';
    }
}


// Queries the database about the total number of vessels saved, used for reporting
function getNumberOfVesselsSaved(){

    global $conn;
    $sql = "SELECT ID FROM alerts WHERE vessel_status='saved'";

    if ($result = $conn->query($sql)) {

        return $result->num_rows;          

    } else {
        echo 'Fail';
    }
}


// TODO: Queries the database about the total number of responders that helped, used for reporting
function determineNumberOfRespondersThatHelped(){

}

// TODO: Queries the database about the total number of responders that could not help, used for reporting
function determineOfRespondersThatCouldNotHelp(){

}


// Computes the necessary dataset for reporting, TODO: HTML Formatting and Emailing or Web Based Interface for use
function produceReport(){
    $numberOfAlarmsTriggered = determineNumberOfAlarmsTriggered();
    $numberOfFalseAlarmsTriggered = determineNumberOfFalseAlarmsTriggered();
    $numberOfPeopleInvolvedInAccidents = determineTotalPeopleInvolvedInAccidents();
    $nuberOfCasualties = determineNumberOfCasualties();
    $numberOfLivesSaved = $numberOfPeopleInvolvedInAccidents - $nuberOfCasualties;
    $numberOfVesselsLost = determineNumberOfVesselsLost();
    $numberOfVesselsSaved = determineNumberOfVesselsSaved();
    $numberOfRespondersThatHelped = determineNumberOfRespondersThatHelped();
    $numberOfRespondersThatCouldNotHelp = determineOfRespondersThatCouldNotHelp();


}



	