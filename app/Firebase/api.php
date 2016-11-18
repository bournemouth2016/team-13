<?php
require($_SERVER['DOCUMENT_ROOT'] . '/app/Controller.php');

if (isset($_REQUEST["token"])) {

    $token = $_REQUEST["token"];

    if (isset($_REQUEST["location"])) {

        $location = $_REQUEST["location"];
        $data = Array(
            "location" => $location
        );
        $db->where('token', $token);
        $db->update('devices', $data);

    } else {
        // Token Update
        // Insert Update: to prevent duplicate key values.
        $data = Array('token' => $token);
        $updateColumns = Array("token");
        $db->onDuplicate($updateColumns, "id");
        $query = $db->insert('devices', $data);
    }
}

?>