<?php
require_once("function.php");

$content = geturlc("http://movie.douban.com/subject/10807909/?from=showing");
echo $content;