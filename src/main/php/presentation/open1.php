<?php
require_once 'template.php';
require_once 'common.php';
require_once 'formOutput.php';
require_once('../constants.php');

$title = 'Spitfire | Annotate';
$main_folder = '../../';
printHeader($title, $main_folder);


?>
<script type="text/javascript">
function hidediv(divid) { 
	if (document.getElementById) { // DOM3 = IE5, NS6 
	document.getElementById(divid).style.visibility = 'hidden'; 
	} 
	else { 
	if (document.layers) { // Netscape 4 
	document.divid.visibility = 'hidden'; 
	} 
	else { // IE 4 
	document.all.divid.style.visibility = 'hidden'; 
	} 
	} 
	}

	function showdiv(divid) { 
	if (document.getElementById) { // DOM3 = IE5, NS6 
	document.getElementById(divid).style.visibility = 'visible'; 
	} 
	else { 
	if (document.layers) { // Netscape 4 
	document.divid.visibility = 'visible'; 
	} 
	else { // IE 4 
	document.all.divid.style.visibility = 'visible'; 
	} 
	} 
	} 


// Create a new CheckBoxGroup object
var myOptions = new CheckBoxGroup();

// Tell the object which checkboxes exist in your group. You may make multiple
// calls to this function, and/or pass multiple arguments. You may specify
// field names exactly, or use a wildcard at the beginning or end of the 
// name.
myOptions.addToGroup("context_*");

// Optionally set a "control" box which will effect all other boxes.
myOptions.setControlBox("context-all-sections");
// Specify how your control box will interact with the group. Options are
// either "some" or "all" ("all" is default).
// all: Checking the box will select all the other boxes. Unchecking it will
//      uncheck all the group checkboxes. Selecting all the checkboxes in
//      the group will automatically check the control box.
// some: Checking any checkbox in the group will check the control box. The
//       control box may not be unchecked if any option in the group is 
//       still checked. If no boxes in the group are checked, you may still
//       check the control box.
myOptions.setMasterBehavior("all");

// Optionally set the maximum number of boxes in the group which are allowed
// to be checked. You may pass a second argument which is an alert message to
// be displayed to the user if they exceed this limit.
//myOptions.setMaxAllowed(3);
//myOptions.setMaxAllowed(3,"You may only select 3 choices!");

function openWindow(url){

	popupWin = window.open ( url, 'remote', 'width=500,height=350' );
}


</script>
</head>
<?php
$activeMenuItemNum = 4;
$body_onload = "javascript:showdiv('hideShow');";
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

?>

<!-- MAIN START -->
<p>
Generate an RDF description of your data <br /> using LD4Sensors either
through our <a href="apiDocumentation.php">RESTful API</a> or by
filling the form below.<br />

</p>

<form method="post">
<span style="color: red">*Required fields</span><br /> <span>*Date /
Time format: YYYY-MM-DDThh:mm:ssTZD where TZD = Time Zone Designator =
Z (if GMT) or +hh:mm or -hh:mm </span><br /> <br />
<?php

printSelectedSectionsMenu();

printObservationValueSection();
printDeviceSection();

printSerializationOptionsSection();
printStorageOptionSection();
printLD4SInstanceOptionSection();

printContext();

?>
	<input type="submit" name="generate" value="Generate" />
</form>

<!-- MAIN END -->

<?php 



function printSelectedSectionsMenu(){
?>
<input type="checkbox" name="context-all-sections" value="ALL" onClick="myOptions.check(this)"
	<?php if ($_POST['context-all-sections']){echo "checked = \"\"";} ?> />

Select All<br />
 
<!--  <select name="actions">
<option value="1">Actions</option>
 <option value="2" onClick="openWindow('context.php')">Add Context</option>
</select>-->


<?php 
}

