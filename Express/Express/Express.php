<?php

class Express{

	var $conf = array();
	var $initial_depth = 0;
	var $zh2PinYin = null;

	public function Express($conf){
		$this->zh2PinYin = loadClass(BaseDir.'\Express\Zh2PinYin.php');
		$this->conf = $conf;
		$this->initial_depth = substr_count($this->conf->blogSrcDir,'\\');//初始深度
	}

	public function generate(){

		if(!is_dir($this->conf->blogDestDir) || !$this->conf->blogSrcDir){//如果没有获取到参数或者参数
			log('参数没有输入、或者不正确、或者源路径不为目录，进程已经停止',ERRO);
		}elseif($this->conf->blogDestDir && !is_dir($this->conf->blogDestDir)) {
			mkdir($this->conf->blogDestDir);
		}

		$fileList = glob($this->conf->blogSrcDir.'\*');

		foreach( $fileList as $fullPath ){

			$depth = substr_count($fullPath,'\\') - $this->initial_depth;//当前相对深度

			if (is_dir($fullPath)) {
				
				$srcDirName = $srcObjName; $destDirFullpath = $destObjFullpath;
				list($srcObjName,$destObjName,$destObjFullpath) = $this->convertFileName($fullPath);
				Elog("目标目录：".$destObjFullpath);
				Elog("目标目录：".$destObjFullpath);
				//makeBlog($fullPath);

			}elseif($this->is_markdown($fullPath)){
				$fullPath = $this->str_rreplace_once('.md','',$fullPath);
				$fullPath = $this->str_rreplace_once('.markdown','',$fullPath);
				list($srcObjName,$destObjName,$destObjFullpath) = $this->convertFileName($fullPath);
				//file_put_contents($destObjFullpath."\\index.htm", Markdown(file_get_contents($fullPath)));
				Elog("目标文件：".$destObjFullpath);
				Elog("目标文件：".$destObjName);
			}else{
				Elog("存在未预料到的文件：".$fullPath,WARN);
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
		$destObjName = $this->zh2PinYin->getPinYin($destObjName[0]);
		// Elog($fullPath);
		$destObjFullpath = $this->str_replace_once($this->conf->blogSrcDir,$this->conf->blogDestDir,$fullPath);
		// Elog($destObjFullpath);
		$destObjFullpath = $this->str_rreplace_once($srcObjName, $destObjName, $destObjFullpath);
		// Elog($srcObjName.'  '.$destObjName);
		return array($srcObjName,$destObjName,$destObjFullpath);
	}

	private function str_replace_once($search, $replace, $subject){
		$pos = strrpos($subject,$search);
		$length = strlen($search);
		if ($pos == false) {
			return $subject;
		}
		return substr_replace($subject,$replace,$pos,$length);
	}

	private function str_rreplace_once($search, $replace, $subject){
		$pos = strpos($subject,$search);
		$length = strlen($search);
		if ($pos == false) {
			return $subject;
		}
		return substr_replace($subject,$replace,$pos,$length);
	}

	//
	private function copyStyle(){

	}

	private function makeIndex(){

	}



}

