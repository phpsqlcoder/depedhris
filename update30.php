<?php
ob_start();
	include("dbcon.php");
	$a=mysql_query("update employee set isActive=1 where ndex=854");
echo 	"successfull!";

?>



