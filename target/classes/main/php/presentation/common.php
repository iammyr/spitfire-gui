<?php

$context = array(
		'geo' => false, 
		'cross' => false, 
		'science' => false,
		'gov' => false, 
		'pub' => false, 
		'user' => false, 
		'media' => false,
		'locations' => false, 
		'time' => false, 
		'things' => false,
		'confidence' => false,
		'ov' => false,
		'device' => false
);

$ov = array (
    "sensor_id"  => '',
    "start_time" => '',
    "end_time"   => '',
	"reading_value"   => '',
     constant('base-uri') => ''
);

$device = array (
		'device_id' => '',
	'base_time' => '',
	'base_name' => '',
	'base_ov_name' => '',
	'ov_uris' => '',
	'observed_property' => '',
	'unit-of-measurement' => '',
	'sensor_temporal_prop_uris' => '',
	'dev_type' => '',
	'dev_type_device' => '',
	'dev_type_sensing_device' => '',
	'dev_type_sensor' => '',
);

function removeAllSpecialCharacters($string) {
	return preg_replace("/[^a-zA-Z0-9\s-]/", "", $string);
}


function getPrintableCode($data){
	$data_4html = str_ireplace("<", "&lt", $data);
	$data_4html = str_ireplace(">", "&gt", $data_4html);
	$data_4html = '<pre><code>'.$data_4html.'</pre></code>';
	return $data_4html;
}




?>