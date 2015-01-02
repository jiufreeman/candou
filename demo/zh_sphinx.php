<?php
$search = new SphinxClient();
$search -> setServer('127.0.0.1', 9312);
$search -> setConnectTimeout(2);
$search -> setArrayResult(true);
$search -> setMatchMode(SPH_MATCH_ANY);
$search -> setRankingMode(SPH_RANK_PROXIMITY_BM25);
$search -> setSortMode(SPH_SORT_EXTENDED, '@relevance desc,@weight desc');
$search -> setLimits($offset, $perNum);
$search -> setFieldWeights(array('subject' => 2000, 'message' => 0));
dump($search);
?>