<?php
ob_start();
include("dbcon.php");

$aprovsh=mysql_query("delete from shifting where timeIn='00:05:00' and timeOut='12:13:00' and name='AM'");


echo "success";
?>




