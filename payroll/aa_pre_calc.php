<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");
include ("../employeefunctions.php");
include ("payrollfunctions.php");

$data = '';
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


if ($_GET['pageact'] == "runComputation"){

	//$sqlReset = "delete FROM `loan_employee_payments` where datePaid='".$_POST['cutoffDate']."' and remarks='';
	//	UPDATE  payroll SET d_other=0, d_sssloan=0, pagibigloan=0, d_hospital=0, pagibigloanh=0 where pay_period='".$_POST['cutoffDate']."' ";
	//$updateReset = mysql_query($sqlReset,$conn);
	$sql = "SELECT e.ndex employeeId, e.isUnionMember, e.isCoopMember, e.paytype, e.isTaxable,e.employeeNo,e.lastName,e.firstName,
								p.*, e.employmentStatus, e.residencyTrainingProgram, e.level,
								 ec.allowance, ec.honorarium, ec.cola, ec.basicPay basicPay, ec.taxType, ec.payTypeNdex, ec.pagibigSavings,ec.incentive,ec.hazardPay
										FROM employee e 
												LEFT JOIN employee_compensation ec ON ec.employeeId=e.ndex
												LEFT JOIN payroll p ON p.empid=e.ndex
													WHERE p.pay_period='".$_POST['cutoffDate']."' && ec.basicPay>0"; 
							
										// WHERE e.isActive='0' && p.pay_period='".$_POST['cutoffDate']."' && ec.basicPay>0 && (p.days_work>0 || p.grossPay>0) && e.ndex='20'
	$rs = mysql_query($sql,$conn);		
	//echo $sql;
	//die();
	while($dt = mysql_fetch_assoc($rs)){
		//echo $dt['employeeId']."<br>";
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
		$prevGrossInc = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period = '".date('Y-m-15',strtotime($_POST['cutoffDate']))."' && empid='".$dt['employeeId']."'",$conn));  // getting the previous payroll value of ee
	//	echo "$grossIncome -- $netBasic + $ot_reg + $ot_exc + $spholiday + $lholiday + $night_prem + $duty_rd + $allowance + $honorarium + $cola + $otherIncome - $undertime <br><bR>";
		

		if ($_POST['cutoffDate'] == date('Y-m-t', strtotime($_POST['cutoffDate']))){
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
		
	$checkIfOnFreeze=mysql_num_rows(mysql_query("select * from loanpayments_freeze where employeeId='".$dt['employeeId']."' && cutoffDate = '".$_POST['cutoffDate']."'"));
	
	if($checkIfOnFreeze==0){ //ADDED BY JUNDRIE - TO DEDUCT ONLY THOSE EMPLOYEES WHO ARE MARK AS FREEZE DEDUCTION. DATA is at table loanpayments_freeze
		//echo "NOT FREEZE <br>";
		$loansqry=mysql_query("SELECT ndex as loans, loanId FROM `loan_employee` WHERE employeeId=".$dt['employeeId']." AND posted='1' AND dedDateStart <='".$_POST['cutoffDate']."' AND isDeleted='0'");
		$dt['pagibigloanh'] = 0;
		//echo "SELECT ndex as loans FROM `loan_employee` where employeeId=".$dt['employeeId']." and posted='1'";die();
		while($lsq=mysql_fetch_object($loansqry)){
			$checkIfalreadyprocess=mysql_num_rows(mysql_query("select * from loan_employee_payments where datePaid = '".$_POST['cutoffDate']."' and loanSetupId='".$lsq->loans."'")); // and remarks=''
						
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
					$loansqryins=mysql_query("insert into loan_employee_payments (`loanSetupId`, `datePaid`, `amountPaid`,`remarks`) VALUES ('".$lsq->loans."','".$_POST['cutoffDate']."','".getDeductionData($lsq->loans,'Currect Deduction Amount')."','')");
				}
				else{
					//echo '2';
					$loansqryupd=mysql_query("update loan_employee_payments set `amountPaid`='".getDeductionData($lsq->loans,'Currect Deduction Amount')."' WHERE datePaid = '".$_POST['cutoffDate']."' and loanSetupId='".$lsq->loans."' and remarks=''");
				}
				*/
				//echo getDeductionData($lsq->loans,'Currect Deduction Amount')."<br>";
			}

			//echo 's';
		}
		//$d_hospital += process_payment($dt['employeeId'], $_POST['cutoffDate']);
	}
	else{ // IF ON FREEZE BUT ALREADY SUBMITTED DATA on loanpayments.. This process will delete data from loanpayments and return the amount to loanbalance.
		//echo "FREEZE <br>";
		$dt['d_sssloan']=0;
		$dt['pagibigloanh']=0;
		$dt['pagibigloan']=0;
		$dt['d_hospital']=0;
		$qryToCheckLoanPayment=mysql_query("select * from loanpayments where employeeId='".$dt['employeeId']."' and datePaid='".$_POST['cutoffDate']."'");
		while($lp=mysql_fetch_object($qryToCheckLoanPayment)){			
			$lsdata=mysql_fetch_object(mysql_query("select * from loansetup where ndex=".$lp->loanSetupId.""));
			if($lsdata->loanType=='OTHERS'){
				//$updateSql = mysql_query("UPDATE payroll SET d_other=(d_other - ".$lp->amountPaid.") where empid='".$lp->EmployeeId."' && pay_period='".$lp->datePaid."'");
				//$d_other += $lp->amountPaid;
			}
			/*
			$returndata=mysql_query("update loansetup set loanBalance = (loanBalance + ".$lp->amountPaid.") where ndex=".$lp->loanSetupId."");
			$insertToDeletedTable=mysql_query("INSERT INTO `loanpayments_deleted`( `loanSetupId`, `EmployeeId`, `datePaid`, `amountPaid`, `deletedDate`) VALUES ('".$lp->loanSetupId."','".$lp->EmployeeId."','".$lp->datePaid."','".$lp->amountPaid."','".date('Y-m-d H:i:s')."')");
			$deletedata=mysql_query("delete from loanpayments where ndex=".$lp->ndex."");
			*/
		}
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

		/*
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
																						WHERE empid='".$dt['employeeId']."' && pay_period='".$_POST['cutoffDate']."'",$conn);
																						*/
		
		// IF GROSS INCOME GREATER THAN NETPAY d_other = (d_other + '".$d_other."')

		//echo $dt['employeeId']."<br />";
		$r = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".$_POST['cutoffDate']."' && empid='".$dt['employeeId']."'",$conn));
		$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['payOTExc'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'] - $r['payUndertime'] +  $r['adj_other'] +  $r['hazardPay'] +  $r['incentive'];

		$grossPay = $netBasic + $cola + $allowance + $honorarium + $night_prem + $ot_reg + $ot_exc + $otherIncome + $duty_rd + $spholiday + $lholiday + $otRDLHoliday + $otRDSHoliday + $otLHoliday + $otSHoliday + $otRestDay - $undertime +  $r['adj_other']  +  $hazardPay  +  $incentive;

		$totalDeduction = $r['d_pnb'] + $r['d_parkingFee'] + $taxwithHeld + $sssEmployeeShare + $philhealthDed + $pagibigDed + $dt['pagibigloan'] + $dt['pagibigloanh'] + $dt['d_unionDues'] + $mortuaryDed['amount'] + $dt['d_sssloan'] + $d_hospital + $r['d_cashAdvance'] + $d_other + $r['d_coopTotal'] + $faDed['amount'] + $dt['pagibigSavings'];
		$netPay = $grossPay - $totalDeduction;
		$thirthyPercentOfGross = ($grossPay * 0.30);

		//echo "$grossPay - $totalDeduction <br>";
		// if ($thirthyPercentOfGross > ($netPay)){
		// 	if ($r['d_hospital']>0){
		// 		$hospitalDedCurPayroll = $r['d_hospital'] - ($thirthyPercentOfGross - $netPay);

		// 		if ($hospitalDedCurPayroll<=0) $hospitalDedCurPayroll = 0;
		// 		$updpayroll = mysql_query("UPDATE payroll SET d_hospital='".$hospitalDedCurPayroll."' WHERE empid='".$dt['employeeId']."' && pay_period='".$_POST['cutoffDate']."'",$conn);
		// 		$netPay += $r['d_hospital'] - $hospitalDedCurPayroll;
		// 	}
		// } 
		$hos = process_payment($dt['empid'], $_POST['cutoffDate']);
		if ($thirthyPercentOfGross > ($netPay)){

			$data.="<tr bgcolor='".$bgclr1s."' align='right'>
				<td align='left'>".getID($dt['employmentStatus'],$dt['employeeNo'])."&nbsp; </td>
				<td align='left'> ".$dt['lastName'].", ".$dt['firstName']."&nbsp; </td>
				<td>".number_format($r['basicpay'],2)."&nbsp; </td>				
				<td>".number_format($totalDeduction,2)."&nbsp; </td>
				<td>".number_format($grossPay,2)."</td>
				<td>".number_format($thirthyPercentOfGross,2)."</td>
				<td>".number_format($netPay,2)."</td>
				<td>".number_format($hos,2)."</td>
				<td>".number_format(($netPay - $hos),2)."</td>

			</tr>";

			
		}

	/*
		if ($thirthyPercentOfGross > ($netPay)){
			if ($r['d_coopTotal']>0){
				echo $r['d_coopTotal']." > 0";
				$coopDedCurPayroll = $r['d_coopTotal'] - (($grossPay * 0.30) - $netPay);
				if ($coopDedCurPayroll<=0) $coopDedCurPayroll = 0;
				$updpayroll = mysql_query("UPDATE payroll SET d_coopTotal='".$coopDedCurPayroll."' WHERE empid='".$dt['employeeId']."' && pay_period='".$_POST['cutoffDate']."'",$conn);				
				$netPay += $r['d_coopTotal'] - $coopDedCurPayroll;
			}
		} 

		if ($thirthyPercentOfGross > ($netPay)){
			if ($r['financialAssistance']>0){
				//echo $r['d_coopTotal']." > 0";
				$fassistanceDedCurPayroll = $r['financialAssistance'] - (($grossPay * 0.30) - $netPay);
				if ($fassistanceDedCurPayroll<=0) $fassistanceDedCurPayroll = 0;
				$updpayroll = mysql_query("UPDATE payroll SET financialAssistance='".$fassistanceDedCurPayroll."' WHERE empid='".$dt['employeeId']."' && pay_period='".$_POST['cutoffDate']."'",$conn);				
				$netPay += $r['financialAssistance'] - $fassistanceDedCurPayroll;
			}
		} 

		if ($thirthyPercentOfGross > ($netPay)){
			if ($r['d_mortuary']>0){
				//echo $r['d_coopTotal']." > 0";
				$mortuaryDedCurPayroll = $r['d_mortuary'] - (($grossPay * 0.30) - $netPay);
				if ($mortuaryDedCurPayroll<=0) $mortuaryDedCurPayroll = 0;
				$updpayroll = mysql_query("UPDATE payroll SET d_mortuary='".$mortuaryDedCurPayroll."' WHERE empid='".$dt['employeeId']."' && pay_period='".$_POST['cutoffDate']."'",$conn);				
				$netPay += $r['d_mortuary'] - $mortuaryDedCurPayroll;
			}
		} 

		if ($thirthyPercentOfGross > ($netPay)){
			if ($r['d_other']>0){
				//echo $r['d_coopTotal']." > 0";
				$dotherDedCurPayroll = $r['d_other'] - (($grossPay * 0.30) - $netPay);
				if ($dotherDedCurPayroll<=0) $dotherDedCurPayroll = 0;
				$updpayroll = mysql_query("UPDATE payroll SET d_other='".$dotherDedCurPayroll."' WHERE empid='".$dt['employeeId']."' && pay_period='".$_POST['cutoffDate']."'",$conn);				
				$netPay += $r['d_other'] - $dotherDedCurPayroll;
			}
		} 

		if ($thirthyPercentOfGross > ($netPay)){
			if ($r['d_hospital']>0){
				$hospitalDedCurPayroll = $r['d_hospital'] - ($thirthyPercentOfGross - $netPay);

				if ($hospitalDedCurPayroll<=0) $hospitalDedCurPayroll = 0;
				$updpayroll = mysql_query("UPDATE payroll SET d_hospital='".$hospitalDedCurPayroll."' WHERE empid='".$dt['employeeId']."' && pay_period='".$_POST['cutoffDate']."'",$conn);
				$netPay += $r['d_hospital'] - $hospitalDedCurPayroll;
			}
		} 

		
	*/
		
		
		//echo "NET PAY".$netPay."<br>";
		//die();
	} // end while
	//$lockPayrolCutOff = mysql_query("UPDATE cutoffdates SET isLock='0' WHERE  payrollDate ='".$_POST['cutoffDate']."' ",$conn);
	//echo "Government premium succesfully processed. Payroll is Lock";
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
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=runComputation" margin="0px;" name="myForm">
				<select name="cutoffDate"><?php echo $optionSelectPayrollCutoffDate;?></select>
				<button>Re/Compute Gov't Premiums</button>
			</form>
			<div>Make sure you've already Freeze </div>
    </div> 
	<h2>&nbsp;</h2>
	<table width="100%" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:11px;">
		 <tr valign="bottom" align="center">
			<td>ID</td>
	     	<td>NAME</td>
	      <td>BASIC</td>		  
				<td>TOTAL <br>DED</td>				
				<td>Gross</td>
				<td>30%</td>
				<td>NET PAY</td>
				<td>Hospital</td>
				<td>Net</td>
	  </tr>
	   <?php echo $data;?>
	</table>
	<?php include "footer.php";?>
  </div>
</body>
</html>