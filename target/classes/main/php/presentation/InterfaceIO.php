<?php 
interface InterfaceIO {
	
	/**
	 * Forwards the given (not null) parameters to the API Manager that is able to handle them.
	 * Returns a multidimensional array: rows = API resource to eb addressed; columns = parameter's label ; value.    
	 * @param String $uri
	 * @param String $sensor_id
	 * @param String $start_time
	 * @param String $end_time
	 * @param String $values  - values divided by ','
	 */
	public static function submitRequest(String $ld4s_uri, String $store_option, 
			String $serialization, String $uri, String $sensor_id, String $start_time,
			 String $end_time, String $values);
	/**
	 * Returns a string representing the HTTP media type corresponding to the given RDF serialization language.
	 * @param unknown $serialization_lang
	 */
	public static function parseSerializationOptions($serialization_lang);
	
	public static function validateString(String $string);
	
	public static function validateDateTime(String $string);
	
	public static function validateHttpUri(String $uri);
	
	public static function validateReadingValues(String $values);
	
	public static function getAnswer(String $url, String $json_payload, $req_type, String $accept_header);
	
}

?>