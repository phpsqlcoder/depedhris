<?php
$conn=mysqli_connect("localhost","root","pangitka");
mysqli_select_db($conn,"hris");
include ("options.php");
include_once("restriction.php");
$uri=explode("/",$_SERVER['REQUEST_URI']);

//if($uri[2]=='payroll' && $_SESSION['isAllowedOnPayroll']==0 && $uri[4]!='output'){
//	echo "You are not allowed to access this page!";
//	die();
//}

// function save_user_logs_history($data){
// 	date_default_timezone_set("Asia/Manila");
// 	$ins=mysql_query("insert into user_history_logs (user,log_date,details,tran_id,tran_table) values 
// 		('".$_SESSION['fullName']."','".date('Y-m-d H:i:s')."','".$data['details']."','".$data['tran_id']."','".$data['tran_table']."')");
// }
?>