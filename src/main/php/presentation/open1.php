<?php
require_once 'template.php';
require_once 'common.php';
require_once 'formOutput.php';
require_once('../constants.php');

$title = 'Spitfire | Annotate';
printHeader($title);


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

function printSensorReadingSection(){
?>	<div style="border-style: solid; border-width: thin;"
				id="sensor-reading-info">
				<span style="font-weight: bold;">Sensor Reading</span><br /> <span
				style="color: red">
				* Sensor ID :</span> <input type="text"
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
				</div>
<?php }

function printSerializationOptionsSection(){$sid=constant('base-uri');echo $sid.' - '.$_POST[constant('base-uri')];
?>	<br /> Serialize as <select name="<?php echo constant('serialization');?>">
						<option value="JSON"
						<?php if (isset($_POST[constant('serialization')]) && strcasecmp($_POST[constant('serialization')], "JSON") == 0){echo "selected=\"selected\"";}?>>RDF/JSON</option>
						<option value="XML"
						<?php if (isset($_POST[constant('serialization')]) && strcasecmp($_POST[constant('serialization')], "XML") == 0) echo "selected=\"selected\"";  ?>>RDF/XML</option>
						<option value="NTRIPLES"
						<?php if (isset($_POST[constant('serialization')]) && strcasecmp($_POST[constant('serialization')], "NTRIPLES") == 0) echo "selected=\"selected\"";  ?>>N-Triples</option>
						<option value="TURTLE"
						<?php if (isset($_POST[constant('serialization')]) && strcasecmp($_POST[constant('serialization')], "TURTLE") == 0) echo "selected=\"selected\"";  ?>>Turtle</option>
						</select> <br />
						
						
<?php }

function printStorageOptionSection(){
?>
<input
	type="radio" name="<?php echo constant('store'); ?>" value="<?php echo constant('req-store1');?>" />
Ask LD4S to store the generated Linked Data on your behalf
<!-- a pop-up window asks for the available storage systems -->
<br />
<input
	type="radio" name="<?php echo constant('store'); ?>" value="<?php echo constant('req-store2');?>" checked="checked" />
I will store the generated Linked Data on
<input type="text"
	name="<?php echo constant('base-uri'); ?>"
	value="<?php if (isset($_POST[constant('base-uri')])) echo $_POST[constant('base-uri')];
			else "http://www.example.com"; ?>" />
<br />
<br />

<?php 

}

function printLD4SInstanceOptionSection(){

?>
LD4Sensor instance URI:
<input
	type="text" name="<?php echo constant('ld4s-instance'); ?>"
	value="<?php if (isset($_POST[constant('ld4s-instance')])) echo $_POST[constant('ld4s-instance')]; else echo ""; ?>" />
<br />
<?php 

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
<?php 
	printSensorReadingSection(); 
	printSerializationOptionsSection();
	printStorageOptionSection(); 
	printLD4SInstanceOptionSection();
	 ?>
	<input	type="submit" name="generate" value="Generate" />
</form>

<?php 




if(isset($_POST['generate'])) {
	$response = "";
	$serialization = "";
	// 1) validate the user's input
	echo $_POST[constant('start-time')];
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
	if (!FormOutputParser::validateReadingValues(($_POST[constant('reading-value')]))){
		echo 'Error:invalid Sensor Reading Values entered.';
	}
	// 2) submit all the provided data to obtain a response by the API
	$response = FormOutputParser::submitRequest($_POST[constant('ld4s-instance')], $_POST[constant('store')], $_POST[constant('serialization')],
			$_POST[constant('base-uri')], $_POST[constant('sensor-id')], $_POST[constant('start-time')], $_POST[constant('end-time')],
			$_POST[constant('reading-value')]);
echo "in generate";
}
if ($response){
	echo "<hr /><strong>Generated representation:</strong><br />";
	echo getPrintableCode($response);
}
?>

<?php 
$copyright_uri = 'http://www.spitfire-project.eu';
$copyright_name = 'SPITFIRE';
printFooter($copyright_uri, $copyright_name);
?>