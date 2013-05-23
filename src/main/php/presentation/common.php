<?php
include_once '../util.php';



$context = array(
		'geo' => '', 
		'cross' => '', 
		'science' => '',
		'gov' => '', 
		'pub' => '', 
		'user' => '', 
		'media' => '',
		'criteria_locations_near' => '',
		'criteria_locations_under' => '',
		'criteria_locations_over' => '',
		'criteria_time_start' => '',
		'criteria_time_end' => '',
		'criteria_things' => '',
		'criteria_confidence' => '',
		'settings_ov' => '',
		'settings_device' => ''
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
	constant('ov-ids') => '',
	'observed_property' => '',
	'unit-of-measurement' => '',
	'sensor_temporal_prop_uris' => '',
	'dev_type' => '',
	'dev_type_device' => '',
	'dev_type_sensing_device' => '',
	'dev_type_sensor' => '',
		constant('base-uri') => ''
);

function removeAllSpecialCharacters($string) {
	return preg_replace("/[^a-zA-Z0-9\s-]/", "", $string);
}

/**
 * 
 * @param unknown $data
 * @param unknown $uri_pattern - pattern used to recognise when m that has to be matched for an anchor tag to be inserted around a token
 * @return string
 */
function getPrintableCode($data, $anc_pattern){
	$data_4html = str_ireplace("<", "&lt", $data);
	$data_4html = str_ireplace(">", "&gt", $data_4html);
	$data_4html = '<pre><code>'.$data_4html.'</pre></code>';
	$data_4html = util::linkify($data_4html);
	
	//search for uris starting with the same ld4s instance root and add 
	//a botton for displaying the link-review form  
	
	
	return $data_4html;
}


// $match_all = false: returns string with first match
// $match_all = true:  returns array of strings with all matches

function pattern_match($subject, $pattern, $match_all = false)
{
	$pattern = preg_quote($pattern, '|');

	$ar_pattern_replaces = array(
			'n' => '[0-9]',
			'c' => '[a-z]',
			'C' => '[A-Z]',
	);

	$pattern = strtr($pattern, $ar_pattern_replaces);

	$pattern = "|".$pattern."|";

	if ($match_all)
	{
		preg_match_all($pattern, $subject, $matches);
	}
	else
	{
		preg_match($pattern, $subject, $matches);
	}

	return $matches[0];
}

// $subject = '$2,500 + $550 on-time bonus, paid 50% upfront ($1,250), 50% on delivery ($1,250 + on-time bonus).';
// $pattern = '$n,nnn';

// $result = pattern_match($subject, $pattern, 0);
// var_dump($result);

// $result = pattern_match($subject, $pattern, 1);
// var_dump($result);

?>