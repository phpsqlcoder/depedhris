<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");
include ("../../../myfunctions.php");

//echo $_POST['payrollYear'];
// for enhancement.. need a generate button and separate table of leave conversion with year field

echo "<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
</head>
<body>";

//echo $_POST['PayrollCutoff'];
//$_POST['PayrollCutoff'] ='2013-01-15';
//$_POST['mbtcCompany'] =3;
$sql = "SELECT e.ndex empid, e.lastName, e.firstName, e.middleName, e.employeeNo, e.dateHired, d.name, ec.basicPay, ec.payTypeNdex
								FROM employee e 
								LEFT JOIN dept d ON d.ndex=e.deptId
								LEFT JOIN employee_compensation ec ON ec.employeeId=e.ndex
								LEFT JOIN payroll p ON p.empid=e.ndex
										WHERE DATE_FORMAT(p.pay_period,'%Y') = '".$_POST['PayrollYear']."'";// && e.ndex=828";
									
//$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));												
if ($_POST['employeeId']){
	$sql .= " && e.ndex='".$_POST['employeeId']."'";
} elseif ($_POST['mbtcCompany'] == 1){
	$sql .= " && p.level IN (0)";
	$level = " && level IN (0)";
	$reportTitle = 'TEMPORARY';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && p.level IN (1,2)";
	$level = " && level IN (1,2)";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && p.level IN (3,4,5)";
	$level = " && level IN (3,4,5)";
	$reportTitle = 'SECTION HEADS AND CONFI';
} elseif ($_POST['mbtcCompany'] == 0) {
	$reportTitle = 'ALL EMPLOYEE';
}


if ($_POST['employeeId']){
	$sql .= " && e.ndex='".$_POST['employeeId']."'";
} elseif ($_POST['mbtcCompany'] == 1){
	$sql .= " && e.isActive='1' && e.level IN (0)";
	$reportTitle = 'CONTRACTUAL';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && e.isActive='1' && e.level IN (1,2)";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && e.isActive='1' && e.level IN (3,4,5)";
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
	
	//echo $_POST['PayrollYear'];
	
	$leave = mysql_fetch_assoc(mysql_query("SELECT SUM(leaveLimit) allowedLeave FROM employee_leave_limit WHERE employeeId='".$r['empid']."' && year='".$_POST['PayrollYear']."' && leaveId='10'"));  // ALLOWED NO OF SICK LEAVE
	$usedLeave = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) usedLeave FROM employee_leave WHERE employeeId='".$r['empid']."' && YEAR(startDate)='".$_POST['PayrollYear']."' && leaveId='10'"));		// USED  SICK LEAVE
	//echo "SELECT COUNT(*) usedLeave FROM employee_leave WHERE employeeId='".$r['empid']."' && YEAR(startDate)='".$_POST['PayrollYear']."' && leaveId='10'";
	$rPayroll = mysql_fetch_assoc(mysql_query("SELECT basicpay AS basicPay, pay_type FROM `payroll` WHERE pay_period = '".$_POST['PayrollYear']."-12-31' && `empid` = '".$r['empid']."'"));
	
	if ($r['payTypeNdex'] == 1){
		$dailyRate = payPerSpecificTime($rPayroll['basicPay'],'day');
	} else {
		$dailyRate = payPerSpecificTimeDaily($rPayroll['basicPay'],'day');
	}
	$unusedLeave = $leave['allowedLeave'] - $usedLeave['usedLeave'];
	$convertedLeaveAmount = $dailyRate * $unusedLeave;
	$excessOf10DaysLeave = $unusedLeave > 10 ? ($unusedLeave - 10) : 0;
	$taxableLeaveAmount = $excessOf10DaysLeave * $dailyRate;
	//$itw = $convertedLeaveAmount * 0.1;
	$itw = withHeldTax (0, $convertedLeaveAmount, $dedFrequency='SEMI-MONTHLY');
	$totalAmount = $convertedLeaveAmount - $itw;
	$totalDeduction = $itw;
	// SICK LEAVE 
	
	
	//$convertedLeaveAmountTotal += $convertedLeaveAmount; 
	//$taxableLeaveAmountTotal += $taxableLeaveAmount;
	
	
	
	$itwTotal += $itw;
	$totalAmountTotal += $totalAmount;

	$data.="<tr bgcolor='".$bgclr1s."' align='right'>
			<td align='left'>".$r['name']."</td>
			<td align='left'>".$r['dateHired']."</td>
	 		<td align='left'>".$r['employeeNo']."</td>
	     	<td align='left'>".$r['lastName'].", ".$r['firstName']."</td>
			<td >".number_format($r['basicPay'],2)."</td>
			<td >".number_format($dailyRate,2)."</td>
	      	<td >".$leave['allowedLeave']."</td>
			<td >".$usedLeave['usedLeave']."</td>
			<td >".$unusedLeave."</td>
			<td >".number_format($convertedLeaveAmount,2)."</td>
			
			<td >".number_format($itw,2)."</td>
			<td >".number_format($totalAmount,2)."</td>
				     </tr>";
	$data .= "<tr><td colspan='24'>&nbsp;</td></tr>";

