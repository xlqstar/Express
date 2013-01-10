<?php

define("ERRO", 5);
define("WARN", 6);
define("INFO", 7);

function log2($message,$level = INFO){
	if ($level == WARN) {
		$level = 'WARN';
	}elseif ($level == ERRO) {
		$level = 'ERRO';
	}else{
		$level = 'INFO';
	}

	echo "[$level]$message\n";

	$level == 'ERRO' && exit;
}

class Base{

	static function loadClass($fullFileName) {
		include($fullFileName);
		$fileName = substr(strrchr($fullFileName, '\\'), 1);
		$className = substr($fileName,0,strrpos($fileName,'.'));
		return new $className();
	}

	static function loadConfig($fullFileName) {
		$config = file_get_contents($fullFileName);
		return json_decode($config);
	}
}

class Express extends Base{

	var $conf = array();
	var $initial_depth = 0;
	var $zh2PinYin = null;

	public function Express(){
		$this->zh2PinYin = parent::loadClass('lib\\Zh2PinYin.php');
		$this->conf = parent::loadConfig('E:\GitHub\Express\Generator\config.js');
		var_dump($this->conf);
		//include('config.php');

		//require('lib\Zh2PinYin.php');
		//global $config,$zh2PinYin;
		//$this->conf = $config;
		//$this->zh2PinYin = $zh2PinYin;
		//var_dump($zh2PinYin);
		//require('lib\Zh2PinYin.php');
		
		//$this->initial_depth = substr_count($this->conf['blogSrcDir'],'\\');//初始深度
	}

	public function generate($blogSrcDir,$blogDestDir){

		if(!is_dir($blogDestDir) || !$blogSrcDir){//如果没有获取到参数或者参数
			log('参数没有输入、或者不正确、或者源路径不为目录，进程已经停止',ERRO);
		}elseif($blogDestDir && !is_dir($blogDestDir)) {
			mkdir($blogDestDir);
		}

		$fileList = glob($blogSrcDir.'\*');

		foreach( $fileList as $fullPath ){

			$depth = substr_count($fullPath,'\\') - $this->initial_depth;//当前相对深度

			if (is_dir($fullPath)) {
				$srcDirName = $srcObjName; $destDirFullpath = $destObjFullpath;
				makeBlog($fullPath);
			}elseif(is_markdown($fullPath)){	
				makeBlog($fullPath);
			}else{
				log("存在未预料到的文件：".$fullPath,WARN);
			}
		}

	}

	private function is_markdown($fullFileName){
		$suffix = substr(strrchr($fullFileName, '.'), 1);
		return  $suffix == "md" || $suffix == "markdown";
	}

	private function makeBlog(){

	}

	private function makeArticle(){

	}

	private function makePhoto(){

	}

	public function convertFileName($fullPath){
		$srcObjName = substr(strrchr($fullPath, '\\'), 1);

		$destObjName = explode('@', $srcObjName);
		$destObjName = $this->zh2PinYin->getPinYin($destObjName[1]);
		
		$destObjFullpath = $this->str_replace_once($this->conf['blogSrcDir'],$this->conf['blogDestDir'],$fullPath);
		$destObjFullpath = $this->str_rreplace_once($srcObjName, $destObjName, $destObjFullpath);

		return array($srcObjName,$destObjName,$destObjFullpath);
	}

	private function str_replace_once($search, $replace, $subject){
		$pos = strrpos($subject,$search);
		$length = count($subject);
		return substr_replace($subject,$replace,$pos,$length);
	}

	private function str_rreplace_once($search, $eplace, $subject){
		$pos = strpos($subject,$search);
		$length = count($subject);
		return substr_replace($subject,$replace,$pos,$length);
	}

	//
	private function copyStyle(){

	}

	private function makeIndex(){

	}



}


//global $zh2PinYin;
// $zh2PinYin = new Zh2PinYin();

$express = new Express();
//echo $express->convertFileName("E:\GitHub\Express\TestSpace\BlogSrc\工作日志1@2012-12-12.md");