<?php
ob_start();
	include("dbcon.php");
	$a=mysql_query("
CREATE TABLE  `hris`.`payroll_other_deduction` (
`ndex` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 200 ) NOT NULL ,
`isActive` INT NOT NULL ,
`addedBy` INT NOT NULL ,
`addedDate` DATE NOT NULL
) ENGINE = MYISAM ;");

	$b=mysql_query("CREATE TABLE  `hris`.`payroll_other_deduction_data` (
`ndex` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`payroll_Id` INT NOT NULL ,
`otherDeductionId` INT NOT NULL ,
`dAmount` DECIMAL( 11, 2 ) NOT NULL
) ENGINE = MYISAM ;");

	$c=mysql_query("CREATE TABLE `hris`.`payroll_hospital_deduction_data` (
`ndex` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`payroll_Id` INT NOT NULL ,
`dAmount` DECIMAL( 10, 2 ) NOT NULL ,
`hospitalType` VARCHAR( 20 ) NOT NULL
) ENGINE = MYISAM ;");

echo 	"successfull!";

?>



