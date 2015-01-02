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
  $data = culr_exec($ch);
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

