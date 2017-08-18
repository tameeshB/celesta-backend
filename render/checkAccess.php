<?php
	if(isset($_POST['apiKey'])){
		if(in_array($_POST['apiKey'],$apiKeys)){
			//all Good
		}
		else {
			http_response_code(403);
                	exit(1);
		}
	} else {
		http_response_code(403);
		exit(1);
	}
$values = '';
foreach($_POST as $key=>$value) {
  if($key!='apiKey')
    $values.= $key . "=" . $value . "\t";
}

