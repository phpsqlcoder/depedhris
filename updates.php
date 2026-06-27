<?php
ob_start();
include("dbcon.php");

$aprovsh=mysql_query("update `loansetup` set posted='0' WHERE employeeId='528'",$conn);
echo "success";
?>




