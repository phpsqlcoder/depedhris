<?php
$conn=mysql_connect("localhost","root","pangitka");
mysql_select_db("hris",$conn);

$sql = "SELECT * FROM pagibigloantemp";
$rs = mysql_query($sql,$conn);
while ($dt = mysql_fetch_assoc($rs)){
	echo $dt['employeeId']."<br>";
	$sql2 = "INSERT INTO loan_employee (employeeId, loanId, loanAmount, nOfDeduction, dedDateStart, posted, postedDate) 
			VALUES('".$dt['employeeId']."', '".$dt['loanId']."', '".$dt['loanAmount']."', '".$dt['noOfDeductio']."', '2014-01-08', '1', '2014-01-08')";
	$insert = mysql_query($sql2);
}


?>