<?php
require_once '../constants.php';
require_once 'ApiMan.php';

/**
 *  Calls to the LD4S API */
/**
 * curl --request POST --data '{"tsproperties":[["id123","id456","id789","id101"]],
 "values":[["12.4","21.9","88.7","24.5"]],
 "start_range":["5800"],
 "end_range":["10321"]

{"values":[["0.1","0.1"]],"start_range":["1984-03-30T00:00:00+00:00"],"end_range":["1984-03-30T00:00:00+00:00"],
"uri":["http:\/\/www.exampllle.org"]}
 *@author iammyr_at_email.com
 *
 */
class LD4SApiMan 
extends ApiMan 
{

	public static $code_sensor_readings = "ov";
	public static $code_device = "device";

	/**
	 *
	 * @return array of array that will contain metadata divided per resource accepted by the LD4S API 1.0
	 */
	private static function initAPIResources(){
		$data = array("ov" => array(),
				"device" => array(),
				"tps" => array(),
				"platform" => array(),
				"tpp" => array(),
				"meas_capab" => array(),
				"meas_prop" => array(),
				"link" => array(),
				"link/feedback" => array(),
				"resource" => array(),
				"activity" => array(),
				"sn" => array()
		);
		return $data;
	}

	/**
	 * Validate the values that have to be passed as appended to the url,
	 * and eventually append them, to build a correct URL for the API request
	 * @param boolean $has_to_be_stored true if the data in the payload have to be stored by LD4S;
	 * 									false otherwise.
	 * @param string $resource_type_code code representing the resource type addressed in the request:
	 * 				1 ==> Sensor Readings
	 */
	public static function buildUrl($ld4s_url, $has_to_be_stored, $resource_type_code){
		if ($has_to_be_stored){
			//check and consider where the user wants to store his data
		}else{
			//if the submitted data refer to the ov resource
			if ($resource_type_code == LD4SApiMan::$code_sensor_readings){
				$ld4s_url .= "/".LD4SApiMan::$code_sensor_readings;
			}
		}
		return $ld4s_url;
	}


	/**
	 * Fill an array with the given data, assigning to each data a label
	 * that follows the LD4S API specification to submit a payload.
	 *
	 */
	public static function fillPayload($uri, $sensor_id, $start_time, $end_time, $values){
		$data = LD4SApiMan::initAPIResources();
		if ($values){
			$data['ov']['values'] = array($values);
		}
		if ($start_time){
			$data['ov']['start_range'] = array($start_time);
		}
		if ($end_time){
			$data['ov']['end_range'] = array($end_time);
		}
		if ($uri){
			$data['ov']['uri'] = array($uri);
		}
// 		echo "in fillpayload".print_r($data);
		return $data;
	}

	public static function makeRequest($url, $json_payload, $req_type, $accept_header){
		$ret_data = "";
		// 		echo "Called @ ".
		// 				xdebug_call_file().
		// 				":".
		// 				xdebug_call_line().
		// 				" from ".
		// 				xdebug_call_function();

		if(function_exists("curl_init")){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, $req_type, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-Type: application/json", $accept_header));
// 			echo "json=".$json_payload;
			$ret_data = curl_exec($ch);
			if(!$ret_data){;
				$ret_data = $url." is invalid.";
			}else{
				$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				// 			$info = curl_getinfo($ch);
				if ($http_status != 200){
					$ret_data = "Unable to contact the server at ".$url.". Response code: ".$http_status;
				}
			}
			curl_close($ch);
// 						echo "ret_data=\n".$ret_data."\nret_data with print_r:\n".print_r($ret_data);
// 						$data_array = explode(",",$ret_data);
// 						echo "\nexploded=".$data_array."\nwith print_r:\n".print_r($data_array);
// 				echo "respy=".$ret_data;
		}else{
			$ret_data = "Error: the CURL module for PHP is not installed.";
		}
		return $ret_data;
	}
}
?>