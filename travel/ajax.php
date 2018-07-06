<?php
  require 'class/lib.class.php';
  require 'class/sabre/App/vendor/autoload.php';
  include "class/sabre/App/vendor/cometari/sabreapi.php";
  use cometari\SabreAPI as Sabre;
  $sabre= new Sabre();
  $lib  = new lib();

  $result = '';
  $mode = filter_input(INPUT_POST, "mode");

  switch ($mode) {
    case 'airport':


        $query  = filter_input(INPUT_POST, "query");

        $airportNames = array();

        if(strlen($query)>2){
          $airports = $sabre->searchAirports($query);
          $airports = $lib->unique_multidim_array($airports,'code');

          if(count($airports) > 0){
            foreach($airports as $key => $value) {
              $airportNames[] = $value['city'].', '.$value['COUNTRY_CODE'].' ('.$value['code'].')';
            }
          }
        }

        echo json_encode($airportNames);
      break;

    default:

      break;
  }