?>

			<table cellspacing="1" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%">
			  	<tr>
					<td width="200">&nbsp;</td>
			    <td  width="700" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br> SICK LEAVE CONVERSION PAYSLIP  <br>
						 				 FOR THE YEAR 2014 <?php echo date('Y',strtotime($_POST['PayrollYear']));?>
					</td>
					<!--<td width="20">:<br>:<br>:</td>
					<td width="250">&nbsp;</td>-->
			  	</tr>
				<tr valign="TOP">
					<td colspan="2">
						EMPNO: <?php echo $r['employeeNo'];?> <br>
						NAME: <?php echo $r['lastName'].", ".$r['firstName'];?> <br>
						DATE HIRED: <?php echo $r['dateHired'];?> 
					</td>
					<!--<td rowspan="3">:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:<br>:</td>
					<td>
					</td>-->
			  	</tr>
				<tr>
					<td colspan="3">
						<table  style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" width="100%">
							<td width="33%">Allowable Sick Leave = <?php echo $leave['allowedLeave'];?> days</td>
							<td width="33%">Used Sick Leave  =   <?php echo $usedLeave['usedLeave'];?> days</td>
							<td width="33%">Unused Sick Leave    = <?php echo $unusedLeave;?> days</td>
						</table>
					</td>
				</tr>
				
				<tr>
					<td colspan="3">
						Basic Rate = <?php echo number_format($r['basicPay'],2)?>
					</td>
				<tr valign="TOP">
					<td colspan="2">
							<table cellpadding="4" cellspacing="5" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%"0>
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
								<tr><td>SL Conversion</td><td>=</td><td align="right"><?php echo number_format($convertedLeaveAmount,2);?></td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>W/TAX</td><td>=</td><td align="right"><?php echo number_format($itw,2);?></td><td rowspan="14" width="5"></td><td><!--SSS LOAN--></td><td><!--=--></td><td align="right"><!--<?php echo number_format($r['d_sssloan'],2);?>--></td></tr>	
								<tr><td></td><td></td><td align="right"></td><td></td><td>Hospital Bill</td><td>=</td><td align="right"><?php echo number_format($r['hospitalBill'],2);?></td><td><!--PAG PREM--></td><td><!--=--></td><td align="right"><!--<?php echo number_format($r['pagibig'] + $r['pagibigSavings'],2);?>--></td></tr>	
								<tr><td></td><td></td><td align="right"></td><td></td><td>Cash Advance</td><td>=</td><td align="right"><?php echo number_format($r['cashAdvance'],2);?></td><td><!--PAG PREM--></td><td><!--=--></td><td align="right"><!--<?php echo number_format($r['pagibig'] + $r['pagibigSavings'],2);?>--></td></tr>
								<tr><td></td><td></td><td align="right"></td><td></td><td>Other Deduction</td><td>=</td><td align="right"><?php echo number_format($r['otherDeduction'],2);?></td><td><!--PAG PREM--></td><td><!--=--></td><td align="right"><!--<?php echo number_format($r['pagibig'] + $r['pagibigSavings'],2);?>--></td></tr>
								<tr><td></td><td></td><td align="right">--------------------</td><td></td><td><!--Other Deduction--></td><td>=</td><td align="right">-------</td><td><!--PAG PREM--></td><td><!--=--></td><td align="right"><!--<?php echo number_format($r['pagibig'] + $r['pagibigSavings'],2);?>--></td></tr>
								<tr><td>Gross  SL Conversion</td><td></td><td align="right"><?php echo number_format($convertedLeaveAmount,2);?></td><td></td><td>Total Deduction</td><td></td><td align="right"><?php echo number_format($totalDeduction,2);?></td><td><!--PAG PREM--></td><td><!--=--></td><td align="right"><!--<?php echo number_format($r['pagibig'] + $r['pagibigSavings'],2);?>--></td></tr>
								<tr><td colspan="15"><br></td></tr>
								
								<tr><td>Net SL Conversion</td><td></td><td align="right"><?php echo number_format($totalAmount,2);?></td><td></td><td></td><td></td><td align="right"></td><td><!--PAG PREM--></td><td><!--=--></td><td align="right"><!--<?php echo number_format($r['pagibig'] + $r['pagibigSavings'],2);?>--></td></tr>
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