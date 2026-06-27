<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");

//echo $_POST['PayrollYear']." ".$_POST['mbtcCompany'];
//$lastDateJan = date('t',date($_POST['PayrollYear'].'-m-d'));

$sql = "SELECT e.ndex empid, e.lastName, e.firstName, e.middleName, e.employeeNo 
								FROM employee e 
								LEFT JOIN payroll p ON p.empid=e.ndex
										WHERE DATE_FORMAT(p.pay_period,'%Y') = '".$_POST['PayrollYear']."' ";// && e.ndex=828";

//$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));												

if ($_POST['mbtcCompany'] == 1){
	$sql .= " && p.level IN (0)";
	$level = " && level IN (0)";
	$reportTitle = 'TEMPORARY';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && p.level IN (1,2)";
	$level = " && level IN (1,2)";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && p.level IN (3,4,5,6,7,8,9)";
	$level = " && level IN (3,4,5,6,7,8,9)";
	$reportTitle = 'SECTION HEADS AND CONFI';
} elseif ($_POST['mbtcCompany'] == 0) {
	$reportTitle = 'ALL EMPLOYEE';
}

$sql.=" GROUP BY e.ndex, e.lastName, e.firstName, e.middleName, e.employeeNo
		ORDER BY  e.lastName, e.firstName";
