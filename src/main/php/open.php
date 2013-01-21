<?php
require_once 'template.php';
$title = 'Spitfire | Annotate';
printHeader($title);
?>

<script>
var counter = 1;
var condition_counters = new Array();
condition_counters[1] = 1; //since the first capability has one default condition already set 
var limit = 3;
function addInput(divName){
//  if (counter == limit)  {
//       alert("You have reached the limit of adding " + counter + " inputs");
//  }
//  else {
       var newdiv = document.createElement('div');
       condition_counters[counter+1] = 1;
       newdiv.innerHTML = "Sensor Capability("+ (counter + 1) +"): <input type='text' name='myInputs[]' value='' /> Property - min/max - unitOfMeasurement,symbol "
       + "<div id='conditions_set'><div id='conditions"+ (counter + 1) +"'>"
       + "InCondition(1): <input type='text'	name='myCondition["+ (counter + 1) +"][]' value=''/> <input type='button' value='Add more Conditions' onclick='addCondition('conditions"
       + (counter + 1) +"', "+ (counter + 1) +");' />   	</div>	<br />";
       document.getElementById(divName).appendChild(newdiv);
       counter++;
//  }
}
function addCondition(divName, capabNumber){
//  if (counter == limit)  {
//       alert("You have reached the limit of adding " + counter + " inputs");
//  }
//  else {	
       var newdiv = document.createElement('div');
       condition_counters[capabNumber]++;
       newdiv.innerHTML = "In Condition("+ condition_counters[capabNumber] +"): <input type='text' name='myCondition["+ capabNumber +"]["
       + condition_counters[capabNumber] +"]' value='' /> Property - min/max - unitOfMeasurement,symbol";
       document.getElementById(divName).appendChild(newdiv);
       
//  }
}
</script>
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
?>
<?php 
//require_once 'resources/AbstractResource.php';
//require_once 'resources/TripleCreator.php';
//require_once 'resources/ConceptHandler.php';

$intrinsic_index = array();
$additional_node_index = array();
$additional_owner_index = array();
$ser_intrinsic = '';
$ser_intrinsic_sider = '';
$ser_additional_node = '';
$ser_additional_owner = '';

$ser_reading = '';
$ser_sensor = '';
$ser_platform = '';

