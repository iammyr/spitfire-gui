<?php

function removeAllSpecialCharacters($string) {
	return preg_replace("/[^a-zA-Z0-9\s-]/", "", $string);
}


function getPrintableCode($data){
	$data_4html = str_ireplace("<", "&lt", $data);
	$data_4html = str_ireplace(">", "&gt", $data_4html);
	$data_4html = '<pre><code>'.$data_4html.'</pre></code>';
	return $data_4html;
}


?>