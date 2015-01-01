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
	  return curl_exec($ch);
  }


