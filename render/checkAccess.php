<?php
	
	if(isset($_POST['apiKey'])){
		if($_POST['apiKey']==$apiKey){
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
