<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");
$start_Cutoff=date('Y')."-01-01";
$a13MonthCutoff=$_POST['PayrollCutoff'];
$sql = "SELECT e.ndex as empid,e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, e.bankAccountNo,e.payType, ec.basicPay
								FROM employee e 
									LEFT JOIN dept d ON d.ndex = e.deptId
									LEFT JOIN employee_compensation ec ON ec.employeeId = e.ndex 
										WHERE e.isActive='1' && e.employmentStatus IN ('Regular','Temporary','Probationary')  && e.residencyTrainingProgram='' && e.dateHired <= '".$a13MonthCutoff."'";
//if ($_POST['division']){$sql .= " && e.divisionId='".$_POST['division']."'";}
//echo $division." --".$PayrollCutoff."<br>".$sql.mysql_num_rows(mysql_query($sql));

if ($_POST['mbtcCompany'] == 1){
	$sql .= " && e.level IN (0) && e.bankAccountNo<>''";
	$reportTitle = 'TEMPORARY';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && e.level IN (1,2) && e.bankAccountNo<>''";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && e.level IN (3,4,5,6,7,8,9) && e.bankAccountNo<>''";
	$reportTitle = 'OFFICER';
} elseif ($_POST['mbtcCompany'] == 0) {
	$reportTitle = 'ALL EMPLOYEE';
	$sql .= " && e.bankAccountNo<>''";
} elseif ($_POST['mbtcCompany'] == 5){
	$sql .= " && e.level IN (0) && e.bankAccountNo=''";
	$reportTitle = 'TEMPORARY';
} elseif ($_POST['mbtcCompany'] == 6) {
	$sql .= " && e.level IN (1,2) && e.bankAccountNo=''";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 7) {
	$sql .= " && e.level IN (3,4,5,6,7,8,9) && e.bankAccountNo=''";
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
		
	$final=mysql_fetch_assoc(mysql_query("select * from payroll13thmonth where empNo='".$r['empid']."' and cutOffDate='".$a13MonthCutoff."'"));
	$totalDeduction = $final['wtax'] + $final['cashAdvance'] + $final['hospitalBill'] + $final['otherDeduction'];
	$final_amount = $final['amount13thMonth'] - $totalDeduction;
		$var++;
   	 	$ctr1s++;
		$ln++;
    	$data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 							<td align='left'>".$ln."</td>
					      <td align='left'>".$r['lastName'].", ".$r['firstName']."</td>
					      <td>".$r['bankAccountNo']."</td>
								<td>".number_format($final_amount,2)."</td>
				     </tr>";
		$totalNetPay += $final_amount;
	
}


	//$payrollDateRange = '2014-01-01 to '.$a13MonthCutoff;
	$payrollDateRange = date('M. 1, Y',strtotime(date('Y').'-01-01'))." to ".date('M. 15, Y',strtotime($a13MonthCutoff));

?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="bankreport131thmonth.xls";
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
						representing our 13th Month Pay on period : <?php echo $payrollDateRange;?>.
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




