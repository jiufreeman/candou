<?php
include_once('function.php');

$html = geturlc("http://movie.douban.com/subject/10807909/?from=showing");

$pattern0= '/v:itemreviewed">(.*)</';
$pattern1= '/v:directedBy">(.*)</';
$pattern2= "/编剧<.*>: <s.an class='attrs'>(.*)</";
$pattern3= '/主演<.*>: <span class=.attrs.>(.*)</';
$pattern4= '/<span property="v:genre">(.*)<.*span>/';
$pattern5= '/制片国家.*:<.*> (.*)<br/';
$pattern6= '/语言:<.*> (.*)<br/';
$pattern7= '/v:initialReleaseDate" content="(.*)"/';
$pattern8= '/v:runtime" content="(.*)"/';
$pattern9= '/又名:<.*> (.*)<br/';
$pattern10 = '/IMDb链接:<.*> <a href="(.*)" target="_blank" rel="nofollow">(.*)</';
$pattern11 = '/v:average">(.*)</';
$pattern12 = '/v:summary".*>\s*(.*)\s*</';


preg_match($pattern0 ,$html,$match1);
preg_match($pattern1,$html,$match2);
preg_match($pattern2,$html,$match3);
preg_match($pattern3,$html,$match4);
preg_match($pattern4,$html,$match5);
preg_match($pattern5,$html,$match6);
preg_match($pattern6,$html,$match7);
preg_match($pattern7,$html,$match8);
preg_match($pattern8,$html,$match9);
preg_match($pattern9,$html,$match10);
preg_match($pattern10,$html,$match11);
preg_match($pattern11,$html,$match12);
preg_match($pattern12,$html,$match13);

$data['title']      = $match1[1];
$data['daoyan']     = $match2[1];
$data['bianju']     = strip_tags($match3[1]);
$data['zhuyan']     = strip_tags($match4[1]);
$data['leixin']     = $match5[1];
$data['diqu']       = $match6[1];
$data['yuyan']      = $match7[1];
$data['shangyin']   = $match8[1];
$data['pianchang']  = $match9[1];
$data['youming']    = $match10[1];
$data['imdburl']    = $match11[1];
$data['imdbname']   = $match11[2];
$data['pinfen']     = $match11[1];
$data['jianjie']    = $match13[1];



var_dump($data);



?>