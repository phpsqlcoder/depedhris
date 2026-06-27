<?php
ob_start();
	include("dbcon.php");
	$a=mysql_query("ALTER TABLE  `employee_leave_limit` ADD  `yer` INT NOT NULL");

?>