//echo $sql;
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$lk=0;
$tlk=0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
	$var++;
  	$ctr1s++;
	$ln++;
	$lk++;
	$tlk++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	
	//$totalDeduction = $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['pagibigloan'] + $r['pagibigloanh'] + $r['d_unionDues'] + $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal'] + $r['financialAssistance'] + $r['pagibigSavings'];
	//	$netPay = $grossPay - $totalDeduction;
	$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['payOTExc'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'] - $r['payUndertime'] +  $r['adj_other']  +  $r['hazardPay']  +  $r['incentive'] + $r['adj_13th_mon_pay'];

	$janA = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-01-15')))."' && empid='".$r['empid']."' {$level}"));
	$janB = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					 FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-01-01')))."' && empid='".$r['empid']."' {$level}"));
	$febA = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					 FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-02-15')))."' && empid='".$r['empid']."' {$level}"));
	$febB = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					 FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-02-01')))."' && empid='".$r['empid']."' {$level}"));
	$marA = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-03-15')))."' && empid='".$r['empid']."' {$level}"));
	$marB = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-03-01')))."' && empid='".$r['empid']."' {$level}"));
	$aprA = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-04-15')))."' && empid='".$r['empid']."' {$level}"));
	$aprB = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-04-01')))."' && empid='".$r['empid']."' {$level}"));
	$mayA = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-05-15')))."' && empid='".$r['empid']."' {$level}"));
	$mayB = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-05-01')))."' && empid='".$r['empid']."' {$level}"));
	$junA = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-06-15')))."' && empid='".$r['empid']."' {$level}"));
	$junB = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-06-01')))."' && empid='".$r['empid']."' {$level}"));
	$julA = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-07-15')))."' && empid='".$r['empid']."' {$level}"));
	$julB = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-07-01')))."' && empid='".$r['empid']."' {$level}"));
	$augA = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-08-15')))."' && empid='".$r['empid']."' {$level}"));
	$augB = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-08-01')))."' && empid='".$r['empid']."' {$level}"));
	$sepA = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-09-15')))."' && empid='".$r['empid']."' {$level}"));
	$sepB = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-09-01')))."' && empid='".$r['empid']."' {$level}"));
	$octA = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-10-15')))."' && empid='".$r['empid']."' {$level}"));
	$octB = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-10-01')))."' && empid='".$r['empid']."' {$level}"));
	$novA = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-11-15')))."' && empid='".$r['empid']."' {$level}"));
	$novB = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-11-01')))."' && empid='".$r['empid']."' {$level}"));
	$decA = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-12-15')))."' && empid='".$r['empid']."' {$level}"));
	$decB = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other + hazardPay + incentive + adj_13th_mon_pay) grossPay, 
																 SUM(d_pnb + d_parkingFee + d_whtax + d_sss + d_philhealth + pagibig + pagibigloan + pagibigloanh + d_unionDues + d_mortuary + d_sssloan + d_hospital + d_cashAdvance + d_other + d_coopTotal + financialAssistance + pagibigSavings) totalDeduction
																					  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-12-01')))."' && empid='".$r['empid']."' {$level}"));

	// SICK LEAVE 
	//$grossPay = SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other)
	
	$totalV = $janA['grossPay'] - $janA['totalDeduction'] + 
			  $janB['grossPay'] - $janB['totalDeduction'] +
			  $febA['grossPay'] - $febA['totalDeduction'] + 
			  $febB['grossPay'] - $febB['totalDeduction'] +
			  $marA['grossPay'] - $marA['totalDeduction'] + 
			  $marB['grossPay'] - $marB['totalDeduction'] +
			  $aprA['grossPay'] - $aprA['totalDeduction'] + 
			  $aprB['grossPay'] - $aprB['totalDeduction'] +
			  $mayA['grossPay'] - $mayA['totalDeduction'] + 
			  $mayB['grossPay'] - $mayB['totalDeduction'] +
			  $junA['grossPay'] - $junA['totalDeduction'] + 
			  $junB['grossPay'] - $junB['totalDeduction'] +
			  $julA['grossPay'] - $julA['totalDeduction'] + 
			  $julB['grossPay'] - $julB['totalDeduction'] +
			  $augA['grossPay'] - $augA['totalDeduction'] + 
			  $augB['grossPay'] - $augB['totalDeduction'] +
			  $sepA['grossPay'] - $sepA['totalDeduction'] + 
			  $sepB['grossPay'] - $sepB['totalDeduction'] +
			  $octA['grossPay'] - $octA['totalDeduction'] + 
			  $octB['grossPay'] - $octB['totalDeduction'] +
			  $novA['grossPay'] - $novA['totalDeduction'] + 
			  $novB['grossPay'] - $novB['totalDeduction'] +
			  $decA['grossPay'] - $decA['totalDeduction'] + 
			  $decB['grossPay'] - $decB['totalDeduction'];
	$grandTotal += $totalV;
	$janATotal += $janA['grossPay'] - $janA['totalDeduction']; 
	$janBTotal += $janB['grossPay'] - $janB['totalDeduction'];
	$febATotal += $febA['grossPay'] - $febA['totalDeduction']; 
	$febBTotal += $febB['grossPay'] - $febB['totalDeduction'];
	$marATotal += $marA['grossPay'] - $marA['totalDeduction']; 
	$marBTotal += $marB['grossPay'] - $marB['totalDeduction'];
	$aprATotal += $aprA['grossPay'] - $aprA['totalDeduction']; 
	$aprBTotal += $aprB['grossPay'] - $aprB['totalDeduction'];
	$mayATotal += $mayA['grossPay'] - $mayA['totalDeduction']; 
	$mayBTotal += $mayB['grossPay'] - $mayB['totalDeduction'];
	$junATotal += $junA['grossPay'] - $junA['totalDeduction']; 
	$junBTotal += $junB['grossPay'] - $junB['totalDeduction'];
	$julATotal += $julA['grossPay'] - $julA['totalDeduction']; 
	$julBTotal += $julB['grossPay'] - $julB['totalDeduction'];
	$augATotal += $augA['grossPay'] - $augA['totalDeduction']; 
	$augBTotal += $augB['grossPay'] - $augB['totalDeduction'];
	$sepATotal += $sepA['grossPay'] - $sepA['totalDeduction']; 
	$sepBTotal += $sepB['grossPay'] - $sepB['totalDeduction'];
	$octATotal += $octA['grossPay'] - $octA['totalDeduction']; 
	$octBTotal += $octB['grossPay'] - $octB['totalDeduction'];
	$novATotal += $novA['grossPay'] - $novA['totalDeduction']; 
	$novBTotal += $novB['grossPay'] - $novB['totalDeduction'];
	$decATotal += $decA['grossPay'] - $decA['totalDeduction']; 
	$decBTotal += $decB['grossPay'] - $decB['totalDeduction'];
	
	$data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 		<td>".$r['employeeNo']."</td>
	     	<td align='left'>".$r['lastName'].", ".$r['firstName']."</td>
	      	<td>".number_format((($janA['grossPay'] - $janA['totalDeduction']) != 0 ? $janA['grossPay'] - $janA['totalDeduction'] : '0' ),2)."</td>
		   	<td>".number_format((($janB['grossPay'] - $janB['totalDeduction']) != 0 ? $janB['grossPay'] - $janB['totalDeduction'] : '0' ),2)."</td>
	      	<td>".number_format((($febA['grossPay'] - $febA['totalDeduction']) != 0 ? $febA['grossPay'] - $febA['totalDeduction'] : '0' ),2)."</td>
		   	<td>".number_format((($febB['grossPay'] - $febB['totalDeduction']) != 0 ? $febB['grossPay'] - $febB['totalDeduction'] : '0' ),2)."</td>
			<td>".number_format((($marA['grossPay'] - $marA['totalDeduction']) != 0 ? $marA['grossPay'] - $marA['totalDeduction'] : '0' ),2)."</td>
		   	<td>".number_format((($marB['grossPay'] - $marB['totalDeduction']) != 0 ? $marB['grossPay'] - $marB['totalDeduction'] : '0' ),2)."</td>
	      	<td>".number_format((($aprA['grossPay'] - $aprA['totalDeduction']) != 0 ? $aprA['grossPay'] - $aprA['totalDeduction'] : '0' ),2)."</td>
		   	<td>".number_format((($aprB['grossPay'] - $aprB['totalDeduction']) != 0 ? $aprB['grossPay'] - $aprB['totalDeduction'] : '0' ),2)."</td>
			<td>".number_format((($mayA['grossPay'] - $mayA['totalDeduction']) != 0 ? $mayA['grossPay'] - $mayA['totalDeduction'] : '0' ),2)."</td>
		   	<td>".number_format((($mayB['grossPay'] - $mayB['totalDeduction']) != 0 ? $mayB['grossPay'] - $mayB['totalDeduction'] : '0' ),2)."</td>
	      	<td>".number_format((($junA['grossPay'] - $junA['totalDeduction']) != 0 ? $junA['grossPay'] - $junA['totalDeduction'] : '0' ),2)."</td>
		   	<td>".number_format((($junB['grossPay'] - $junB['totalDeduction']) != 0 ? $junB['grossPay'] - $junB['totalDeduction'] : '0' ),2)."</td>
			<td>".number_format((($julA['grossPay'] - $julA['totalDeduction']) != 0 ? $julA['grossPay'] - $julA['totalDeduction'] : '0' ),2)."</td>
		   	<td>".number_format((($julB['grossPay'] - $julB['totalDeduction']) != 0 ? $julB['grossPay'] - $julB['totalDeduction'] : '0' ),2)."</td>
	      	<td>".number_format((($augA['grossPay'] - $augA['totalDeduction']) != 0 ? $augA['grossPay'] - $augA['totalDeduction'] : '0' ),2)."</td>
		   	<td>".number_format((($augB['grossPay'] - $augB['totalDeduction']) != 0 ? $augB['grossPay'] - $augB['totalDeduction'] : '0' ),2)."</td>
			<td>".number_format((($sepA['grossPay'] - $sepA['totalDeduction']) != 0 ? $sepA['grossPay'] - $sepA['totalDeduction'] : '0' ),2)."</td>
		   	<td>".number_format((($sepB['grossPay'] - $sepB['totalDeduction']) != 0 ? $sepB['grossPay'] - $julA['totalDeduction'] : '0' ),2)."</td>
	      	<td>".number_format((($octA['grossPay'] - $octA['totalDeduction']) != 0 ? $octA['grossPay'] - $octA['totalDeduction'] : '0' ),2)."</td>
		   	<td>".number_format((($octB['grossPay'] - $octB['totalDeduction']) != 0 ? $octB['grossPay'] - $octB['totalDeduction'] : '0' ),2)."</td>
			<td>".number_format((($novA['grossPay'] - $novA['totalDeduction']) != 0 ? $novA['grossPay'] - $julA['totalDeduction'] : '0' ),2)."</td>
		   	<td>".number_format((($novB['grossPay'] - $novB['totalDeduction']) != 0 ? $novB['grossPay'] - $novB['totalDeduction'] : '0' ),2)."</td>
	      	<td>".number_format((($decA['grossPay'] - $decA['totalDeduction']) != 0 ? $decA['grossPay'] - $decA['totalDeduction'] : '0' ),2)."</td>
		   	<td>".number_format((($decB['grossPay'] - $decB['totalDeduction']) != 0 ? $decB['grossPay'] - $decB['totalDeduction'] : '0' ),2)."</td>
			<td>".number_format($totalV, 2)."</td>
				     </tr>";
	$data .= "<tr><td colspan='24'>&nbsp;</td></tr>";

}



