<?php
class Express{

	var $conf = array();
	var $initial_depth = 0;
	var $zh2PinYin = null;

	private function Express(){
		include('config.php');
		$this->conf = $config;
		include('lib\Zh2PinYin.php');
		$this->zh2PinYin = new Zh2PinYin();
		$this->initial_depth = substr_count($this->conf['blogSrcDir'],'\\');//初始深度
	}

	public function generate($blogSrcDir,$blogDestDir){

		if(!is_dir($blogDestDir) || !$blogSrcDir){//如果没有获取到参数或者参数
			log('参数没有输入、或者不正确、或者源路径不为目录，进程已经停止',ERRO);
		}elseif($blogDestDir && !is_dir($blogDestDir)) {
			mkdir($blogDestDir);
		}

		$fileList = glob($blogSrcDir.'\*');

		foreach( $fileList as $fullPath ){

			$srcObjName = substr(strrchr($fullPath, '\\'), 1);
			$destObjFullpath = str_replace($blogSrcDir,$blogDestDir,$fullPath);
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

	private function 

	private function is_markdown($fullFileName){
		$suffix = substr(strrchr($fullFileName, '.'), 1)
		return  $suffix == "md" || $suffix == "markdown";
	}

	private function makeBlog(){

	}

	private function makeArticle(){

	}

	private function makePhoto(){

	}

	//
	private function copyStyle(){

	}

	private function makeIndex(){

	}

	private function log($message,$level = INFO){
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

}