function printObservationValueSection(){
?>
<div style="border-style: solid; border-width: thin;"
	id="sensor-reading-info">
	
	<br /> 	
<input type="checkbox" name="context_ov" onClick="myOptions.check(this)" 
<?php if ($_POST['context_ov']){echo "checked = \"\"";} ?> />
	<span style="font-weight: bold;">Sensor Reading</span>	
	<br /> <br />
	
	<span style="color: red"> * Sensor ID :</span> <input type="text"
		name="<?php echo constant('sensor-id'); ?>"
		value="<?php if (isset($_POST[constant('sensor-id')])) echo $_POST[constant('sensor-id')]; else echo ""; ?>" /><br />
	* Start Time: <input type="text"
		name="<?php echo constant('start-time'); ?>"
		value="<?php if (isset($_POST[constant('start-time')])) echo $_POST[constant('start-time')]; else echo date(DATE_ATOM, mktime(0,0,0,3,30,1984)); ?>" /><br />
	* End Time: <input type="text"
		name="<?php echo constant('end-time'); ?>"
		value="<?php if (isset($_POST[constant('end-time')])) echo $_POST[constant('end-time')]; else echo date(DATE_ATOM, mktime(0,0,0,3,30,1984)); ?>" /><br />
	Value/s (divided by ","): <input type="text"
		name="<?php echo constant('reading-value'); ?>"
		value="<?php if (isset($_POST[constant('reading-value')])) echo $_POST[constant('reading-value')]; else echo "0.1,0.1"; ?>" /><br />
	<br />
</div>
<?php }


function printDeviceSection(){
	?>
<br />
<br />
<div style="border-style: solid; border-width: thin;" id="device-info">
	
	<br /> 
	<input type="checkbox" name="context_device" onClick="myOptions.check(this)" 
		<?php if ($_POST['context_device']){echo "checked = \"\"";} ?> />
	
	<span style="font-weight: bold;">Device, Sensing Device, Sensor</span>
	
	<br /><br /> 
	
	<span style="color: red"> * ID :</span> <input type="text"
		name="<?php echo constant('device-id'); ?>"
		value="<?php if (isset($_POST[constant('device-id')])) echo $_POST[constant('device-id')]; else echo ""; ?>" /><br />
	* Base Time: <input type="text"
		name="<?php echo constant('base-time'); ?>"
		value="<?php if (isset($_POST[constant('base-time')])) echo $_POST[constant('base-time')]; else echo date(DATE_ATOM, mktime(0,0,0,3,30,1984)); ?>" /><br />
	* Base URI: <input type="text"
		name="<?php echo constant('base-name'); ?>"
		value="<?php if (isset($_POST[constant('base-name')])) echo $_POST[constant('base-name')]; else echo "http://www.example.com/device/"; ?>" /><br />
	Sensor Readings IDs (divided by ","): <input type="text"
		name="<?php echo constant('ov-ids'); ?>"
		value="<?php if (isset($_POST[constant('ov-ids')])) echo $_POST[constant('ov-ids')]; else echo "reading1,reading2"; ?>" /><br />
	Sensor Readings URI's base: <input type="text"
		name="<?php echo constant('base-ov-name'); ?>"
		value="<?php if (isset($_POST[constant('base-ov-name')])) echo $_POST[constant('base-ov-name')]; else echo "http://www.example.com/reading"; ?>" /><br />
	Observed Property: <input type="text"
		name="<?php echo constant('observed-property'); ?>"
		value="<?php if (isset($_POST[constant('observed-property')])) echo $_POST[constant('observed-property')]; else echo "Temperature"; ?>" /><br />
	Unit of Measurement: <input type="text"
		name="<?php echo constant('uom'); ?>"
		value="<?php if (isset($_POST[constant('uom')])) echo $_POST[constant('uom')]; else echo "Centigrade"; ?>" /><br />
	Sensor Temporal Property URI/s (divided by ","): <input type="text"
		name="<?php echo constant('stp-uris'); ?>"
		value="<?php if (isset($_POST[constant('stp-uris')])) echo $_POST[constant('stp-uris')]; else echo "http://www.example.com/stp/1, http://www.example.com/stp/2"; ?>" /><br />

	Device type <select name="<?php echo constant('dev-type'); ?>">
		<option value="<?php echo constant('dev-type-device'); ?>"
		<?php if (isset($_POST[constant('dev-type')]) && strcasecmp($_POST[constant('dev-type')], constant('dev-type-device')) == 0){echo "selected=\"selected\"";}?>>Device</option>
		<option value="<?php echo constant('dev-type-sensing-device'); ?>"
		<?php if (isset($_POST[constant('dev-type')]) && strcasecmp($_POST[constant('dev-type')], constant('dev-type-sensing-device')) == 0) echo "selected=\"selected\"";  ?>>Sensing
			Device</option>
		<option value="<?php echo constant('dev-type-sensor'); ?>"
		<?php if (isset($_POST[constant('dev-type')]) && strcasecmp($_POST[constant('dev-type')], constant('dev-type-sensor')) == 0) echo "selected=\"selected\"";  ?>>Sensor</option>
	</select> <br /> <br />
</div>
<?php }

