<?php
$conn=mysql_connect("localhost","root","pangitka");
mysql_select_db("hris",$conn);

$sql = "ALTER TABLE `employee_compensation` ADD `pagibigSavings` DECIMAL( 9, 2 ) NOT NULL AFTER `taxType` ;
ALTER TABLE `payroll` ADD `pagibigSavings` DECIMAL( 11, 2 ) NOT NULL AFTER `pagibig` ;";
$rs = mysql_query($sql,$conn);



?>