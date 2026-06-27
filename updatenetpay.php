<?php
$conn=mysql_connect("localhost","root","pangitka");
mysql_select_db("hris",$conn);

$cutoffDate = '2014-01-15';

//------------------------------------

$sql = "SELECT * FROM payroll WHERE pay_period='".$cutoffDate."'";
$rs = mysql_query($sql,$conn);
while ($dt = mysql_fetch_assoc($rs)){
		$r = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE ndex='".$dt['ndex']."'",$conn));
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
} // end of while loop
?>