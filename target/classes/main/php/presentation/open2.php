<?php
require_once 'template.php';
require_once 'common.php';
require_once 'formOutput.php';

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
// if(isset($_POST['generate'])) {
// 	$response = "";
// 	$serialization = "";
// 	// 1) validate the user's input
// 	if (!FormOutputParser::validateDateTime($_POST['start_time']) || !validateDateTime($_POST['end_time'])){
// 		echo 'Error: invalid date-time entered. The required format is
// 		year-month-dayThour:minutes:secondsTZ ; where "T" divides date from time while
// 		"TZ" stands for "time zone" and should be substituted by "Z" in case of GMT or "+hours:minutes"
// 		or "-hours:minutes" in case of either a positive (+) or negative (-) shift from the GMT time.';
// 	}
// 	if (!FormOutputParser::validateSttring($_POST['sensor_id'])){
// 		echo 'Error: invalid characters entered. The sensor ID can contain only alphanumeric characters.';;
// 	}
// 	if (!FormOutputParser::validateUri($_POST['uri'])){
// 		echo 'Error:invalid URI entered.';
// 	}
// 	if (!FormOutputParser::validateUri($_POST['ld4s_instance'])){
// 		echo 'Error:invalid URI entered for LD4S.';
// 	}
// 	if (!FormOutputParser::validateReadingValues(($_POST['reading_value']))){
// 		echo 'Error:invalid Sensor Reading Values entered.';
// 	}
// 	// 2) submit all the provided data to obtain a response by the API
// 	$response = FormOutputParser::submitRequest($_POST['ld4s_instance'], $_POST['store'], $_POST["serialization"],
// 			$_POST['uri'], $_POST['sensor_id'], $_POST['start_time'], $_POST['end_time'],
// 			$_POST['reading_value']);
// }
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
	<br /> Serialize as <select name="serialization">
		<option value="JSON"
		<?php if (isset($POST["serialization"]) && strcasecmp($_POST["serialization"], "JSON") == 0){echo "selected=\"selected\"";}?>>RDF/JSON</option>
		<option value="XML"
		<?php if (isset($_POST["serialization"]) && strcasecmp($_POST["serialization"], "XML") == 0) echo "selected=\"selected\"";  ?>>RDF/XML</option>
		<option value="NTRIPLES"
		<?php if (isset($_POST["serialization"]) && strcasecmp($_POST["serialization"], "NTRIPLES") == 0) echo "selected=\"selected\"";  ?>>N-Triples</option>
		<option value="TURTLE"
		<?php if (isset($_POST["serialization"]) && strcasecmp($_POST["serialization"], "TURTLE") == 0) echo "selected=\"selected\"";  ?>>Turtle</option>
	</select> <br /> 
	
	<input type="radio" name="store" value="please_store" />Ask LD4S to store
	the generated Linked Data on your behalf
	<!-- a pop-up window asks for the available storage systems -->
	<br />
	  <input type="radio" name="store" value="i_store" checked="checked"/>I will store
	  the generated Linked Data on  <input type="text" name="base_uri"
			value="<?php if (isset($_POST["base_uri"])) echo $_POST["base_uri"]; else echo 'http://www.example.org'; ?>" /><br />
	
	<br />
	
	 LD4Sensor instance URI: <input
		type="text" name="ld4s_instance"
		value="<?php if (isset($_POST['ld4s_instance'])) echo $_POST['ld4s_instance']; else echo ""; ?>" /><br />
	<input type="submit" name="generate" value="Generate" />

</form>

<?php 
if ($response){
	echo "<hr /><strong>Generated representation:</strong><br />";
	echo getPrintableCode($response);
}
?>