<?php
/**
 *
 * @return array of array that will contain metadata divided per resource accepted by the LD4S API 1.0
 */
function initAPIResources(){
	$data = array("ov" => array(),
			"device" => array(),
			"tps" => array(),
			"platform" => array(),
			"tpp" => array(),
			"meas_capab" => array(),
			"meas_prop" => array(),
			"link" => array(),
			"link/feedback" => array(),
			"resource" => array(),
			"activity" => array(),
			"sn" => array()
	);
	return $data;
}

function getPrintableCode($data){
	$data_4html = str_ireplace("<", "&lt", $data);
	$data_4html = str_ireplace(">", "&gt", $data_4html);
	$data_4html = '<pre><code>'.$data_4html.'</pre></code>';
	return $data_4html;
}


?>