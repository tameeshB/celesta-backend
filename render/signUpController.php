<?php
include 'dbConfig.php';
include 'render/checkAccess.php'
function SQLInjFilter(&$unfilteredString){
		$unfilteredString = mb_convert_encoding($unfilteredString, 'UTF-8', 'UTF-8');
		$unfilteredString = htmlentities($unfilteredString, ENT_QUOTES, 'UTF-8');
}
$error = "";
$return = "";
$status = 0;
$ret = array();
//$json = file_get_contents('php://input');
//$obj = json_decode($json);
if (!isset($_POST['name']) || $_POST['name']=="") {
	$error .= "Name invalid. ";
	$status = 400;
}
if (!isset($_POST['emailid']) || !filter_var($_POST['emailid'], FILTER_VALIDATE_EMAIL) ) {
	$error .= "emailID invalid. ";
	$status = 400;
}
if (!isset($_POST['password']) || $_POST['password']=='' ) {
        $error .= "password empty ";
        $status = 400;
}
if (!isset($_POST['mobile']) || !preg_match('/^[789][0-9]{9}$/',$_POST['mobile'])) {//simple regex for mobile validation
	$error .= "Mobile no Invalid. ";
	$status = 400;
}
if (!isset($_POST['college']) || $_POST['college']=="") {
	$error .= "College blank. ";
	$status = 400;
}
//if (!isset($_POST['year']) || $_POST['year']<1 || $_POST['year']>4) {
//	$error .= "Year invalid. ";
//	$status = 400;
//}
if($status!=400){
	//sql injection filter function call goes here
	SQLInjFilter($_POST['mobile']);
	SQLInjFilter($_POST['emailid']);
	SQLInjFilter($_POST['college']);
	SQLInjFilter($_POST['password']);
	SQLInjFilter($_POST['name']);
	//db stuff here
	$sql = "INSERT INTO `users`(name,email, phone,pswd, college, year) VALUES ('".$_POST['name']."', '".$_POST['emailid']."', '".$_POST['mobile']."', '".sha1($_POST['password'])."', '".$_POST['college']."','1')";
	//password field absent, otherwise also store sha1($_POST['password'])
	//assuming table name 'users' as not given in email
	if($link =mysqli_connect($servername, $username, $password, $dbname)){
	$result = mysqli_query($link,$sql);
	    if($result){
	    	$status=200;
	    	$return="Successfully Registered";
	    	
	    } else {
	    	//error to fetch result
	    	$status = 400;
	    	$error = "error to fetch result ".mysqli_errorno($link);
	    }
	if(mysqli_errno($link)==1062){
		$status = 409;
		$error = "Duplicate entry";
	}
    }else{
    	//error to connect to db
    	$status = 500;
    	$error = "error connecting to DB";
	$error.=   "Debugging errno: " . mysqli_connect_errno();
    }
}
// $status=200;
// 	$return="Successfully Registered";
if($status == 200){
	$ret["status"] = 200;
	$ret["message"] = $return;
}else{
	$ret["status"] = $status;
	$ret["message"] = $error;
}
//$ret['deb']=$_POST['deb'];
//$data_back = json_decode(file_get_contents('php://input'));
//echo $data_back->{"data1"};
//echo var_dump($obj);
//http_response_code($status);
echo json_encode($ret);

?>
