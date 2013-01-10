<?php

	function main(){
		global $articleDataDir,$articleHTMLDir,$articleCateDir,$articleToCategoryMap,$blogHTMLDir;
		if(!is_dir($blogHTMLDir)){mkdir($blogHTMLDir);}
		//mkArticle($articleDataDir,$articleHTMLDir);
		listArticleIndex($articleDataDir);
		listArticleCategory($articleCateDir);
		//创建文章索引
		mkArticleIndex();

		mkStyle();
	}

	function mkStyle(){
		global $blogHTMLDir;
		copyDir('template/style', $blogHTMLDir.'\\style');
	}

	//遍历目录
	function mkArticle($DirFrom,$DirTo){
		
		global $initial_depth,$articleHTMLDir,$config;
		
		if ($DirTo) {
			if (inImagesDir($DirFrom)) {
				$DirTo .= $config['imgMaxWidth'];
			}
			if(!is_dir($DirTo)){
				mkdir($DirTo);
			}else{//如果已经存在该目录
				debug("have exist:".$DirTo);

				if ($config['ifRemake'] && $config['makeTarget']==DIR && ifInScrope($DirFrom)) {//如果是更新目录,并且当前正处于该目录，并且"重建模式"开启
					removeDir($DirTo);
					mkdir($DirTo);
					debug('remove and remake '.$DirTo);
				}
			}
		}

		foreach( glob($DirFrom.'\*') as $fullFileName ){

			$fileName = substr(strrchr($fullFileName, '\\'), 1);
			$fileToBeMaked = str_replace($DirFrom,$DirTo,$fullFileName);

			$full_depth = substr_count($fullFileName,'\\');//当前深度
			$depth = $full_depth - $initial_depth;//当前相对深度

			if(is_dir($fullFileName)){

				if($depth == 3){//如果是日志目录

					$fileToBeMaked = $articleHTMLDir.'\\'.$fileName;
					mkArticle($fullFileName,$fileToBeMaked);

				}elseif($depth > 3){

					mkArticle($fullFileName,$fileToBeMaked);
				
				}else{
				
					mkArticle($fullFileName);
				
				}
			}elseif ($DirTo) {
				$suffix = substr(strrchr($fullFileName, '.'), 1);
				if (ifInScrope($fullFileName)) {
					if($depth > 3){//日志内容深度
						if ($fileName == 'article.md') {

							$fileToBeMaked = str_replace('article.md','index.html',$fileToBeMaked);
							$html = Markdown(file_get_contents($fullFileName));
							
							file_put_contents ($fileToBeMaked, $html);

						}elseif( ($suffix == 'png' || $suffix == 'jpg') && $config['makeTarget'] == DIR){
							
							if ($suffix == 'jpg') {
								$filetype = 'jpeg';
							}else{
								$filetype = 'png';
							}
							
							_resizeImage($fullFileName,$config['imgMaxWidth'],null,$filetype,$fileToBeMaked);
						
						}elseif($config['makeTarget'] == DIR){
							
							copy($fullFileName,$fileToBeMaked);
						}
					}

				}
			}
		}
	}

	function mkArticleIndex(){
		
		global $articleToCategoryMap,$config,$articleHTMLDir,$articleDataDir;
		

		$categoryList = array_keys($articleToCategoryMap);
		foreach ($articleToCategoryMap as $categoryDirName => $articleList) {
			
			if($categoryDirName != 'index' && !is_dir($articleHTMLDir.'\\'.$categoryDirName)){
				mkdir($articleHTMLDir.'\\'.$categoryDirName);
			}
			$currentNum =1; $list = array();$article = array();
			foreach ($articleList as $articleDirName) {

				$articleFullDirName = $articleDataDir.'\\'.$articleDirName;
				//$article['about'] = json_decode(file_get_contents($articleFullDirName.'\\about.json'));
				$article['content'] = file_get_contents($articleFullDirName.'\\article.md');
				if ($article['content'] != false) {
					$article['content'] = Markdown(($article['content']));
					$list[] = $article;
				}else{
					debug($articleFullDirName.'\\article.md dosen\'t exist',ERRO);
					// debug('wahaha');
					exit;
				}
				if (count($list) == $config['perPage']-1 || count($articleList) == $currentNum) {//如果当前项是当前列表中“每页最大条目数个”或者是总列表中最后一个的话
					//TODO生成htm
					//list
					$data[0] = $list;
					//totalNum
					$data[1] = count($list);
					//currentNum
					$data[2] = $currentNum;
					//perPage
					$data[3] = $config['perPage'];
					//totalPage
					$data[4] = ($data['totalNum'] % $config['perPage']) ? (int)($data['totalNum'] / $config['perPage'])+2 : (int)($data['totalNum'] / $config['perPage'])+1;
					//currentPage
					$data[5] = ($data['currentNum'] % $config['perPage']) ? (int)($data['currentNum'] / $config['perPage'])+2 : (int)($data['currentNum'] / $config['perPage'])+1;
					//分类
					$data[6] = $categoryList;
					if($categoryDirName == 'index'){
						$fileToBeMaked = $articleHTMLDir.'\\index.html';
					}else{
						$fileToBeMaked = $articleHTMLDir.'\\'.$categoryDirName.'\\index.html';
					}
				
					makeHTML('template/list.php',$fileToBeMaked,$data);
					//清空list
					$list = array();
				}
				$currentNum++;
			}
		}
	}


	function mkPhoto(){


	}

	function listArticleCategory($Dir){
		global $articleToCategoryMap,$initial_depth;
		foreach( glob($Dir.'\*') as $fullFileName ){
			$fileName = substr(strrchr($fullFileName, '\\'), 1);

			$full_depth = substr_count($fullFileName,'\\');//当前深度
			$depth = $full_depth - $initial_depth;//当前相对深度


			if(is_dir($fullFileName)){
				if ($depth == 3) {
					$categoryName = getCateDirName($fullFileName);
					$articleToCategoryMap[$categoryName] = array();
				}
				listArticleCategory($fullFileName);
			}elseif($depth == 4){//如果
				$categoryName = getCateDirName($fullFileName);
				$articleToCategoryMap[$categoryName][] = $fileName;
			}

		}
	}

	function listArticleIndex($Dir){
		global $articleToCategoryMap;
		foreach( glob($Dir.'\*') as $fullFileName ){
			$fileName = substr(strrchr($fullFileName, '\\'), 1);
			$articleToCategoryMap['index'][] = $fileName;
		}
	}

	function inImagesDir($fullFileName){
		if(_getDepthDirName($fullFileName,4) == 'images'){
			return true;
		}else{
			return false;
		}
	}

	function getCateDirName($fullFileName)
	{
		return _getDepthDirName($fullFileName,3);
	}

	function getArticleDirName($fullFileName){
		return _getDepthDirName($fullFileName,3);
	}

	function _getDepthDirName($fullFileName,$depth){
		global $initial_depth;
		$fullFileName = explode('\\', $fullFileName);
		return $fullFileName[$depth+$initial_depth];
	}

	function ifInScrope($fullFileName){
		global $config;
		return 
			$config['makeScope'][0] == CATE && in_array(getCateDirName($fullFileName),$config['makeScope'][1])
		||	$config['makeScope'][0] == ARTICLE && in_array(getArticleDirName($fullFileName),$config['makeScope'][0])
		||	$config['makeScope'][0] == null;
	}


	function _resizeImage($sourceImg,$towidth,$toheight,$filetype,$destImg)
	{
		if ($filetype != 'jpeg' && $filetype != 'png') {
			return false;
		}

		eval('$im'.' = imagecreatefrom'.$filetype.'($sourceImg);');

		$pic_width = imagesx($im);
		$pic_height = imagesy($im);

		if ($toheight) {
			$bili = $towidth/$toheight;
			$newbili = $pic_width/$pic_height;

			if ($bili > $newbili) {
				//以宽为标准
				$ratio = $towidth / $pic_width;
				$srcwidth = $pic_width;
				$srcheight = $toheight / $ratio;
			}else{
				//以高为标准
				$ratio = $toheight / $pic_height;
				$srcheight = $pic_height;
				$srcwidth = $towidth / $ratio;
			}

		}else{

			$ratio = $towidth / $pic_width;
			$toheight = $pic_height * $ratio;

			$srcwidth = $pic_width;
			$srcheight = $pic_height;

		}

		if(function_exists("imagecopyresampled"))
		{
			$newim = imagecreatetruecolor($towidth,$toheight);
		   imagecopyresampled($newim,$im,0,0,0,0,$towidth,$toheight,$srcwidth,$srcheight);
		}
		else
		{
			$newim = imagecreate($towidth,$toheight);
		   imagecopyresized($newim,$im,0,0,0,0,$towidth,$toheight,$srcwidth,$srcheight);
		}

		if ($destImg) {
			eval('image'.$filetype.'($newim,$destImg);');
		}else{
			eval('image'.$filetype.'($newim);');	
		}

		// imagejpeg($newim);
		imagedestroy($newim);          
	}


	function makeHTML($templateFileName,$fileToBeMaked,$data){
		global $config;
		list($list,$totalNum,$currentNum,$perPage,$totalPage,$currentPage,$categoryList) = $data;
		ob_start();

		include $templateFileName;

		$content = ob_get_clean();
		file_put_contents($fileToBeMaked, $content);

	}

	function debug($message,$level = INFO){
		if ($level) {
			if ($level == 1 || $level == WARN) {
				$level = 'WARN';
			}elseif ($level == 2 || $level == ERRO) {
				$level = 'ERRO';
			}else{
				$level = 'INFO';
			}
		}else{
			$level = "INFO";
		}
		echo "[$level]$message\n";
	}

	function removeDir($path){

		if(PATH_SEPARATOR==':'){//Linux
			$shellStatement = 'rm -rf '.$path;
		}else{//Windows
			$shellStatement = 'rd /q /s '.$path;
		}

		$result = system($shellStatement);
		debug($result);
	}

	function copyDir($src,$dst) {  // 原目录，复制到的目录(function_name)
		debug($dst);
		$dir = opendir($src);
		@mkdir($dst);
		while(false !== ( $file = readdir($dir)) ) {

			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					copyDir($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					copy($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
	}