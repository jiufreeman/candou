<?php
$www_folder = "/data/htdocs/candou";

$raw_json = file_get_contents('php://input');
print_r(json_decode($raw_json, true));

echo shell_exec(" cd $www_folder ; git pull 2>&1");
