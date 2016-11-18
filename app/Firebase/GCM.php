<?php

require($_SERVER['DOCUMENT_ROOT'] . '/app/Controller.php');
require($_SERVER['DOCUMENT_ROOT'] . '/app/Classes/DB.php');

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
            'Authorization:key = AIzaSyBw2c9m1kBPXF0jeiVnjXpte0nLmTqFzp4',
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

    private function getAllTokens()
    {
        // TODO: MYSQL CONN CLASS HERE!!
        $conn = mysqli_connect("mysql.hostinger.co.uk", "u809101341_main", "Hc3z9ft0lFU7SyeI9P", "u809101341_main");
        $sql = " Select token From devices";
        $result = mysqli_query($conn, $sql);
        $tokens = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $tokens[] = $row["token"];
            }
        }

        mysqli_close($conn);
        return $tokens;
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
$notify->sendNotification("Yo, talking to you mate.");