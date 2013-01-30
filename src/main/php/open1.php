<?php
require_once 'template.php';
$title = 'Spitfire | Annotate';
printHeader($title);
require_once 'common.php';


/**
 * fill the given array with all the data necessary as a LD4S API call's payload
 */
function fillPayload($data){
	//if (isset($_POST['reading_value'])){
	//echo "in reading value";
	// 		$data['ov']['values'] = $_POST['reading_value'];
	// 	}
	if (isset($_POST['start_time'])){
		$data['ov']['start_range'] = array($_POST['start_time']);
	}
	if (isset($_POST['end_time'])){
		$data['ov']['end_range'] = array($_POST['end_time']);
	}
	$data['ov']['uri'] = array("http://www.example.org/ov/");
	//echo "in fillpayload".print_r($data);
	return $data;
}

/**
 * forwards a $req_type request (e.g., CURLOPT_POST) using the cURL module, to the
 * specified $url and with the given payload
 * @param string $url
 * @param any $payload
 * @param constant $req_type (e.g. CURLOPT_POST)
 * @param accept_header HTML accept header as a string (e.g., "Accept: text/plain")
 * @return LD4S API answer as an array:
 */
function getAnswer($url, $payload, $req_type, $accept_header){
	// 	echo "json=".$payload."\n";
	echo "Called @ ".
			xdebug_call_file().
			":".
			xdebug_call_line().
			" from ".
			xdebug_call_function();
	
	if(!empty($url)){
		if(function_exists("curl_init"))
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, $req_type, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
			curl_setopt($ch, CURLOPT_HTTPHEADER,array("Content-Type: application/json", $accept_header));
				
			$ret_data = curl_exec($ch);
			$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			// 			$info = curl_getinfo($ch);
			curl_close($ch);
			// 			echo "ret_data=\n".$ret_data."\nret_data with print_r:\n".print_r($ret_data);
// 			$data_array = explode(",",$ret_data);
			// 			echo "\nexploded=".$data_array."\nwith print_r:\n".print_r($data_array);
			if ($http_status != 200){
				$ret_data = "Unable to contact the server at ".$url.". Response code: ".$http_status;
			}
			return $ret_data;
		}
	}
}
?>
</head>
<?php
$activeMenuItemNum = 4;
$body_onload = '';
$sidebar = '<div class="gadget">
		<h2 class="star"><span>What</span></h2>
		<div class="clr"></div>
		We help you getting linked data out of your raw sensor and sensor-related data (also via
		<a href="apiDocumentation.php">RESTful API</a>); abstracting over them
		and suggest semantic annotations according to your raw data stream.</div>
		<div class="gadget">
		<h2 class="star"><span>Why</span></h2>
		<div class="clr"></div>
		Linked Data add context through the links with external data sources. They are also based on RDF whose
		unambiguity and schema-independency features are key-enabler of
		<ul>
		<li>plug-and-play sensor nodes</li>
		<li>interoperability among different sensors.</li>
		</ul>
		</div>';

printBodyTop($activeMenuItemNum, $sidebar, $body_onload);


require_once '../../../lib/semsol-arc2/ARC2.php';

