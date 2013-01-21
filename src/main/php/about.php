<?php 
require_once 'template.php';
$title = 'Spitfire | About Us';
printHeader($title);
?>
</head>
<?php 
$activeMenuItemNum = 3;
$body_onload = '';
$sidebar = '<div class="gadget">
<h2 class="star"><span>What</span></h2>
<div class="clr"></div>
Generate a RDF description for single sensor nodes.<br /> 
The returned description is split into sections according to the 
dependency of the included information, and "where" to store it is suggested. 
This service is available through either the manually fillable form here
or a RESTful API.</div>
<div class="gadget">
<h2 class="star"><span>Why</span></h2>
<div class="clr"></div>
The unambiguity and schema-indepenedency features that characterize
RDF, are the key to enable 
<ul>
	<li>plug-and-play sensor nodes</li>
	<li>interoperability among different sensors</li>
</ul>
</div>';

printBodyTop($activeMenuItemNum, $sidebar, $body_onload);
?>

       <!--        <p>
        <span style="font-weight: bold">LD4Sensors</span> is a web server system that shows how
users with no expertise can benefit of a Linked Data representation to make sense of raw sensor data. Our motivations
are that <ol>
<li> these users are becoming the main consumers of sensor data,
but sensors conceptualisation do not consider their point of view; </li>
<li>so far, no application dynamically creates Linked Data for sensors, since the linked datasets are usually predefined.</li>
</ol></p>
<p>
A DEMO paper has been published about this at the <span style="font-style: italic;">International Semantic Web Conference 2011 (ISWC2011)</span>:
<a href = "http://iswc2011.semanticweb.org/fileadmin/iswc/Papers/PostersDemos/iswc11pd_submission_100.pdf" target="_blank">inContext Sensing: LOD augmented sensor data </a><br />
</p>
<br />
        <p>
        <span style="font-weight: bold">RDF4Sensors</span> is a generator of RDF descriptions for sensor metadata and sensor observations. It accepts input sent either manually by filling an online form or
        through a REST API (see <a href = "apiDocumentation.php">REST API SPECIFICATION</a>).<br />
        It's going to be integrated into the inContextSensing application.
        </p><br /><br />
        
        -->
        
        The SPITFIRE Framework is the outcome of the European Project SPITFIRE, 
        and relies on the work performed by each of the partners.
        
        <span style="font-weight: bold">Main Contacts:</span><ul>
<li>DERI - <a href = "http://www.deri.ie/about/team/member/manfred_hauswirth" target="_blank">Manfred Hauswirth</a>, <a href="http://www.deri.ie/about/team/member/myriam_leggieri" target="_blank">Myriam Leggieri</a></li> 
<li>ITI - <a href = "http://www.iti.uni-luebeck.de/mitarbeiter/prof-dr-kay-roemer.html" target="_blank">Kay Roemer</a>, <a href="http://www.iti.uni-luebeck.de/mitarbeiter/cuong-truong-m-sc.html" target="_blank">Cuong Truong</a>, <a href="https://www.itm.uni-luebeck.de/people/kleine1/" target="_blank">Oliver Kleine</a></li>
<li>TUBS - <a href = "http://www.ibr.cs.tu-bs.de/users/kroeller" target="_blank">Alexander Kroeller</a>, <a href="http://www.ibr.cs.tu-bs.de/users/hasemann" target="_blank">Henning Hasemann</a></li>
<li>COALESENSES- <a href = "http://www.coalesenses.com/index.php?page=contact" target="_blank">Carsten Bormann</a>, <a href="http://www.coalesenses.com/index.php?page=contact" target="_blank">Christian Tille</a></li>
<li>CTI - <a href = "http://ru1.cti.gr/~ichatz/" target="_blank">Ioannis Chatzigiannakis</a>, <a href="http://ru1.cti.gr/index.php/people/8-people/57-amaxilatis-dimitris" target="_blank">Dimitrios Amaxilatis</a></li>
<li>IBBT - <a href = "http://users.atlantis.ugent.be/eldpoort/" target="_blank">Eli De Porteer</a>, <a href="mailto:isam.ishaq@intec.ugent.be">Isam Ishaq</a></li>
<li>DAYSHA - <a href = "http://www.dayshaconsulting.com/about/our-team/" target="_blank">Ray Richardson</a></li>
</ul>        
         
        
<?php 
$copyright_uri = 'http://www.deri.ie';
$copyright_name = 'DERI';
printFooter($copyright_uri, $copyright_name);
?>
 