if(isset($_POST['generate'])) {
	if (!$_POST['sensor_id']
	//|| !$_POST['uom'] ||
	//!$_POST['observed_property'] ||
	//!$_POST['observed_value']
	)
	{
		?>
<p>
	<span style="color: red">Error: One or more of the required fields have
		been left empty</span>
</p>
<?php
	}else{
		/** Initialize the local variables with the submitted (via POST) content */
		$sensor_id = $_POST['sensor_id'];
		//	$sensor_id = conceptCleaner($sensor_id);
		$start_time = $_POST['start_time'];
		$end_time = $_POST['end_time'];
		$reading_value = $_POST['reading_value'];

		$observed_property = $_POST['observed_property'];
		$arr = split(" , ", $_POST['uom']);
		if (sizeof($arr) == 2){
			$uom_uri = getValue('uom', $arr[0]);
			$uom_symbol = $arr[1];
		}else{
			$uom_uri = getValue('uom', $_POST['uom']);
			$uom_symbol = '';
		}
		$observed_property_uri = getValue("property", $observed_property);
		$observed_value = $_POST['observed_value'];
		$timestamp = $_POST['timestamp'];
		//$sensor_id_uri = AbstractResource::$resource_base.'sensor/'.trim($sensor_id);


		// 		---------------------------------------------------------------------

		$capabilities = $_POST["myInputs"];
		$conditions = $_POST["myCondition"];
		$model = $_POST['sensor_model'];
		$model_uri = '';
		$manual_uri = '';
		$stimulus_uri = '';
		if ($model){
			//$model_uri = getValue('sensor_model',$model);
		}
		$manual = $_POST['sensor_manual'];
		if ($manual){
			//$manual_uri = getValue('sensor_manual', $manual);
		}
		$stimulus = $_POST['sensor_stimulus'];
		if ($stimulus){
			//$stimulus_uri = getValue('sensor_stimulus', $stimulus);
		}
		//$additional_node_index = getAdditionalNodeTriples($sensor_id_uri, $model_uri, $manual_uri, $stimulus_uri, $capabilities, $observed_property_uri, $conditions);


		//		---------------------------------------------------------------------

		$sensor_owner = $_POST['sensor_owner'];
		if ($sensor_owner){
			//$sensor_owner = getValue('sensor_owner', $sensor_owner, true, false);
		}
		$sensor_publisher = $_POST['sensor_publisher'];
		if ($sensor_publisher){
			//$sensor_publisher = getValue('sensor_publisher', $sensor_publisher, true, false);
		}
		$sensor_location = $_POST['sensor_location'];
		if ($sensor_location){
			//$sensor_location = getValue('sensor_location', $sensor_location, false, true);
		}
		$foi = $_POST['sensor_foi'];
		if ($foi){
			//$foi = getValue('foi', $foi);
		}
		$foi_name = $_POST['sensor_foi_name'];
		if ($foi_name){
			//$foi_name = getValue('foi', $foi_name, false, false, true);
		}
		$platform_uri = $_POST['sensor_platform'];
		if ($platform_uri){
			//$platform_uri = getValue('sensor_platform', $platform_uri);
		}
		$history_uri = $_POST['sensor_history'];
		if ($history_uri){
			//$history_uri = getValue('sensor_history', $history_uri);
		}
		//$additional_owner_index +=	getAdditionalOwnerTriples($sensor_id_uri, $sensor_owner, $sensor_publisher, $sensor_location, $foi, $platform_uri, $history_uri, $foi_name);

		//		---------------------------------------------------------------------
		$serialization_pref = $_POST['serialization'];
		//$temp = array($sensor_id_uri => $intrinsic_index[$sensor_id_uri]);
		//unset($intrinsic_index[$sensor_id_uri]);
		//$ser_intrinsic = getRDFSerialization($serialization_pref, $temp);
		//$ser_intrinsic_sider = getRDFSerialization($serialization_pref, $intrinsic_index);
		//$ser_additional_node = getRDFSerialization($serialization_pref, $additional_node_index);
		//$ser_additional_owner = getRDFSerialization($serialization_pref, $additional_owner_index);
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
			value="<?php if (isset($sensor_id)) echo $sensor_id; else echo ""; ?>" /><br />
		* Start Time: <input type="text" name="start-time"
			value="<?php if (isset($start_time)) echo $start_time; else echo date(DATE_ATOM, mktime(0,0,0,3,30,1984)); ?>" /><br />
		* End Time: <input type="text" name="end-time"
			value="<?php if (isset($end_time)) echo $end_time; else echo date(DATE_ATOM, mktime(0,0,0,3,30,1984)); ?>" /><br />
		Value/s (divided by ","): <input type="text" name="reading-value"
			value="<?php if (isset($reading_value)) echo $reading_value; else echo "0.1,0.1"; ?>" /><br />
	</div>
	<br />

	<div style="border-style: solid; border-width: thin;" id="sensor-info">
		<span style="font-weight: bold;">Sensor</span><br /> <span
			style="color: red">* Unit of Measurement:</span> <input type="text"
			name="uom"
			value=<?php if (isset($uom_uri) && isset($uom_symbol)) echo '"'.$uom_uri. ' , '.$uom_symbol.'"'; else echo '"Centigrades , C"'; ?> /><br />
		<span style="color: red">* Observed Property:</span> <input
			type="text" name="observed_property"
			value="<?php if (isset($observed_property)) echo $observed_property; else echo "Temperature"; ?>" /><br />
		<span style="color: red">* Observed Value:</span> <input type="text"
			name="observed_value"
			value="<?php if (isset($observed_value)) echo $observed_value; else echo "10.2"; ?>" /><br />
		<span style="color: red">* Time (YYYY-MM-DDThh:mmTZD)</span> <input
			type="text" name="timestamp"
			value="<?php if (isset($timestamp)) echo $timestamp; else {date_default_timezone_set('UTC'); echo date("y-m-d\TG:i\Z");} ?>" />
		<br /> TZD = time zone designator(Z or +hh:mm or -hh:mm) <br />
	</div>
	<br />


	<div style="border-style: solid; border-width: thin;"
		id="platform-info">
		<span style="font-weight: bold;">Platform / Testbed</span><br />
		Sensor Model : <input type="text" name="sensor_model"
			value="<?php if (isset($model)) echo $model; else echo ""; ?>" /><br />
		Sensor Manual: <input type="text" name="sensor_manual"
			value="<?php if (isset($manual)) echo $manual; else echo ""; ?>" /><br />
		Sensor Stimulus: <input type="text" name="sensor_stimulus"
			value="<?php if (isset($stimulus)) echo $stimulus; else echo "silver expansion"; ?>" /><br />
	</div>
	<br />

	<div style="border-style: solid; border-width: thin;"
		id="measurement-info">
		<span style="font-weight: bold;">Measurement Capability</span><br />
		<div id="capabilities">
			<?php if (isset($capabilities)&& $capabilities){
				$count = 1;
				foreach ($capabilities as $capab){
		?>
			<br /> Sensor Capability(
			<?php echo $count;?>
			): <input type="text" name="myInputs[]" value="<?php echo $capab; ?>" />
			Property - min/max - unitOfMeasurement,symbol
			<div id="conditions_set">
				<?php 
if (isset($conditions[$count])&& $conditions[$count]){?>
				<div id="conditions<?php echo $count;?>">
					<?php 
					$condition_count = 1;
					foreach($conditions as $cond){	?>
					InCondition(
					<?php echo $condition_count;?>
					): <input type="text" name="myCondition[<?php echo $count;?>][]"
						value="<?php echo $cond; ?>" />
					<?php
					if ($condition_count === 2){
		?>
					<input type="button" value="Add more Conditions"
						onclick="addCondition('conditions<?php echo $count;?>', <?php echo $count;?>);" />
					<?php }
					$condition_count++;
} ?>
				</div>
				<?php }
				if ($count === 2){
	?>
			</div>
			<br /> <input type="button" value="Add more"
				onclick="addInput('capabilities' );" />
			<?php 
}
$count++;
	}

			}else{?>
			<br /> Sensor Capability(1): <input type="text" name="myInputs[]"
				value="Battery Life Time - 1/2 - month,m" /> Property - min/max -
			unitOfMeasurement,symbol;
			<div id="conditions_set">
				<div id="conditions1">
					InCondition(1): <input type="text" name="myCondition[1][]"
						value="Temperature - -40/-35 - Fahrenheit,F" /> <input
						type="button" value="Add more Conditions"
						onclick="addCondition('conditions1', 1);" />
				</div>
			</div>
			<br /> <input type="button" value="Add more Capabilities"
				onclick="addInput('capabilities');" />
			<?php }?>
		</div>
	</div>
	<br />

	<div style="border-style: solid; border-width: thin;" id="device-info">
		<span style="font-weight: bold;">Device</span><br /> Sensor Owner: <input
			type="text" name="sensor_owner"
			value="<?php if (isset($sensor_owner)) echo $sensor_owner; else echo ""; ?>" /><br />
		Sensor Data Publisher: <input type="text" name="sensor_publisher"
			value="<?php if (isset($sensor_publisher)) echo $sensor_publisher; else echo ""; ?>" /><br />
		Location: <input type="text" name="sensor_location"
			value="<?php if (isset($sensor_location)) echo $sensor_location; else echo ""; ?>" /><br />
		Attached Platform URI: <input type="text" name="sensor_platform"
			value="<?php if (isset($platform_uri)) echo $platform_uri; else echo ""; ?>" /><br />
		History Archive URI: <input type="text" name="sensor_history"
			value="<?php if (isset($history_uri)) echo $history_uri; else echo ""; ?>" /><br />
		Feature of Interest(FOI): <input type="text" name="sensor_foi_name"
			value="<?php if (isset($foi_name)) echo $foi_name; else echo "Room 12"; ?>" />
		FOI category: <input type="text" name="sensor_foi"
			value="<?php if (isset($foi)) echo $foi; else echo "Room"; ?>" /> <br />

	</div>
	<br />

	<div style="border-style: solid; border-width: thin;"
		id="activity-info">
		<span style="font-weight: bold;">Activity / Stimulus</span><br />
		Sensor Owner: <input type="text" name="sensor_owner"
			value="<?php if (isset($sensor_owner)) echo $sensor_owner; else echo ""; ?>" /><br />
		Sensor Data Publisher: <input type="text" name="sensor_publisher"
			value="<?php if (isset($sensor_publisher)) echo $sensor_publisher; else echo ""; ?>" /><br />
		Location: <input type="text" name="sensor_location"
			value="<?php if (isset($sensor_location)) echo $sensor_location; else echo ""; ?>" /><br />
		Attached Platform URI: <input type="text" name="sensor_platform"
			value="<?php if (isset($platform_uri)) echo $platform_uri; else echo ""; ?>" /><br />
		History Archive URI: <input type="text" name="sensor_history"
			value="<?php if (isset($history_uri)) echo $history_uri; else echo ""; ?>" /><br />
		Feature of Interest(FOI): <input type="text" name="sensor_foi_name"
			value="<?php if (isset($foi_name)) echo $foi_name; else echo "Room 12"; ?>" />
		FOI category: <input type="text" name="sensor_foi"
			value="<?php if (isset($foi)) echo $foi; else echo "Room"; ?>" /> <br />

	</div>
	<br />

	<div style="border-style: solid; border-width: thin;"
		id="sensor-network-info">
		<span style="font-weight: bold;">Sensor Network</span><br /> Sensor
		Owner: <input type="text" name="sensor_owner"
			value="<?php if (isset($sensor_owner)) echo $sensor_owner; else echo ""; ?>" /><br />
		Sensor Data Publisher: <input type="text" name="sensor_publisher"
			value="<?php if (isset($sensor_publisher)) echo $sensor_publisher; else echo ""; ?>" /><br />
		Location: <input type="text" name="sensor_location"
			value="<?php if (isset($sensor_location)) echo $sensor_location; else echo ""; ?>" /><br />
		Attached Platform URI: <input type="text" name="sensor_platform"
			value="<?php if (isset($platform_uri)) echo $platform_uri; else echo ""; ?>" /><br />
		History Archive URI: <input type="text" name="sensor_history"
			value="<?php if (isset($history_uri)) echo $history_uri; else echo ""; ?>" /><br />
		Feature of Interest(FOI): <input type="text" name="sensor_foi_name"
			value="<?php if (isset($foi_name)) echo $foi_name; else echo "Room 12"; ?>" />
		FOI category: <input type="text" name="sensor_foi"
			value="<?php if (isset($foi)) echo $foi; else echo "Room"; ?>" /> <br />

	</div>
	<br />

	<div style="border-style: solid; border-width: thin;" id="link-info">
		<span style="font-weight: bold;">Link</span><br /> Sensor Owner: <input
			type="text" name="sensor_owner"
			value="<?php if (isset($sensor_owner)) echo $sensor_owner; else echo ""; ?>" /><br />
		Sensor Data Publisher: <input type="text" name="sensor_publisher"
			value="<?php if (isset($sensor_publisher)) echo $sensor_publisher; else echo ""; ?>" /><br />
		Location: <input type="text" name="sensor_location"
			value="<?php if (isset($sensor_location)) echo $sensor_location; else echo ""; ?>" /><br />
		Attached Platform URI: <input type="text" name="sensor_platform"
			value="<?php if (isset($platform_uri)) echo $platform_uri; else echo ""; ?>" /><br />
		History Archive URI: <input type="text" name="sensor_history"
			value="<?php if (isset($history_uri)) echo $history_uri; else echo ""; ?>" /><br />
		Feature of Interest(FOI): <input type="text" name="sensor_foi_name"
			value="<?php if (isset($foi_name)) echo $foi_name; else echo "Room 12"; ?>" />
		FOI category: <input type="text" name="sensor_foi"
			value="<?php if (isset($foi)) echo $foi; else echo "Room"; ?>" /> <br />

	</div>
	<br />

	<div style="border-style: solid; border-width: thin;"
		id="link-review-info">
		<span style="font-weight: bold;">Link Feedback</span><br /> Sensor
		Owner: <input type="text" name="sensor_owner"
			value="<?php if (isset($sensor_owner)) echo $sensor_owner; else echo ""; ?>" /><br />
		Sensor Data Publisher: <input type="text" name="sensor_publisher"
			value="<?php if (isset($sensor_publisher)) echo $sensor_publisher; else echo ""; ?>" /><br />
		Location: <input type="text" name="sensor_location"
			value="<?php if (isset($sensor_location)) echo $sensor_location; else echo ""; ?>" /><br />
		Attached Platform URI: <input type="text" name="sensor_platform"
			value="<?php if (isset($platform_uri)) echo $platform_uri; else echo ""; ?>" /><br />
		History Archive URI: <input type="text" name="sensor_history"
			value="<?php if (isset($history_uri)) echo $history_uri; else echo ""; ?>" /><br />
		Feature of Interest(FOI): <input type="text" name="sensor_foi_name"
			value="<?php if (isset($foi_name)) echo $foi_name; else echo "Room 12"; ?>" />
		FOI category: <input type="text" name="sensor_foi"
			value="<?php if (isset($foi)) echo $foi; else echo "Room"; ?>" /> <br />

	</div>
	<br /> <br /> Preferred RDF serialization: <select name="serialization">
		<option value="JSON"
		<?php if (isset($_POST['serialization']) && strcasecmp($_POST['serialization'], 'JSON') == 0) echo 'selected="selected"';  ?>>RDF/JSON</option>
		<option value="XML"
		<?php if (isset($_POST['serialization']) && strcasecmp($_POST['serialization'], 'XML') == 0) echo 'selected="selected"';  ?>>RDF/XML</option>
		<option value="NTRIPLES"
		<?php if (isset($_POST['serialization']) && strcasecmp($_POST['serialization'], 'NTRIPLES') == 0) echo 'selected="selected"';  ?>>N-Triples</option>
		<option value="TURTLE"
		<?php if (isset($_POST['serialization']) && strcasecmp($_POST['serialization'], 'TURTLE') == 0) echo 'selected="selected"';  ?>>Turtle</option>
	</select><br /> <input type="checkbox" name="store" value="1" />Store
	somewhere <br /> LD4Sensor instances' URIs (divided by ','): <input
		type="text" name="ld4s-instances"
		value="<?php if (isset($ld4s_instances)) echo $ld4s_instances; else echo ""; ?>" /><br />
	<input type="submit" name="generate" value="Generate" />
</form>



<?php

/** LD4S instances initialization */
if (isset($ld4s_instances)){
 $ld4s_arr = split(',',$ld4s_instances);
$ld4s_curr_ind = 0; 
	 /** Calls to the LD4S API */
	 //$intrinsic_index += getIntrinsicTriples($sensor_id_uri, $observed_property_uri, $uom_symbol, $uom_uri, $observed_value, $timestamp);
	 if (isset($sensor_id) || isset($start_time) || isset($end_time) || isset($reading_value)){
			/**
			 * curl --request POST --data '{"tsproperties":[["id123","id456","id789","id101"]],
			 "values":[["12.4","21.9","88.7","24.5"]],
			 "start_range":["5800"],
			 "end_range":["10321"]
			 */
		if(isset($ld4s_arr[$ld4s_curr_ind])){
			$url = $ld4s_arr[$ld4s_curr_ind];
			if (isset($_POST['store']) && $_POST['store'] == '1'){
				//check where the user wants to store
			}else{
				$url += "/ov";
			}
			if(!empty($url)){
				//create the json object to submit in the call
				if(function_exists("curl_init"))
				{
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
					curl_setopt($ch, CURLOPT_HTTPHEADER, 
					array("Content-Type: application/json","Content-length: ".strlen($data)));
					$ret_data = curl_exec($ch);
				
					curl_close($ch);
				
					$data_array = explode(",",$ret_data);
					return $data_array;
				}
			}
		}
	}

}
//$ser_reading = callToLD4S()

if ($ser_intrinsic){ ?>

<h3>Generated description of your Sensor</h3>
<p>
	Your Sensor Unique Resource Identifier(URI) is
	<?php echo $sensor_id_uri; ?>
	<br /> <br /> Chosen RDF serialization language =
	<?php echo $serialization_pref; ?>
</p>

<div>
	<span style="font-weight: bold;">Intrinsic node description - to be
		stored on the sensor node:</span>
	<div id="serialization_intrinsic" style="overflow: auto">
		<?php echo $ser_intrinsic; ?>
	</div>
</div>

<div>
	<span style="font-weight: bold;">Sider information regarding the
		intrinsic node description - to be stored anywhere:</span>
	<div id="serialization_intrinsic_sider" style="overflow: auto">
		<?php echo $ser_intrinsic_sider; ?>
	</div>
</div>

<?php if ($ser_additional_node){ ?>
<div>
	<span style="font-weight: bold;">Additional node-dependent description
		- to be stored anywhere:</span>
	<div id="serialization_additional_node" style="overflow: auto">
		<?php echo $ser_additional_node; ?>
	</div>
</div>

<?php } if ($ser_additional_owner){ ?>
<div>
	<span style="font-weight: bold;">Additional owner-dependent description
		- to be stored anywhere:</span>
	<div id="serialization_additional_owner" style="overflow: auto">
		<?php echo $ser_additional_owner; ?>
	</div>
</div>

<?php }?>

<?php }
$copyright_uri = 'http://www.deri.ie';
$copyright_name = 'DERI';
printFooter($copyright_uri, $copyright_name);

?>





