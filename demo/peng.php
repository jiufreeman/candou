<?php
header("Content-type:text/html;charset=utf-8");
require_once("function.php");
$url="http://movie.douban.com/celebrity/1002668/";
$s=geturlc($url);
function get_con($s){
     $arr=array();
	 $str1='/<h1>(.*)<\/h1>/';
	 $str2='/<a class="nbg" title="(.*)" href="(.*)"/';
	 $str3='/<li>\s.*<span>性别<.*>:\s*(.*)\s*</';
	 $str4='/<li>\s.*<span>星座<.*>:\s*(.*)\s*</';
	 $str5='/<li>\s.*<span>出生日期<.*>:\s*(.*)\s*</';
	 $str6='/<li>\s.*<span>出生地<.*>:\s*(.*)\s*</';
	 $str7='/<li>\s.*<span>职业<.*>:\s*(.*)\s*</';
	 $str8='/<li>\s.*<span>更多外文名<.*>:\s*(.*)\s*</';
	 $str9='/<li>\s.*<span>更多中文名<.*>:\s*(.*)\s*</';
	 $str11='/<li>\s.*<span>imdb编号<.*>:\s*(.*)\s*</';
	 $str12='/<li>\s.*<span>官方网站<.*>:\s*(.*)\s*</';
	 $str13='/<h2>.*\s.*\s.*.*\s.*<.*>\s<.*>\s.*<div\sclass="bd">\s(.*)\s.*</';
	 $str14='/<h2>.*\s.*\s.*.*\s.*<.*>\s<.*>\s.*<div\sclass="bd">\s(.*)\s.*</';
	 $str15='/<li>\s.*<span>家庭成员<.*>:\s*(.*)\s*</';
	 
	 preg_match($str1,$s,$match1);
	 preg_match($str2,$s,$match2);
	 preg_match($str3,$s,$match3);
	 preg_match($str4,$s,$match4);
	 preg_match($str5,$s,$match5);
	 preg_match($str6,$s,$match6);
	 preg_match($str7,$s,$match7);
	 preg_match($str11,$s,$match11);
	 preg_match($str13,$s,$match13);
	 preg_match($str13,$s,$match13);
	 preg_match($str15,$s,$match15);
	 
	 $arr['title']  = $match1[1];
	 $arr['src']  = $match2[2];
	 $arr['instro']  = $match13[1];
	 $arr['family']  = $match15[1];
	 $arr['sex']  = $match3[1];
	 $arr['costellation']  = $match4[1];
	 $arr['birthday']  = $match5[1];
	 $arr['homeplace']  = $match6[1];
	 $arr['jobs']  = $match7[1];
	 $arr['imdb_num']  = strip_tags($match11[1]);
	 if(preg_match($str8,$s,$match8)){
		$arr['name_eng']  = $match8[1];
	 }
	 if(preg_match($str9,$s,$match9)){
		$arr['name_chs']  = $match9[1]; 
	 }
	 if(preg_match($str12,$s,$match12)){
		$arr['g_url']  = strip_tags($match12[1]);
	 }
	 return $arr;
}
$s=geturlc($url);
echo "<pre>";print_r(get_con($s));