<?php
include 'dbConfig.php';
include 'render/log.php';
include 'render/checkAccess.php';
function SQLInjFilter(&$unfilteredString){
	$unfilteredString = mb_convert_encoding($unfilteredString, 'UTF-8', 'UTF-8');
	$unfilteredString = htmlentities($unfilteredString, ENT_QUOTES, 'UTF-8');
}
$error = "";
$return = "";
$status = 0;
$uID=-1;
$ret = array();
if (!isset($_POST['emailid']) || !filter_var($_POST['emailid'], FILTER_VALIDATE_EMAIL) ) {
	$error .= "emailID blank. ";
	$status = 400;
}
if (!isset($_POST['password']) || $_POST['password']=='' ) {
        $error .= "password blank. ";
        $status = 400;
}
if($status!=400){
	//SQL inj sanitation here?
	SQLInjFilter($_POST['mobile']);
	SQLInjFilter($_POST['password']);
	//db stuff here
	$sql = "SELECT `pswd`,`regID`,`name` FROM `users` WHERE `email`= '".$_POST['emailid']."'";
	if($link =mysqli_connect($servername, $username, $password, $dbname)){
	$result = mysqli_query($link,$sql);
	    if(!$result || mysqli_num_rows($result)<1){
	    	$status=403;
	    	$return="Invalid credentials. Access Forbidden.";
		errorLog(mysqli_errorno($link)." ".mysqli_error($link));
	    } else {
	    	while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
	    		if($row['pswd']==sha1($_POST['password'])){
	    			$status=200;
	    			$return="Welcome ".$row['name'];
				$uID=$row['regID'];
				$_SESSION['uid']=$uID;
				$_SESSION['name'] = $row['name'];
	    			//set sessionID etc etc...
	    		}else{
	    			$status=403;
	    			$return="Invalid credentials. Access Forbidden.";	
				errorLog(mysqli_errorno($link)." ".mysqli_error($link));
}
	    	}
	    }
    }else{
    	//error to connect to db
    	$status = 500;
    	$error = "error connecting to DB";
	errorLog(mysqli_errorno($link)." ".mysqli_error($link));
    }
}
if($status == 200){
	$ret["status"] = 200;
	$ret["userID"] = $uID;
	$ret["message"] = $return;
}else{
	$ret["status"] = $status;
	$ret["message"] = $error." For help, error reference no: $errRef";
	errorLog($error);
}
echo json_encode($ret);

?>
