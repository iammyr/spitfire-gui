<?php
require_once '../constants.php';
require_once 'ApiMan.php';
require_once '../presentation/common.php';

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

	private static $domains = array(
			'geo' => array("sws.geonames.org","ecowlim.tfri.gov.tw",
					"geo.linkeddata.es", "aemet.linkeddata.es"),
			'cross' => array("data.southampton.ac.uk", "dbpedia.org",
			"factforge.net"),
			'science' => array(),
			'gov' => array(),
			'pub' => array(),
			'user' => array(),
			'media' => array()
	);
	
	public static $code_sensor_readings = "ov";
	public static $code_device = "device";
	public static $code_any = "any";

	/**
	 *
	 * @return array of array that will contain metadata divided per 
	 * resource accepted by the LD4S API 1.0
	 */
	private static function initAPIResources(){
		$data = array(LD4SApiMan::$code_sensor_readings => array(),
				LD4SApiMan::$code_device => array(),
				LD4SApiMan::$code_any => array(),
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
	public static function buildUrl($ld4s_url, $has_to_be_stored, 
			$resource_type_code, $resource_id){
		
			//if the submitted data refer to the ov resource
			if ($resource_type_code == LD4SApiMan::$code_sensor_readings){
				$ld4s_url .= "/".LD4SApiMan::$code_sensor_readings;
				
			}else if ($resource_type_code == LD4SApiMan::$code_device){
				$ld4s_url .= "/".LD4SApiMan::$code_device;
			} 
			if ($has_to_be_stored){
				//check and consider where the user wants to store his data
				$ld4s_url .= '/'.$resource_id;
			}
		return $ld4s_url;
	}

	/**
	 * Fill an array with the given data, assigning to each data a label
	 * that follows the LD4S API specification to submit a payload.
	 *
	 */
	public static function fillPayload(
			$geo, $cross, $science,
			$gov, $pub, $user, $media,
			$locations_near, $locations_under, $locations_over,
			$timestart, $timeend, $things,
			$confidence,
			
			$sensor_id, $uri, $start_time, 
			$end_time, $values,	
					
			
			$base_time, $base_uri, $ov_ids, $ov_base_uri, 
			$observed_property,
			$uom, $stp_uris, $device_type, $device_id
			){
		
		$data = LD4SApiMan::initAPIResources();
		
		$data[LD4SApiMan::$code_any][constant('context')] = 
		array(LD4SApiMan::getContextQueryString($geo, $cross, $science, 
				$gov, $pub, $user, $media, $locations_near, $locations_under, 
				$locations_over, $timestart, $timeend, $things, $confidence));
		
		if ($uri){
			if ($sensor_id){
				$data[LD4SApiMan::$code_sensor_readings][constant('base-uri')] = array($uri.'/'.$sensor_id);
			}
			if ($device_id){
				$data[LD4SApiMan::$code_device][constant('base-uri')] = array($uri.'/'.$device_id);
			}
		}
		
		if ($sensor_id){
			$data[LD4SApiMan::$code_sensor_readings]['sensor_id'] = 
			array($sensor_id);
		}
		
		if ($values){
			print_r($values);
			$data[LD4SApiMan::$code_sensor_readings]['values'] = array($values);
		}
		
		if ($start_time){
			$data[LD4SApiMan::$code_sensor_readings]['start_range'] = array($start_time);
		}
		
		if ($end_time){
			$data[LD4SApiMan::$code_sensor_readings]['end_range'] = array($end_time);
		}

		
	if ($ov_ids){
			$data[LD4SApiMan::$code_device][constant('ov-ids')] = array($ov_ids);
		}

		if ($device_id){
			$data[LD4SApiMan::$code_device][constant('device-id')] = array($device_id);
		}

		if ($base_time){
			$data[LD4SApiMan::$code_device][constant('base-time')] = array($base_time);
		}

		if ($stp_uris){
			$data[LD4SApiMan::$code_device][constant('stp-uris')] = array(
					split(",",$stp_uris));
		}

		if ($ov_base_uri){
			$data[LD4SApiMan::$code_device][constant('base-ov-name')] = array($ov_base_uri);
		}

		if ($observed_property){
			$data[LD4SApiMan::$code_device][constant('observed-property')] = array($observed_property);
		}

		if ($uom){
			$data[LD4SApiMan::$code_device][constant('uom')] = array($uom);
		}

		if ($device_type){
			$data[LD4SApiMan::$code_device]['dev-type'] = array($device_type); // not implemented yet in the api
		}

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

// 		echo "url=".$url."with json=".$json_payload;
		if(function_exists("curl_init")){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, $req_type, true);
			echo "JSON-begins=".$json_payload."--JSON-ends to the url=".$url;
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-Type: application/json", "Accept: ".$accept_header));
			$ret_data = curl_exec($ch);
			if(!$ret_data){;
				$ret_data = $url." is invalid.";
			}else{
				$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				// 			$info = curl_getinfo($ch);
				if ($http_status != 200){
					$ret_data = "\nUnable to contact the server at ".$url."\nResponse code: ".$http_status;
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
	
// /*
// 	 * Creates a string fo the form  
// 	 * ?d=dbpedia.org%20OR%20rkbexplorer.com..
// 		&s=NEAR<any nested series of OR and AND in prefix notation and items separated by commas>
// 		UNDER<any nested series of OR and AND in prefix notation and items separated by commas>
// 		OVER<any nested series of OR and AND in prefix notation and items separated by commas>
// 		&th=<any nested series of OR and AND in prefix notation and items separated by commas>
// 		&trange=<start datetime>_<end datetime>
// 		as required for the LD4S API
// 	 */
// 	public static function getContextQueryString(
// 			$geo, $cross, $science,
// 			$gov, $pub, $user, $media, 
// 			$locations, $timestart, $timeend, $things,
// 			$confidence){
		
// 		$appendix = '';
// 		echo "getContextQuery=".$geo. $cross. $science.
// 			$gov. $pub. $user. $media. 
// 			$locations. $timestart. $timeend. $things.
// 			$confidence;
// 		return $appendix;
// 	}

	private static function appendDomainsToQuery($appendix, $inserted, $domain_name){
		if ($inserted){
			$appendix .= '%20OR%20';
		}
		$current = LD4SApiMan::$domains[$domain_name];
		echo "***current=".print_r($current)."***";
		$count = count($current);
		for($i = 0; $i < $count; $i++){
			$appendix .= $current[$i];
			if ($i+1 < $count){
				$appendix .= '%20OR%20';
			}
		}
		return $appendix;
	}
	/*
	 * Creates a string fo the form
	* ?d=dbpedia.org%20OR%20rkbexplorer.com..
	&s=NEAR<any nested series of OR and AND in prefix notation and items separated by commas>
	UNDER<any nested series of OR and AND in prefix notation and items separated by commas>
	OVER<any nested series of OR and AND in prefix notation and items separated by commas>
	&th=<any nested series of OR and AND in prefix notation and items separated by commas>
	&trange=<start datetime>_<end datetime>
	as required for the LD4S API
	example:
	
	"d=crossdomain%20OR%20geography" +
	"&s=NEAR(OR(shop1, shop2,shop3))UNDER(OR(home,d'avanzo,#
	AND(italy, OR(palace, building), bari),south-italy))" +
	"OVER(AND(floor,garden,OR(metro,train),sky))" +
	"&th=OR(red,AND(cotton,tshirt),tissue,dress)"
	*/
	public static function getContextQueryString(
			$geo, $cross, $science,
			$gov, $pub, $user, $media,
			$locations_near, $locations_under, $locations_over, 
			$timestart, $timeend, $things,
			$confidence){
	
				if ($geo){
					echo "geo is selected";
				}else{
					echo "geo is not selected";
				}
					
		$appendix = '';
		$inserted = false;
		if ($cross || $geo || $science || $gov || $pub || $user || $media ){
			if ($inserted){
				$appendix .= '&';
			}
			$appendix .= 'd=';
			
			if ($cross){
				echo "cross domain";
				$appendix .= 'crossdomain';
				$inserted = true;
			}
			if ($geo){
				if ($inserted){
					$appendix .= '%20OR%20';
				}
				$appendix .= 'geography';
				$inserted = true;
			}
			if ($science){
				if ($inserted){
					$appendix .= '%20OR%20';
				}
				$appendix .= 'lifescience';
				$inserted = true;
			}
			if ($gov){
				if ($inserted){
					$appendix .= '%20OR%20';
				}
				$appendix .= 'government';
				$inserted = true;
			}
			if ($pub){
				if ($inserted){
					$appendix .= '%20OR%20';
				}
				$appendix .= 'publication';
				$inserted = true;
			}
			if ($user){
				if ($inserted){
					$appendix .= '%20OR%20';
				}
				$appendix .= 'usergenerated';
				$inserted = true;
			}
			if ($media){
				if ($inserted){
					$appendix .= '%20OR%20';
				}
				$appendix .= 'media';
				$inserted = true;
			}
		}
		//THINGS
		if ($things){
			if ($inserted){
				$appendix .= '&';
			}
			$appendix .= 'th=';
			$appendix.='OR('.$things.')';
		}
		
		//LOCATION
		if ($locations_near || $locations_over || $locations_under){
			if ($inserted){
				$appendix .= '&';
			}
			$appendix .= 's=';
			if ($locations_near){
				$appendix .= 'NEAR(OR('.$locations_near.'))';
			}
			if ($locations_under){
				$appendix .= 'UNDER(OR('.$locations_under.'))';
			}
			if ($locations_over){
				$appendix .= 'OVER(OR('.$locations_over.'))';
			}
		}
		// 		?d=dbpedia.org%20OR%20rkbexplorer.com..
		// 		&s=NEAR<any nested series of OR and AND in prefix notation and items separated by commas>
		// 		UNDER<any nested series of OR and AND in prefix notation and items separated by commas>
		// 		OVER<any nested series of OR and AND in prefix notation and items separated by commas>
		// 		&th=<any nested series of OR and AND in prefix notation and items separated by commas>
		// 		&trange=<start datetime>_<end datetime>
		return $appendix;
	}
}
?>