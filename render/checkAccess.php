<?php
include 'dbConfig.php';
$filename = 'logs/p_log.txt';
$handle = fopen($filename, 'a');
fwrite($handle, $_POST['apiKey'].PHP_EOL);
fclose($handle);
	if(isset($_POST['apiKey'])){
		if($_POST['apiKey']=='99537874e694ecc9323cc986dd452d2502740dcc'){
			//all Good
		}
		else {
//			http_response_code(403);
  //              	exit(1);
include 'dbConfig.php';
$filename = 'logs/p_log.txt';
$handle = fopen($filename, 'a');
fwrite($handle, "m".PHP_EOL);		
}
	} else {
		//http_response_code(400);
		//exit(1);
include 'dbConfig.php';
$filename = 'logs/p_log.txt';
$handle = fopen($filename, 'a');
fwrite($handle, "n".PHP_EOL);
	}
$values = '';
foreach($_POST as $key=>$value) {
  if($key!='apiKey')
    $values.= $key . "=" . $value . "\t";
}

