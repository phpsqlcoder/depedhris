<?php
include("dbcon.php");
$a=mysql_query("CREATE TABLE  `hris`.`sap` (
`ndex` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`sapId` INT NOT NULL ,
`name` VARCHAR( 100 ) NOT NULL
) ENGINE = MYISAM ;"); //sap table
$a2=mysql_query("ALTER TABLE  `dept` ADD  `sapId` INT NOT NULL"); // dept table add sapId

?>