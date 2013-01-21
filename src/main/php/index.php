<?php 
require_once 'template.php';
$title = 'Spitfire | Home';
printHeader($title);
?>

<?php 
$activeMenuItemNum = 1;
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
?>
<h2>LD4Sensors (LD4S) 2.0</h2>


<ol class="tree">
		<li>
			<label for="folder2">Search</label> <input type="checkbox" id="folder2" /> 
			<ol>
				<li class="file"><a href="search.php">Search for Semantic Annotation</a></li>
				<li class="file"><a href="global-search.php" title="Search for Sensor Data streams (based on Fuzzy Sets)">Search for Predicted Annotation</a></li>
			</ol>
		</li>
		<li>
			<label for="folder3">Create</label> <input type="checkbox" id="folder3" /> 
			<ol>
				<li class="file">If you know the metadata to annotate: <a href="open.php">Semantic Annotation</a></li>
				<li class="file">If you do not know the metadata to annotate: <a href="auto-annotation.php" title="Predict metadata comparing fuzzy sets on historical data">Predicted Annotation</a></li>
			</ol>
		</li>
		<li>
			<label for="folder4">Storage</label> <input type="checkbox" id="folder4" /> 
			<ol>
				<li class="file"><a href="store.php" title="Set where you want your data to be stored">Available Storage Resources</a></li>
			</ol>
		</li>
		<li>
			<label for="folder5">Open</label> <input type="checkbox" id="folder5" /> 
			<ol>
				<li class="file"><a href="open.php">Semantic Annotations</a></li>
				<li class="file"><a href="global-search.php" title="Search for Sensor Data streams (based on Fuzzy Sets)">Predicted Annotation</a></li>
			</ol>
		</li>
	</ol>



<?php 
$copyright_uri = 'http://www.spitfire-project.eu';
$copyright_name = 'SPITFIRE';
printFooter($copyright_uri, $copyright_name);
?>
 