<?php
ob_start();

	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
    include ("myfunctions.php");

	$c=mysql_query("select * from kiosk_request");
	while($x=mysql_fetch_object($c)){
		
		$created = mysql_fetch_array(mysql_query("select * from kiosk_request_logs where action='Create Request' and request_id='".$x->ndex."'"));
		$approved_dept = mysql_fetch_array(mysql_query("select * from kiosk_request_logs where action='Approve Request' and request_id='".$x->ndex."'"));
		$approved_hr = mysql_fetch_array(mysql_query("select * from kiosk_request_logs where action='Approve Request (HR)' and request_id='".$x->ndex."'"));

		$upd=mysql_query("update kiosk_request set 
		createdDate='".$created['timelog']."',
		deptApprovedDate='".$approved_dept['timelog']."',
		hrApprovedDate='".$approved_hr['timelog']."',
		deptApprovedBy='".$approved_dept['user']."',
		hrApprovedBy='".$approved_hr['user']."'
		where ndex='".$x->ndex."'");
		//echo $x->c." - ".$e->lastName.", ".$e->firstName."<br>";
	}
	
?>
