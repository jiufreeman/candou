<?php
require('function.php');
/*
$data = geturlc('http://movie.douban.com/subject/10807909/photos?type=S&start=0&sortby=vote&size=a&subtype=o');

$preg = '/<div class="cover">\s*<a href="(.*)">\s*<img src="(.*)" \/>\s*<\/a>\s*<\/div>/';
preg_match_all($preg,$data,$maths,PREG_SET_ORDER);
var_dump($maths);
*/

class stage {
	public $data = '';
	public $result = '';
	public $prefix = 'http://movie.douban.com/subject/';
	public $pageSize = 40;
	public $movie_id = 0;
//25779218/photos?type=S&start=0&sortby=vote&size=a&subtype=o
	public function getOfficialStage()
	{

	}
	//设置movie_id ，或者使用初始化函数
	public function setMovie_id($id){
		$this->movie_id = $id;
	}

	public function getAllPage()
	{
		$data = geturlc($prefix.$movie_id);
	}
}