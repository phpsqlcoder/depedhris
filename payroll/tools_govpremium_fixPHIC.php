<?php
ob_start();
session_start();
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");
include ("payrollfunctions.php");
$_POST['cutoffDate'] = '2020-03-31';

echo $_POST['cutoffDate'];

die();
	$sql = "SELECT e.ndex employeeId, e.isUnionMember, e.isCoopMember, e.paytype, e.isTaxable,
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
		
			if ($pagibigDed > 100){
				//$pagibigDedNoTax = 100;
				$pagibigDed = 100;
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

		// SSS SALARY LOAN, HDMF LOAN, PAG-IBIG SALARY LOAN
		
		//echo $dt['d_sssloan']."<br>".$dt['pagibigloanh']."<br>".$dt['pagibigloan'];
		//echo $undertime."asdflkmnasdf";
		//echo "[ ".$dt['d_hospital']." ]";
		//die();
		$updatePayrolltable = mysql_query("UPDATE payroll SET
																						
																						d_philhealth='".$philhealthDed."',
																						d_whtax='".$taxwithHeld."'
																						WHERE empid='".$dt['employeeId']."' && pay_period='".$_POST['cutoffDate']."'",$conn);
		
		// IF GROSS INCOME GREATER THAN NETPAY

		//echo $dt['employeeId']."<br />";
		$r = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".$_POST['cutoffDate']."' && empid='".$dt['employeeId']."'",$conn));
		$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['payOTExc'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'] - $r['payUndertime'] +  $r['adj_other'] +  $r['hazardPay'] +  $r['incentive'];
		$totalDeduction = $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['pagibigloan'] + $r['pagibigloanh'] + $r['d_unionDues'] + $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal'] + $r['financialAssistance'] + $r['pagibigSavings'];
		$netPay = $grossPay - $totalDeduction;
		$thirthyPercentOfGross = ($grossPay * 0.30);

		
	
		
		//echo "NET PAY".$netPay."<br>";
		//die();
	} // end while
	$lockPayrolCutOff = mysql_query("UPDATE cutoffdates SET isLock='0' WHERE  payrollDate ='".$_POST['cutoffDate']."' ",$conn);
	echo "Government premium succesfully processed. Payroll is Lock";
?>