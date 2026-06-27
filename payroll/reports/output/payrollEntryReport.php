<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");
include("../../../scripts/scripts.php");
include ("../../../myfunctions.php");
include ("../../payrollfunctions.php");

$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid, p.*,
		ec.allowance as allowance2, ec.honorarium as honorarium2, ec.cola as kola, ec.basicPay as basicPay2, ec.taxType, ec.payTypeNdex, ec.pagibigSavings
								FROM employee e 
									LEFT JOIN employee_compensation ec ON ec.employeeId=e.ndex
									LEFT JOIN payroll p ON p.empid = e.ndex 
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE p.pay_period>='".$_POST['PayrollCutoffstart']."'  && p.pay_period<='".$_POST['PayrollCutoffend']."' && p.residencyTrainingProgram='' && p.basicpay<>0 && p.holdSalary<>'1' ";// && e.ndex=163";&& d.name LIKE '%basicPay2%'
									
//$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));		
$cutoffDatestart = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoffstart']	."'",$conn));		
$cutoffDateend = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoffend']	."'",$conn));											
if ($_POST['mbtcCompany'] == 1){
	$sql .= " && p.level IN (0)";
	$reportTitle = 'TEMPORARY';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && p.level IN (1,2)";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && p.level IN (3,4,5,6,7,8,9)";
	$reportTitle = 'SECTION HEADS AND CONFI';
} elseif ($_POST['mbtcCompany'] == 0) {
	$reportTitle = 'ALL EMPLOYEE';
}
/*
if ($_POST['mbtcCompany'] == 1){
	$sql .= " && e.level IN (0)";
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && e.level IN (1,2)";
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && e.level IN (3,4,5)";
}*/
if ($_POST['division']){$sql .= " && e.divisionId='".$_POST['division']."'";}
//echo $division." --".$PayrollCutoff."<br>".$sql.mysql_num_rows(mysql_query($sql));
$sql.=" ORDER BY  d.name, e.lastName,e.firstName";
//echo $sql;	
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
	
	//echo "anhslkn".$r['payTypeNdex']."asfasdf<br><br>";
	if ($r['payTypeNdex'] == '1'){
			$basicpayPerDay = payPerSpecificTime($r['basicPay2'],'day');
			$basicpayPerHour = payPerSpecificTime($r['basicPay2'],'hour');
			$basicpayPerMinute = payPerSpecificTime($r['basicPay2'],'minute');
			
			$colaPerDay = payPerSpecificTime($r['kola'],'day');
			$colaPerHour = payPerSpecificTime($r['kola'],'hour');
			$colaPerMinute = payPerSpecificTime($r['kola'],'minute');
			
		} else {
			$basicpayPerDay = payPerSpecificTimeDaily($r['basicPay2'],'day');
			$basicpayPerHour = payPerSpecificTimeDaily($r['basicPay2'],'hour');
			$basicpayPerMinute = payPerSpecificTimeDaily($r['basicPay2'],'minute');
			
			$colaPerDay = payPerSpecificTimeDaily($r['kola'],'day');
			$colaPerHour = payPerSpecificTimeDaily($r['kola'],'hour');
			$colaPerMinute = payPerSpecificTimeDaily($r['kola'],'minute');
	}
	
	
	/*$otherIncome = ($basicpayPerDay + $colaPerDay + payPerSpecificTime($r['honorarium'],'day') + payPerSpecificTime($r['allowance'],'day') ) * 
						( $r['adj_sick_lve'] + 
						  $r['adj_days_work'] + 
						  $r['adj_vac_lve'] + 
						  $r['adj_bday_lve'] + 
						  $r['adj_official_lve'] ) + 
						($basicpayPerHour * ( $r['adj_ot_reg'] + $r['adj_duty_rd'] )) + (($basicpayPerHour + $colaPerHour) * $r['adj_undertime']) + (($basicpayPerHour * .10) * $r['adj_night_prem']);
		*/
		//OVERTIME  sick_lve
		$adjustmentOvertimeBasipay = $basicpayPerHour * ( $r['adj_ot_reg'] + $r['adj_duty_rd'] );
		
		//UNDERTIME
		$adjustmentUndertimeBasicpay = $basicpayPerHour * $r['adj_undertime'];
		$adjustmentUndertimeCola = $colaPerHour * $r['adj_undertime'];
		
		//DAYSWORK
		$adjustmentDaysworkBasicpay = $basicpayPerDay * $r['adj_days_work'];
		$adjustmentDaysworkCola = $colaPerDay * $r['adj_days_work'];
		$adjustmentDaysworkAllowance = payPerSpecificTime($r['allowance2'],'day') * $r['adj_days_work'];
		$adjustmentDaysworkHonorarium = payPerSpecificTime($r['honorarium2'],'day') * $r['adj_days_work'];
		
		//SICK LEAVE
		$addjustmentSickLeaveSL = $basicpayPerDay * ($r['adj_sick_lve'] + $r['sick_lve']) ;
		$addjustmentSickLeaveCola = $colaPerDay * $r['adj_sick_lve'];
		$addjustmentSickLeaveAllowance = payPerSpecificTime($r['allowance2'],'day') * $r['adj_sick_lve'];
		$addjustmentSickLeaveHonorarium = payPerSpecificTime($r['honorarium2'],'day') * $r['adj_sick_lve'];
		
		//VACATION LEAVE
		$adjustmentVacationLeaveBasicpay = $basicpayPerDay * $r['adj_vac_lve'];
		$adjustmentVacationLeaveCola = $colaPerDay * $r['adj_vac_lve'];
		$adjustmentVacationLeaveAllowance = payPerSpecificTime($r['allowance2'],'day') * $r['adj_vac_lve'];
		$adjustmentVacationLeaveHonorarium = payPerSpecificTime($r['honorarium2'],'day') * $r['adj_vac_lve'];
		
		//OFFICIAL LEAVE
		$adjustmentOfficialLeaveBasicpay = $basicpayPerDay * $r['adj_official_lve'];
		$adjustmentOfficialLeaveCola = $colaPerDay * $r['adj_official_lve'];
		$adjustmentOfficialLeaveAllowance = payPerSpecificTime($r['allowance2'],'day') * $r['adj_official_lve'];
		$adjustmentOfficialLeaveHonorarium = payPerSpecificTime($r['honorarium2'],'day') * $r['adj_official_lve'];		
		
		//BIRTHDAYLEAVE
		$adjustmentBirhtdayLeaveBasicpay = $basicpayPerDay * $r['adj_bday_lve'];
		$adjustmentBirhtdayLeaveCola = $colaPerDay * $r['adj_bday_lve'];
		$adjustmentBirhtdayLeaveAllowance = payPerSpecificTime($r['allowance2'],'day') * $r['adj_bday_lve'];
		$adjustmentBirhtdayLeaveHonorarium = payPerSpecificTime($r['honorarium2'],'day') * $r['adj_bday_lve'];	

		
			//Basic	Cola	Allow	Hon	SL
			
		$addjustmentBasicpay = $adjustmentUndertimeBasicpay + $adjustmentDaysworkBasicpay + $adjustmentVacationLeaveBasicpay + $adjustmentOfficialLeaveBasicpay + $adjustmentBirhtdayLeaveBasicpay;
		$adjusmentCola = $adjustmentUndertimeCola + $adjustmentDaysworkCola + $addjustmentSickLeaveCola + $adjustmentVacationLeaveCola + $adjustmentOfficialLeaveCola + $adjustmentBirhtdayLeaveCola;
		$addjustmentAllowance = $adjustmentDaysworkAllowance + $addjustmentSickLeaveAllowance + $adjustmentVacationLeaveAllowance + $adjustmentOfficialLeaveAllowance + $adjustmentBirhtdayLeaveAllowance;
		$adjustmentHonorarium = $adjustmentDaysworkHonorarium + $addjustmentSickLeaveHonorarium + $adjustmentVacationLeaveHonorarium + $adjustmentOfficialLeaveHonorarium + $adjustmentBirhtdayLeaveHonorarium;
		$adjustmentSickLeave = $addjustmentSickLeaveSL;
		$addjustmentSickLeaveLess = $basicpayPerDay * $r['sick_lve'];
		
		
		
		
		//echo $r['lastName']." --- ".$r['netBasic']."<br>";
