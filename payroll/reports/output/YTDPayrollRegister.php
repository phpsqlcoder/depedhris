<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");


//echo $_POST['PayrollYear'];

$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, e.ndex employeeIdNo
								FROM employee e 
									LEFT JOIN employee_compensation c on e.ndex=c.employeeId
									LEFT JOIN payroll p ON p.empid = e.ndex 
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE DATE_FORMAT(p.pay_period,'%Y')='".$_POST['PayrollYear']."'
											 
											";

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

$sql.=" GROUP BY e.ndex, e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name
				ORDER BY  d.name, e.lastName,e.firstName";
//echo $sql;
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$countPerDepartment = 0;
$rowCount = mysql_num_rows($exec);
while($rs=mysql_fetch_assoc($exec)){
	//echo $rs['lastName'];
	$employeeInfo = mysql_fetch_assoc(mysql_query("SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, e.level, d.name departmentName 
																		FROM employee e 
																		  	LEFT JOIN dept d ON d.ndex = e.deptId
																				WHERE e.ndex='".$rs['employeeIdNo']."'"));
																				
	$r = mysql_fetch_assoc(mysql_query("SELECT
																SUM(netBasic) netBasic,
																SUM(cola) cola,
																SUM(allowance) allowance,
																SUM(honorarium) honorarium,
																SUM(payNightPremium) payNightPremium,
																SUM(payOTReg) payOTReg,
																SUM(payOTExc) payOTExc,
																SUM(oth_income) oth_income,
																SUM(onCallOvertime) onCallOvertime,
																SUM(payDutyRd) payDutyRd,
																SUM(paySpHoliday) paySpHoliday,
																SUM(payLHoliday) payLHoliday,
																SUM(otRDLHolidayPay) otRDLHolidayPay,
																SUM(otRDSHolidayPay) otRDSHolidayPay,
																SUM(otLHolidayPay) otLHolidayPay,
																SUM(otSHolidayPay) otSHolidayPay,
																SUM(otRestDayPay) otRestDayPay,
																SUM(payUndertime) payUndertime,
																SUM(adj_other) adj_other,
																SUM(hazardPay) hazardPay,
																SUM(incentive) incentive,
																SUM(d_pnb) d_pnb,
																SUM(d_parkingFee) d_parkingFee,
																SUM(d_whtax) d_whtax,
																SUM(d_sss) d_sss,
																SUM(d_philhealth) d_philhealth,
																SUM(pagibig) pagibig,
																SUM(pagibigloan) pagibigloan,
																SUM(pagibigloanh) pagibigloanh,
																SUM(d_unionDues) d_unionDues,
																SUM(d_mortuary) d_mortuary,
																SUM(d_sssloan) d_sssloan,
																SUM(d_hospital) d_hospital,
																SUM(d_cashAdvance) d_cashAdvance,
																SUM(d_other) d_other,
																SUM(d_coopTotal) d_coopTotal,
																SUM(financialAssistance) financialAssistance,
																SUM(pagibigSavings) pagibigSavings,
																SUM(undertime) undertime,
																SUM(d_other) d_other,
																SUM(days_work) days_work	
																 FROM payroll 
																	 WHERE DATE_FORMAT(pay_period,'%Y') = '".$_POST['PayrollYear']."' && empid='".$rs['employeeIdNo']."'"));
															 
																	 
	$grossPay += $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['payOTExc'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'] - $r['payUndertime'] +  $r['adj_other']  +  $r['hazardPay']  +  $r['incentive'];
	
	$totalDeduction += $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['pagibigloan'] + $r['pagibigloanh'] + $r['d_unionDues'] 
	+ $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal'] + $r['financialAssistance'] + $r['pagibigSavings'];
		
	$netPay = $grossPay - $totalDeduction;
	$thirthyPercentOfGross = ($grossPay * 0.30);
														 
	$grostemp = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['payOTExc'] + $r['oth_income'] + $r['onCallOvertime']
	 + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'] - $r['payUndertime'] +  $r['adj_other']  +  $r['hazardPay']  +  $r['incentive'];
	$totalDeduction += $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['pagibigloan'] + $r['pagibigloanh'] + $r['d_unionDues'] 
	+ $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal'] + $r['financialAssistance'] + $r['pagibigSavings'];
	$var++;
	$ctr1s++;
	$ln++;
	
	$colaUndertimeAmount = $r['undertime'] != 0 && $r['days_work'] != 0? ((($r['cola'] / $r['days_work']) / 8) * $r['undertime']) : 0;
		
	if($rs['departmentName'] != $prevDepartment){
		if($ln != 1){
			$data .= "<tr><td colspan='2'>No. of Personnel: $countPerDepartment</td><td colspan='22'><hr></td></tr>";
			$countPerDepartment = 0;
			$data .= " <tr>
										<td colspan='2'> Sub Total</td>
										<td align='right'>".number_format($subTotalBasicPay,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalNetBasic,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalCola,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalAllowIncen,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalAllowHonorarium,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalIncentive,2)."&nbsp; </td>
										<!-- <td align='right'>".$subTotalPayUndertime."</td> -->
										<td align='right'>".number_format($subTotalPayNightPremium,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalOvertime,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalAdjustment,2)."&nbsp; </td>
										<td align='right'>".number_format($subtotalHazardPay,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalgrostemp,2)."&nbsp; </td>
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
			
			$subTotalBasicPay = 0;
			$subTotalNetBasic = 0;
			$subTotalCola = 0;
			$subTotalAllowIncen = 0;
			$subTotalIncentive = 0;
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
$subTotalgrostemp = 0;


		}
		$data .= " <tr><td colspan='24' align='left' style='font-size:11px;'>".$rs['departmentName']."</td></tr>";
	}		
	$countPerDepartment++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	$data.="<tr bgcolor='".$bgclr1s."' align='right'>
						<td align='left'>".getID($rs['employmentStatus'],$rs['employeeNo'])."&nbsp; </td>
				      <td align='left'>".$rs['lastName'].", ".$rs['firstName']."&nbsp; </td>
				      <td>".number_format($r['basicpay'],2)."&nbsp; </td>
					   	<td>".number_format(($r['netBasic'] - $r['payUndertime'] + $colaUndertimeAmount),2)."&nbsp; </td>
				      <td>".number_format($r['cola'] - $colaUndertimeAmount,2)."</td>
							<td>".number_format($r['allowance'],2)."&nbsp; </td>
							<td>".number_format($r['honorarium'],2)."&nbsp; </td>
							<td>".number_format($r['incentive'],2)."&nbsp; </td>
							<!-- <td>".$r['payUndertime']."</td> -->
							<td>".number_format($r['payNightPremium'],2)."&nbsp; </td>
							<td>".number_format(($r['payOTExc'] + $r['payOTReg'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payDutyRd'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay']),2)."&nbsp; </td>
							<td>".number_format(($r['oth_income'] + $r['onCallOvertime'] + $r['adj_other']),2)."&nbsp; </td>
							<td>".number_format($r['hazardPay'],2)."&nbsp; </td>	
							<td>".number_format($grostemp,2)."&nbsp; </td>
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
							<td>".number_format($netPay,2)."</td>
			     </tr>";
	$prevDepartment = $rs['departmentName'];

	$subTotalBasicPay += $r['basicpay'];
	$subTotalNetBasic += $r['netBasic']  - $r['payUndertime'] + $colaUndertimeAmount;
	$subTotalCola += $r['cola'] - $colaUndertimeAmount;
	$subTotalAllowIncen += $r['allowance'];
	$subTotalAllowHonorarium += $r['honorarium'];
	$subTotalIncentive += $r['incentive'];
	//$subTotalPayUndertime += $r['payUndertime'];
	$subTotalPayNightPremium += $r['payNightPremium'];
	$subTotalOvertime += $r['payOTExc'] + $r['payOTReg'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payDutyRd'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'];
	$subTotalAdjustment += $r['oth_income']  + $r['onCallOvertime']  + $r['adj_other'];
	$subtotalHazardPay += $r['hazardPay'];
	$subTotalGrossPay += $grossPay;
	$subTotald_whtax += $r['d_whtax'];
	$subTotald_sss += $r['d_sss'];
	$subTotald_philhealth += $r['d_philhealth'];
	$subTotalpagibig += $r['pagibig'] + $r['pagibigSavings'];
	$subTotalUnioMort += $r['d_unionDues'] + $r['d_mortuary'];
	$subTotald_sssloan += $r['d_sssloan'];
	$subTotalpagibigloan += $r['pagibigloan'] + $r['pagibigloanh'];
	$subTotald_hospital += $r['d_hospital'];
	$subTotald_cashAdvance += $r['d_cashAdvance'];
	$subTotalCoop += $r['d_coopTotal'];
	$subTotalOthers += ($r['d_other'] + $r['d_pnb'] + $r['d_parkingFee'] + $r['financialAssistance']);
	$subTotalTotalDed += $totalDeduction;
	$subTotalNetPay += $netPay;
$subTotalgrostemp += $grostemp;

	//GRAND TOTAL
	$grandTotalBasicPay += $r['basicpay'];
	$grandTotalNetBasic += $r['netBasic'] - $r['payUndertime'] + $colaUndertimeAmount;
	$grandTotalCola += $r['cola'] - $colaUndertimeAmount;
	$grandTotalAllowIncen += $r['allowance'];
	$grandTotalAllowHonorarium += $r['honorarium'];
	$grandTotalIncentive += $r['incentive'];
	//$grandTotalPayUndertime += $r['payUndertime'];
	$grandTotalPayNightPremium += $r['payNightPremium'];
	$grandTotalOvertime += $r['payOTExc'] + $r['payOTReg'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payDutyRd']  + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'];
	$grandTotalAdjustment += $r['oth_income'] + $r['onCallOvertime']  + $r['adj_other'];
	$grandTotalHazardPay += $r['hazardPay'];
	$grandTotalGrossPay += $grossPay;
	$grandTotald_whtax += $r['d_whtax'];
	$grandTotald_sss += $r['d_sss'];
	$grandTotald_philhealth += $r['d_philhealth'];
	$grandTotalpagibig += $r['pagibig'] + $r['pagibigSavings'];
	$grandTotalUnioMort += $r['d_unionDues'] + $r['d_mortuary'];
	$grandTotald_sssloan += $r['d_sssloan'];
	$grandTotalpagibigloan += $r['pagibigloan'] + $r['pagibigloanh'];
	$grandTotald_hospital += $r['d_hospital'];
	$grandTotald_cashAdvance += $r['d_cashAdvance'];
	$grandTotalCoopA += $r['d_coopTotal'];
	$grandTotalOthers += ($r['d_other'] + $r['d_pnb'] + $r['d_parkingFee'] + $r['financialAssistance']);
	$grandTotalTotalDed += $totalDeduction;
	$grandTotalNetPay += $netPay;
$grandTotalgrostemp += $grostemp;
	$Gpersonnel += $countPerDepartment;
	
	if ($ln == $rowCount){
		//$data .= "<tr><td colspan='2'></td><td colspan='22'><hr></td></tr>";
			$data .= "<tr><td colspan='2'>No. of Personnel: $countPerDepartment</td><td colspan='22'><hr></td></tr>";
			$countPerDepartment = 0;
			$data .= " <tr>
										<td colspan='2'> Sub Total</td>
										<td align='right'>".number_format($subTotalBasicPay,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalNetBasic,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalCola,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalAllowIncen,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalAllowHonorarium,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalIncentive,2)."&nbsp; </td>
										<!-- <td align='right'>".number_format($subTotalPayUndertime,2)."&nbsp; </td> -->
										<td align='right'>".number_format($subTotalPayNightPremium,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalOvertime,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalAdjustment,2)."&nbsp; </td>
										<td align='right'>".number_format($subtotalHazardPay,2)."&nbsp; </td>
										<td align='right'>".number_format($subTotalgrostemp,2)."&nbsp;</td>
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
			$data .= "<tr><td colspan='24'>&nbsp;Total no of personnel: $ln</td></tr>";
			$data .= " <tr>
										<td colspan='2'> Grand Total</td>
										<td align='right'>".number_format($grandTotalBasicPay,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotalNetBasic,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotalCola,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotalAllowIncen,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotalAllowHonorarium,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotalIncentive,2)."&nbsp; </td>
										<!-- <td align='right'>".number_format($grandTotalPayUndertime,2)."&nbsp; </td> -->
										<td align='right'>".number_format($grandTotalPayNightPremium,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotalOvertime,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotalAdjustment,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotalHazardPay,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotalgrostemp,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotald_whtax,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotald_sss,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotald_philhealth,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotalpagibig,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotalUnioMort,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotald_sssloan,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotalpagibigloan,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotald_hospital,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotald_cashAdvance,2)."&nbsp; </td>
										<td align='right'>".number_format($grandTotalCoopA,2)."&nbsp; </td>
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
	       <td colspan="24" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>YTD PAYROLL REGISTER<br /><?php echo $reportTitle;?><br>
			 PERIOD: JANUARY 1, <?php echo $_POST['PayrollYear'];?> TO DECEMBER 31, <?php echo $_POST['PayrollYear'];?>
				 				</td>
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
				<td>NET PAY</td>
	  </tr>
	  <tr><td colspan="24"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




