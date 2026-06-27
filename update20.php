<?php
ob_start();
	include("dbcon.php");
	$a=mysql_query("update employee set isActive=0,endDate='2012-10-23' where ndex=917");
	$b=mysql_query("update employee set isActive=1,endDate='0000-00-00' where ndex=914");
?>



