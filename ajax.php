<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");

if($_GET['acts']=='afterdivisionselectfordept'){
	$dept=mysql_query("SELECT * FROM dept WHERE status<>1 and divisionId='".$_POST['divisionId']."'");
	$optiondepts.="<select name='deptId' onchange=\"dbcon('afterdeptselectforunit','unitdiv',employeefrm);\">";
	while($rsdept=mysql_fetch_object($dept)){
		$optiondepts.="<option value='".$rsdept->ndex."'>".$rsdept->name."";
	}
	$optiondepts.="</select>";
	echo $optiondepts;
}
if($_GET['acts']=='afterdeptselectforunit'){
	$unit=mysql_query("SELECT * FROM unit WHERE status<>1 and departmentId='".$_POST['deptId']."'");
	$optionunits.="<select name='unitId'><option value='0'>- Select Department -";
	while($rsunit=mysql_fetch_object($unit)){
		$optionunits.="<option value='".$rsunit->ndex."'>".$rsunit->name."";
	}
	$optionunits.="</select>";
	echo $optionunits;
}


