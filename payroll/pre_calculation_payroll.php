<?php
ob_start();
session_start();
include("../dbcon.php");
include("../employeefunctions.php");
include ("../myfunctions.php");
//For Clearing
/*
	$cola = 0;
	$sssEmployeeShare = 0;
	$philhealthDed = 0;
	$taxwithHeld = 0;
	$pagibigDed = 0;
	$allowance = 0;
	$hazardPay = 0;
	$incentive = 0;
	$honorarium = 0;
	$otRDLHoliday = 0;
	$otRDSHoliday = 0;
	$otLHoliday = 0;
	$otSHoliday = 0;
	$otRestDay = 0;
	$night_prem = 0;
	$undertime = 0;
	$ot_reg = 0;
	$ot_exc = 0;
	$otherIncome = 0;
	$spholiday = 0;
	$lholiday = 0;
	$duty_rd = 0;
	$netBasic = 0;
	$dt['payTypeNdex'] = 0;
	$dt['basicPay'] = 0;
	$grossIncome = 0;
	$dt['d_unionDues'] = 0;
	$faDed['amount'] = 0;
	$mortuaryDed['amount'] = 0;
	$dt['d_sssloan'] = 0;
	$d_hospital = 0;
	$dt['pagibigloanh'] = 0;
	$dt['pagibigloan'] = 0;
	$dt['pagibigSavings'] = 0;
	$adj_other = 0;
	$d_other = 0;


	$updatePayrolltable = mysql_query("UPDATE payroll SET
	cola='".$cola."',
	d_sss='".$sssEmployeeShare."',
	d_philhealth='".$philhealthDed."',
	d_whtax='".$taxwithHeld."',
	pagibig='".$pagibigDed."',
	allowance='".$allowance."',
	hazardPay='".$hazardPay."',
	incentive='".$incentive."',
	honorarium='".$honorarium."',
	otRDLHolidayPay = '".$otRDLHoliday."',
	otRDSHolidayPay = '".$otRDSHoliday."',
	otLHolidayPay = '".$otLHoliday."',
	otSHolidayPay = '".$otSHoliday."',
	otRestDayPay = '".$otRestDay."',
	payNightPremium='".$night_prem."',
	payUndertime='".$undertime."',
	payOTReg='".$ot_reg."',
	payOTExc='".$ot_exc."',
	oth_income ='".$otherIncome."',
	paySpHoliday='".$spholiday."',
	payLHoliday='".$lholiday."',
	payDutyRd='".$duty_rd."',
	netBasic='".$netBasic."',
	adj_other='".$adj_other."',
	pay_type='".$dt['payTypeNdex']."',
	basicpay='".$dt['basicPay']."',
	grossPay='".$grossIncome."',
	d_unionDues = '".$dt['d_unionDues']."',
	financialAssistance = '".$faDed['amount']."',
	d_mortuary='".$mortuaryDed['amount']."',
	d_sssloan = '".$dt['d_sssloan']."',
	d_hospital = '".$d_hospital."',
	pagibigloanh = '".$dt['pagibigloanh']."',
	pagibigloan = '".$dt['pagibigloan']."',
	pagibigSavings = '".$dt['pagibigSavings']."',
	d_other = '".$d_other."'
	WHERE pay_period='2023-08-15'");

	die("UPDATE payroll SET
	cola='".$cola."',
	d_sss='".$sssEmployeeShare."',
	d_philhealth='".$philhealthDed."',
	d_whtax='".$taxwithHeld."',
	pagibig='".$pagibigDed."',
	allowance='".$allowance."',
	hazardPay='".$hazardPay."',
	incentive='".$incentive."',
	honorarium='".$honorarium."',
	otRDLHolidayPay = '".$otRDLHoliday."',
	otRDSHolidayPay = '".$otRDSHoliday."',
	otLHolidayPay = '".$otLHoliday."',
	otSHolidayPay = '".$otSHoliday."',
	otRestDayPay = '".$otRestDay."',
	payNightPremium='".$night_prem."',
	payUndertime='".$undertime."',
	payOTReg='".$ot_reg."',
	payOTExc='".$ot_exc."',
	oth_income ='".$otherIncome."',
	paySpHoliday='".$spholiday."',
	payLHoliday='".$lholiday."',
	payDutyRd='".$duty_rd."',
	netBasic='".$netBasic."',
	adj_other='".$adj_other."',
	pay_type='".$dt['payTypeNdex']."',
	basicpay='".$dt['basicPay']."',
	grossPay='".$grossIncome."',
	d_unionDues = '".$dt['d_unionDues']."',
	financialAssistance = '".$faDed['amount']."',
	d_mortuary='".$mortuaryDed['amount']."',
	d_sssloan = '".$dt['d_sssloan']."',
	d_hospital = '".$d_hospital."',
	pagibigloanh = '".$dt['pagibigloanh']."',
	pagibigloan = '".$dt['pagibigloan']."',
	pagibigSavings = '".$dt['pagibigSavings']."',
	d_other = '".$d_other."'
	WHERE pay_period='2023-08-15'");

*/

