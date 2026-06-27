<?php
ob_start();
session_start();
include("../dbcon.php");
include("../employeefunctions.php");
include("payrollfunctions.php");

$dateToProcessed = date('Y-04-15');


function netBasic($employeeNo, $cutoffDateStart, $cutoffDateEnd){
	$rs = mysql_query("SELECT IFNULL(SUM(netBasic),0) netBasic FROM payroll WHERE empid='".$employeeNo."' AND pay_period BETWEEN '".$cutoffDateStart."' AND '".$cutoffDateEnd."'");
	$dt = mysql_fetch_assoc($rs);
	return $dt['netBasic'];
}

/**
* 1. GET ACTIVE EMPLOYEE REGULAR.
* 2. IF EMPLOYEE IS EMPLOYED AFTER JANUARY THEN GET NUMBER OF MONTHS BY SUBTRACTING CURRENT MONTH AND DATE OF EMPLOYMENT.
* 3. GET BASIC PAY
* 4. MULTIPLY QUOTIENT OF BASICPAY / 12 TO THE RESULT OF NO. 2
* 5. INSERT INTO PAYROLL 13TH MONTH TABLE.
*	April released (1st Half)									
*									
*		Scope: 	Regular Employees only								
*					Computation for Monthly paid:								
*					Prevaling and/or latest basic pay DIVIDED by 2								
*					(ex. P10,000 / 12 = P5,000)								
*													
*					Computation for Daily paid:								
*					Total basic salary earned from December of the previous year to March of the current year DIVIDED by 12 months		
*/

$sql = "SELECT e.*, ec.basicPay, ec.payTypeNdex FROM employee e 
							LEFT JOIN employee_compensation ec ON ec.employeeId = e.ndex
								WHERE e.isActive = '1' && e.employmentStatus='Regular'  && e.residencyTrainingProgram='' 
									ORDER BY e.dateHired DESC";
									
$rs = mysql_query($sql, $conn);
while ($dt = mysql_fetch_assoc($rs)){
	// 13th Month Amount
	//echo "SELECT IFNULL(SUM(netBasic),0) netBasic FROM payroll WHERE empid='".$dt['ndex']."' AND pay_period BETWEEN '".date('Y-12-01',strtotime('-1 year',strtotime($dateToProcessed)))."' AND '".date('Y-03-31', strtotime($dateToProcessed))."' <br><br><br>";
	$totalNetBasic = netBasic($dt['ndex'],date('Y-12-01',strtotime('-1 year',strtotime($dateToProcessed))), date('Y-03-31', strtotime($dateToProcessed)));  // daily
	$amount13thMonth = $dt['payTypeNdex'] == 2 ? ((($totalNetBasic / 12) / 4) * 6) : ($dt['basicPay'] / 12) * 6;

	echo "Employee No = ".$dt['ndex']."; ";

	// Check If Employee Already Exist in cutoff
	$checkIfExist = mysql_num_rows(mysql_query("SELECT * FROM payroll13thmonth WHERE empNo='".$dt['ndex']."' AND cutOffDate='".$dateToProcessed."'",$conn));
	if ($checkIfExist){
		echo "exist";
		$updateRow = mysql_query("UPDATE payroll13thmonth SET emp_level='".$dt['level']."', basicPay='".$dt['basicPay']."', totalNetBasic='".$totalNetBasic."', amount13thMonth='".$amount13thMonth."' WHERE empNo='".$dt['ndex']."' AND cutOffDate='".$dateToProcessed."'", $conn);
	} else {
		echo "not exist";
		$insertRow = mysql_query("INSERT INTO payroll13thmonth (emp_level,`empNo`, `basicPay`, `cutOffDate`, `totalNetBasic`, `amount13thMonth`) 
									 VALUES ('".$dt['level']."', '".$dt['ndex']."', '".$dt['basicPay']."', '".$dateToProcessed."', '".$totalNetBasic."', '".$amount13thMonth."')",$conn);
	}
	echo "<br>";
}

echo "Generation of 13th month done..";
?>



