<?php
class Express(){

	var $conf = array();
	var $initial_depth = 0;

	private function Express(){
		include('config.php');
		$this->conf = $config;
		$this->initial_depth = substr_count($this->conf['blogSrcDir'],'\\');//初始深度
	}

	public function generate($blogSrcDir,$blogDestDir){
		//TODO:
		$blogSrcDir  = $blogSrcDir && $this->conf['blogSrcDir'];
		$blogDestDir = $blogDestDir && $this->conf['blogDestDir'];

		if($blogDestDir && !is_dir($blogDestDir)){//如果存在目标目录参数并且目标目录不存在则创建该目录
			mkdir($blogDestDir);
		}elseif (!$blogDestDir) {
			log('blogDestDir dosen\'t exist; progress has stop!',ERRO);
		}

		foreach( glob($blogSrcDir.'\*') as $fullPath ){
			$srcObjName = substr(strrchr($fullPath, '\\'), 1);
			$destObjFullpath = str_replace($blogSrcDir,$blogDestDir,$fullPath);
			$depth = substr_count($fullPath,'\\') - $this->initial_depth;//当前相对深度
			if (is_dir($fullPath)) {
				$srcDirName = $srcObjName; $destDirFullpath = $destObjFullpath;
				if ($depth == ) {
					# code...
				}
			}
		}
	}

	private function makeBlog(){
		
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