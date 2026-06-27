<?php
ob_start();
session_start();

include("dbcon.php");
include ("employeefunctions.php");

if($_GET['act']=='disapprove'){
	$id = $_GET['id'];

	//echo "sss";

	date_default_timezone_set("Asia/Manila");
	$update = mysql_query("update kiosk_request set isDisapproved='1' where ndex='".$id."'");
	$logs = mysql_query("insert into kiosk_request_logs (action,timelog,request_id,user,remarks) VALUES ('Disapproved Request','".date('Y-m-d H:i:s')."','".$id."','".$_SESSION['nym']."','".$_GET['remark']."')");
	
}

if($_GET['act']=='approve'){
	$id = $_GET['id'];

	date_default_timezone_set("Asia/Manila");	
	$update = mysql_query("update kiosk_request set approve1='1',deptApprovedDate='".date('Y-m-d H:i:s')."',deptApprovedBy='".$_SESSION['nym']."' where ndex='".$id."'");
	date_default_timezone_set("Asia/Manila");
	$logs = mysql_query("insert into kiosk_request_logs (action,timelog,request_id,user,remarks) 
		VALUES ('Approve Request','".date('Y-m-d H:i:s')."','".$id."','".$_SESSION['nym']."','".$_GET['remark']."')");
	
}

