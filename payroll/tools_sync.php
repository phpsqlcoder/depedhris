<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");

	
	mysql_select_db("hris",$conn);
	$last_id = mysql_fetch_array(mysql_query("select * from ar_hospital_ee_trx order by ndex desc limit 1",$conn));


	mysql_select_db("hr_interface_db",$conn);
	$trx_qry = mysql_query("select * from ar_hospital_ee_trx where ndex > '".$last_id['ndex']."'",$conn);
	echo "select * from ar_hospital_ee_trx where ndex > '".$last_id['ndex']."'";
	while($trx = mysql_fetch_array($trx_qry)){
		mysql_select_db("hris",$conn);
		
		$ins = mysql_query("insert into ar_hospital_ee_trx (
			`ndex`, `employeeId`, `compcode`, `compname`, `Batch_No`, `AR_No`, `Pat_No`, `Pat_Name`, `PatType`, `Status`, `Trx_type`, `ORNo`, `Amount`, `TrxDate`, `amortization`, `doctorId`, `doctorName`, `priorityNo`, `dateInterfaced`, `no_of_deduction`, `start_date`) 

			VALUES (
			'".$trx['ndex']."','".$trx['employeeId']."','".$trx['compcode']."','".$trx['compname']."','".$trx['Batch_No']."','".$trx['AR_No']."','".$trx['Pat_No']."','".$trx['Pat_Name']."','".$trx['PatType']."','".$trx['Status']."','".$trx['Trx_type']."','".$trx['ORNo']."','".$trx['Amount']."','".$trx['TrxDate']."','".$trx['amortization']."','".$trx['doctorId']."','".$trx['doctorName']."','".$trx['priorityNo']."','".$trx['dateInterfaced']."','".$trx['no_of_deduction']."','".$trx['start_date']."'
		)");
	}

	
	header("location:tools.php?sync=success");

?>