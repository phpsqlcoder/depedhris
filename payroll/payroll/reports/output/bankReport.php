<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");


$cutOffInfo = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']."'",$conn));
$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, e.bankAccountNo, p.*
								FROM employee e 
									LEFT JOIN payroll p ON p.empid = e.ndex 
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE e.isActive='1' && p.pay_period='".$_POST['PayrollCutoff']."' && e.residencyTrainingProgram=''";
//if ($_POST['division']){$sql .= " && e.divisionId='".$_POST['division']."'";}
//echo $division." --".$PayrollCutoff."<br>".$sql.mysql_num_rows(mysql_query($sql));

if ($_POST['mbtcCompany'] == 1){
	$sql .= " && e.level IN (0) && e.bankAccountNo<>''";
	$reportTitle = 'CONTRACTUAL';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && e.level IN (1,2) && e.bankAccountNo<>''";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && e.level IN (3,4,5) && e.bankAccountNo<>''";
	$reportTitle = 'OFFICER';
} elseif ($_POST['mbtcCompany'] == 0) {
	$reportTitle = 'ALL EMPLOYEE';
	$sql .= " && e.bankAccountNo<>''";
} elseif ($_POST['mbtcCompany'] == 5){
	$sql .= " && e.level IN (0) && e.bankAccountNo=''";
	$reportTitle = 'CONTRACTUAL';
} elseif ($_POST['mbtcCompany'] == 6) {
	$sql .= " && e.level IN (1,2) && e.bankAccountNo=''";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 7) {
	$sql .= " && e.level IN (3,4,5) && e.bankAccountNo=''";
	$reportTitle = 'OFFICER';
} elseif ($_POST['mbtcCompany'] == 4) {
	$reportTitle = 'ALL EMPLOYEE';
	$sql .= " && e.bankAccountNo=''";
}



$sql.=" ORDER BY  e.lastName,e.firstName";
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
		$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payOTExc'] - $r['payUndertime'] +  $r['adj_other'];
		$totalDeduction = $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['d_unionDues'] + $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal']  + $r['financialAssistance'];
		
		$netPay = $grossPay - $totalDeduction;
	if ($netPay != 0){
		$var++;
   	 	$ctr1s++;
		$ln++;
    	$data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 							<td align='left'>".$ln."</td>
					      <td align='left'>".$r['lastName'].", ".$r['firstName']."</td>
					      <td>".$r['bankAccountNo']."</td>
								<td>".number_format($netPay,2)."</td>
				     </tr>";
						 
		$totalNetPay += $netPay;
	}
}


if ($_POST['PayrollCutoff'] == date('Y-m-t',strtotime($_POST['PayrollCutoff']))){
	$payrollDateRange = date('M. 16, Y',strtotime($_POST['PayrollCutoff']))." to ".date('M t, Y',strtotime($_POST['PayrollCutoff']));
} else {
	$payrollDateRange = date('M. 1, Y',strtotime($_POST['PayrollCutoff']))." to ".date('M. 15, Y',strtotime($_POST['PayrollCutoff']));
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="reportbyposition.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php //include("../rptheader.php");?>
     <table width="700" style="font-family:Arial;font-size:12px;">
	  <thead>
	  <tr>
	       <td colspan="4" align="left" style="font-size:14px;"><?php echo date('d F Y');?><br><br> 
				 		METROBANK - DAVAO DOCTORS HOSPITAL <br>
						E. QUIRINO AVENUE, DAVAO CITY <br><br><br>


						Gentlemen: <br><br>

						Please debit our Savings Acount No. 3-66701583-0 the total amount of Pesos: P &nbsp;&nbsp;<?php echo number_format($totalNetPay,2);?> an credit to the following accounts
						representing our payroll on period : <?php echo $payrollDateRange;?>.
				 </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
				<td></td>
	     	<td>ACCOUNT NAME</td>
	      <td>ACCOUNT NO.</td>
		   	<td>AMOUNT</td>
	  </tr>
	  <tr><td colspan="4"><hr></td></tr>
	  </thead>
	  <tbody>
	 	<?php echo $data;?>
	  </tbody>
		<tr valign="bottom" align="center">
				<td colspan="3"></td>
		   	<td align="right"><hr></td>
	  </tr>
		<tr><td colspan="4" align="right"><?php echo number_format($totalNetPay,2);?></td></tr>
			<tr valign="bottom" align="center">
				<td colspan="3"></td>
		   	<td align="right"><hr><hr></td>
	  </tr>
		<tr>
	       <td colspan="4" align="left" style="font-size:14px;">
				 		
						<br><br><br>
						Approved by:
						<br><br><br>
				 </td>
	  </tr>
		<tr valign="bottom" align="center">
				<td>R.CS. DEL VAL</td>
	     	<td>L.B. BUENO</td>
	      <td>C.C. GO, M.D.</td>
		   	<td>D.C. DELA PAZ, M.D.</td>
	  </tr>
      </table>
	  <?php //include("../rptfooter.php");?>




