<?php

include('Express\\global.php');
include('Express\\Express.php');
$conf = loadConfig(BaseDir.'\config');
$express = new Express($conf);
$express -> generate();
//var_dump( $express->convertFileName("E:\GitHub\Express\TestSpace\BlogSrc\������־dfksdhf1@2012-12-12.md"));

//global $zh2PinYin;
// $zh2PinYin = new Zh2PinYin();

//$express = new Express();
//echo $express->convertFileName("E:\GitHub\Express\TestSpace\BlogSrc\������־1@2012-12-12.md");