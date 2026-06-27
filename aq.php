<?php
ob_start();
include("dbcon.php");
$a=mysql_query(
"ALTER TABLE  `dailytimesummary` ADD  `isBirthday` INT NOT NULL"
);
	$c=mysql_query("delete from leave where `ndex`=9");
	$b=mysql_query("delete from employee_leave where leaveId=9");
?>
