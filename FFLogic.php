<?php

require($_SERVER['DOCUMENT_ROOT'] . '/app/DB/DB.php');

// Selects the 50 closest ships within a radius of the specifies coordinates in KMs
function determineRescuers($lat, $lng, $radius){
    $query = 'SELECT id, ( 6371 * acos( cos( radians(' . $lat .') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat .') ) * sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < ' . $radius .' ORDER BY distance LIMIT 0 , 50';
}

?>