<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");
include ("payrollfunctions.php");
if ($_GET['pageact'] == "runComputation"){
	/*$sqlReset = "delete FROM `loan_employee_payments` where datePaid='".$_POST['cutoffDate']."' and remarks='';
		UPDATE  payroll SET d_other=0, d_sssloan=0, pagibigloan=0, d_hospital=0, pagibigloanh=0 where pay_period='".$_POST['cutoffDate']."' ";
	*///$updateReset = mysql_query($sqlReset,$conn);
	$sql = "SELECT e.ndex employeeId, e.isUnionMember, e.isCoopMember, e.paytype, e.isTaxable,
								p.*, e.employmentStatus, e.residencyTrainingProgram, e.level,
								 ec.allowance, ec.honorarium, ec.cola, ec.basicPay basicPay, ec.taxType, ec.payTypeNdex
										FROM employee e 
												LEFT JOIN employee_compensation ec ON ec.employeeId=e.ndex
												LEFT JOIN payroll p ON p.empid=e.ndex
													WHERE p.pay_period='".$_POST['cutoffDate']."' && ec.basicPay>0 and e.ndex=1157"; 
							
										// WHERE e.isActive='0' && p.pay_period='".$_POST['cutoffDate']."' && ec.basicPay>0 && (p.days_work>0 || p.grossPay>0) && e.ndex='20'
	$rs = mysql_query($sql,$conn);		
	//echo $sql;
	//die();
	while($dt = mysql_fetch_assoc($rs)){
		//echo 
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
		
		$otLHoliday = ($basicpayPerHour * 2.60) * $dt['otLHoliday'];		// overtime on legal holiday
		$otSHoliday = ($basicpayPerHour * 1.69) * $dt['otSHoliday'];		// overtime on special holiday
		$otRDLHoliday = ($basicpayPerHour * 3.38) * $dt['otRDLHoliday'];		// overtime on restday which is Legal holiday
		$otRDSHoliday = ($basicpayPerHour * 1.95) * $dt['otRDSHoliday'];		// overtime on restday which is special holiday
		$night_prem = ($basicpayPerHour * 0.10) * $dt['night_prem'];	// night premium amount
		
		//$duty_rd = ($basicpayPerHour * 1.30) * $dt['duty_rd'];				// duty restday amount 
		$hazardPay = $dt['hazardPay'];																													// HazardPay
		//$cola = $dt['cola'] / 2;
		//$cola = $cola - ($colaPerDay * $dt['days_absent'])  - ($colaPerMinute * $dt['undertime']);																															// cola divided by 2
		// DEDUCTION
		$absent = $basicpayPerDay * $dt['days_absent'];     					// absent amount deduction on ee with pay type is equal to MONTHLY
		$undertime = (($basicpayPerHour + $colaPerHour) * $dt['undertime']);		
		//$undertime = $basicpayPerMinute * $dt['undertime'];						// undertime amount deduction on all ee
		
		//echo $basicpayPerHour;
		$allowance = ( ($dt['allowance'] / 2) - ((payPerSpecificTime($dt['allowance'],'day')) * $dt['days_absent']) );		// allowance divided by 2 less days ansent
		$honorarium = ( ($dt['honorarium'] / 2) - ((payPerSpecificTime($dt['honorarium'],'day')) * $dt['days_absent']) );		// honorarium divided by 2 less days ansent
		//$union = $dt['d_unionDues'];																														// union dues deduction on all ee
		$mortuary = $dt['d_mortuary'];																													// mortuary deduction on all ee
		$coopTotalDed = $dt['d_coopTotal'];																											// coop total deduction
		$financialAssistance = $dt['financialAssistance'];																			// financial assistance

		// EE STATUS
		$dependents = noOfDependents($dt['employeeId']);																				// ee's dependent

		if ($dt['payTypeNdex'] == '1'){																															
			$netBasic = ($dt['basicPay'] / 2) - $absent ;		
			$cola = $dt['cola'] / 2;											// cutoff pay less absent and undertime for MONTHLY ee\
			$cola = $cola - ($colaPerDay * $dt['days_absent']);
			$duty_rd = ($basicpayPerHour * 1.30) * $dt['duty_rd'];				// duty restday amount 
			$otRestDay = ($basicpayPerHour * 1.69) * $dt['otRestDay'];		// overtime restday
		} elseif ($dt['payTypeNdex'] == '2'){
			$netBasic = ($basicpayPerDay * $dt['days_work']); 	// cutoff pay less absent and undertime for daily ee
			$cola = ($dt['cola'] * $dt['days_work']);
			$duty_rd = ($basicpayPerHour * 1.30) * $dt['duty_rd'];				// duty restday amount 
			$otRestDay = ($basicpayPerHour * 1.69) * $dt['otRestDay'];		// overtime restday
		}
		
		// ADJUSTMENTS  undertime, dayswork, leaves, days work and overtime.
		//$otherIncome = 0;    
		$otherIncome = ($basicpayPerDay + $colaPerDay + payPerSpecificTime($dt['honorarium'],'day') + payPerSpecificTime($dt['allowance'],'day') ) * ( $dt['adj_sick_lve'] + $dt['adj_days_work'] + $dt['adj_vac_lve'] + $dt['adj_bday_lve'] + $dt['adj_official_lve'] ) + 
						($basicpayPerHour * ( $dt['adj_ot_reg'] + $dt['adj_duty_rd'] )) + (($basicpayPerDay + $colaPerDay) * $dt['adj_undertime']) + (($basicpayPerHour * .10) * $dt['adj_night_prem']);
		//echo ($basicpayPerDay + $colaPerDay + payPerSpecificTime($dt['honorarium'],'day') + payPerSpecificTime($dt['allowance'],'day') );
		//die();
		//echo $dt['adj_sick_lve'];
		$grossIncome = ($otRestDay + $otRDSHoliday + $otRDLHoliday + $otSHoliday + $otLHoliday + $netBasic + $ot_reg + $ot_exc + $spholiday + $lholiday + $night_prem + $duty_rd + $allowance + $honorarium + $cola + $otherIncome - $undertime + $dt['adj_other']);	// Gross TOTAL cutoff Income
		$prevGrossInc = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period = '".date('Y-m-15',strtotime($_POST['cutoffDate']))."' && empid='".$dt['employeeId']."'",$conn));  // getting the previous payroll value of ee
	//	echo "$grossIncome -- $netBasic + $ot_reg + $ot_exc + $spholiday + $lholiday + $night_prem + $duty_rd + $allowance + $honorarium + $cola + $otherIncome - $undertime <br><bR>";
		if ($_POST['cutoffDate'] == date('Y-m-t', strtotime($_POST['cutoffDate']))){
			// PAG-IBIG DEDUCTION 
			if ($dt['payTypeNdex'] == '1'){	                    
				$pagibigDed = ($dt['basicPay'] + $dt['cola']) * 0.02;
				$philhealth = philHelthPremium($dt['basicPay']);
			} else {
				$pagibigDed = (($dt['basicPay'] + $dt['cola']) * ($dt['days_work'] + $prevGrossInc['days_work']) ) * 0.02;
				$philhealth = philHelthPremium($dt['basicPay'] * ($dt['days_work'] + $prevGrossInc['days_work']));
			}
		
			// SSS DEDUCTION  
			$sss = sssPremium($grossIncome + $prevGrossInc['grossPay']);     /// every  end of the month   total gross income of the month..
			$sssEmployeeShare = $sss['eeShare'];
		
			// PHILHEALTH DEDUCTION
			//$philhealth = philHelthPremium($dt['basicPay']);
			$philhealthDed = $philhealth['eeShare'];
			
			// NO  DEDUCTION
			$dt['d_unionDues'] = 0;
		} else {
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
		$mortuaryDed = mysql_fetch_assoc(mysql_query("SELECT SUM(amount) amount FROM mortuary WHERE payrollDate='".$_POST['cutoffDate']."'",$conn));
		if ($dt['isUnionMember'] == 0){
			$mortuaryDed['amount'] = 0;
		}
		//echo $mortuaryDed['amount']." === SELECT SUM(amount) amount FROM mortuary WHERE payrollDate='".$_POST['cutoffDate']."'";
		//die();
		//FINANCIAL ASSISTANCE
		$faDed = mysql_fetch_assoc(mysql_query("SELECT SUM(amount) amount FROM financialassistance WHERE payrollDate='".$_POST['cutoffDate']."'",$conn));
		if ($dt['isUnionMember'] == 0){
			$faDed['amount'] = 0;
		}

		// WITHHOLDING TAX DEDUCTION   ----    incomce less union and mortuary.
		$taxableIncome = $grossIncome + $dt['onCallOvertime'] - ($pagibigDed + $sssEmployeeShare + $philhealthDed + $dt['d_unionDues'] + $mortuaryDed['amount'] + $faDed['amount']);
		//echo $taxableIncome." - - ".$grossIncome;
		//die();
		if ($dt['isTaxable'] != 0){
			if($dt['taxType'] == 1){
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
		
	$checkIfOnFreeze=mysql_num_rows(mysql_query("select * from loanpayments_freeze where employeeId='".$dt['employeeId']."' && cutoffDate = '".$_POST['cutoffDate']."'"));
	
	if($checkIfOnFreeze==0){ //ADDED BY JUNDRIE - TO DEDUCT ONLY THOSE EMPLOYEES WHO ARE MARK AS FREEZE DEDUCTION. DATA is at table loanpayments_freeze
		
		$loansqry=mysql_query("SELECT ndex as loans, loanId FROM `loan_employee` WHERE employeeId=".$dt['employeeId']." AND posted='1' AND dedDateStart <='".$_POST['cutoffDate']."' AND isDeleted='0'");
		//echo "SELECT ndex as loans FROM `loan_employee` where employeeId=".$dt['employeeId']." and posted='1'";die();
		while($lsq=mysql_fetch_object($loansqry)){
			$checkIfalreadyprocess=mysql_num_rows(mysql_query("select * from loan_employee_payments where datePaid = '".$_POST['cutoffDate']."' and loanSetupId='".$lsq->loans."'")); // and remarks=''
			
			
			
			if (getDeductionData($lsq->loans,'current balance') > 0){
				
				
				if ($lsq->loanId == '18' || $lsq->loanId == '8'){
					//HDMF LOAN
					$dt['pagibigloanh'] = getDeductionData($lsq->loans,'Currect Deduction Amount');
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
					$d_other += getDeductionData($lsq->loans,'Currect Deduction Amount');;
				}
				
				if($checkIfalreadyprocess==0){
					//echo '1';
					$loansqryins=mysql_query("insert into loan_employee_payments (`loanSetupId`, `datePaid`, `amountPaid`,`remarks`) VALUES ('".$lsq->loans."','".$_POST['cutoffDate']."','".getDeductionData($lsq->loans,'Currect Deduction Amount')."','')");
				}
				else{
					//echo '2';
					$loansqryupd=mysql_query("update loan_employee_payments set `amountPaid`='".getDeductionData($lsq->loans,'Currect Deduction Amount')."' WHERE datePaid = '".$_POST['cutoffDate']."' and loanSetupId='".$lsq->loans."' and remarks=''");
				}
				//echo getDeductionData($lsq->loans,'Currect Deduction Amount')."<br>";
			}
			//echo 's';
		}
	}
	else{ // IF ON FREEZE BUT ALREADY SUBMITTED DATA on loanpayments.. This process will delete data from loanpayments and return the amount to loanbalance.
		$dt['d_sssloan']=0;
		$dt['pagibigloanh']=0;
		$dt['pagibigloan']=0;
		$dt['d_hospital']=0;
		$qryToCheckLoanPayment=mysql_query("select * from loanpayments where employeeId='".$dt['employeeId']."' and datePaid='".$_POST['cutoffDate']."'");
		while($lp=mysql_fetch_object($qryToCheckLoanPayment)){
			$lsdata=mysql_fetch_object(mysql_query("select * from loansetup where ndex=".$lp->loanSetupId.""));
			if($lsdata->loadType=='OTHERS'){
				$updateSql = mysql_query("UPDATE payroll SET d_other=(d_other - ".$lp->amountPaid.") where empid='".$lp->EmployeeId."' && pay_period='".$lp->datePaid."'");
			}
			$returndata=mysql_query("update loansetup set loanBalance = (loanBalance + ".$lp->amountPaid.") where ndex=".$lp->loanSetupId."");
			$insertToDeletedTable=mysql_query("INSERT INTO `loanpayments_deleted`( `loanSetupId`, `EmployeeId`, `datePaid`, `amountPaid`, `deletedDate`) VALUES ('".$lp->loanSetupId."','".$lp->EmployeeId."','".$lp->datePaid."','".$lp->amountPaid."','".date('Y-m-d H:i:s')."')");
			$deletedata=mysql_query("delete from loanpayments where ndex=".$lp->ndex."");
		}
	}

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

		// SSS SALARY LOAN, HDMF LOAN, PAG-IBIG SALARY LOAN
		
		//echo $dt['d_sssloan']."<br>".$dt['pagibigloanh']."<br>".$dt['pagibigloan'];
		//echo $undertime."asdflkmnasdf";
		//echo "[ ".$dt['d_hospital']." ]";
		//die();
		$updatePayrolltable = mysql_query("UPDATE payroll SET
																						cola='".$cola."',
																						d_sss='".$sssEmployeeShare."',
																						d_philhealth='".$philhealthDed."',
																						d_whtax='".$taxwithHeld."',
																						pagibig='".$pagibigDed."',
																						allowance='".$allowance."',
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
																						d_other = (d_other + '".$d_other."')
																						WHERE empid='".$dt['employeeId']."' && pay_period='".$_POST['cutoffDate']."'",$conn);
		
		// IF GROSS INCOME GREATER THAN NETPAY

		//echo $dt['employeeId']."<br />";
		$r = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".$_POST['cutoffDate']."' && empid='".$dt['employeeId']."'",$conn));
		$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['payOTExc'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'] - $r['payUndertime'] +  $r['adj_other'];
		$totalDeduction = $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['pagibigloan'] + $r['pagibigloanh'] + $r['d_unionDues'] + $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal'] + $r['financialAssistance'];
		$netPay = $grossPay - $totalDeduction;
		$thirthyPercentOfGross = ($grossPay * 0.30);

		if ($thirthyPercentOfGross > ($netPay)){
			//echo $r['d_coopTotal']." > ".(($grossPay * 0.30) - $netPay);
			if ($r['d_coopTotal']>0){
					//echo  "asdjkh";
				$coopDedCurPayroll = $r['d_coopTotal'] - (($grossPay * 0.30) - $netPay);
				if ($coopDedCurPayroll<=0) $coopDedCurPayroll = 0;
				$updpayroll = mysql_query("UPDATE payroll SET d_coopTotal='".$coopDedCurPayroll."' WHERE empid='".$dt['employeeId']."' && pay_period='".$_POST['cutoffDate']."'",$conn);
				
				$netPay += (($grossPay * 0.30) - $netPay);
				//$r['d_coopTotal'] = $coopDedCurPayroll;
				//echo $netPay." < ".($grossPay * 0.30)." == ".$coopDedCurPayroll." / ".$r['empid']."waaaaaahhh<br>";
			}
		} 
		
		
		if ($thirthyPercentOfGross > ($netPay)){
			//echo $r['d_coopTotal']." > ".(($grossPay * 0.30) - $netPay);
			if ($r['d_hospital']>0){
					//echo  "asdjkh";
				$hospitalDedCurPayroll = $r['d_hospital'] - (($grossPay * 0.30) - $netPay);
				if ($hospitalDedCurPayroll<=0) $hospitalDedCurPayroll = 0;
				$updpayroll = mysql_query("UPDATE payroll SET d_hospital='".$hospitalDedCurPayroll."' WHERE empid='".$dt['employeeId']."' && pay_period='".$_POST['cutoffDate']."'",$conn);
				
				$netPay += (($grossPay * 0.30) - $netPay);
				//$r['d_coopTotal'] = $coopDedCurPayroll;
				//echo $netPay." < ".($grossPay * 0.30)." == ".$coopDedCurPayroll." / ".$r['empid']."waaaaaahhh<br>";
			}
		} 
	} // end while
	$lockPayrolCutOff = mysql_query("UPDATE cutoffdates SET isLock='0' WHERE  payrollDate ='".$_POST['cutoffDate']."' ",$conn);
	echo "Government premium succesfully processed. Payroll is Lock";
} // end if

//-------------------------------------------------
//-------------------------------------------------
//-------------------------------------------------
$rs = mysql_query("SELECT * FROM cutoffdates where isLock='0' ORDER BY payrollDate DESC limit 12",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- <title>HRIS - Davao Doctors Hospital</title> -->
    <link href="../css/styles.css" rel="stylesheet" type="text/css" />
    <link href="../css/facebox.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Compute Goverment Premiums</h2>   
    <div class="clearfix">
			<form method="POST" action="tools_govpremium_.php?pageact=runComputation" margin="0px;" name="myForm">
				<select name="cutoffDate"><?php echo $optionSelectPayrollCutoffDate;?></select>
				<button>Re/Compute Gov't Premiums</button>
			</form>
			<div>Make sure you've already Freeze </div>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>