?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="reportbyposition.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:11px;">
	 <thead>
	  <tr>
	       <td colspan="27" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>PAYROLL REGISTER ON NET EARNINGS<br /><?php echo $reportTitle;?><br>
				 				<?php echo date('M d, Y',strtotime($PayrollYear.'-01-01'))." to ".date('M d, Y',strtotime($PayrollYear.'-12-31'));?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
			<td>IDNUM</td>
	     	<td>NAME</td>
	      	<td>15-Jan</td>
		   	<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-01-d')));?>-Jan</td>
	      	<td>15-Feb</td>
			<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-02-d')));?>-Feb</td>
			<td>15-Mar</td>
		   	<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-03-d')));?>-Mar</td>
	      	<td>15-Apr</td>
			<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-04-01')));?>-Apr</td>
			<td>15-May</td>
		   	<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-05-d')));?>-May</td>
	      	<td>15-Jun</td>
			<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-06-01')));?>-Jun</td>
			<td>15-Jul</td>
		   	<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-07-d')));?>-Jul</td>
	      	<td>15-Aug</td>
			<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-08-01')));?>-Aug</td>
			<td>15-Sep</td>
		   	<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-09-d')));?>-Sep</td>
	      	<td>15-Oct</td>
			<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-10-01')));?>-Oct</td>
			<td>15-Nov</td>
		   	<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-11-d')));?>-Nov</td>
	      	<td>15-Dec</td>
			<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-12-01')));?>-Dec</td>
			<td>TOTAL</td>
	  </tr>
	  <tr><td colspan="27"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
	  <tr><td colspan="27"><hr></td></tr>
	  <tr><td colspan="2">Grand Total</td>
	  <td><?php echo number_format($janATotal,2);?></td>
	  <td><?php echo number_format($janBTotal,2);?></td>
	  <td><?php echo number_format($febATotal,2);?></td>
	  <td><?php echo number_format($febBTotal,2);?></td>
	  <td><?php echo number_format($marATotal,2);?></td>
	  <td><?php echo number_format($marBTotal,2);?></td>
	  <td><?php echo number_format($aprATotal,2);?></td>
	  <td><?php echo number_format($aprBTotal,2);?></td>
	  <td><?php echo number_format($mayATotal,2);?></td>
	  <td><?php echo number_format($mayBTotal,2);?></td>
	  <td><?php echo number_format($junATotal,2);?></td>
	  <td><?php echo number_format($junBTotal,2);?></td>
	  <td><?php echo number_format($julATotal,2);?></td>
	  <td><?php echo number_format($julBTotal,2);?></td>
	  <td><?php echo number_format($augATotal,2);?></td>
	  <td><?php echo number_format($augBTotal,2);?></td>
	  <td><?php echo number_format($sepATotal,2);?></td>
	  <td><?php echo number_format($sepBTotal,2);?></td>
	  <td><?php echo number_format($octATotal,2);?></td>
	  <td><?php echo number_format($octBTotal,2);?></td>
	  <td><?php echo number_format($novATotal,2);?></td>
	  <td><?php echo number_format($novBTotal,2);?></td>
	  <td><?php echo number_format($decATotal,2);?></td>
	  <td><?php echo number_format($decBTotal,2);?></td>
	  <td><?php echo number_format($grandTotal,2);?></td>
	  </tr>
	  <tr><td colspan="27"><hr></td></tr>
      </table>
	  <?php include("../rptfooter.php");?>




