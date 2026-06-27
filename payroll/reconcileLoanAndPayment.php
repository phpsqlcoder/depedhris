<?php
include("../dbcon.php");

$sql = "SELECT * FROM loansetup WHERE posted='1'";  // && employeeId='88'
$rs = mysql_query($sql);
while($dt = mysql_fetch_assoc($rs)){
	//echo $dt['ndex']." ".$dt['employeeId']." ".$dt['dedAmount']."<br>";

	$sqlLP = "SELECT * FROM loanpayments WHERE EmployeeId='".$dt['employeeId']."' && amountPaid='".$dt['dedAmount']."' && loanSetupId NOT IN (select ndex FROM loansetup WHERE posted='1' )";
	$rsLP = mysql_query($sqlLP,$conn);
	while($dtLP = mysql_fetch_assoc($rsLP)){
		//echo "&nbsp; &nbsp; &nbsp; ".$dtLP['loanSetupId']." ".$dt['ndex']." ".$dtLP['ndex']."<br>";
		$updateLoansetupId = mysql_query("UPDATE loanpayments SET loanSetupId='".$dt['ndex']."' WHERE ndex='".$dtLP['ndex']."'",$conn);
	}
	$updateLoanSetupBalance=mysql_query("UPDATE loansetup SET loanBalance=(loanAmount - (SELECT SUM(amountPaid) FROM loanpayments WHERE loanSetupId='".$dt['ndex']."')) WHERE ndex='".$dt['ndex']."'",$conn);
}
?>