function printSerializationOptionsSection(){
$sid=constant('base-uri');echo $sid.' - '.$_POST[constant('base-uri')];
?>
<br />
Serialize as
<select name="<?php echo constant('serialization'); ?>">
	<option value="<?php echo constant('json'); ?>"
	<?php if (isset($_POST[constant('serialization')]) && strcasecmp($_POST[constant('serialization')], constant('json')) == 0){echo "selected=\"selected\"";}?>>RDF/JSON</option>
	<option value="<?php echo constant('xml'); ?>"
	<?php if (isset($_POST[constant('serialization')]) && strcasecmp($_POST[constant('serialization')], constant('xml')) == 0) echo "selected=\"selected\"";  ?>>RDF/XML</option>
	<option value="<?php echo constant('ntriple'); ?>"
	<?php if (isset($_POST[constant('serialization')]) && strcasecmp($_POST[constant('serialization')], constant('ntriple')) == 0) echo "selected=\"selected\"";  ?>>N-Triples</option>
	<option value="<?php echo constant('turtle'); ?>"
	<?php if (isset($_POST[constant('serialization')]) && strcasecmp($_POST[constant('serialization')], constant('turtle')) == 0) echo "selected=\"selected\"";  ?>>Turtle</option>
</select>
<br />


<?php }

function printStorageOptionSection(){
?>
<input
	type="radio" name="<?php echo constant('store'); ?>"
	value="<?php echo constant('req-store1');?>" />
Ask LD4S to store the generated Linked Data on your behalf
<!-- a pop-up window asks for the available storage systems -->
<br />
<input
	type="radio" name="<?php echo constant('store'); ?>"
	value="<?php echo constant('req-store2');?>" checked="checked" />
I will store the generated Linked Data on
<input type="text"
	name="<?php echo constant('base-uri'); ?>"
	value="<?php if (isset($_POST[constant('base-uri')])){ echo $_POST[constant('base-uri')];}
			else{ echo "http://www.example.com/resource";} ?>" />
<br />
<br />

<?php 

}

