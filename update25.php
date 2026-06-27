<?php
ob_start();
	include("dbcon.php");
	$a=mysql_query("update users set deptId='0' where deptId=''");

?>



