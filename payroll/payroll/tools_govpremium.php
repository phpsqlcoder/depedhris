<?php
ob_start();
session_start();
if(!$_SESSION['ndex']){header("location:../login.php");}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");

if ($_GET['pageact'] == "runComputation"){

	$sql = "SELECT e.ndex employeeId, e.isUnionMember, e.isCoopMember, e.paytype, e.isTaxable,
								p.*, e.employmentStatus, e.residencyTrainingProgram, e.level,
								 ec.allowance, ec.honorarium, ec.cola, ec.basicPay basicPay, ec.taxType, ec.payTypeNdex
										FROM employee e 
												LEFT JOIN employee_compensation ec ON ec.employeeId=e.ndex
												LEFT JOIN payroll p ON p.empid=e.ndex
													WHERE e.isActive='1' && p.pay_period='".$_POST['cutoffDate']."' && ec.basicPay>0 && (p.days_work>0 || p.grossPay>0) ";

	$rs = mysql_query($sql,$conn);		
	while($dt = mysql_fetch_assoc($rs)){
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
			$ot_exc = $basicpayPerHour * $dt['ot_exc'];										// adjustment pay amount 
			$ot_reg = ($basicpayPerHour * 1.25) * $dt['ot_reg'];					// overtime pay amount 
			$spholiday = ($basicpayPerHour * 0.30) * $dt['spholiday'];		// special holiday premium amount
			$lholiday = $basicpayPerHour * $dt['lholiday'];								// legal holiday premium amount
			$night_prem = ($basicpayPerHour * 0.10) * $dt['night_prem'];	// night premium amount
			//$duty_rd = ($basicpayPerHour * 1.30) * $dt['duty_rd'];				// duty restday amount 
			$hazardPay = $dt['hazardPay'];																													// HazardPay
			//$cola = $dt['cola'] / 2;
			//$cola = $cola - ($colaPerDay * $dt['days_absent'])  - ($colaPerMinute * $dt['undertime']);																															// cola divided by 2
			// DEDUCTION
			$absent = $basicpayPerDay * $dt['days_absent'];     					// absent amount deduction on ee with pay type is equal to MONTHLY
			$undertime = $basicpayPerHour * $dt['undertime'];		
			//$undertime = $basicpayPerMinute * $dt['undertime'];						// undertime amount deduction on all ee
		
		
		//echo $basicpayPerHour;
		$allowance = ( ($dt['allowance'] / 2) - ((payPerSpecificTime($dt['allowance'],'day')) * $dt['days_absent']) );		// allowance divided by 2 less days ansent
		$honorarium = ( ($dt['honorarium'] / 2) - ((payPerSpecificTime($dt['honorarium'],'day')) * $dt['days_absent']) );		// honorarium divided by 2 less days ansent
		$union = $dt['d_unionDues'];																														// union dues deduction on all ee
		$mortuary = $dt['d_mortuary'];																													// mortuary deduction on all ee
		$coopTotalDed = $dt['d_coopTotal'];																											// coop total deduction
		$financialAssistance = $dt['financialAssistance'];																			// financial assistance
		
	
		// EE STATUS
		$dependents = noOfDependents($dt['employeeId']);																				// ee's dependent

		if ($dt['payTypeNdex'] == '1'){																															
			$netBasic = ($dt['basicPay'] / 2) - $absent ;		
			$cola = $dt['cola'] / 2;											// cutoff pay less absent and undertime for MONTHLY ee\
			$cola = $cola - ($colaPerDay * $dt['days_absent'])  - ($colaPerHour * $dt['undertime']);
			$duty_rd = ($basicpayPerHour * 0.30) * $dt['duty_rd'];				// duty restday amount 
		} elseif ($dt['payTypeNdex'] == '2'){
			$netBasic = ($basicpayPerDay * $dt['days_work']); 	// cutoff pay less absent and undertime for daily ee
			$cola = ($dt['cola'] * $dt['days_work'])  - ($colaPerHour * $dt['undertime']);
			$duty_rd = ($basicpayPerHour * 1.30) * $dt['duty_rd'];				// duty restday amount 
		}
		
		// ADJUSTMENTS  leaves, days work and overtime.
		//$otherIncome = 0;    
		$otherIncome = ($basicpayPerDay + $colaPerDay + payPerSpecificTime($dt['honorarium'],'day') + payPerSpecificTime($dt['allowance'],'day') )* ( $dt['adj_sick_lve'] + $dt['adj_days_work'] + $dt['adj_vac_lve'] + $dt['adj_bday_lve'] + $dt['adj_official_lve'] ) + 
						($basicpayPerHour * ( $dt['adj_ot_reg'] + $dt['adj_night_prem'] + $dt['adj_duty_rd'] ));
		
		//echo $dt['adj_sick_lve'];
		$grossIncome = ($netBasic + $ot_reg + $ot_exc + $spholiday + $lholiday + $night_prem + $duty_rd + $allowance + $honorarium + $cola + $otherIncome - $undertime + $dt['adj_other']);	// Gross TOTAL cutoff Income
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
			
			// NO UNION DEDUCTION
			$union = 0;
		} else {
			// WITH UNION DEDUCTION
			$unionDues = mysql_fetch_assoc(mysql_query("SELECT * FROM dedsetup WHERE dedType='UNIONDUES'"));
			if ($dt['isUnionMember'] == 1){
				$union = $unionDues['amount'];
			}
		}

		// MORTUARY DEDUCTION SCHEDULE ON PAYROLL DATE
		$mortuaryDed = mysql_fetch_assoc(mysql_query("SELECT SUM(amount) amount FROM mortuary WHERE payrollDate='".$_POST['cutoffDate']."'",$conn));
		if ($dt['isUnionMember'] == 0){
			$mortuaryDed['amount'] = 0;
		}
		
		//FINANCIAL ASSISTANCE
		$faDed = mysql_fetch_assoc(mysql_query("SELECT SUM(amount) amount FROM financialassistance WHERE payrollDate='".$_POST['cutoffDate']."'",$conn));
		if ($dt['isUnionMember'] == 0){
			$faDed['amount'] = 0;
		}

		// WITHHOLDING TAX DEDUCTION   ----    incomce less union and mortuary.
		$taxableIncome = $grossIncome + $dt['onCallOvertime'] - ($pagibigDed + $sssEmployeeShare + $philhealthDed + $union + $mortuaryDed['amount'] + $faDed['amount']);
		//echo $taxableIncome." - - ".$grossIncome;
		//die();
		if ($dt['isTaxable'] != 0){
			if($dt['taxType'] == 1){
				$taxwithHeld = withHeldTax ($dependents, $taxableIncome, $dedFrequency='SEMI-MONTHLY');
			} else {
				$taxwithHeld = $taxwithHeld * 0.10;
			}
		} else {
			$taxwithHeld = 0;
		}
		
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
		}
		$updatePayrolltable = mysql_query("UPDATE payroll SET
																						cola='".$cola."',
																						d_sss='".$sssEmployeeShare."',
																						d_philhealth='".$philhealthDed."',
																						d_whtax='".$taxwithHeld."',
																						pagibig='".$pagibigDed."',
																						allowance='".$allowance."',
																						honorarium='".$honorarium."',
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
																						d_unionDues = '".$union."',
																						financialAssistance = '".$faDed['amount']."',
																						d_mortuary='".($mortuaryDed['amount'])."'
																								WHERE empid='".$dt['employeeId']."' && pay_period='".$_POST['cutoffDate']."'",$conn);
			
			
	} // end while
} // end if

//-------------------------------------------------
//-------------------------------------------------
//-------------------------------------------------
$rs = mysql_query("SELECT * FROM cutoffdates ORDER BY payrollDate DESC limit 12",$conn);
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
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