function printLD4SInstanceOptionSection(){

?>
LD4Sensor instance URI:
<input
	type="text" name="<?php echo constant('ld4s-instance'); ?>"
	value="<?php if (isset($_POST[constant('ld4s-instance')])) echo $_POST[constant('ld4s-instance')]; else echo "http://localhost:8182/ld4s"; ?>" />
<br />
<?php 

	}
	function printContext(){
		?>
	Context Search Setting: <a href="javascript:hidediv('hideShow')">Hide</a>
	<a href="javascript:showdiv('hideShow')"> - Show</a>
	<div id="hideShow"
		style="height: 220px; overflow: auto; border-style: double; background-color: #95B9F5; color: black;">
	If you want to link the data sections selected above with external information, in order 
	to better define their context:
	Which criteria do you want to apply in the search for external information?.<br />
	<input
	type="checkbox" name="cat_geo"
			<?php if (isset($_POST['cat_geo'])) {echo "checked = \"\"";} ?> />Geography
	<br />
	<input
	type="checkbox" name="cat_cross"
			<?php if (isset($_POST['cat_cross'])) {echo "checked = \"\"";} ?> />Cross-domain
	<br />
	<input type="checkbox" name="cat_gov"
					<?php if (isset($_POST['cat_gov'])){echo "checked = \"\"";}?> />Government
	<br /> <input type="checkbox"
					name="cat_media"
					<?php if (isset($_POST['cat_media'])){echo "checked = \"\"";}
					?> />Media<br /> <input type="checkbox"
					name="cat_user"
					<?php if (isset($_POST['cat_user'])){echo "checked = \"\"";}
					?> />User Generated Content<br /> <input
					type="checkbox" name="cat_pub"
					<?php if (isset($_POST['cat_pub'])){echo "checked = \"\"";}
					?> />Publication<br /> <input
					type="checkbox" name="cat_science"
					<?php if (isset($_POST['cat_science'])){echo "checked = \"\"";}
					?> />Life Science<br /> <br /> 
					
					Select criteria to consider while linking:<br /> <br />
				<strong>Locations (comma separated):</strong><br />
				NEAR : <input type="text"
					name="criteria_locations_near"
					<?php if (isset($_POST['criteria_locations_near']))
					{echo "value = \"".$_POST['criteria_locations_near']."\"";}
					?> /><br /> 
					 UNDER : <input type="text"
					name="criteria_locations_under"
					<?php if (isset($_POST['criteria_locations_under']))
					{echo "value = \"".$_POST['criteria_locations_under']."\"";}
					?> /><br /> 
				 OVER : <input type="text"
					name="criteria_locations_over"
					<?php if (isset($_POST['criteria_locations_over']))
					{echo "value = \"".$_POST['criteria_locations_over']."\"";}
					?> /><br /> 
					
					<strong>Time</strong><br />
					Start : 
					<input type="text"
		name="criteria_time_start"
		value="<?php if (isset($_POST[criteria_time_start])) 
			echo $_POST[criteria_time_start]; else echo date(DATE_ATOM, mktime(0,0,0,3,30,1984)); ?>" /><br />
					End : <input type="text"
					name="criteria_time_end"
		value="<?php if (isset($_POST[criteria_time_end])) 
			echo $_POST[criteria_time_end]; else echo date(DATE_ATOM, mktime(0,0,0,3,30,1984)); ?>" /><br />
					
					Things <input type="text"
					name="criteria_things"
					<?php if (isset($_POST['criteria_things']))
					{echo "value = \"".$_POST['criteria_things']."\"";}
					?> /><br /> Confidence Level threshold:
				<input type="text"
					value="<?php if (isset($_POST['criteria_confidence'])){ echo htmlspecialchars($_POST['criteria_confidence']);} else {echo "0.2"; } ?>"
					name="criteria_confidence" />
					</div>
	<?php 
	}
	
	function initOV(){
// 1) validate the user's input
if (!FormOutputParser::validateDateTime($_POST[constant('start-time')]) ||
!FormOutputParser::validateDateTime($_POST[constant('end-time')])){
	echo 'Error: invalid date-time entered. The correct format is: YYYY-MM-DDThh:mm:ssTZD where TZD = Time Zone Designator =
		Z (if GMT) or +hh:mm or -hh:mm';
}
if (!FormOutputParser::validateString($_POST[constant('sensor-id')])){
	echo 'Error: invalid characters entered. The sensor ID can contain only alphanumeric characters.';;
}
if (!FormOutputParser::validateHttpUri($_POST[constant('base-uri')])){
	echo 'Error:invalid URI entered.';
}
if (!FormOutputParser::validateHttpUri($_POST[constant('ld4s-instance')])){
	echo 'Error:invalid URI entered for LD4S.';
}
$values = FormOutputParser::validateReadingValues(($_POST[constant('reading-value')]));
if (!$values){
	echo 'Error:invalid Sensor Reading Values entered.';
}
$ov[constant('sensor-id')] = $_POST[constant('sensor-id')];
$ov[constant('base-uri')] = $_POST[constant('base-uri')];
$ov[constant('start-time')] = $_POST[constant('start-time')];
$ov[constant('end-time')] = $_POST[constant('end-time')];
$ov[constant('reading-value')] = $values;

return $ov;
}

