<?php
ob_start();
session_start();
include("../dbcon.php");
include("../employeefunctions.php");
include("payrollfunctions.php");


//echo ( (strtotime($dt['dateHired']) - strtotime(date('Y-m-d'))) / 3600 / 24 );

//die();

$dateToProcessed = date('Y-12-15');
//$cutoffStart = date('Y-04-01',strtotime($dateToProcessed));
$cutoffStart = date('Y-m-d',strtotime("-1 year",strtotime(date($dateToProcessed))));
$cutoffEnd = date('Y-11-t', strtotime($dateToProcessed));


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
*
*
* 
December released (2nd half)									
									
*1 Year Continuous in service (regardless of employment status)									
Reckoning period - January 1 to December 31									
	Computation:								
	Current/latest basic pay less portion of 13th month released on April								
	(ex. P10,000 basic - P5,000 1st half 13th month pay = P5,000 )								
									
*Less than 1 year of service and on leave without pay									
	Computation:								
	Current/latest basic pay DIVIDED by 12 months a year times the number of months in service								
									
*Temporary employees									
Reckoning period - hiring date to November 30									
	Computation:								
	Total basic salary earned from the date of hiring to November of the current year DIVIDED by 12 months								
									
*Daily paid employees 									
	Computation:								
	Total basic salary earned from April to November of the current year DIVIDED by 12 months								

*/

$sql = "SELECT e.*, ec.basicPay, ec.payTypeNdex FROM employee e 
							LEFT JOIN employee_compensation ec ON ec.employeeId = e.ndex
								WHERE e.isActive = '1' && e.employmentStatus IN ('Regular','Temporary','Probationary')  && e.residencyTrainingProgram=''
									ORDER BY e.dateHired DESC"; //&& e.ndex='1438' ;  && e.ndex IN ('1554')
									
$rs = mysql_query($sql, $conn);
while ($dt = mysql_fetch_assoc($rs)){
	
	$totalNetBasic = netBasic($dt['ndex'], $cutoffStart, $cutoffEnd);  // daily
	
	// Get 13th Month of April
	$payroll13thmonth = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll13thmonth WHERE empNo = '".$dt['ndex']."' && cutOffDate ='".date('Y-04-15')."'", $conn));
		
	// 1.) 1 Year Continuous in service (regardless of employment status) and paytype is monthly (payTypeNdex = 1) 
	//echo "EmpStatus: ".$dt['employmentStatus']."<br> dateHired: ".$dt['dateHired'];
	if (date('Y', strtotime($dt['dateHired'])) < date('Y')){ 
		
		if ($dt['employmentStatus'] == 'Regular'){
			$amount13thMonth = $dt['payTypeNdex'] == 2 ? ($totalNetBasic / 12) - $payroll13thmonth['amount13thMonth'] : $dt['basicPay'] - $payroll13thmonth['amount13thMonth'];
		} elseif ($dt['employmentStatus'] == 'Temporary'){
			$amount13thMonth = netBasic($dt['ndex'], date('Y-01-01',strtotime($dateToProcessed)), date('Y-11-t',strtotime($dateToProcessed))) / 12;
		}
	} else {
		// get no of Months in service
		//echo "EmpStatus: ".$dt['employmentStatus']."<br> dateHired: ".$dt['dateHired'];
		if ($dt['employmentStatus'] == 'Regular' || $dt['employmentStatus'] == 'Probationary'){
			// get the number of months in service
			
			$noOfDaysInService =( (((strtotime(date('Y-12-31')) - strtotime( $dt['dateHired'])) / 3600 / 24) + 1));  //23:59:59
			//$amount13thMonth = $dt['payTypeNdex'] == 2 ? ($totalNetBasic / 12) : (($dt['basicPay']/12) * $noOfMonthsInService) - $payroll13thmonth['amount13thMonth'];
			$amount13thMonth = $dt['payTypeNdex'] == 2 ? ($totalNetBasic / 12) - $payroll13thmonth['amount13thMonth'] : (($dt['basicPay']/365) * $noOfDaysInService) - $payroll13thmonth['amount13thMonth'];
			//echo $noOfMonthsInService."asdf";
			//echo "<br>".$amount13thMonth."<br> daysWorkInAYear ".$noOfDaysInService;
		} elseif ($dt['employmentStatus'] == 'Temporary'){
			$amount13thMonth = netBasic($dt['ndex'], date('Y-01-01',strtotime($dateToProcessed)), date('Y-11-t',strtotime($dateToProcessed))) / 12;
		}
	}
	
	//echo "Employee No = ".$dt['ndex']." dateHired: ".$dt['dateHired']." status: ".$dt['employmentStatus']." PayType: ".$dt['payTypeNdex']." NetBasicPay: "
	//		.( $dt['payTypeNdex'] == 2  ? $totalNetBasic : $dt['basicPay'])." 13th Month : ".$amount13thMonth."<br>";
	// 13th Month Amount
	
	//
	
//	echo "Basic Pay = ".$dt['basicPay']."; ";
//	echo "Cut-Off Date = ".$dateToProcessed."; ";
//	echo "TotalNetBasic = ".$totalNetBasic."; ";
//	echo "Basic Pay = ".$amount13thMonth; 
	


	// Check If Employee Already Exist in cutoff
	$checkIfExist = mysql_num_rows(mysql_query("SELECT * FROM payroll13thmonth WHERE empNo='".$dt['ndex']."' AND cutOffDate='".$dateToProcessed."'",$conn));
	if ($checkIfExist){
		//echo "exist";
		$updateRow = mysql_query("UPDATE payroll13thmonth SET emp_level='".$dt['level']."', basicPay='".$dt['basicPay']."', totalNetBasic='".$totalNetBasic."', amount13thMonth='".$amount13thMonth."' WHERE empNo='".$dt['ndex']."' AND cutOffDate='".$dateToProcessed."'", $conn);
	} else {
		//echo "not exist";
		$insertRow = mysql_query("INSERT INTO payroll13thmonth (emp_level, `empNo`, `basicPay`, `cutOffDate`, `totalNetBasic`, `amount13thMonth`) 
									 VALUES ('".$dt['level']."', '".$dt['ndex']."', '".$dt['basicPay']."', '".$dateToProcessed."', '".$totalNetBasic."', '".$amount13thMonth."')",$conn);
	}

}

?>



