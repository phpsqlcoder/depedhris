<?php
ob_start();
include("dbcon.php");

$aprovsh=mysql_query("ALTER TABLE  `empdependents` ADD  `isMedicalDependent` INT NOT NULL");


echo "success";
?>




