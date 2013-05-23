<?php 

Abstract class ApiMan {
	
	/**
	 * Initializes an array containing all the resources provided by the API
	 */
	private static function initAPIResources(){
		
	}
	
	/**
	 * Builds the URL that has to be invoked according to the API specifications,
	 * the resource that is addressed and the given options.
	 * @param String $base_url
	 * @param array $options
	 * @param String $resource_type_code
	 */
	private static function buildUrl(String $base_url, boolean $has_to_be_stored, String $resource_type_code){
		
	}
	
	/**
	 * Forwards an HTTP request of the speficied type (e.g., POST)
	 * to the API using the given URL, payload and accept-header. 
 	 * @param string $url
	 * @param any $payload
	 * @param constant $req_type (e.g. CURLOPT_POST)
	 * @param accept_header HTML accept header as a string (e.g., "Accept: text/plain")
	 * @return API answer 
	 */
	public static function makeRequest(String $url, String $json_payload, $req_type, String $accept_header){
		
	}
	
	
}

?>