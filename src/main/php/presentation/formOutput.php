<?php 
require_once('../external_service/ld4sApiMan.php');
require_once('../constants.php');

class FormOutputParser
// implements InterfaceIO
{


	public static function parseSerializationOptions($serialization_lang){
		$pref_mt = "";
		if (strcasecmp($serialization_lang, constant('json')) == 0){
			$pref_mt = constant('mt_json');
		}else
		if (strcasecmp($serialization_lang, constant('xml')) == 0){
			$pref_mt = constant('mt_xml');
		}else
		if (strcasecmp($serialization_lang, constant('ntriple')) == 0){
			$pref_mt = constant('mt_ntriple');
		}else
		if (strcasecmp($serialization_lang, constant('turtle')) == 0){
			$pref_mt = constant('mt_turtle');
		}
		return $pref_mt;
	}


	public static function submitRequest4LD4S( $ld4s_uri, $store_option,
			$serialization, $ov, $device, $context){
		$data = Array();
		$has_to_be_stored = ($store_option === constant('req_store1'));
		$serialization = FormOutputParser::parseSerializationOptions($serialization);
		if ($ov || $device){
			//associate each data set to the proper (according to the LD4S REST API) resource type
			$data = LD4SApiMan::fillPayload(
					$context['geo'],
					$context['cross'], $context['science'],
					$context['gov'], $context['pub'],
					$context['user'], $context['media'],
					$context['criteria_locations_near'],
					$context['criteria_locations_over'],
					$context['criteria_locations_under'],
					$context['criteria_time_start'],
					$context['criteria_time_end'],
					$context['criteria_things'],
					$context['criteria_confidence'],
						
					$ov[constant('sensor-id')],
					$ov[constant('base-uri')],
					$ov[constant('start-time')],
					$ov[constant('end-time')],
					$ov[constant('reading-value')],

					$device[constant('base-time')],
					$device[constant('base-name')],
					$device[constant('ov-ids')],
					$device[constant('base-ov-name')],
					$device[constant('observed-property')],
					$device[constant('uom')],
					$device[constant('stp-uris')],
					$device[constant('dev-type')],
					$device[constant('device-id')]
			);

			//make a request to the LD4S API for each resource type
			//re. OV
			$api_uri = LD4SApiMan::buildUrl(
					$ld4s_uri, $has_to_be_stored,
					LD4SApiMan::$code_sensor_readings,
					$ov[constant('sensor-id')]);
			//eventually append the context search criteria
			echo "ov-context=".$context['ov'];
			if ($context['settings_ov']){
				echo "**context for OV**";
				$data[LD4SApiMan::$code_sensor_readings][constant('context')] =
						$data[LD4SApiMan::$code_any][constant('context')];
			}
			// 			echo "making request for api-uri=".$api_uri." - data=".print_r(print_r($data[LD4SApiMan::$code_sensor_readings]));
			$response = LD4SApiMan::makeRequest($api_uri,
					json_encode($data[LD4SApiMan::$code_sensor_readings]),
					CURLOPT_POST, $serialization);
				

			// 			//re. Device
			$api_uri = LD4SApiMan::buildUrl($ld4s_uri, $has_to_be_stored, 
					LD4SApiMan::$code_device, $device[constant('device-id')]);
			
			if ($context['settings_device']){
				echo "**context for Device**";
				$data[LD4SApiMan::$code_device][constant('context')] =
						$data[LD4SApiMan::$code_any][constant('context')];
			}
			$response .= LD4SApiMan::makeRequest($api_uri,
					json_encode($data[LD4SApiMan::$code_device]), 
					CURLOPT_POST, $serialization);
		}
		return $response;
	}


	/**
	 * Parse the input that the user has provided in the "Sensor Reading" section of the GUI.
	 */
	public static function validateString($string){
		$ret = '';
		$ret = removeAllSpecialCharacters($string);
		return $ret;
	}


	private static function validateDate($date){
		$ret = false;
		$date_splitted = split('-', $date);
		if (sizeof($date_splitted) != '3'){
			$ret = false;
		}else{
			$ret = checkdate($date_splitted[1], $date_splitted[2], $date_splitted[0]);
		}
		return $ret;
	}

	private static function validateTime($string){
		// 		example of a valid string 00:00:00+00:00
		$ret = true;
		$time_zone = split('+',$string);
		$valid_tz = true;
		print_r($time_zone);
		if (sizeof($time_zone) != '2'){
			if(substr($string, -1) !== 'Z'){
				$valid_tz = false;
			}
		}else{
			$tz_split = split(':', $time_zone[1]);
			print_r($tz_split);
			if (sizeof($tz_split) != '2' || $tz_split[0] > '12' || $tz_split[1] > 60){
				$valid_tz = false;
			}
		}
		if ($valid_tz){
			$time_splitted = split(':', $time_zone[0]);
			if (sizeof($time_splitted) != '3'){
				$ret = false;
			}else if ($time_splitted[0] > '24' || $time_splitted[1] > '60' || $time_splitted[2] > '60'){
				$ret = false;
			}
		}
		return $ret;
	}


	public static function validateDateTime($string){
		//e.g. 1984-03-30T00:00:00+00:00
		$ret = true;
		$datetime = split('T', $string);
		if (sizeof($datetime) != '2'){
			$ret = false;
		}else{
			$ret = FormOutputParser::validateDate($datetime[0]);
			if ($ret){
				$ret = FormOutputParser::validateTime($datetime[1]);
			}
		}
		return $ret;
	}


	public static function validateHttpUri($uri){
		$ret = true;
		if (stripos($uri, 'http', 0) !== 0){ //i.e., if !$uri.startsWith('http')..
			$ret = false;
		}
		return $ret;
	}

	public static function validateReadingValues($values){
		$values = trim($values);
		return split(',', $values);
	}



}
?>