<html>
<head>
<?php
$geography = isset($_POST['cat_geo']);
$cross = isset($_POST['cat_cross']);
$science = isset($_POST['cat_science']);
$gov = isset($_POST['cat_gov']);
$pub = isset($_POST['cat_pub']);
$user = isset($_POST['cat_user']);
$media = isset($_POST['cat_media']);
$space = isset($_POST['criteria_space']);
$time = isset($_POST['criteria_time']);
$thing = isset($_POST['criteria_thing']);
if (isset($_POST['confidence'])){
	$confidence = $_POST['confidence'];
}else{
	$confidence = "0.2";
}
?>
<script>
function hideShowDiv(divid, visible) { 
	
	if (visible){
		if (document.getElementById) { // DOM3 = IE5, NS6 
			document.getElementById(divid).style.visibility = 'visible'; 
		}else if (document.layers) { // Netscape 4
			document.hideShow.visibility = 'visible';
		}else{ // IE 4 
				document.all.hideShow.style.visibility = 'visible';
		}
	}else if (document.getElementById) { // DOM3 = IE5, NS6 
		document.getElementById(divid).style.visibility = 'hidden'; 
	}else if (document.layers) { // Netscape 4 
		document.hideShow.visibility = 'hidden';
	}else{ // IE 4  
		document.all.hideShow.style.visibility = 'hidden'; 
	}	 
	return true;
}

	function show_popup(id) {
	    if (document.getElementById){ 
	        obj = document.getElementById(id); 
		//if (obj.style.display == "none") { 
		//obj.style.display = ""; 
		//}
	        myWindow=window.open('','','width=600,height=500,"location=1,status=1,scrollbars=yes')
	        myWindow.document.write(obj.innerHTML);
	        myWindow.focus() 
	    } 
	}


function submitObj(element_id, name, style, label, data, send_to_file, object){
	var obj=ajaxInit();
	style = "background-color: #B8D4F4; border-style: dashed; overflow:auto; height: auto";
	object_state = obj.readyState;
//	object_state values:
//0: request not initialized
//1: server connection established
//2: request received
//3: processing request
//4: request finished and response is ready
div = "<div id=\""+name+"\"><div style=\""+style+"\"><h2><span><strong>"
+label+"</strong> Info About <span style=\"text-decoration: underline;\">"+name+"</span></span></h2>";
if (object_state==0)
{
div+="request not initialized"+"</div></div>";
}else if (object_state==1)
{
div+="server connection established"+"</div></div>";
}else if (object_state==2)
{
div+="request received"+"</div></div>";
}else if (object_state==3)
{
	div+="Loading, please wait..."+"</div></div>";
	}else if (object_state==4 && status==200)
{
div+=object.responseText+"</div></div><br />";

}
document.getElementById(element_id).innerHTML=div;
obj.open("POST",send_to_file,true);
obj.setRequestHeader("Content-type","application/x-www-form-urlencoded");
obj.send(data);
}

function ajaxInit(){	
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  return new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  return new ActiveXObject("Microsoft.XMLHTTP");
	  }
}

function getData(){
	return <?php echo "&confidence=$confidence&space=$space&time=$time&concept=$thing\""; ?>;
}

function loadInfo(name, location, when)
{	 
style = "background-color: #B8D4F4; border-style: dashed; overflow:auto; height: auto";

/** DATASTREAM */
//data to be sent 
mainform = document.forms['main_form'];
feed_id = mainform.elements['feed_id'].value;
start = document.getElementById('date3').value;
end = document.getElementById('date4').value;
//xmlhttp_datastream.onreadystatechange=
	submitObj("datastream", name+"_datastream", style, "Datastream Visualization", "name="+name+"&feedid="+feed_id+"&start="+start+"&end="+end, "datastreamPerFoi.php", object);

	data_tail = "&location="+location+"&start="+start+"&end="+end+"&when="+when;

	/** MEDIA */
<?php if ($media){ ?>
//xmlhttp_media.onreadystatechange=function()
data = "name="+name+<?php echo "\"&media=$media"; ?>;
submitObj("observedFeature_media", name+"_media", style, "Media", data+getData()+data_tail, "harvest_info.php", object);
/** GEOGRAPHY */
<?php } if ($geography){ ?>
data = "name="+name+<?php echo "\"&geo=$geography"; ?>;
submitObj("observedFeature_geo", name+"_geo", style, "Geography", data+getData()+data_tail, "harvest_info.php", object);

<?php } if ($gov){ ?>
data = "name="+name+<?php echo "\"&gov=$gov"; ?>;
submitObj("observedFeature_gov", name+"_gov", style, "Government", data+getData()+data_tail, "harvest_info.php", object);

<?php } if ($cross){ ?>
data = "name="+name+<?php echo "\"&cross=$cross"; ?>;
submitObj("observedFeature_cross", name+"_cross", style, "Cross-domain", data+getData()+data_tail, "harvest_info.php", object);

<?php } if ($pub){ ?>
data = "name="+name+<?php echo "\"&pub=$pub"; ?>;
submitObj("observedFeature_oub", name+"_pub", style, "Publication", data+getData()+data_tail, "harvest_info.php", object);

<?php } if ($science){ ?>
data = "name="+name+<?php echo "\"&science=$science"; ?>;
submitObj("observedFeature_science", name+"_science", style, "Life Science", data+getData()+data_tail, "harvest_info.php", object);

<?php } if ($user){ ?>
data = "name="+name+<?php echo "\"&user=$user"; ?>;
submitObj("observedFeature_user", name+"_user", style, "User-generated", data+getData()+data_tail, "harvest_info.php", object);
<?php } ?>

return false; //this is to not execute the href
}

