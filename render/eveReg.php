<?php
include 'dbConfig.php';
session_start();
$app=0;
$err=0;
function SQLInjFilter(&$unfilteredString){
		$unfilteredString = mb_convert_encoding($unfilteredString, 'UTF-8', 'UTF-8');
		$unfilteredString = htmlentities($unfilteredString, ENT_QUOTES, 'UTF-8');
		// return $unfilteredString;
}
$out="";
$urlParse = explode("/", $_SERVER['REQUEST_URI']);
$eveID = $urlParse[3];
$userID = $urlParse[4];
$eveName = $urlParse[5];
$eveName_ = $eveName;
SQLInjFilter($eveID);
SQLInjFilter($userID);
SQLInjFilter($eveID);
SQLInjFilter($eveName_);

$redirURL = "http://".$_SERVER['HTTP_HOST']."/event/".$urlParse[5].".htm";
if(!isset($_SESSION["uid"]) && $userID == "0"){
	$out .="Need to login";
	$err = 1;
}else if($userID == "0" && $eveName != "0"){
	//Web request
	$userID=$_SESSION["uid"];
	$app=0;

}
else if($userID != "0" && $eveName == "0"){
	//App request
	$app=1;
	//validate userID using app ID
	if(isset($_POST["key"])){
		if(sha1($_POST["key"])!='e9f58b895b534f8c23ad5c06fc5c3eb796193ca1'){
			$err=1;
			$out .= "Invalid App Access Key";
		}	
	}else{
		$err=1;
		$out .= "Invalid App Access Key";
	}
}

$hash = ($userID * 100 ) + $eveID;

$sql = "INSERT INTO `eventreg`(eveID,uID, eveName,hash) VALUES ('".$eveID."', '".$userID."', '".$eveName_."', '".$hash."')";

if($err==0 && $link =mysqli_connect($servername, $username, $password, $dbname)){
	$result = mysqli_query($link,$sql);
    if($result){
    	$out .= "Registered!";
    }else{
    	if(mysqli_errno($link)==1062){
    			$out .= "Already registered";
    	}else{
    		$out .= mysqli_errno($link).":".mysqli_error($link);
    	}
    }
    
}

if($app){
	echo json_encode([(int)!$err,$out]);
}else{
	header( "refresh:2; url=".$redirURL );
	echo $out;
}


