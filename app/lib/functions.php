<?php

function QUO($str,$encoding = "utf-8") {
	return htmlspecialchars($str,ENT_QUOTES,$encoding);
}

function UNQUO($str) {
	return htmlspecialchars_decode($str);
}

function NE($array,$key,$empty = "") {
	return QUO(Bob::create('array',$array)->getKey($key,$empty));
}

function NEC($array,$key,$empty = "") {
	return Bob::create('array',$array)->getKey($key,$empty);
}

function SEL($input,$value,$type = "integer") {
	if($type == "integer") return ((int)$input == (int)$value) ? " selected='selected'":"";
	elseif($type == "string") return ((string)$input == (string)$value) ? " selected='selected'":"";
	else return ($input == $value) ? " selected='selected'":"";
}

function CLA($input,$value,$class = "selected",$type = "string") {
	if($type == "integer") return ((int)$input == (int)$value) ? " ".$class:"";
	elseif($type == "string") return ((string)$input == (string)$value) ? " ".$class:"";
	else return ($input == $value) ? " ".$class:"";
}