/*		
		$Overtime
		Undertime
		Days work
		Sick leave
		Vacation leave
		Official Leave
		Birthday Leave
		DRD
		NP
*/ 
		
		$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['payOTExc'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] - $r['payUndertime'] +  $r['adj_other'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay']  +  $r['hazardPay']  +  $r['incentive'] + $r['adj_13th_mon_pay'];
		//$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['payOTExc'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'] - $r['payUndertime'] +  $r['adj_other'];
		$totalDeduction = $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['pagibigloan'] + $r['pagibigloanh'] + $r['d_unionDues'] + $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal'] + $r['financialAssistance'] + $r['pagibigSavings'];
		$netPay = $grossPay - $totalDeduction;
		if ($netPay < ($grossPay * 0.30)){
			if ($r['d_coopTotal'] > (($grossPay * 0.30) - $netPay)){
				$coopDedCurPayroll = $r['d_coopTotal'] - (($grossPay * 0.30) - $netPay);
				$updpayroll = mysql_query("UPDATE payroll SET d_coopTotal='".$coopDedCurPayroll."' WHERE empid='".$r['empid']."' && pay_period='".$_POST['PayrollCutoff']."'",$conn);
				//echo "UPDATE payroll SET d_coopTotal='".$coopDedCurPayroll."' WHERE empid='".$r['empid']."' && pay_period='".$_POST['PayrollCutoff']."'";
				$netPay += (($grossPay * 0.30) - $netPay);
				$r['d_coopTotal'] = $coopDedCurPayroll;
			}
		} 
    $var++;
    $ctr1s++;
		$ln++;
		
		// extract cola undertime on payUndertime
		$colaUndertimeAmount = $r['undertime'] != 0 ? ((($r['cola'] / $r['days_work']) / 8) * $r['undertime']) : 0;
		
		if($r['departmentName'] != $prevDepartment){
			if($ln != 1){
				//$data .= "<tr><td colspan='2'></td><td colspan='22'><hr></td></tr>";
				$data .= " <tr>
											<td colspan='2'> ".$prevDepartment." </td>
											<td align='right'>".number_format($subTotalBasicPay,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalNetBasic,2)." &nbsp; </td>
											<td align='right'>".number_format($subTotalCola,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalAllowIncen,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalIncentive,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalAllowHonorarium,2)."&nbsp; </td>
											<!-- <td align='right'>".$subTotalPayUndertime."</td> -->
											<td align='right'>".number_format($subTotalPayNightPremium,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalOvertime,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalSickLeave,2)."&nbsp; </td>
											<!-- <td align='right'>".number_format($subTotalAdjustment,2)."&nbsp; </td> -->
											<td align='right'>".number_format($subtotalHazardPay,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalGrossPay,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_whtax,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_sss,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_philhealth,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalpagibig,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalUnioMort,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_sssloan,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalpagibigloan,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_hospital,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_cashAdvance,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalCoop,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalOthers,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalTotalDed,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalNetPay,2)."&nbsp; </td>
										</tr>";
				//$data .= "<tr><td colspan='24'>&nbsp;</td></tr>";
				
				$subTotalBasicPay = 0;
				$subTotalNetBasic = 0;
				$subTotalCola = 0;
				$subTotalAllowIncen = 0;
				$subTotalIncentive=0;
				$subTotalAllowHonorarium = 0;
				$subTotalPayUndertime = 0;
				$subTotalPayNightPremium = 0;
				$subTotalOvertime = 0;
				$subTotalAdjustment = 0;
				$subtotalHazardPay = 0;
				$subTotalGrossPay = 0;
				$subTotald_whtax = 0;
				$subTotald_sss = 0;
				$subTotald_philhealth = 0;
				$subTotalpagibig = 0;
				$subTotalUnioMort = 0;
				$subTotald_sssloan = 0;
				$subTotalpagibigloan = 0;
				$subTotald_hospital = 0;
				$subTotald_cashAdvance = 0;
				$subTotalCoop = 0;
				$subTotalOthers = 0;
				$subTotalTotalDed = 0;
				$subTotalNetPay = 0;
				$subTotalSickLeave = 0;
			
			}
			//$data .= " <tr><td colspan='24' align='left' style='font-size:11px;'>".$r['departmentName']."</td></tr>";
		}		
    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		/*
    $data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 							<td align='left'>".getID($r['employmentStatus'],$r['employeeNo'])."&nbsp; </td>
					      <td align='left'>".$r['lastName'].", ".$r['firstName']."&nbsp; </td>
					      <td>".number_format($r['basicpay'],2)."&nbsp; </td>
						   	<td>".number_format(($r['netBasic'] - $r['payUndertime']),2)."&nbsp; </td>
					      <td>".number_format($r['cola'],2)."</td>
								<td>".number_format($r['allowance'],2)."&nbsp; </td>
								<td>".number_format($r['honorarium'],2)."&nbsp; </td>
								<!-- <td>".$r['payUndertime']."</td> -->
								<td>".number_format($r['payNightPremium'],2)."&nbsp; </td>
								<td>".number_format(($r['payOTReg'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payDutyRd']),2)."&nbsp; </td>
								<td>".number_format(($r['oth_income'] + $r['onCallOvertime'] + $r['adj_other']),2)."&nbsp; </td>
								<td>".number_format($r['hazardPay'],2)."&nbsp; </td>	
								<td>".number_format($grossPay,2)."&nbsp; </td>
								<td>".number_format($r['d_whtax'],2)."&nbsp; </td>
								<td>".number_format($r['d_sss'],2)."&nbsp; </td>
								<td>".number_format($r['d_philhealth'],2)."&nbsp; </td>
								<td>".number_format($r['pagibig'],2)."&nbsp; </td>
								<td>".number_format(($r['d_unionDues'] + $r['d_mortuary']),2)."&nbsp; </td>
								<td>".number_format($r['d_sssloan'],2)."&nbsp; </td>
								<td>".number_format($r['pagibigloan'],2)."&nbsp; </td>
								<td>".number_format($r['d_hospital'],2)."&nbsp; </td>
								<td>".number_format($r['d_cashAdvance'],2)."&nbsp; </td>
								<td>".number_format($r['d_coopTotal'],2)."&nbsp; </td>
								<td>".number_format(($r['d_other'] + $r['d_pnb'] + $r['d_parkingFee'] + $r['financialAssistance']),2)."&nbsp; </td>
								<td>".number_format($totalDeduction,2)."&nbsp; </td>
								<td>".number_format($netPay,2)."</td>
				     </tr>";
		*/				 
		
		
		$prevDepartment = $r['departmentName'];
		$subTotalBasicPay += $r['basicpay'];
		//$subTotalNetBasic += $r['netBasic']  - $r['payUndertime'] + $colaUndertimeAmount;
		$subTotalNetBasic += $r['netBasic']  - $r['payUndertime'] + $colaUndertimeAmount + $addjustmentBasicpay - $addjustmentSickLeaveLess;
		$subTotalCola += $r['cola'] - $colaUndertimeAmount + $adjusmentCola;
		$subTotalAllowIncen += $r['allowance'] + $addjustmentAllowance;
		$subTotalIncentive += $r['incentive'];
		$subTotalAllowHonorarium += $r['honorarium'] + $adjustmentHonorarium;
		$subTotalSickLeave += $adjustmentSickLeave;
		//$subTotalPayUndertime += $r['payUndertime'];
		$subTotalPayNightPremium += $r['payNightPremium'] + (($basicpayPerHour * .10) * $r['adj_night_prem']);
		$subTotalOvertime += $r['payOTExc'] + $r['payOTReg'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payDutyRd'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay']  + $r['onCallOvertime'] + $adjustmentOvertimeBasipay;
		//$subTotalAdjustment += $r['oth_income']  + $r['onCallOvertime']  + $r['adj_other'];
		$subTotalAdjustment += $r['oth_income']  + $r['adj_other'];
		$subtotalHazardPay += $r['hazardPay'];
		$subTotalGrossPay += $grossPay;
		$subTotald_whtax += $r['d_whtax'];
		$subTotald_sss += $r['d_sss'];
		$subTotald_philhealth += $r['d_philhealth'];
		$subTotalpagibig += $r['pagibig'] + $r['pagibigSavings'];
		$subTotalUnioMort += $r['d_unionDues'] + $r['d_mortuary'];
		$subTotald_sssloan += $r['d_sssloan'];
		$subTotalpagibigloan += $r['pagibigloan'];
		$subTotald_hospital += $r['d_hospital'];
		$subTotald_cashAdvance += $r['d_cashAdvance'];
		$subTotalCoop += $r['d_coopTotal'];
		$subTotalOthers += ($r['d_other'] + $r['d_pnb'] + $r['d_parkingFee'] + $r['financialAssistance']);
		$subTotalTotalDed += $totalDeduction;
		$subTotalNetPay += $netPay;
		
		//GRAND TOTAL
		$grandTotalBasicPay += $r['basicpay'];
		$grandTotalNetBasic += $r['netBasic'] - $r['payUndertime'] + $colaUndertimeAmount + $addjustmentBasicpay - $addjustmentSickLeaveLess;
		$grandTotalCola += $r['cola'] - $colaUndertimeAmount + $adjusmentCola;
		$grandTotalAllowIncen += $r['allowance'] + $addjustmentAllowance;
		$grandTotalIncentive += $r['incentive'];
		$grandTotalAllowHonorarium += $r['honorarium'] + $adjustmentHonorarium;
		$grandTotalSickLeave += $adjustmentSickLeave;
		//$grandTotalPayUndertime += $r['payUndertime'];
		$grandTotalPayNightPremium += $r['payNightPremium'] + (($basicpayPerHour * .10) * $r['adj_night_prem']);
		$grandTotalOvertime += $r['payOTExc'] + $r['payOTReg'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payDutyRd'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'] + $r['onCallOvertime'] + $adjustmentOvertimeBasipay;
		$grandTotalAdjustment += $r['oth_income'] + $r['onCallOvertime'] + $r['adj_other'] + $r['adj_13th_mon_pay'];
		$grandTotalHazardPay += $r['hazardPay'];
		$grandTotalGrossPay += $grossPay;
		$grandTotald_whtax += $r['d_whtax'];
		$grandTotald_sss += $r['d_sss'];
		$grandTotald_philhealth += $r['d_philhealth'];
		$grandTotalpagibig += $r['pagibig'] + $r['pagibigSavings'];
		$grandTotalUnioMort += $r['d_unionDues'] + $r['d_mortuary'];
		$grandTotald_sssloan += $r['d_sssloan'];
		$grandTotalpagibigloan += $r['pagibigloan'];
		$grandTotald_hospital += $r['d_hospital'];
		$grandTotald_cashAdvance += $r['d_cashAdvance'];
		$grandTotalCoop += $r['d_coopTotal'];
		$grandTotalOthers += ($r['d_other'] + $r['d_pnb'] + $r['d_parkingFee'] + $r['financialAssistance']);
		$grandTotalTotalDed += $totalDeduction;
		$grandTotalNetPay += $netPay;
		
		if ($ln == $rowCount){
			//$data .= "<tr><td colspan='2'></td><td colspan='22'><hr></td></tr>";
				$data .= " <tr>
											<td colspan='2'> ".$r['departmentName']."</td>
											<td align='right'>".number_format($subTotalBasicPay,2)." &nbsp; </td>
											<td align='right'>".number_format($subTotalNetBasic,2)." &nbsp; </td>
											<td align='right'>".number_format($subTotalCola,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalAllowIncen,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalIncentive,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalAllowHonorarium,2)."&nbsp; </td>
											<!-- <td align='right'>".number_format($subTotalPayUndertime,2)."&nbsp; </td> -->
											<td align='right'>".number_format($subTotalPayNightPremium,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalOvertime,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalSickLeave,2)."&nbsp; </td>
											<!-- <td align='right'>".number_format($subTotalAdjustment,2)."&nbsp; </td> -->
											<td align='right'>".number_format($subtotalHazardPay,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalGrossPay,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_whtax,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_sss,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_philhealth,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalpagibig,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalUnioMort,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_sssloan,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalpagibigloan,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_hospital,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_cashAdvance,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalCoop,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalOthers,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalTotalDed,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalNetPay,2)."&nbsp; </td>
										</tr>";
				$data .= "<tr><td colspan='24'>&nbsp;</td></tr>";
				$data .= " <tr>
											<td colspan='2'> Grand Total</td>
											<td align='right'>".number_format($grandTotalBasicPay,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalNetBasic,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalCola,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalAllowIncen,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalIncentive,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalAllowHonorarium,2)."&nbsp; </td>
											<!-- <td align='right'>".number_format($grandTotalPayUndertime,2)."&nbsp; </td> -->
											<td align='right'>".number_format($grandTotalPayNightPremium,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalOvertime,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalSickLeave,2)."&nbsp; </td>
											<!-- <td align='right'>".number_format($grandTotalAdjustment,2)."&nbsp; </td> -->
											<td align='right'>".number_format($grandTotalHazardPay,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalGrossPay,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotald_whtax,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotald_sss,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotald_philhealth,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalpagibig,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalUnioMort,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotald_sssloan,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalpagibigloan,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotald_hospital,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotald_cashAdvance,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalCoop,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalOthers,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalTotalDed,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalNetPay,2)."&nbsp; </td>
										</tr>";
		}
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
	       <td colspan="26" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>PAYROLL ENTRY REPORTS<br /><?php echo $reportTitle;?><br>
				 				<?php echo date('F d, Y',strtotime($cutoffDatestart['cutoffDateStart']))." to ".date('F d, Y',strtotime($cutoffDateend['cutoffDateEnd']));?> </td>
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
				<td>NP</td>
				<td>OT</td>
				<td>SL</td>
				<td>H PAY</td>
				<td>GROSS <br />PAY</td>
				<td>W/TAX</td>
				<td>SSS</td>
				<td>PHIC</td>
				<td>PAGIBIG</td>
				<td>UNIO/MOR</td>
				<td>SSS LOAN</td>
				<td>HDMF LOAN</td>
				<td>HOSP</td>
				<td>CA</td>
				<td>COOP</td>
				<td>OTHERS</td>
				<td>TOTAL <br>DED</td>
				<td>NET PAY</td>
	  </tr>
	  <tr><td colspan="26"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




