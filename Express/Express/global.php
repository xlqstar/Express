<?php

define("ERRO", 5);
define("WARN", 6);
define("INFO", 7);
define("BaseDir", getBaseDir());

function getBaseDir(){
	return "E:\GitHub\Express\Express";
}

function Elog($message,$level = INFO){
	if ($level == WARN) {
		$level = 'WARN';
	}elseif ($level == ERRO) {
		$level = 'ERRO';
	}else{
		$level = 'INFO';
	}
	$message = mb_convert_encoding($message,"GBK","UTF-8");

	echo "[$level]$message\n";

	$level == 'ERRO' && exit;
}

function loadClass($fullFileName) {
	include($fullFileName);
	$fileName = substr(strrchr($fullFileName, '\\'), 1);
	$className = substr($fileName,0,strrpos($fileName,'.'));
	return new $className();
}

function loadConfig($fullFileName) {
	$config = file_get_contents($fullFileName);
	return json_decode($config);
}