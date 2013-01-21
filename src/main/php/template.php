<?php

function printHeader($title, $main_folder = ''){
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title; ?></title>
<link rel="shortcut icon" href="../../../images/favicon.ico" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="<?php echo $main_folder; ?>style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $main_folder; ?>styles-treemenu.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $main_folder; ?>../../../js/cufon-yui.js"></script>
<script type="text/javascript" src="<?php echo $main_folder; ?>../../../js/arial.js"></script>
<script type="text/javascript" src="<?php echo $main_folder; ?>../../../js/cuf_run.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
</head>
<?php 

} 

function printBodyTop($activeMenuItemNum, $sidebar, $body_onload){
	?>
	<body onload="<?php echo $body_onload; ?>">
<div class="main">
<div class="header" title="Philips bubble mood sensing dress">
<div class="header_resize">
<div class="logo">
<h1><a href="#">SPIT<span>FIRE</span> <small>A Framework for augmenting your Sensor
Data</small></a></h1>
</div>
<div class="clr"></div>
<div class="htext">
<h2>Network Heterogeneities</h2>
<p>transparent to the user</p>
<h2>Linked Data for Sensors</h2>
<p>Semantically annotate your sensor data,
inference over it  
and link with the Web of Data.</p>
</div>
<div class="clr"></div>
<div class = "menu_nav">

<ul id="coolMenu">
	<li <?php echo $activeMenuItemNum===1?' class="active"':''; ?>><a href="index.php">Home</a></li>
	<li <?php echo $activeMenuItemNum===4?' class="active"':''; ?>><a href="open.php">Annotate</a>
	<ul class="noJS">
	<li><a href="open.php?mode=new">New</a></li>
	<li><a href="open.php?mode=open">Open</a></li>
	</ul> 		</li>
	
	<li <?php echo $activeMenuItemNum===5?' class="active"':''; ?>><a href="index.php">Predict</a></li>
	<li <?php echo $activeMenuItemNum===6?' class="active"':''; ?>><a href="index.php">Aggregate</a></li>
	<li <?php echo $activeMenuItemNum===7?' class="active"':''; ?>><a href="index.php">Linking</a></li>
	<li <?php echo $activeMenuItemNum===2?' class="active"':''; ?>><a href="apis.php">APIs</a>

	</li>
	<li <?php echo $activeMenuItemNum===3?' class="active"':''; ?>><a href="about.php">About Us</a></li>
</ul>
</div>


<div class="clr"></div>
</div>
</div>
<div class="content">

<div class="content_resize">

<div class="sidebar">

<?php echo $sidebar ?>

</div>

<div class="mainbar">
<div class="article" style="color: black;">

<div class="clr"></div>
<?php }

function printFooter($copyright_uri, $copyright_name){
	?>
	</div>

</div>

<div class="clr"></div>
</div>
</div>
<div class="fbg">
<div class="fbg_resize">
<div class="col c1"></div>
<div class="col c3"></div>
<div class="clr"></div>
</div>
</div>
<div class="footer">
<div class="footer_resize">
<p class="lf">&copy; Copyright <a href="<?php echo $copyright_uri;?>"><?php echo $copyright_name;?></a>.</p>
<p class="rf">Layout by Rocket <a
	href="http://www.rocketwebsitetemplates.com/">Website Templates</a></p>
<div class="clr"></div>
</div>
</div>
</div>
</body>
</html>
<?php 	
}?>

