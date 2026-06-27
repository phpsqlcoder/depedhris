<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");



//echo $_POST['employeeId'].";".$_POST['PayrollYear'];
if ($_POST['PayrollYear'] < date('Y')){
	$dateRange = "January 1, ".$_POST['PayrollYear']." to December 31, ".$_POST['PayrollYear'];
} else {
	$dateRange = "January 1, 2016 to current date";
}


$employeeInfo = mysql_fetch_assoc(mysql_query("SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, e.level, d.name departmentName 
																		FROM employee e 
																		  	LEFT JOIN dept d ON d.ndex = e.deptId
																				WHERE e.ndex='".$_POST['employeeId']."'"));
if ($employeeInfo['level'] == 0){
	//$sql .= " && e.isActive='1' && e.level IN (0)";
	$reportTitle = 'CONTRACTUAL';
} elseif ($employeeInfo['level'] == 1 || $employeeInfo['level'] == 2) {
	//$sql .= " && e.isActive='1' && e.level IN (1,2)";
	$reportTitle = 'RANK & FILE';
} elseif ($employeeInfo['level'] == 3 || $employeeInfo['level'] == 4 || $employeeInfo['level'] == 5) {
	//$sql .= " && e.isActive='1' && e.level IN (3,4,5)";
	$reportTitle = 'SECTION HEADS AND CONFI';
}

$sql = "SELECT payrollDate	 FROM cutoffdates 
							WHERE DATE_FORMAT(payrollDate,'%Y') = '".$_POST['PayrollYear']."'
								ORDER BY payrollDate ASC";
//echo $sql;
$exec=mysql_query($sql);
while($rs=mysql_fetch_assoc($exec)){
	$r = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".$rs['payrollDate']."' && empid='".$_POST['employeeId']."'"));
	
	$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['incentive'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['payOTExc'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['hazardPay'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'] - $r['payUndertime'] +  $r['adj_other'] + $r['adj_13th_mon_pay'];
	$totalDeduction = $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['pagibigloan'] + $r['pagibigloanh'] + $r['d_unionDues'] + $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal'] + $r['financialAssistance'] + $r['pagibigSavings'];
	$netPay = $grossPay - $totalDeduction;
	$thirthyPercentOfGross = ($grossPay * 0.30);

	
		
	$colaUndertimeAmount = $r['undertime'] != 0 && $r['days_work'] != 0? ((($r['cola'] / $r['days_work']) / 8) * $r['undertime']) : 0;
	
	$data.="<tr bgcolor='".$bgclr1s."' align='right'>
					      <td align='left'>".date('M d, Y',strtotime($rs['payrollDate']))."&nbsp; </td>
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
								<td>".number_format($netPay,2)."</td>
				     </tr>";
	$grandTotalBasicPay += $r['basicpay'];
	$grandTotalNetBasic += $r['netBasic'] - $r['payUndertime'] + $colaUndertimeAmount;
	$grandTotalCola += $r['cola'] - $colaUndertimeAmount;
	$grandTotalAllowIncen += $r['allowance'];
	$grandTotalinc += $r['incentive'];
	$grandTotalAllowHonorarium += $r['honorarium'];
	//$grandTotalPayUndertime += $r['payUndertime'];
	$grandTotalPayNightPremium += $r['payNightPremium'];
	$grandTotalOvertime += $r['payOTExc'] + $r['payOTReg'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payDutyRd']  + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'];
	$grandTotalAdjustment += $r['oth_income']  + $r['onCallOvertime']  + $r['adj_other'] + $r['adj_13th_mon_pay'];
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
	$Gpersonnel += $countPerDepartment;					  
					  
					  
}
$data .= "<tr><td colspan='2'></td><td colspan='22'><hr></td></tr>
										<tr>
											<td colspan='2'> Grand Total</td>
											<td align='right'>".number_format($grandTotalNetBasic,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalCola,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalAllowIncen,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalinc,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalAllowHonorarium,2)."&nbsp; </td>
											<!-- <td align='right'>".number_format($grandTotalPayUndertime,2)."&nbsp; </td> -->
											<td align='right'>".number_format($grandTotalPayNightPremium,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalOvertime,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalAdjustment,2)."&nbsp; </td>
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
											<td align='right'>".number_format($grandTotalCoopA,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalOthers,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalTotalDed,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalNetPay,2)."&nbsp; </td>
										</tr>";




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
       <td colspan="24" style="font-size:13px;">
			DAVAO DOCTORS HOSPITAL	<br>
			PAYROLL REGISTER	<br>
			<?php echo $dateRange;?>	<br><br><br>

		 	IDNUM:	<?php echo $employeeInfo['employeeNo'];?><br>
			NAME:		<?php echo $employeeInfo['lastName'].", ".$employeeInfo['firstName'];?><br>
			DEPARTMENT:		<?php echo $employeeInfo['departmentName'];?><br>
			CATEGORY:	<?php echo $reportTitle;?><br>
			LEVEL:	<?php echo $employeeInfo['level'];?><br>
			
		 </td>
	  </tr>

	  
	  
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
				<td>PAYROLL PERIOD</td>
	      <td>BASIC</td>
		   	<td>NETBASIC</td>
	      <td>COLA</td>
				<td>ALLOW</td>
				<td>INC</td>
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