if(isset($_POST['generate'])) {
	//echo "in post generate";
	$ser_reading = "";
	//construct the JSON payload
	$data = initAPIResources();
	$data = fillPayload($data);

	//echo "payload=";print_r($data);
    $pref_mt = "";
	if (strcasecmp($_POST["serialization"], "JSON") == 0){
		$pref_mt = "application/json";
	}else
	if (strcasecmp($_POST["serialization"], "XML") == 0){
 		$pref_mt = "application/rdf+xml";
	}else
	if (strcasecmp($_POST["serialization"], "NTRIPLES") == 0){
		$pref_mt = "text/plain";
	}else
	if (strcasecmp($_POST["serialization"], "TURTLE") == 0){
		$pref_mt = "text/turtle";
	}

	//submit it to the suggested ld4s instance
	if (isset($_POST['ld4s_instances'])){
		$ld4s_arr = split(',',$_POST['ld4s_instances']);
		//echo "ld4s_arr=".print_r($ld4s_arr);
		$ld4s_curr_ind = 0;
		//echo "ld4sinstances - curr ind=".$ld4s_curr_ind;
		/** Calls to the LD4S API */
		/**
		 * curl --request POST --data '{"tsproperties":[["id123","id456","id789","id101"]],
		 "values":[["12.4","21.9","88.7","24.5"]],
		 "start_range":["5800"],
		 "end_range":["10321"]
		 */
		if(isset($ld4s_arr[$ld4s_curr_ind])){
			$url = $ld4s_arr[$ld4s_curr_ind];
			//echo "url=".$url;
			if (isset($_POST['store']) && $_POST['store'] == '1'){
					//check and consider where the user wants to store his data
				}else{
					//if there have been data submitted for the ov resource
					//echo "no store";
					//print_r($data['ov']);
					if ($data['ov']){
						$url .= "/ov";
						//echo $url;
						$ser_reading = getAnswer($url, json_encode($data['ov']), CURLOPT_POST, "Accept: ".$pref_mt);
					}
				}
		}

	}
}

?>
<p>
	Generate a RDF representation which describes your data.<br /> Interact
	with the RDF4Sensors application either by using our <a
		href="apiDocumentation.php">RESTful API</a> or by filling the form
	below.<br />

</p>
<form method="post">
	<span style="color: red">*Required fields</span><br /> <span>*Date /
		Time format: YYYY-MM-DDThh:mm:ssTZD where TZD = Time Zone Designator =
		Z (if GMT) or +hh:mm or -hh:mm </span><br /> <br />


	<div style="border-style: solid; border-width: thin;"
		id="sensor-reading-info">
		<span style="font-weight: bold;">Sensor Reading</span><br /> <span
			style="color: red">* Sensor ID :</span> <input type="text"
			name="sensor_id"
			value="<?php if (isset($_POST["sensor_id"])) echo $_POST["sensor_id"]; else echo ""; ?>" /><br />
		* Start Time: <input type="text" name="start_time"
			value="<?php if (isset($_POST["start_time"])) echo $_POST["start_time"]; else echo date(DATE_ATOM, mktime(0,0,0,3,30,1984)); ?>" /><br />
		* End Time: <input type="text" name="end_time"
			value="<?php if (isset($_POST["end_time"])) echo $_POST["end_time"]; else echo date(DATE_ATOM, mktime(0,0,0,3,30,1984)); ?>" /><br />
		Value/s (divided by ","): <input type="text" name="reading_value"
			value="<?php if (isset($_POST["reading_value"])) echo $_POST["reading_value"]; else echo "0.1,0.1"; ?>" /><br />
	</div>
	<br /> <select name="serialization">
		<option value="JSON"
		<?php if (isset($POST["serialization"]) && strcasecmp($_POST["serialization"], "JSON") == 0){echo "selected=\"selected\"";}?>>RDF/JSON</option>
		<option value="XML"
		<?php if (isset($_POST["serialization"]) && strcasecmp($_POST["serialization"], "XML") == 0) echo "selected=\"selected\"";  ?>>RDF/XML</option>
		<option value="NTRIPLES"
		<?php if (isset($_POST["serialization"]) && strcasecmp($_POST["serialization"], "NTRIPLES") == 0) echo "selected=\"selected\"";  ?>>N-Triples</option>
		<option value="TURTLE"
		<?php if (isset($_POST["serialization"]) && strcasecmp($_POST["serialization"], "TURTLE") == 0) echo "selected=\"selected\"";  ?>>Turtle</option>
	</select> <br /> <input type="checkbox" name="store" value="1" />Store
	somewhere <br /> LD4Sensor instances URIs (divided by ","): <input
		type="text" name="ld4s_instances"
		value="<?php if (isset($_POST['ld4s_instances'])) echo $_POST['ld4s_instances']; else echo ""; ?>" /><br />
	<input type="submit" name="generate" value="Generate" />

</form>

<?php 
if ($ser_reading){
	echo "<hr /><strong>Generated representation:</strong><br />";
	echo getPrintableCode($ser_reading);
}
?>