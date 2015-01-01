<?php
include_once('function.php');

$html = geturlc("http://movie.douban.com/subject/10807909/?from=showing");

$pattern = '/^v:itemreviewed">(.*)</';

preg_match($pattern,$html,$match);
var_dump($match);

$pattern = '<div id="info">\s*<span ><span class=\'pl\'>导演</span>: <span class=\'attrs\'><a href=".*" rel="v:directedBy">(.*)</a></span></span><br/>\s*<span ><span class=\'pl\'>编剧</span>: <span class=\'attrs\'>(.*)</span></span><br/>\s*<span class="actor"><span class=\'pl\'>主演</span>: <span class=\'attrs\'>(.*)</span></span><br/>\s*<span class="pl">类型:</span> (.*) / <span property="v:genre">(.*)</span><br/>\s*<span class="pl">制片国家/地区:</span> (.*)<br/>\s*<span class="pl">语言:</span> (.*)<br/>\s*<span class="pl">上映日期:</span> <span property="v:initialReleaseDate" content=".*">.*</span><br/>\s*<span class="pl">片长:</span> <span property="v:runtime" content=".*">(.*)</span><br/>\s*<span class="pl">又名:</span> (.*)<br/>\s*<span class="pl">IMDb链接:</span> <a href="(.*)" target="_blank" rel="nofollow">(.*)</a><br>';

preg_match($pattern,$html,$matches);
var_dump($matches);



?>