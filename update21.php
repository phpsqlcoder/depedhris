<?php
ob_start();
	include("dbcon.php");
	$a=mysql_query("ALTER TABLE  `employeechangestatus` ADD  `remarks` VARCHAR( 200 ) NOT NULL");

?>



