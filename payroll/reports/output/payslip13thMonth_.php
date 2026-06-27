<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");

//echo $_POST['PayrollCutoff'];
//$_POST['PayrollCutoff'] ='2013-01-15';
//$_POST['mbtcCompany'] =3;
$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid , p.*
								FROM payroll_13thmonth p 
									LEFT JOIN employee e ON p.empid = e.ndex 
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE p.dyt='".$_POST['PayrollCutoff']."' && e.residencyTrainingProgram='' ";
										
//$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']."'",$conn));												
if ($_POST['employeeId']){
	$sql .= " && e.ndex='".$_POST['employeeId']."'";
} elseif ($_POST['mbtcCompany'] == 1){
	$sql .= " && e.isActive='1' && e.level IN (0)";
	$reportTitle = 'CONTRACTUAL';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && e.isActive='1' && e.level IN (1,2)";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && e.isActive='1' && e.level IN (3,4,5,6,7,8,9)";
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
$sql.=" ORDER BY  e.employeeNo, d.name, e.lastName,e.firstName";

//echo $sql;
//echo date('Y',strtotime($_POST['PayrollCutoff']));
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){

		//$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg']  + $r['payOTExc'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] - $r['payUndertime'] +  $r['adj_other'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'];
		//$totalDeduction = $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['pagibigloan'] + $r['pagibigloanh'] + $r['d_unionDues'] + $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal'] + $r['financialAssistance'] + $r['pagibigSavings'];
		//$netPay = $grossPay - $totalDeduction;
		
		if (date('M',strtotime($_POST['PayrollCutoff'])) == 'May'){
			$rPayroll = mysql_fetch_assoc(mysql_query("SELECT basicpay AS basicPay, pay_type FROM `payroll` WHERE pay_period <= '".date('Y',strtotime($_POST['PayrollCutoff']))."-05-31' && `empid` = '".$r['empid']."' order by pay_period DESC limit 1"));
			//echo "SELECT basicpay AS basicPay, pay_type FROM `payroll` WHERE pay_period = '".date('Y',strtotime($_POST['PayrollCutoff']))."-05-31' && `empid` = '".$r['empid']."'";
		} else {
			$rPayroll = mysql_fetch_assoc(mysql_query("SELECT basicpay AS basicPay, pay_type FROM `payroll` WHERE pay_period <= '".date('Y',strtotime($_POST['PayrollCutoff']))."-12-31' && `empid` = '".$r['empid']."' order by pay_period DESC limit 1"));
			$sumJanToNov = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic) netBasic FROM `payroll` WHERE pay_period >='".date('Y',strtotime($_POST['PayrollCutoff']))."-01-01' pay_period <= '".date('Y',strtotime($_POST['PayrollCutoff']))."-12-01' && `empid` = '".$r['empid']."'"));
			$rPayrollMay = mysql_fetch_assoc(mysql_query("SELECT basicpay AS basicPay, pay_type FROM `payroll` WHERE pay_period <= '".date('Y',strtotime($_POST['PayrollCutoff']))."-05-31' && `empid` = '".$r['empid']."' order by pay_period DESC limit 1"));
		}

		if (date('M',strtotime($_POST['PayrollCutoff'])) == 'May'){
			if ($rPayroll['pay_type'] != '1'){
				//Daily
				$basicpay = $rPayroll['basicPay'];
				$a13thMonth = (($basicpay * 365) /12) / 2;
			} elseif ($rPayroll['pay_type'] == '1') {
				//Monthly
				$basicpay = $rPayroll['basicPay'];
				$a13thMonth = $basicpay / 2;
			}
		} else {
			if ($rPayroll['pay_type'] != '1'){
				//Daily
				$basicpay = ($rPayroll['basicPay'] * 365) / 12;
				$basicpayMay = $rPayrollMay['basicPay'];
				$a13thMonthMay = (($basicpayMay * 365) /12) / 2;
			} elseif ($rPayroll['pay_type'] == '1') {
				//Monthly
				$basicpay = $rPayroll['basicPay'];
				$basicpayMay = $rPayrollMay['basicPay'];
				$a13thMonthMay = $basicpayMay / 2;
			}
			$a13thMonth = (($sumJanToNov['netBasic'] + $basicpay) / 12) - $a13thMonthMay;
		}

		$grossPay = $a13thMonth;
		$totalDeduction = $r['deduction'];
		$netPay = $grossPay - $totalDeduction;
		
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
			<table cellspacing="1" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%">
			  <tr>
					<td width="200">&nbsp;</td>
			    <td  width="700" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>PAYSLIP 13TH MONTH <br>
						 				COVERAGE: <?php echo date('M, Y',strtotime($_POST['PayrollCutoff']));?>
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
					<td>
					</td>
			  </tr>
				<tr valign="TOP">
					<td colspan="2">
							<table cellpadding="1" cellspacing="0" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%"0>
								<tr>
									<td>EARNINGS:</td>
									<td></td>
									<td></td>
									<td></td>
									<td rowspan="15" width="5"></td>
									<td>DEDUCTIONS:</td>
									<td></td>
									<td with="125"></td>
									<td></td>
								</tr>	
								<tr><td>Net Basic</td><td>=</td><td align="right"><?php echo number_format($r['netBasic'],2);?></td><td>Rate= <?php echo number_format($basicpay,2)?></td><td>W/TAX</td><td>=</td><td align="right"><?php echo number_format($r['d_whtax'],2);?></td><td rowspan="14" width="5"></td><td>SSS LOAN</td><td>=</td><td align="right"><?php echo number_format($r['d_sssloan'],2);?></td></tr>	
								<tr><td>13th Mo.</td><td>=</td><td align="right"><?php echo number_format($a13thMonth,2);?></td><td><?php echo $dayReflect;?></td><td>PHIC</td><td>=</td><td align="right"><?php echo number_format($r['d_philhealth'],2);?></td><td>PAG PREM</td><td>=</td><td align="right"><?php echo number_format($r['pagibig'] + $r['pagibigSavings'],2);?></td></tr>	
								<tr><td>Adjustment</td><td>=</td><td align="right"><?php echo number_format($r['adj_other'],2);?></td><td>U.T= <?php echo number_format($r['undertime'],2);?>hrs</td><td>SSS PREM</td><td>=</td><td align="right"><?php echo number_format($r['d_sss'],2);?></td><td>PBIG Salary LOAN</td><td>=</td><td align="right"><?php echo number_format($r['pagibigloan'],2);?></td></tr>	
								<tr><td>Overtime</td><td>=</td><td align="right"><?php echo number_format(($r['payOTReg'] + $r['payOTExc'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay']),2);?></td><td>&nbsp;</td><td>Hospital</td><td>=</td><td align="right"><?php echo number_format($r['d_hospital'],2);?></td><td>PBIG Housing LOAN</td><td>=</td><td align="right"><?php echo number_format($r['pagibigloanh'],2);?></td></tr>
								<tr><td>Night Prem</td><td>=</td><td align="right"><?php echo number_format($r['payNightPremium'],2);?></td><td>&nbsp;</td><td>UNION</td><td>=</td><td align="right"><?php echo number_format($r['d_unionDues'],2);?></td><td>Parking Fee</td><td>=</td><td align="right"><?php echo number_format($r['d_parkingFee'],2);?></td></tr>	
								<tr><td>Honorarium</td><td>=</td><td align="right"><?php echo number_format($r['honorarium'],2);?></td><td>&nbsp;</td><td>MORTUARY</td><td>=</td><td align="right"><?php echo number_format($r['d_mortuary'],2);?></td><td>Other Ded</td><td>=</td><td align="right"><?php echo number_format($r['d_other'],2);?></td></tr>	
								<tr><td>Allowance</td><td>=</td><td align="right"><?php echo number_format($r['allowance'],2);?></td><td>&nbsp;</td><td>13th Month MAY</td><td>=</td><td align="right"><?php echo number_format($a13thMonthMay,2);?></td><td>PNB Loan</td><td>=</td><td align="right"><?php echo number_format($r['d_pnb'],2);?></td></tr>	
								<tr><td>HazardPay</td><td>=</td><td align="right"><?php echo number_format($r['hazardPay'],2);?></td><td>&nbsp;</td><td>HOSP. BILL</td><td>=</td><td align="right"><?php echo number_format($r['deduction'],2);?></td><td>Financial Assistance</td><td>=</td><td align="right"><?php echo number_format($r['financialAssistance'],2);?></td></tr>	
								<tr><td>OnCall OT</td><td>=</td><td align="right"><?php echo number_format($r['onCallOvertime'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>CASH ADV</td><td>=</td><td align="right"><?php echo number_format($r['d_cashAdvance'],2);?></td></tr>	
								
								<tr><td>SH Prem</td><td>=</td><td align="right"><?php echo number_format($r['paySpHoliday'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								<tr><td>LH Prem</td><td>=</td><td align="right"><?php echo number_format($r['payLHoliday'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								<tr><td>DRD Prem</td><td>=</td><td align="right"><?php echo number_format($r['payDutyRd'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								
								<tr><td>Other Inc</td><td>=</td><td align="right"><?php echo number_format($r['oth_income'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="right">-----------------</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								<tr><td>Gross Pay</td><td>=></td><td align="right"><?php echo number_format($grossPay,2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>Total Ded</td><td>=</td><td align="right"><?php echo number_format($totalDeduction + $r['payUndertime'],2);?></td><td>&nbsp;</td><td>Net Pay</td><td>=</td><td align="right"><?php echo number_format($netPay,2);?></td></tr>	
							</table>
					</td>
					</td>
					<td>
						<table cellpadding="3" cellspacing="0" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0">
							<tr>
								<td width="150">OT Reg = </td>
								<td align="right"><?php echo $r['ot_reg'];?></td>
							</tr>	
							<tr>
								<td>OT Exc = </td>
								<td align="right"><?php echo $r['ot_exc'];?></td>
							</tr>	
							<tr>
								<td>OT SHoliday  = </td>
								<td align="right"><?php echo $r['otSHoliday'];?></td>
							</tr>	
							<tr>
								<td>OT LHoliday  = </td>
								<td align="right"><?php echo $r['otLHoliday'];?></td>
							</tr>	
							<tr>
								<td>Duty SH = </td>
								<td align="right"><?php echo $r['spholiday'];?></td>
							</tr>	
							<tr>
								<td>Duty LH = </td>
								<td align="right"><?php echo $r['lholiday'];?></td>
							</tr>	
							
							<tr>
								<td> Duty Rest Day = </td>
								<td align="right"><?php echo $r['duty_rd'];?></td>
							</tr>	
							<tr>
								<td> Night Prem = </td>
								<td align="right"><?php echo $r['night_prem'];?></td>
							</tr>	
							<tr>
								<td> OT Restday<br> SHol = </td>
								<td align="right"><?php echo $r['otRDSHoliday'];?></td>
							</tr>	
							<tr>
								<td>  OT Restday<br> LHol = </td>
								<td align="right"><?php echo $r['otRDLHoliday'];?></td>
							</tr>
							<tr>
								<td> OT Restday = </td>
								<td align="right"><?php echo $r['otRestDay'];?></td>
							</tr>		
						</table>
					</td>
			  </tr>
			  <tr><td colspan="15"><br><br><br><hr><br><br><br></td></tr>
				<tr><td><br style="font-size:5px"></td></tr>
      </table>
<?php			
}
?>
 
</body>

</html>


