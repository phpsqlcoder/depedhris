<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");

$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid, p.*
								FROM employee e 
									LEFT JOIN payroll p ON p.empid = e.ndex 
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE e.isActive='1' && p.pay_period='".$_POST['PayrollCutoff']."' && e.residencyTrainingProgram='' ";
										
$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));												
if ($_POST['mbtcCompany'] == 1){
	$sql .= " && e.level IN (0)";
	$reportTitle = 'CONTRACTUAL';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && e.level IN (1,2)";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && e.level IN (3,4,5)";
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

$exec=mysql_query($sql);
$var=0;
$ln = 0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
								
		$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payOTExc']  +  $r['adj_other'];
		$totalDeduction = $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['pagibigloan'] + $r['pagibigloanh'] + $r['d_unionDues'] + $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal'] + $r['financialAssistance'];
		$netPay = $grossPay - $totalDeduction;

    $data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 							<td align='left'>".getID($r['employmentStatus'],$r['employeeNo'])."&nbsp; </td>
					      <td align='left'>".$r['lastName'].", ".$r['firstName']."&nbsp; </td>
					      <td>".number_format($r['basicpay'],2)."&nbsp; </td>
						   	<td>".number_format(($r['netBasic'] - $r['payUndertime']),2)."&nbsp; </td>
					      <td>".number_format($r['cola'],2)."</td>
								<td>".number_format($r['allowance'],2)."&nbsp; </td>
								<td>".number_format($r['incentive'],2)."&nbsp; </td>
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
								<td>".number_format($r['d_other'],2)."&nbsp; </td>
								<td>".number_format($totalDeduction,2)."&nbsp; </td>
								<td>".number_format($netPay,2)."</td>
				     </tr>";
						 
						 $totalIncome = $r['netBasic'] + $r['cola'];
?>
			<table cellspacing="3" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0">
			  <tr>
					<td width="200">&nbsp;</td>
			    <td  width="500" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>PAYSLIP <br>
						 				Payroll Period: <?php echo date('M d, Y',strtotime($cutoffDate['cutoffDateStart']))." to ".date('M d, Y',strtotime($cutoffDate['cutoffDateEnd']));?>
					</td>
					<td width="20">:<br>:<br>:</td>
					<td width="250">&nbsp;</td>
			  </tr>
				<tr valign="TOP">
					<td colspan="2">
						EMPNO: <?php echo $r['employeeNo'];?> <br>
						NAME: <?php echo $r['lastName'].", ".$r['firstName'];?>
					</td>
					<td rowspan="3">:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:</td>
					<td rowspan="3">
						<table cellpadding="3" cellspacing="0" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0">
							<tr><td>OVERTIME</td><td>HOURS</td><td>OT AMOUNT</td></tr>	
							<tr align="right"><td>30% = </td><td>99.99 hrs</td><td>9,999.99</td></tr>	
							<tr align="right"><td>100% = </td><td>99.99 hrs</td><td>9,999.99</td></tr>	
							<tr align="right"><td>125% = </td><td>99.99 hrs</td><td>9,999.99</td></tr>	
							<tr align="right"><td>130% = </td><td>99.99 hrs</td><td>9,999.99</td></tr>	
							<tr align="right"><td>&nbsp;</td><td>------------</td><td>------------</td></tr>	
							<tr align="right"><td>&nbsp;</td><td>999.99 hrs</td><td>99,999.99</td></tr>
							<tr align="right"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
							<tr><td>Excess</td><td>HOURS</td><td>OT AMOUNT</td></tr>	
							<tr align="right"><td>30% = </td><td>99.99 hrs</td><td>9,999.99</td></tr>	
							<tr align="right"><td>100% = </td><td>99.99 hrs</td><td>9,999.99</td></tr>	
							<tr align="right"><td>125% = </td><td>99.99 hrs</td><td>9,999.99</td></tr>	
							<tr align="right"><td>130% = </td><td>99.99 hrs</td><td>9,999.99</td></tr>
							<tr align="right"><td>&nbsp;</td><td>------------</td><td>------------</td></tr>		
							<tr align="right"><td>&nbsp;</td><td>999.99 hrs</td><td>99,999.99</td></tr>	
						</table>
					</td>
			  </tr>
				<tr valign="TOP">
					<td colspan="2">
							<table cellpadding="3" cellspacing="0" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0">
								<tr>
									<td>EARNINGS:</td>
									<td></td>
									<td></td>
									<td></td>
									<td rowspan="15" width="5"></td>
									<td>DEDUCTIONS:</td>
									<td></td>
									<td></td>
									<td></td>
								</tr>	
								<tr><td>Net Basic</td><td>=</td><td align="right"><?php echo number_format($r['netBasic'],2);?></td><td>Rate=********</td><td>W/TAX</td><td>=</td><td align="right"><?php echo number_format($r['d_whtax'],2);?></td><td rowspan="14" width="5"></td><td>SSS LOAN</td><td>=</td><td align="right"><?php echo number_format($r['d_whtax'],2);?></td></tr>	
								<tr><td>Cola</td><td>=</td><td align="right"><?php echo number_format($r['cola'],2);?></td><td>Days=<?php echo number_format($r['days_work'],2);?>dys</td><td>MED PREM</td><td>=</td><td align="right"><?php echo number_format($r['d_philhealth'],2);?></td><td>PAG PREM</td><td>=</td><td align="right"><?php echo number_format($r['pagibig'],2);?></td></tr>	
								<tr><td>Adjustment</td><td>=</td><td align="right"><?php echo number_format($r['adj_other'],2);?></td><td>Tardi=<?php echo number_format($r['undertime'],2);?>hrs</td><td>SSS PREM</td><td>=</td><td align="right"><?php echo number_format($r['d_sss'],2);?></td><td>PBIG LOAN</td><td>=</td><td align="right"><?php echo number_format($r['pagibigloan'],2);?></td></tr>	
								<tr><td>Overtime</td><td>=</td><td align="right"><?php echo number_format(($r['payOTReg'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payDutyRd']),2);?></td><td>&nbsp;</td><td>Hospital</td><td>=</td><td align="right"><?php echo number_format($r['d_hospital'],2);?></td><td>CASH ADV</td><td>=</td><td align="right"><?php echo number_format($r['d_cashAdvance'],2);?></td></tr>	
								<tr><td>Night Prem</td><td>=</td><td align="right"><?php echo number_format($r['payNightPremium'],2);?></td><td>&nbsp;</td><td>UNION</td><td>=</td><td align="right"><?php echo number_format($r['d_unionDues'],2);?></td><td>Parking Fee</td><td>=</td><td align="right"><?php echo number_format($r['d_parkingFee'],2);?></td></tr>	
								<tr><td>Honorarium</td><td>=</td><td align="right"><?php echo number_format($r['incentive'],2);?></td><td>&nbsp;</td><td>MORTUARY</td><td>=</td><td align="right"><?php echo number_format($r['d_mortuary'],2);?></td><td>Other Ded</td><td>=</td><td align="right"><?php echo number_format($r['d_other'],2);?></td></tr>	
								<tr><td>Allowance</td><td>=</td><td align="right"><?php echo number_format($r['allowance'],2);?></td><td>&nbsp;</td><td>DDCOOP</td><td>=</td><td align="right"><?php echo number_format($r['d_coopTotal'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td align="right">&nbsp;</td></tr>	
								<tr><td>HazardPay</td><td>=</td><td align="right"><?php echo number_format($r['hazardPay'],2);?></td><td>&nbsp;</td><td>Undertime</td><td>=</td><td align="right"><?php echo number_format($r['payUndertime'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								<tr><td>OnCall OT</td><td>=</td><td align="right"><?php echo number_format($r['onCallOvertime'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								<tr><td>Other Inc</td><td>=</td><td align="right"><?php echo number_format($r['oth_income'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								<tr><td>&nbsp;</td><td>&nbsp;</td><td>-----------------</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								<tr><td>Gross Pay</td><td>=></td><td align="right"><?php echo number_format($grossPay,2);?></td><td>&nbsp;</td><td>Total Ded</td><td>=</td><td align="right"><?php echo number_format($totalDeduction,2);?></td><td>Net Pay</td><td>=</td><td align="right"><?php echo number_format($netPay,2);?></td></tr>	
							</table>
					</td>
					</td>
					<td>
					</td>
			  </tr>
      </table>
<?php			
}
?>
 