function showObservedFeature(name, uri, source, content){
	if (document.getElementById){ 
        obj = document.getElementById('observedFeature'); 
        if (obj.style.display == "none") { 
            obj.style.display = ""; 
        }
        obj.getElementById('foi_name').innerHTML = name;
        obj.getElementById('foi_uri').innerHTML = uri;
        obj.getElementById('foi_source').innerHTML = source;
        obj.getElementById('foi_content').innerHTML = content;
    } 
}

</script>

</head>
<body>
	<?php if ($cross){?>
	<input type="hidden" name="cat_cross" value="" />
	<?php } if ($geography){?>
	<input type="hidden" name="cat_geo" value="" />
	<?php }if($gov){?>
	<input type="hidden" name="cat_gov" value="" />
	<?php }if ($media){?>
	<input type="hidden" name="cat_media" value="" />
	<?php }if($pub){?>
	<input type="hidden" name="cat_pub" value="" />
	<?php }if ($science){?>
	<input type="hidden" name="cat_science" value="" />
	<?php }if ($user){ ?>
	<input type="hidden" name="cat_user" value="" />
	<?php } if ($space){?>
	<input type="hidden" name="criteria_space" value="" />
	<?php } if ($time){?>
	<input type="hidden" name="criteria_time" value="" />
	<?php }if($thing){?>
	<input type="hidden" name="criteria_thing" value="" />
	<?php }if ($confidence){?>
	<input type="hidden" name="confidence"
		value="<?php echo $confidence; ?>" />
	<?php } ?>
	<br />



	<a href="javascript:showdiv('hideShow')">Advanced search setting</a>
	<div id="hideShow"
		style="height: 120px; overflow: auto; border-style: double; background-color: #95B9F5; color: black;">
		Select which kind of data you want to link to the Pachube sensor feed.<br />
		<form method="post" action="context.php" name="preferences">
			<input type="checkbox" name="cat_geo"
			<?php if ($geography){echo "checked = \"\"";}
			else{echo "value=\"\"";}?> />Geography<br /> <input type="checkbox"
				name="cat_gov"
				<?php if ($gov){echo "checked = \"\"";}
				else{echo "value=\"\"";}?> />Government<br /> <input type="checkbox"
				name="cat_media"
				<?php if ($media){echo "checked = \"\"";}
				else{echo "value=\"\"";}?> />Media<br /> <input type="checkbox"
				name="cat_user"
				<?php if ($user){echo "checked = \"\"";}
				else{echo "value=\"\"";}?> />User Generated Content<br /> <input
				type="checkbox" name="cat_pub"
				<?php if ($pub){echo "checked = \"\"";}
				else{echo "value=\"\"";}?> />Publication<br /> <input
				type="checkbox" name="cat_science"
				<?php if ($science){echo "checked = \"\"";}
				else{echo "value=\"\"";}?> />Life Science<br /> <input
				type="checkbox" name="cat_cross"
				<?php if ($cross){echo "checked = \"\"";}
				else{echo "value=\"\"";}?> />Cross Domain<br /> Select criteria to
			consider while linking.<br /> <input type="checkbox"
				name="criteria_space"
				<?php if ($space){echo "checked = \"\"";}
				else{echo "value=\"\"";}?> />Space<br /> <input type="checkbox"
				name="criteria_time"
				<?php if ($time){echo "checked = \"\"";}
				else{echo "value=\"\"";}?> />Time<br /> <input type="checkbox"
				name="criteria_thing"
				<?php if ($thing){echo "checked = \"\"";}
				else{echo "value=\"\"";}?> />Thing<br /> Confidence Level threshold:
			<input type="text"
				value="<?php if ($confidence){ echo htmlspecialchars($confidence);} else {echo "0.2"; } ?>"
				name="confidence" /> <input type="submit" name="advanced"
				value="Submit" onclick="javascript:hidediv('hideShow')" />
		</form>
	</div>

	<?php

echo "hello!";

?>
</body>

</html>
