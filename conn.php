<?php
include_once('BBVA.php');
include_once('.keys.php');

// The $id and the $key are inside .keys.php
$bbva = new BBVA($id, $key);
$lat  = 20.676;
$lng  = -103.342;
$zipcode = "64102";
$res  = $bbva->gender_distribution_by_zipcode($zipcode);
header("Content-Type: application/json");
echo $res;

?>