function initDevice(){
$device[constant('device-id')] = $_POST[constant('device-id')];
$device[constant('base-time')] = $_POST[constant('base-time')];
$device[constant('base-name')] = $_POST[constant('base-name')];
$device[constant('ov-ids')] = $_POST[constant('ov-ids')];
$device[constant('base-ov-name')] = $_POST[constant('base-ov-name')];
$device[constant('observed-property')] = $_POST[constant('observed-property')];
$device[constant('uom')] = $_POST[constant('uom')];
$device[constant('stp-uris')] = $_POST[constant('stp-uris')];
$device[constant('dev-type')] = $_POST[constant('dev-type')];
return $device;
}

function initContext(){
// echo "geo=".print_r($_POST['cat_geo'])."-contextov=".$_POST['context-ov']
// ."-contextdevice=".$_POST['context-device'];

$context['geo'] = $_POST['cat_geo'];
$context['cross'] = $_POST['cat_cross'];
$context['science'] = $_POST['cat_science'];
$context['gov'] = $_POST['cat_gov'];
$context['pub'] = $_POST['cat_pub'];
$context['user'] = $_POST['cat_user'];
$context['media'] = $_POST['cat_media'];
$context[ 'criteria_locations_near'] = $_POST['criteria_locations_near'];
$context[ 'criteria_locations_under'] = $_POST['criteria_locations_under'];
$context[ 'criteria_locations_over'] = $_POST['criteria_locations_over'];
$context['criteria_time_start'] = $_POST['criteria_time_start'];
$context['criteria_time_end'] = $_POST['criteria_time_end'];
$context['criteria_things'] = $_POST['criteria_things'];
$context['criteria_confidence'] = $_POST['criteria_confidence'];
$context['settings_ov'] = $_POST['context_ov'];
$context['settings_device'] = $_POST['context_device'];
return $context;
}
	?>




<?php 

if(isset($_POST['generate'])) {
	$response = "";
	$serialization = "";
	
	
	// 2) submit all the provided data to obtain a response from the API
$ov = initOV(); 
$device = initDevice();
$context = initContext();
	$response='';
// 	print_r($context);
	$response = FormOutputParser::submitRequest4LD4S($_POST[constant('ld4s-instance')], 
$_POST[constant('store')], $_POST[constant('serialization')], $ov, $device, $context			
 );

	if ($response){
		echo "<hr /><strong>Generated representation:</strong><br />";
		if ($_POST[constant('ld4s-instance')]){
// 			echo getPrintableCode($response, '/'.$_POST[constant('ld4s-instance')].'(/'.'?)s([.^\s|"]*)/');
			echo getPrintableCode($response);
		}else{
			echo getPrintableCode($response, null);
		}
		//$count = count($response);
		// 		for($i = 0; $i < $count; $i++){
		//     		if($array[$i][0] == ' '){
		//         		if($i > 0){
		//             	 	$array[$i-1] .= $array[$i];
		// //              		unset($array[$i]);
		//         		}
		//     		}
		// 		}
		
		
	}
}



?>

<?php 
$copyright_uri = 'http://www.spitfire-project.eu';
$copyright_name = 'SPITFIRE';
printFooter($copyright_uri, $copyright_name);
?>