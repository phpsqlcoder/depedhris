<?php
ob_start();

	include("dbcon.php");
	//$fa=mysql_query("delete from `employeechangestatus` where ndex in (155,156,159)");
	
	
	
	$a=mysql_query("update employee set isActive='1',endDate='0000-00-00' where ndex in (21,614,629)");
	echo "success 4";
?>
