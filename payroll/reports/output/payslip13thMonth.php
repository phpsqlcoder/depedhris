<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");

echo "<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
</head>
<body>";

//echo $_POST['PayrollCutoff'];
//$_POST['PayrollCutoff'] ='2013-01-15';
//$_POST['mbtcCompany'] =3;
$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, e.dateHired, d.name departmentName, p.*
								FROM payroll13thmonth p 
									LEFT JOIN employee e ON p.empNo = e.ndex 
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE p.cutOffDate='".$_POST['PayrollCutoff']."' && e.residencyTrainingProgram='' ";
										
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

if ($_POST['division']){$sql .= " && e.divisionId='".$_POST['division']."'";}
$sql.=" ORDER BY  e.employeeNo, d.name, e.lastName,e.firstName";

//echo $sql;
//echo date('Y',strtotime($_POST['PayrollCutoff']));
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
	$ln++;
	$grossPay = $r['amount13thMonth'];
	$totalDeduction = $r['hospitalBill'] + $r['cashAdvance'] + $r['otherDeduction'] + $r['wtax'];
	$netPay = $r['amount13thMonth'] - $totalDeduction;
?>

			<table cellspacing="1" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%">
			  	<tr>
					<td width="200">&nbsp;</td>
			    <td  width="700" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>PAYSLIP 13TH MONTH <br>
						 				 <?php echo date('F Y',strtotime($_POST['PayrollCutoff']));?>
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
								<tr><td>Net Basic</td><td>=</td><td align="right"><?php echo number_format($r['totalNetBasic'],2);?></td><td>Basic Pay= <?php echo number_format($r['basicPay'],2)?></td><td>W/TAX</td><td>=</td><td align="right"><?php echo number_format($r['wtax'],2);?></td><td rowspan="14" width="5"></td><td><!--SSS LOAN--></td><td><!--=--></td><td align="right"><!--<?php echo number_format($r['d_sssloan'],2);?>--></td></tr>	
								<tr><td>13th Mo.</td><td>=</td><td align="right"><?php echo number_format($r['amount13thMonth'],2);?></td><td></td><td>Hospital Bill</td><td>=</td><td align="right"><?php echo number_format($r['hospitalBill'],2);?></td><td><!--PAG PREM--></td><td><!--=--></td><td align="right"><!--<?php echo number_format($r['pagibig'] + $r['pagibigSavings'],2);?>--></td></tr>	
								<tr><td></td><td></td><td align="right"></td><td></td><td>Cash Advance</td><td>=</td><td align="right"><?php echo number_format($r['cashAdvance'],2);?></td><td><!--PAG PREM--></td><td><!--=--></td><td align="right"><!--<?php echo number_format($r['pagibig'] + $r['pagibigSavings'],2);?>--></td></tr>
								<tr><td></td><td></td><td align="right"></td><td></td><td>Other Deduction</td><td>=</td><td align="right"><?php echo number_format($r['otherDeduction'],2);?></td><td><!--PAG PREM--></td><td><!--=--></td><td align="right"><!--<?php echo number_format($r['pagibig'] + $r['pagibigSavings'],2);?>--></td></tr>
								<tr><td></td><td></td><td align="right">--------------------</td><td></td><td><!--Other Deduction--></td><td>=</td><td align="right">-------</td><td><!--PAG PREM--></td><td><!--=--></td><td align="right"><!--<?php echo number_format($r['pagibig'] + $r['pagibigSavings'],2);?>--></td></tr>
								<tr><td>Gross 13th Month</td><td></td><td align="right"><?php echo number_format($grossPay,2);?></td><td></td><td>Total Deduction</td><td></td><td align="right"><?php echo number_format($totalDeduction,2);?></td><td><!--PAG PREM--></td><td><!--=--></td><td align="right"><!--<?php echo number_format($r['pagibig'] + $r['pagibigSavings'],2);?>--></td></tr>
								<tr><td colspan="15"><br></td></tr>
								
								<tr><td>Net 13th Month</td><td></td><td align="right"><?php echo number_format($netPay,2);?></td><td></td><td></td><td></td><td align="right"></td><td><!--PAG PREM--></td><td><!--=--></td><td align="right"><!--<?php echo number_format($r['pagibig'] + $r['pagibigSavings'],2);?>--></td></tr>
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