include ("payrollfunctions.php");
function process_payment($empid, $cutoff_date){
	
	$total_payment = 0;
	$billings = get_billings($empid, $cutoff_date);

	if($billings == 0)
		return 0;
	else{

		foreach($billings as $bill){

			$payment = compute_payment($bill['Batch_No'], $bill['AR_No'], $empid, $cutoff_date);
			$total_payment += $payment;
		}

	}

	return $total_payment;
}

function get_billings($empid, $cutoff_date){
		

	$sql = mysql_query("select Batch_No,AR_No,employeeId,sum(Amount) as bills 
		from ar_hospital_ee_trx where employeeId='".$empid."' and Status='Active' and trxDate<='".$cutoff_date."'
		group by Batch_No,AR_No,employeeId");
	while($results = mysql_fetch_array($sql)){
		$billings[] = $results;
	}

	if(!isset($billings))
		return 0;
	else
		return $billings;
}

function compute_payment($Batch_No, $AR_No, $empid, $cutoff_date){
	

	$amortization = get_amortization($Batch_No, $AR_No, $empid);
	$balance = 	get_balance($Batch_No, $AR_No, $empid);
	

	if($amortization == 0 )
		return 0;
	if($balance == 0)
		return 0;

	//$process_payment = save_payment($Batch_No, $AR_No, $empid, $amortization, $cutoff_date);
	if($amortization > $balance){
		return $balance;
	}
	else{
		return $amortization;
	}
	//return $process_payment;
}

function get_amortization($Batch_No, $AR_No, $empid){
		
	$amortization = mysql_fetch_array(mysql_query("select * from hospital_deduction_amortization where employeeId='".$empid."' and AR_No = '".$AR_No."' and Batch_No='".$Batch_No."' and start_date<='".date('Y-m-d',strtotime(date('Y-m-d')." + 6 days"))."' and Status='Active'"));

	if(!isset($amortization))
		return 0;
	else
		return $amortization['amortization'];
}

function get_balance($Batch_No,$AR_No, $empid){

	$bills = get_payables($Batch_No,$AR_No, $empid);
	$paid = get_paid($Batch_No,$AR_No, $empid);

	$balance = $bills - $paid;
	if($balance <= 0)
		return 0;
	else
		return $balance;
}

function get_payables($Batch_No,$AR_No, $empid){
	
	$total_bill = mysql_fetch_array(mysql_query("select sum(Amount) as bills from ar_hospital_ee_trx where employeeId='".$empid."'  and AR_No = '".$AR_No."' and Batch_No='".$Batch_No."'"));

	$total_refund = mysql_fetch_array(mysql_query("select sum(amountPaid) as refund from ar_hospital_ee_refund_ledger where employeeId='".$empid."'  and AR_No = '".$AR_No."' and Batch_No='".$Batch_No."' and Status='POSTED'"));

	if(!$total_bill)
		return 0;
	else
		return $total_bill['bills'] + $total_refund['refund'];
}

function get_paid($Batch_No,$AR_No, $empid){
	
	$total_paid = mysql_fetch_array(mysql_query("select sum(amountPaid) as paid from ar_hospital_ee_payment_ledger where employeeId='".$empid."'  and AR_No = '".$AR_No."' and Batch_No='".$Batch_No."'"));

	if(!$total_paid)
		return 0;
	else
		return $total_paid['paid'];
}


$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid, p.*
								FROM employee e 
									left join employee_compensation c on e.ndex=c.employeeId
									LEFT JOIN payroll p ON p.empid = e.ndex 
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE p.pay_period='".$_GET['PayrollCutoff']."' && p.residencyTrainingProgram='' && c.basicPay<>0 && p.holdSalary<>'1'" ;// && e.ndex=828";
						
$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_GET['PayrollCutoff']	."'",$conn));		
$sql.=" ORDER BY  d.name, e.lastName,e.firstName limit 100";
	
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$countPerDepartment = 0;
$rowCount = mysql_num_rows($exec);
while($dt=mysql_fetch_assoc($exec)){



if ($dt['payTypeNdex'] == '1'){
			$basicpayPerDay = payPerSpecificTime($dt['basicPay'],'day');
			$basicpayPerHour = payPerSpecificTime($dt['basicPay'],'hour');
			$basicpayPerMinute = payPerSpecificTime($dt['basicPay'],'minute');
			
			$colaPerDay = payPerSpecificTime($dt['cola'],'day');
			$colaPerHour = payPerSpecificTime($dt['cola'],'hour');
			$colaPerMinute = payPerSpecificTime($dt['cola'],'minute');
			
		} else {
			$basicpayPerDay = payPerSpecificTimeDaily($dt['basicPay'],'day');
			$basicpayPerHour = payPerSpecificTimeDaily($dt['basicPay'],'hour');
			$basicpayPerMinute = payPerSpecificTimeDaily($dt['basicPay'],'minute');
			
			$colaPerDay = payPerSpecificTimeDaily($dt['cola'],'day');
			$colaPerHour = payPerSpecificTimeDaily($dt['cola'],'hour');
			$colaPerMinute = payPerSpecificTimeDaily($dt['cola'],'minute');
		}
		$ot_exc = ($basicpayPerHour * 1.625) * $dt['ot_exc'];					// OT EXCESS IN 8 HOURS pay amount 
		$ot_reg = ($basicpayPerHour * 1.25) * $dt['ot_reg'];					// overtime pay amount 
		$spholiday = ($basicpayPerHour * 0.30) * $dt['spholiday'];		// special holiday premium amount
		$lholiday = $basicpayPerHour * $dt['lholiday'];								// legal holiday premium amount
		$birthday = $basicpayPerHour * $dt['bday_lve'];				// birthday premium
		
		$otLHoliday = ($basicpayPerHour * 2.60) * $dt['otLHoliday'];		// overtime on legal holiday
		$otSHoliday = ($basicpayPerHour * 1.69) * $dt['otSHoliday'];		// overtime on special holiday
		$otRDLHoliday = ($basicpayPerHour * 3.38) * $dt['otRDLHoliday'];		// overtime on restday which is Legal holiday
		$otRDSHoliday = ($basicpayPerHour * 1.95) * $dt['otRDSHoliday'];		// overtime on restday which is special holiday
		$night_prem = ($basicpayPerHour * 0.10) * $dt['night_prem'];	// night premium amount
		
		//$duty_rd = ($basicpayPerHour * 1.30) * $dt['duty_rd'];				// duty restday amount 
		//$hazardPay = $dt['hazardPay'];																													// HazardPay
		//$cola = $dt['cola'] / 2;
		//$cola = $cola - ($colaPerDay * $dt['days_absent'])  - ($colaPerMinute * $dt['undertime']);																															// cola divided by 2
		// DEDUCTION
		$absent = $basicpayPerDay * $dt['days_absent'];     					// absent amount deduction on ee with pay type is equal to MONTHLY
		$undertime = (($basicpayPerHour + $colaPerHour) * $dt['undertime']);		
		//$undertime = $basicpayPerMinute * $dt['undertime'];						// undertime amount deduction on all ee
		
		//echo $basicpayPerHour;

		$allowance = ( ($dt['allowance'] / 2) - ((payPerSpecificTime($dt['allowance'],'day')) * $dt['days_absent']) );		// allowance divided by 2 less days ansent
		$honorarium = ( ($dt['honorarium'] / 2) - ((payPerSpecificTime($dt['honorarium'],'day')) * $dt['days_absent']) );	// honorarium divided by 2 less days ansent
		$hazardPay = ( ($dt['hazardPay'] / 2) - ((payPerSpecificTime($dt['hazardPay'],'day')) * $dt['days_absent']) );		// Hazard Pay divided by 2 less days ansent
		$incentive = ( ($dt['incentive'] / 2) - ((payPerSpecificTime($dt['incentive'],'day')) * $dt['days_absent']) );		// Incentive Pay divided by 2 less days ansent

		if($dt['days_work'] <= 0){
			$allowance = 0;
			$honorarium = 0;
			$hazardPay = 0;
			$incentive = 0;
			$dt['pagibigSavings'] = 0;
		}
		//$union = $dt['d_unionDues'];																														// union dues deduction on all ee
		$mortuary = $dt['d_mortuary'];																													// mortuary deduction on all ee
		$coopTotalDed = $dt['d_coopTotal'];																											// coop total deduction
		$financialAssistance = $dt['financialAssistance'];																			// financial assistance

		// EE NO OF DEPENDENTS
		$dependents = noOfDependents($dt['employeeId']);																				// ee's dependent

		if ($dt['payTypeNdex'] == '1'){																															
			$netBasic = ($dt['basicPay'] / 2) - $absent ;		
			$cola = $dt['cola'] / 2;											// cutoff pay less absent and undertime for MONTHLY ee\
			$cola = $cola - ($colaPerDay * $dt['days_absent']);
			$percentage = $dt['employmentStatus'] == 'Regular' ? 1.3 : 0.3; 	// if employment status is regular premium is 1.3 else 0.3
			$duty_rd = ($basicpayPerHour * $percentage) * $dt['duty_rd'];				// duty restday amount 
			$otRestDay = ($basicpayPerHour * 1.69) * $dt['otRestDay'];		// overtime restday
		} elseif ($dt['payTypeNdex'] == '2'){
			$netBasic = ($basicpayPerDay * $dt['days_work']); 	// cutoff pay less absent and undertime for daily ee
			$cola = ($dt['cola'] * $dt['days_work']);
			$percentage = $dt['employmentStatus'] == 'Regular' ? 1.3 : 0.3;		// if employment status is regular premium is 1.3 else 0.3
			$duty_rd = ($basicpayPerHour * $percentage) * $dt['duty_rd'];				// duty restday amount 
			$otRestDay = ($basicpayPerHour * 1.69) * $dt['otRestDay'];		// overtime restday
		}
		
		// ADJUSTMENTS  undertime, dayswork, leaves, days work and overtime.
		//$otherIncome = 0;    
		$otherIncome = ($basicpayPerDay + $colaPerDay + payPerSpecificTime($dt['honorarium'],'day') + payPerSpecificTime($dt['allowance'],'day') ) * ( $dt['adj_sick_lve'] + $dt['adj_days_work'] + $dt['adj_vac_lve'] + $dt['adj_bday_lve'] + $dt['adj_official_lve'] ) + 
						($basicpayPerHour * ( $dt['adj_ot_reg'] + $dt['adj_duty_rd'] )) + (($basicpayPerHour + $colaPerHour) * $dt['adj_undertime']) + (($basicpayPerHour * .10) * $dt['adj_night_prem']);
		//echo ($basicpayPerDay + $colaPerDay + payPerSpecificTime($dt['honorarium'],'day') + payPerSpecificTime($dt['allowance'],'day') );
		//die();
		//echo $dt['adj_sick_lve'];
		$grossIncome = ($birthday + $otRestDay + $otRDSHoliday + $otRDLHoliday + $otSHoliday + $otLHoliday + $netBasic + $ot_reg + $ot_exc + $spholiday + $lholiday + $night_prem + $duty_rd + $allowance + $honorarium + $cola + $otherIncome - $undertime + $dt['adj_other'] + $hazardPay + $incentive);	// Gross TOTAL cutoff Income
		$prevGrossInc = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period = '".date('Y-m-15',strtotime($_GET['cutoffDate']))."' && empid='".$dt['employeeId']."'",$conn));  // getting the previous payroll value of ee
	//	echo "$grossIncome -- $netBasic + $ot_reg + $ot_exc + $spholiday + $lholiday + $night_prem + $duty_rd + $allowance + $honorarium + $cola + $otherIncome - $undertime <br><bR>";
		

		if ($_GET['cutoffDate'] == date('Y-m-t', strtotime($_GET['cutoffDate']))){
			// PAG-IBIG DEDUCTION 
			if ($dt['payTypeNdex'] == '1'){
				$pagibigDedPercetage = ($dt['basicPay'] + $dt['cola']) < 1500 ? 0.01 : 0.02;
				$pagibigDed = ($dt['basicPay'] + $dt['cola']) * $pagibigDedPercetage;
				$philhealth = philHelthPremium($dt['basicPay']);
			} else {
				$pagibigDedPercetage = (($dt['basicPay'] + $dt['cola']) * ($dt['days_work'] + $prevGrossInc['days_work']) ) < 1500 ? 0.01 : 0.02;
				$pagibigDed = (($dt['basicPay'] + $dt['cola']) * ($dt['days_work'] + $prevGrossInc['days_work']) ) * $pagibigDedPercetage;
				$philhealth = philHelthPremium($dt['basicPay'] * ($dt['days_work'] + $prevGrossInc['days_work']));
			}
		
			if ($pagibigDed > 200){
				//$pagibigDedNoTax = 100;
				$pagibigDed = 200;
			} //else {
				//$pagibigDedNoTax = $pagibigDed;
				//$pagibigDed = $pagibigDed;
			//}
			
			// SSS DEDUCTION  
			$sss = sssPremium($grossIncome + $prevGrossInc['grossPay']);     /// every  end of the month   total gross income of the month..
			$sssEmployeeShare = $sss['eeShare'];
		
			// PHILHEALTH DEDUCTION
			//$philhealth = philHelthPremium($dt['basicPay']);
			$philhealthDed = $philhealth['eeShare'];
			
			// NO  DEDUCTION
			$dt['d_unionDues'] = 0;
		} else {
			//PAGIBIG DEDUCTION
			$dt['pagibigSavings'] = 0;
			// WITH UNION DEDUCTION
			$unionDues = mysql_fetch_assoc(mysql_query("SELECT * FROM dedsetup WHERE dedType='UNIONDUES'"));
			if ($dt['isUnionMember'] == 1){
				//$union = $unionDues['amount'];
				$dt['d_unionDues'] = $unionDues['amount'];
			} else {
				$dt['d_unionDues'] = 0;
			}
		}
		
		// MORTUARY DEDUCTION SCHEDULE ON PAYROLL DATE
		$mortuaryDed = mysql_fetch_assoc(mysql_query("SELECT SUM(amount) amount FROM mortuary WHERE payrollDate='".$_GET['cutoffDate']."'",$conn));
		if ($dt['isUnionMember'] == 0){
			$mortuaryDed['amount'] = 0;
		}
		//echo $mortuaryDed['amount']." === SELECT SUM(amount) amount FROM mortuary WHERE payrollDate='".$_GET['cutoffDate']."'";
		//die();
		//FINANCIAL ASSISTANCE
		$faDed = mysql_fetch_assoc(mysql_query("SELECT SUM(amount) amount FROM financialassistance WHERE payrollDate='".$_GET['cutoffDate']."'",$conn));
		if ($dt['isUnionMember'] == 0){
			$faDed['amount'] = 0;
		}

		// WITHHOLDING TAX DEDUCTION   ----    incomce less union and mortuary.
		$taxableIncome = $grossIncome + $dt['onCallOvertime'] - ($pagibigDed + $sssEmployeeShare + $philhealthDed + $dt['d_unionDues']);   //  + $mortuaryDed['amount'] + $faDed['amount']  removed: September 26, 2014
		//echo $taxableIncome." - - ".$grossIncome;
		//die();
		if ($dt['isTaxable'] != 0){
			if($dt['taxType'] == 1){
				$dependents = 0;
				$taxwithHeld = withHeldTax ($dependents, $taxableIncome, $dedFrequency='SEMI-MONTHLY');
				//echo $taxwithHeld." ".$taxableIncome."TAXTYPE 1<br>";
			} else {
				$taxwithHeld = $taxwithHeld * 0.10;
				//echo $taxwithHeld." TAXTYPE 2<br>";
			}
		} else {
			$taxwithHeld = 0;
			//echo $taxwithHeld." TAXTYPE 3<br>";
		}
		
		//echo $taxwithHeld."<br>";

		//RETRIEVE FROM LOANS SETUP
		// SSS SALARY LOAN
		$d_other = 0;
		$d_hospital = 0;
		
	$checkIfOnFreeze=mysql_num_rows(mysql_query("select * from loanpayments_freeze where employeeId='".$dt['employeeId']."' && cutoffDate = '".$_GET['cutoffDate']."'"));
	
	if($checkIfOnFreeze==0){ //ADDED BY JUNDRIE - TO DEDUCT ONLY THOSE EMPLOYEES WHO ARE MARK AS FREEZE DEDUCTION. DATA is at table loanpayments_freeze
		//echo "NOT FREEZE <br>";
		$loansqry=mysql_query("SELECT ndex as loans, loanId FROM `loan_employee` WHERE employeeId=".$dt['employeeId']." AND posted='1' AND dedDateStart <='".$_GET['cutoffDate']."' AND isDeleted='0'");
		echo "SELECT ndex as loans, loanId FROM `loan_employee` WHERE employeeId=".$dt['employeeId']." AND posted='1' AND dedDateStart <='".$_GET['cutoffDate']."' AND isDeleted='0'";
		$dt['pagibigloanh'] = 0;
		//echo "SELECT ndex as loans FROM `loan_employee` where employeeId=".$dt['employeeId']." and posted='1'";die();
		while($lsq=mysql_fetch_object($loansqry)){
			$checkIfalreadyprocess=mysql_num_rows(mysql_query("select * from loan_employee_payments where datePaid = '".$_GET['cutoffDate']."' and loanSetupId='".$lsq->loans."'")); // and remarks=''
						
			if (getDeductionData($lsq->loans,'current balance') > 0){
				
				
				if ($lsq->loanId == '18' || $lsq->loanId == '8'){
					//HDMF LOAN
					$dt['pagibigloanh'] += getDeductionData($lsq->loans,'Currect Deduction Amount');
				} elseif ($lsq->loanId == '2') {
					//SSS SALARY LOAN
					$dt['d_sssloan'] = getDeductionData($lsq->loans,'Currect Deduction Amount');
				} elseif ($lsq->loanId == '3') {
					//PAG-IBIG SALARY LOAN
					$dt['pagibigloan'] = getDeductionData($lsq->loans,'Currect Deduction Amount');
				} elseif ($lsq->loanId == '5' || $lsq->loanId == '6') {
					//HOSPITAL 
					$d_hospital += getDeductionData($lsq->loans,'Currect Deduction Amount');

					//echo "1".$lsq->loanId." )".getDeductionData($lsq->loans,'Currect Deduction Amount')." <br>";
				} else {
					$d_other += getDeductionData($lsq->loans,'Currect Deduction Amount');
					//echo "D_OTEHR:".$d_other."<br>";
				}
				/*
				if($checkIfalreadyprocess==0){
					//echo '1';
					$loansqryins=mysql_query("insert into loan_employee_payments (`loanSetupId`, `datePaid`, `amountPaid`,`remarks`) VALUES ('".$lsq->loans."','".$_GET['cutoffDate']."','".getDeductionData($lsq->loans,'Currect Deduction Amount')."','')");
				}
				else{
					//echo '2';
					$loansqryupd=mysql_query("update loan_employee_payments set `amountPaid`='".getDeductionData($lsq->loans,'Currect Deduction Amount')."' WHERE datePaid = '".$_GET['cutoffDate']."' and loanSetupId='".$lsq->loans."' and remarks=''");
				}
				*/
				//echo getDeductionData($lsq->loans,'Currect Deduction Amount')."<br>";
			}

			//echo 's';
		}
		//$d_hospital += process_payment($dt['employeeId'], $_GET['cutoffDate']);
	}
	else{ // IF ON FREEZE BUT ALREADY SUBMITTED DATA on loanpayments.. This process will delete data from loanpayments and return the amount to loanbalance.
		//echo "FREEZE <br>";
		$dt['d_sssloan']=0;
		$dt['pagibigloanh']=0;
		$dt['pagibigloan']=0;
		$dt['d_hospital']=0;
		
	}
	//echo "D_OTEHR:".$d_other."<br>";
	//die();
	// END FREEZE DEDUCTION CONDITION
		if ($dt['days_work'] == 0){
			$cola = 0;
			$sssEmployeeShare = 0;
			$philhealthDed = 0;
			$taxwithHeld = 0;
			$pagibigDed = 0;
			$allowance = 0;
			$honorarium = 0;
			$night_prem = 0;
			$undertime = 0;
			$ot_reg = 0;
			$ot_exc = 0;
			$otherIncome = 0;
			$spholiday = 0;
			$lholiday = 0;
			$duty_rd = 0;
			$netBasic = 0;
			$dt['payTypeNdex'] = 0;
			$dt['basicPay'] = 0;
			$grossIncome = 0;
			$union = 0;
			$mortuaryDed['amount'] = 0;
			$dt['d_hospital'] = 0;
		}

		$r = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".$_GET['cutoffDate']."' && empid='".$dt['employeeId']."'",$conn));
		$grossPay = $netBasic + $cola + $allowance + $honorarium + $night_prem + $ot_reg + $ot_exc + $otherIncome + $duty_rd + $spholiday + $lholiday + $otRDLHoliday + $otRDSHoliday + $otLHoliday + $otSHoliday + $otRestDay - $undertime +  $d_other  +  $hazardPay  +  $incentive;
		
		
		$totalDeduction = $r['d_pnb'] + $$r['d_parkingFee'] + $taxwithHeld + $sssEmployeeShare + $philhealthDed + $pagibigDed + $dt['pagibigloan'] + $dt['pagibigloanh'] + $dt['d_unionDues'] + $mortuaryDed['amount'] + $dt['d_sssloan'] + $d_hospital + $r['d_cashAdvance'] + $d_other + $r['d_coopTotal'] + $faDed['amount'] + $dt['pagibigSavings'];

		$netPay = $grossPay - $totalDeduction;
		$thirthyPercentOfGross = ($grossPay * 0.30);
	
	  $var++;
	  
		$ln++;
		
		// extract cola undertime on payUndertime

		$colaUndertimeAmount = $r['undertime'] != 0 && $r['days_work'] != 0? ((($r['cola'] / $r['days_work']) / 8) * $r['undertime']) : 0;
	
	if($netPay < $thirthyPercentOfGross){
		$ctr1s++;
    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
    $hos = process_payment($r['empid'], $_GET['PayrollCutoff']);
    if($hos > 0){
    		$data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 							<td align='left'>".getID($r['employmentStatus'],$r['employeeNo'])."&nbsp; </td>
					      <td align='left'>".$r['lastName'].", ".$r['firstName']."&nbsp; </td>
					      <td>".number_format($r['basicpay'],2)."&nbsp; </td>
						   	<td>".number_format(($r['netBasic'] - $r['payUndertime'] + $colaUndertimeAmount),2)."&nbsp; </td>
					      <td>".number_format($r['cola'] - $colaUndertimeAmount,2)."</td>
								<td>".number_format($r['allowance'],2)."&nbsp; </td>
								<td>".number_format($r['incentive'],2)."&nbsp; </td>
								<td>".number_format($r['honorarium'],2)."&nbsp; </td>
								<!-- <td>".$r['payUndertime']."</td> -->
								<td>".number_format($r['payNightPremium'],2)."&nbsp; </td>
								<td>".number_format(($r['payOTExc'] + $r['payOTReg'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payDutyRd'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay']),2)."&nbsp; </td>
								<td>".number_format(($r['oth_income'] + $r['onCallOvertime'] + $r['adj_other']),2)."&nbsp; </td>
								<td>".number_format($r['hazardPay'],2)."&nbsp; </td>	
								<td>".number_format($grossPay,2)."&nbsp; </td>
								<td>".number_format($r['d_whtax'],2)."&nbsp; </td>
								<td>".number_format($r['d_sss'],2)."&nbsp; </td>
								<td>".number_format($r['d_philhealth'],2)."&nbsp; </td>
								<td>".number_format($r['pagibig'] + $r['pagibigSavings'],2)."&nbsp; </td>
								<td>".number_format(($r['d_unionDues'] + $r['d_mortuary']),2)."&nbsp; </td>
								<td>".number_format($r['d_sssloan'],2)."&nbsp; </td>
								<td>".number_format($r['pagibigloan'] + $r['pagibigloanh'],2)."&nbsp; </td>
								<td>".number_format($r['d_hospital'],2)."&nbsp; </td>
								<td>".number_format($r['d_cashAdvance'],2)."&nbsp; </td>
								<td>".number_format($r['d_coopTotal'],2)."&nbsp; </td>	
								<td>".number_format(($r['d_other'] + $r['d_pnb'] + $r['d_parkingFee'] + $r['financialAssistance']),2)."&nbsp; </td>
								<td>".number_format($totalDeduction,2)."&nbsp; </td>
								<td>".number_format($grossPay,2)."</td>
								<td>".number_format($thirthyPercentOfGross,2)."</td>
								<td>".number_format($netPay,2)."</td>
								<td>".number_format($hos,2)."</td>
								<td>".number_format(($netPay - $hos),2)."</td>

				     </tr>";
		}
	}
	
}

