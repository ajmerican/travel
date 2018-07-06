<?php

require 'vendor/autoload.php';
include "vendor/cometari/sabreapi.php";
use cometari\SabreAPI as Sabre;

// -------------------------------- main-----------------------------------------------
$sabre = new Sabre();

//$row = $sabre->airports($_GET['code']);
$row = $sabre->searchAirports('ahme');

//header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json");

echo json_encode($row);

// ------------------------------------------------------------------------------------
