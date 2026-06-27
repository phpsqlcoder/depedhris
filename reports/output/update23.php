<?php
ob_start();
	include("dbcon.php");
	$a=mysql_query("update `employee_leave_limit` set yer='2013'");

?>



