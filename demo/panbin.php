<?php
require('function.php');

$data = geturlc('http://movie.douban.com/subject/10807909/photos?type=S&start=0&sortby=vote&size=a&subtype=o');
echo $data;
