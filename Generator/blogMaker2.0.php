<?php
	

	include('config.php');
	include('markdown.php');
	require('functins.php');
	$blogDataDir = "E:\GitHub\Express\TestSpace\BlogData";//博客数据目录
	$blogHTMLDir = "E:\GitHub\Express\TestSpace\BlogHTML";//博客HTML

	$articleModuleDir = $blogDataDir.'\\article';
	
	$articleDataDir = $articleModuleDir.'\\articles';
	$articleCateDir	= $articleModuleDir.'\\category';
	
	$articleHTMLDir = $blogHTMLDir;
	
	$initial_depth = substr_count($blogDataDir,'\\');//初始深度

	$articleToCategoryMap = array();

	main();



?>
