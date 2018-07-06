<?php

require_once "vendor/cometari/sabreapi.php";
require_once "vendor/cometari/utils.php";
use cometari\SabreAPI as Sabre;

// -------------------------------- main-----------------------------------------------
$sabre = new Sabre();
$path = '/lists/utilities/geoservices/autocomplete?query=ahme&category=AIR&limit=10';
$response = $sabre->get($path);

if (Utils::startsWith($path, "/shop/flights/fares")) {
    $response = $sabre->formDestinationFinderResponse($response);
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

echo $response;
// ------------------------------------------------------------------------------------
