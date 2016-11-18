<?php

session_start();

// Import
require($_SERVER['DOCUMENT_ROOT'] . '/app/DB/DB.php');

// Init Classes
$db = new MysqliDb();
