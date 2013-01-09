<?php
	$blogDataDir = "F:\kuaipan\blog";//博客数据目录
	$initial_depth = substr_count($blogDataDir,'\\');//初始深度
	$fullFileName = "F:\kuaipan\blog\article\default\\1\article.md";
	
	echo getArticleDirName($fullFileName);

	function getCateDirName($fullFileName)
	{
		return _getDepthDirName($fullFileName,2);
	}

	function getArticleDirName($fullFileName){
		return _getDepthDirName($fullFileName,3);
	}

	function _getDepthDirName($fullFileName,$depth){
		global $initial_depth;
		$fullFileName = explode('\\', $fullFileName);
		return $fullFileName[$depth+$initial_depth];
	}