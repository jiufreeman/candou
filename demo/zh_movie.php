<?php
function geturlc($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $useragent = "Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-us) AppleWebKit/125.5.5 (KHTML, like Gecko) Safari/125.12";
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_REFERER, "http://movie.douban.com/");
    curl_setopt($ch, CURLOPT_COOKIE, 'bid='.gen_gid());
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function gen_gid(){
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

function get_movie($id){

    $html = geturlc("http://movie.douban.com/subject/".$id."/?from=showing");

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

    $data['douban_id']  = $id;
    $data['name']      = $match1[1];
    $data['director']     = $match2[1];
    $data['scriptwriter']     = strip_tags($match3[1]);
    $data['actor']     = strip_tags($match4[1]);
    $data['type']     = $match5[1];
    $data['country']       = $match6[1];
    $data['language']      = $match7[1];
    $data['release_time']   = $match8[1];
    $data['duration']  = $match9[1];
    $data['other_name']    = $match10[1];
    //imdburl 不用
    $data['imdburl']    = $match11[1];
    $data['imdb']   = $match11[2];
    $data['everage']     = $match11[1];
    $data['brief']    = $match13[1];

    return $data;
}

?>