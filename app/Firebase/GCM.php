<?php

require($_SERVER['DOCUMENT_ROOT'] . '/app/DB/DB.php');

class GCM
{
    private $db;
    private $url = 'https://fcm.googleapis.com/fcm/send';

    function __construct()
    {
        $this->db = new MysqliDb();
    }

    public function sendGCMessage($tokens, $message)
    {
        $fields = array(
            'registration_ids' => $tokens,
            'data' => $message
        );
        $headers = array(
            'Authorization:key = AIzaSyD6zKN6IoHCYNi4gTJmFbPfJ78hFaewCNM',
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

    public function getAllTokens()
    {
		$tokens = $this->db->getValue ("alerts", "token", null);		
        print_r($tokens);       
    }

    public function sendNotification($message)
    {
        $message = array("type" => "notification", "data" => "Bringo" . ";;" . $message);
        return $this->sendGCMessage($this->getAllTokens(), $message);
    }

    public function reqPosition($device_token)
    {
        $message = array("type" => "reportPos");
        return $this->sendGCMessage([$device_token], $message);
    }
}
$notify = new GCM();
$notify->getAllTokens();