$rs = mysql_query("SELECT * FROM cutoffdates ORDER BY payrollDate DESC",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}
?>
   
 
     <form method="get">
     <table width="40%" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:11px;">
     	<tr>
		  <td>Payroll Period <select name="PayrollCutoff"><?php echo $optionSelectPayrollCutoffDate;?></select> &nbsp;
		
	  <td><input type="submit" value="Submit"></td>
	  </tr>
     </table>
     </form>
     <table width="100%" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:11px;">
	 <thead>
	  <tr>
	       <td colspan="25" align="center" style="font-size:13px;"><?php echo "Less Than 30% Net";?><br>
				 				<?php echo date('M d, Y',strtotime($cutoffDate['cutoffDateStart']))." to ".date('M d, Y',strtotime($cutoffDate['cutoffDateEnd']));?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
				<td>IDNUM</td>
	     	<td>NAME</td>
	      <td>BASIC</td>
		   	<td>NETBASIC</td>
	      <td>COLA</td>
				<td>ALLOW</td>
				<td>INCENTIVE</td>
				<td>HON</td>
				<!-- <td>UNDERTIME</td> -->
				<td>NIGHT PREMIUM</td>
				<td>OT</td>
				<td>ADJ</td>
				<td>HAZARD PAY</td>
				<td>GROSS <br />PAY</td>
				<td>W/TAX</td>
				<td>SSS</td>
				<td>PHIC</td>
				<td>HDMF</td>
				<td>UNION/<br>MORTUARY</td>
				<td>SSS LOAN</td>
				<td>HDMF LOAN</td>
				<td>HOSP</td>
				<td>CA</td>
				<td>COOP</td>
				<td>OTHERS</td>
				<td>TOTAL <br>DED</td>				
				<td>Gross</td>
				<td>30%</td>
				<td>NET PAY</td>
				<td>Hospital</td>
				<td>Net</td>
	  </tr>
	  <tr><td colspan="30"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>





