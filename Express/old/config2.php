<?php



$config = array(
	'blogSrcDir' => 'E:\GitHub\Express\TestSpace\BlogSrc',
	'blogDestDir' => 'E:\GitHub\Express\TestSpace\BlogData',
	'imgMaxWidth' =>	400,
	'title'       =>	'立Q的博客',
	'perPage'	=>	20,
	'viewMode'	=>  'list',
	'makeScope'	=>	null,//null全部更新|CATE指定分类更新|ARTICLE指定文章目录更新
	'makeTarget'	=>	"DIR",//DIR目录 |ARTICLE 文章
	'ifRemake' => 0,//重建模式
	//'makeMode'	=>	ALL,//all完全重新生成 add增量生成
	'styleResourcePath' => '../style/',
);

echo json_encode($config);
