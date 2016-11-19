<?php

require($_SERVER['DOCUMENT_ROOT'] . '/app/DB/DB.php');

class GCM
{
  
	private $url = 'https://fcm.googleapis.com/fcm/send';

    function __construct()
    {
      
    }

    public function sendMessage($tokens, $message) 				
    {
        $fields = array(
            'registration_ids' => $tokens,
            'data' => $message
        );
        $headers = array(
            'Authorization:key = AIzaSyBw2c9m1kBPXF0jeiVnjXpte0nLmTqFzp4', //AIzaSyDkZ08qw-Dk1fXDciZAx7_eoz6PpYKav0Q', //AIzaSyBw2c9m1kBPXF0jeiVnjXpte0nLmTqFzp4',
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function requestPos($device_token)
    {
        $message = array("type" => "reportPos");
        return $this->sendMessage($device_token, $message);
    }
	
	
    public function sendNotification($device_token, $title, $message)
    {
        $message = array("type" => "notification", "data" => $title.';;'.$message);
        return $this->sendMessage($device_token, $message);
    }
}