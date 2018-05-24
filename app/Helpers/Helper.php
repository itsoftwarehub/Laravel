<?php 
// Code within app\Helpers\Helper.php

namespace App\Helpers;

class Helper
{
    public static function getLatLong($address){

    	$response = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false');
		$response = json_decode($response, true);
		 
		return $response;
    }
}