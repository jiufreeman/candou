<?php

//上传图片
function uploadImg($content, $filename, $bucket='candou-stage') {
    //引入又拍云 PHP SDK
    require_once('upyun.class.php');

    //实例化又拍云对象, bucket, operate user, operate password
    $upyun = new UpYun($bucket, 'maxwelldu', 'maxwelldu');

    $opts = array(
        UpYun::CONTENT_MD5 => md5($content)
    );

    $rsp = $upyun->writeFile("/".$filename, $content, true, $opts);
    $width = intval($rsp['x-upyun-width']);
    $height = intval($rsp['x-upyun-height']);

    echo $width;
    echo $height;
}

$url = "http://img3.douban.com/view/photo/raw/public/p2218972270.jpg";
echo $url;
$filename = basename($url);
echo $filename;
$content = file_get_contents($url);
uploadImg($content, $filename);

