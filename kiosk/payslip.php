<?php
ob_start();
session_start();
if(!$_SESSION['ndex']){header("location:index.php");}
	include("../dbcon.php");
	include("../employeefunctions.php");
if($_GET['act']=='go'){
	$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid, p.*
								FROM employee e 
									LEFT JOIN payroll p ON p.empid = e.ndex 
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE e.isActive='1' && p.pay_period='".$_POST['cutoff']."' && e.ndex='".$_SESSION['ndex']."'";
										
	$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['cutoff']."'",$conn));												
	$exec=mysql_query($sql);
	$var=0;
	$ln = 0;
	$rowCount = mysql_num_rows($exec);
	$r=mysql_fetch_assoc($exec);
			$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg']  + $r['payOTExc'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] - $r['payUndertime'] +  $r['adj_other'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'] + $r['incentive'] + $r['hazardPay'] + $r['adj_13th_mon_pay'];
			$totalDeduction = $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['pagibigloan'] + $r['pagibigSavings'] + $r['d_unionDues'] + $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal'] + $r['financialAssistance'];
			$netPay = $grossPay - $totalDeduction;
						if ($r['pay_type'] != '1'){
							$dayReflect =" Days= ".number_format($r['days_work'],2)." dys";
						} elseif ($r['pay_type'] == '1') {
							$dayReflect =" abs. = ".number_format($r['days_absent'],2)." dys";
						}	 
							 $totalIncome = $r['netBasic'] + $r['cola'];
}
$rs = mysql_query("SELECT * FROM cutoffdates where isLock=1   ORDER BY payrollDate DESC",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>HRIS - Davao Doctors Hospital</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="topPans" style="display:none;"><img src="../images/kiosk.png" title="Green Solutions" alt="Davao Doctors Hospital" border="0" style="width:304px; height:100px; padding:0 0 0 6px;"/>
<img src="images/hris.png" style="width:704px; height:100px; padding:0 0 0 6px;">
</div>
<div class="logo" style="width:100%;margin-left: auto; margin-right: auto;text-align:center;">
	<a href="#">
	<img src="images/newlogo.png" alt="" style="position:relative;top:23px;"/> 
	</a>
	<div style="display:inline;font-size:60px;position:relative;top:10px;color:white;">
	| 
	</div>
	<div style=" margin-top: 5px;    font: 35px arial, sans-serif;  color:white;display:inline;font-size:50px;position:relative;top:10px;">
	 Human Resource Information System
	</div>
</div>
<div id="headerPan">
  <div id="headerPanleft">
    <div id="AA">
      <h2><a href="logout.php">Logout</a></h2>
      <p><a href="logout.php">Logout</a></p>
      <a href="logout.php">&nbsp;</a> </div>
    <div id="AB">
      <h2><a href="changepassword.php">Change<br>Password</a> </h2>
      <a href="changepassword.php">&nbsp;</a> </div>
	<div id="AC">
      <h2><a href="dtr.php">DTR</a> </h2>
      <p><a href="dtr.php">Daily Time Record</a> </p>
      <a href="dtr.php">&nbsp;</a> </div>
	<div id="AD">
      <h2><a href="payslip.php">Pay Slip</a> </h2>
      <a href="payslip.php">&nbsp;</a> </div>
	<div id="AF">
      <h2><a href="editemployee.php">Update 201</a> </h2>
      <a href="editemployee.php">&nbsp;</a> </div>
 </div>
</div>
<div id="bodyPan">
	<table width="100%">
		<tr><td align="center" style="color:maroon;"><h1>Pay Slip</h1></td></tr>
		<tr><td height="10" align="center">&nbsp;<font style="color:red;font-size:40px;font-weight:bold;"><?php echo $msg;?></font></td></tr>
	<tr>
		<td align="center">
		<form method="post" action="payslip.php?act=go" name="frmpayslip">
				<table style="font-family:Arial Rounded MT Bold;font-size:35px;color:maroon;">
					<tr><td><select name="cutoff" onchange="document.frmpayslip.submit();"><option value="0" selected="selected">- Select Cutoff -<?php echo $optionSelectPayrollCutoffDate; ?></select></td></tr>
				</table>
				</form>
			<div style=" -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;width:800px;background-color:#FFF;">
				
				<?php if($_GET['act']=='go'){?>
				
				<table cellspacing="1" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%">
			  <tr>
					<td width="200">&nbsp;</td>
			    <td  width="700" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>PAYSLIP <br>
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
					<td>
					<!-- 	<table cellpadding="3" cellspacing="0" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0">
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
						</table> -->
						
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
								<tr><td>Net Basic</td><td>=</td><td align="right"><?php echo number_format($r['netBasic'],2);?></td><td>Rate= <?php echo number_format($r['basicpay'],2)?></td><td>W/TAX</td><td>=</td><td align="right"><?php echo number_format($r['d_whtax'],2);?></td><td rowspan="14" width="5"></td><td>SSS LOAN</td><td>=</td><td align="right"><?php echo number_format($r['d_sssloan'],2);?></td></tr>	
								<tr><td>Cola</td><td>=</td><td align="right"><?php echo number_format($r['cola'],2);?></td><td><?php echo $dayReflect;?></td><td>PHIC</td><td>=</td><td align="right"><?php echo number_format($r['d_philhealth'],2);?></td><td>PAG PREM</td><td>=</td><td align="right"><?php $pag_total=$r['pagibig'] + $r['pagibigSavings']; echo number_format($pag_total,2);?></td></tr>	
								<tr><td>Adjustment</td><td>=</td><td align="right"><?php echo number_format($r['adj_other'],2);?></td><td>U.T= <?php echo number_format($r['undertime'],2);?>hrs</td><td>SSS PREM</td><td>=</td><td align="right"><?php echo number_format($r['d_sss'],2);?></td><td>PBIG Salary LOAN</td><td>=</td><td align="right"><?php echo number_format($r['pagibigloan'],2);?></td></tr>	
								<tr><td>Overtime</td><td>=</td><td align="right"><?php echo number_format(($r['payOTReg'] + $r['payOTExc'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay']),2);?></td><td>&nbsp;</td><td>Hospital</td><td>=</td><td align="right"><?php echo number_format($r['d_hospital'],2);?></td><td>PBIG Housing LOAN</td><td>=</td><td align="right"><?php echo number_format($r['pagibigloanh'],2);?></td></tr>
								<tr><td>Night Prem</td><td>=</td><td align="right"><?php echo number_format($r['payNightPremium'],2);?></td><td>&nbsp;</td><td>UNION</td><td>=</td><td align="right"><?php echo number_format($r['d_unionDues'],2);?></td><td>Parking Fee</td><td>=</td><td align="right"><?php echo number_format($r['d_parkingFee'],2);?></td></tr>	
								<tr><td>Honorarium</td><td>=</td><td align="right"><?php echo number_format($r['honorarium'],2);?></td><td>&nbsp;</td><td>MORTUARY</td><td>=</td><td align="right"><?php echo number_format($r['d_mortuary'],2);?></td><td>Other Ded</td><td>=</td><td align="right"><?php echo number_format($r['d_other'],2);?></td></tr>	
								<tr><td>Allowance</td><td>=</td><td align="right"><?php echo number_format($r['allowance'],2);?></td><td>&nbsp;</td><td>DDCOOP</td><td>=</td><td align="right"><?php echo number_format($r['d_coopTotal'],2);?></td><td>PNB Loan</td><td>=</td><td align="right"><?php echo number_format($r['d_pnb'],2);?></td></tr>
								<tr><td>Incentive</td><td>=</td><td align="right"><?php echo number_format($r['incentive'],2);?></td><td>&nbsp;</td><td>Undertime</td><td>=</td><td align="right"><?php echo number_format($r['payUndertime'],2);?></td><td>Financial Assistance</td><td>=</td><td align="right"><?php echo number_format($r['financialAssistance'],2);?></td></tr>	
								<tr><td>HazardPay</td><td>=</td><td align="right"><?php echo number_format($r['hazardPay'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align="right">&nbsp;</td><td>CASH ADV</td><td>=</td><td align="right"><?php echo number_format($r['d_cashAdvance'],2);?></td></tr>	
								<tr><td>OnCall OT</td><td>=</td><td align="right"><?php echo number_format($r['onCallOvertime'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align="right">&nbsp;</td></tr>	
								
								<tr><td>SH Prem</td><td>=</td><td align="right"><?php echo number_format($r['paySpHoliday'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								<tr><td>LH Prem</td><td>=</td><td align="right"><?php echo number_format($r['payLHoliday'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								<tr><td>DRD Prem</td><td>=</td><td align="right"><?php echo number_format($r['payDutyRd'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								
								<tr><td>Other Inc</td><td>=</td><td align="right"><?php echo number_format($r['oth_income'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								<tr><td>13th Month</td><td>=</td><td align="right"><?php echo number_format($r['adj_13th_mon_pa'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="right">-----------------</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>	
								<tr><td>Gross Pay</td><td>=></td><td align="right"><?php echo number_format($grossPay + $r['payUndertime'],2);?></td><td>&nbsp;</td><td>&nbsp;</td><td>Total Ded</td><td>=</td><td align="right"><?php echo number_format($totalDeduction + $r['payUndertime'],2);?></td><td>&nbsp;</td><td>Net Pay</td><td>=</td><td align="right"><?php echo number_format($netPay,2);?></td></tr>	
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
						</table>
					</td>
			  </tr>
			  <tr><td colspan="15"><br><br><br><hr><br><br><br></td></tr>
			  <tr><td colspan="15" align="right"><button onclick="window.open('payslip_print.php?id=<?php echo $_SESSION['ndex'];?>&cutoff=<?php echo $_POST['cutoff'];?>','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=500')">Print</button></td></tr>
				<tr><td><br style="font-size:5px"></td></tr>
      </table>
				<?php } ?>
			</div>
		</td>
	</tr>
	<tr><td height="100" colspan="2">&nbsp;</td></tr>
	</table>
</div>
</body>
</html>
