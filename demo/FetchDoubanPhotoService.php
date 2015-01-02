<?php

/**
获取豆瓣的剧照服务
 */

$douban_id = $_GET['douban_id'];
$f = new FetchDoubanPhotoService();
$f->addStage($douban_id);


class FetchDoubanPhotoService {
    //豆瓣电影的前缀
    private $prefix     = "http://movie.douban.com/subject/";
    //页面大小
    private $pageSize   = 40;

    //添加剧照, 根据豆瓣id
    public function addStage($douban_id){
        $urls = $this->getPhotosByUrls($douban_id);
        var_dump($urls);

//        $movie_id = $this->getMovieIdByDoubanId($douban_id);
//        if( !$movie_id ) {
//            return FALSE;
//        }

        //存储图片
        for ($i=0; $i<count($urls), $i++) {
            $url = $urls[$i];
            $content = file_get_contents($url);
            $filename = basename($url);
            $this->uploadImg($content, $filename);
        }

        for( $i=0;$i<count($urls);$i++ ) {
            $user_id        = 0;
            $orders         = $i;
            $type           = 2;
            $status         = 0;
            $create_time    = time();
            $url            = $urls[$i];
            $stage          = basename($url);

            //todo 写入数据库
            $sql = "INSERT INTO n_movie_stage(movie_id, user_id, orders, type, stage, status, create_time, url) "
                                    . " values($movie_id, $user_id, $orders, $type, '$stage', $status, $create_time, '$url')";
            echo $sql;
            /*
echo '<br />'.$i.' //// ';
echo $sql;
try{
    $connection = Yii::app()->db;
    $command = $connection->createCommand($sql);
    $result = $command->query();
    if( !$result ) {
        echo "FALSE";
    } else {
        echo "TRUE";
    }
    echo '<hr />';
}catch(Exception $e){

}
 */
        }
    }

    //获取图片列表根据不同页面的URL
    public function getPhotosByUrls($douban_id){
        $urls = $this->getUrlsByDoubanId($douban_id);
        var_dump($urls);
        if(is_array($urls) ) {
            $photos = array();
            foreach ($urls as $url) {
                $photo = $this->getPhotosByUrl($url);
                $photos = array_merge($photos, $photo);
            }
            return $photos;
        }
        return FALSE;
    }

    //上传图片
    public function uploadImg($content, $filename, $bucket='candou-stage') {
        //todo
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

    //获取图片的页面url，包括剧照和截图，例如剧照有159条，那么就有4页（即4个URL），因为每页显示40条
    public function getUrlsByDoubanId($douban_id) {
        $url                = $this->getIndexUrl($douban_id);
        $content            = $this->getcontentByUrl($url);
        $officialCount      = $this->getOfficialCountByContent($content);
        $screenshotCount    = $this->getScreenshotCountByContent($content);
        $urls = array();
        for ($i=0; $i<$officialCount/$this->pageSize; $i++) {
            $start = $this->pageSize * $i;
            $urls[] = $this->getOfficialUrl($douban_id, $start);
        }
        for ($i=0; $i<$screenshotCount/$this->pageSize; $i++) {
            $start = $this->pageSize * $i;
            $urls[] = $this->getScreenshotUrl($douban_id, $start);
        }
        return $urls;
    }

    //获取到图片地址根据地址
    public function getPhotosByUrl($url) {
        $content = $this->getcontentByUrl($url);
        $photos = $this->matchImgUrlsByContent($content);
        foreach ($photos as &$photo) {
            $photo = $this->getRawUrlByThumbUrl($photo);
        }
        return $photos;
    }


    //获取图片内容
    public function getcontentByUrl($url) {
        $ch = $this->getCurlByUrl($url);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    //获取图片的str值
    public function fetchImageStr($url) {
        return file_get_contents($url);
    }


    //抓取多个页面的内容
    public function getInfosByUrls($urls){
        if (!is_array($urls) or count($urls) == 0) {
            return false;
        }
        $curl = $text = array();
        $handle = curl_multi_init();
        foreach($urls as $k => $v) {
            $nurl[$k]= preg_replace('~([^:\/\.]+)~ei', "rawurlencode('\\1')", $v);
            $curl[$k] = $this->getCurlByUrl($nurl[$k]);
            curl_multi_add_handle ($handle, $curl[$k]);
        }

        $active = null;
        do {
            $mrc = curl_multi_exec($handle, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($handle) != -1) {
                do {
                    $mrc = curl_multi_exec($handle, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        foreach ($curl as $k => $v) {
            $c = (string)curl_multi_getcontent($curl[$k]);
            if (curl_error($curl[$k]) == "") {
                if(preg_match("/Your browser should have redirected you to (.*)/", $c, $ar)){
                    $text[$k] = file_get_contents($ar[1]);
                } else {
                    $text[$k] = $c;
                }
            }
            curl_multi_remove_handle($handle, $curl[$k]);
            curl_close($curl[$k]);
        }
        curl_multi_close($handle);
        return $text;
    }

    //匹配官方剧照的个数
    public function getOfficialCountByContent($content){
        $reg = "/官方剧照 \((\d+)\)/m";
        preg_match($reg, $content, $arr);
        return $arr[1];
    }

    //匹配截图的个数
    public function getScreenshotCountByContent($content){
        $reg = "/截图 \((\d+)\)/m";
        preg_match($reg, $content, $arr);
        return $arr[1];
    }

    //匹配图片的地址列表根据内容
    public function matchImgUrlsByContent($content) {
        $reg = "/<img src=\"(http:\/\/img\d\.douban\.com\/view\/photo\/thumb\/public\/p\d+\.jpg)\".*?>/i";
        preg_match_all($reg, $content, $arr);
        $result = $arr[1];
        return $result;
    }

    //获取获取入口页面的地址
    public function getIndexUrl($douban_id){
        return $this->prefix."$douban_id/photos?type=S";
    }

    //获取官方剧照页面的地址
    public function getOfficialUrl($douban_id, $start) {
        return $this->prefix."$douban_id/photos?type=S&start=". $start ."&sortby=vote&size=a&subtype=o";
    }

    //获取截图页面的地址
    public function getScreenshotUrl($douban_id, $start) {
        return $this->prefix."$douban_id/photos?type=S&start=". $start ."&sortby=vote&size=a&subtype=c";
    }

    //根据缩略图的url, 返回原图的url
    public function getRawUrlByThumbUrl($url){
        return str_replace("thumb", "raw", $url);
    }

    //豆瓣的一个参数产生方法
    private function gen_gid(){
        $bids = array();
        for ($j=0; $j<500; $j++) {
            $bid = array();
            for ($i=0; $i<20; $i++){
                array_push($bid, chr(mt_rand(65, 90)) );
            }
            array_push($bids, join($bid));
        }
        return $bids[array_rand($bids, 1)];
    }

    //得到curl
    public function getCurlByUrl($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ch, CURLOPT_REFERER, $this->refer);
        curl_setopt($ch, CURLOPT_COOKIE, 'bid='.$this->gen_gid());
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        return $ch;
    }
}
