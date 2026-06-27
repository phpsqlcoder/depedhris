<?php
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");

$sql = "SELECT * FROM loansetup WHERE loanType='OTHERS' && dedDateStart>='2013-06-15' && posted='1'";
$rs = mysql_query($sql);
while($dt = mysql_fetch_assoc($rs)){
	echo $dt['ndex']." ".$dt['employeeId']." ".$dt['dedAmount']."<br>";
	
	//update payroll field d_other (add loan Others from loansetup)
	$updateSql = "UPDATE payroll SET d_other=(d_other - ".$dt['dedAmount'].") where empid='".$dt['employeeId']."' && pay_period='2013-05-15'";
	$updatePayroll = mysql_query($updateSql,$conn);
	//insert loanpayments 
	$insertLoanPayments = mysql_query("INSERT INTO loanpayments (loanSetupId, EmployeesId, datePaid, amountPaid) VALUES ('".$dt['ndex']."', '".$dt['employeeId']."', '2013-05-15', '".$dt['dedAmount']."')",$conn);
}
?>