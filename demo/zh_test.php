<?php
include_once('function.php');

echo $html = geturlc("http://movie.douban.com/subject/10807909/?from=showing");



$data['title'] = preg_match('/<span property="v:itemreviewed">(.*)</span>/',$html);
echo $data['title'];


?>