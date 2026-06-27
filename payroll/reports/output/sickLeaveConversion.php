<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");
include ("../../../myfunctions.php");


//echo $_POST['PayrollYear']." ".$_POST['mbtcCompany'];
//$lastDateJan = date('t',date($_POST['PayrollYear'].'-m-d'));

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
	$sql .= " && p.level IN (3,4,5,6,7,8,9)";
	$level = " && level IN (3,4,5,6,7,8,9)";
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

	//<td >".number_format($taxableLeaveAmount,2)."</td>
			//<td >".$excessOf10DaysLeave."</td>
			
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
	       <td colspan="27" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>SICK LEAVE CONVERTION<br /><?php echo $reportTitle;?><br>
				 				<?php echo date('M d, Y',strtotime($_POST['PayrollYear'].'-01-01'))." to ".date('M d, Y',strtotime($_POST['PayrollYear'].'-12-31'));?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
	  		<td>DEPARTMENT</td>
			<td>DATE HIRED</td>
			<td>IDNUM</td>
			<td>NAME</td>
	     	<td>M-Basic Rate</td>
	      	<td>D-Basic Rate</td>
			<td>ALLOWABLE <br> LEAVE</td>
			<td>TAKEN</td>
			<td>UNUSED <br> LEAVE</td>
			<td>AMOUNT</td>
			<!-- <td>EXCESS OF <br> 10 DAYS </td>
			<td>TAXABLE <br> AMOUNT</td> -->
			<td>ITW</td>
			<td>AMOIUNT</td>
	  </tr>
	  <tr><td colspan="27"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
	  <tr><td colspan="13"><hr></td></tr>
	  <tr><td colspan="9">Grand Total</td>
	  
	  <!-- <td colspan="2"><?php echo number_format($convertedLeaveAmountTotal,2);?></td>
	  <td><?php echo number_format($taxableLeaveAmountTotal,2);?></td> -->
	  <td><?php echo number_format($itwTotal,2);?></td>
	  <td><?php echo number_format($totalAmountTotal,2);?></td>
	  </tr>
	  <tr><td colspan="27"><hr></td></tr>
      </table>
	  <?php include("../rptfooter.php");?>




