<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");
include ("../../../myfunctions.php");
include ("../../payrollfunctions.php");

/*******
* Active Employees With Minimum On Basic..
* 
*******/

$sql = "SELECT e.ndex empid, e.lastName, e.firstName, e.middleName, e.employeeNo, e.tin, d.name, ec.basicPay, e.civilStatus, ec.payTypeNdex
								FROM employee e 
								LEFT JOIN dept d ON d.ndex=e.deptId
								LEFT JOIN employee_compensation ec ON ec.employeeId=e.ndex
								LEFT JOIN payroll p ON p.empid=e.ndex
										WHERE e.isActive <> '0' AND ( (ec.basicPay <= 9490 AND payTypeNdex = '1') || (ec.basicPay <= 312 AND payTypeNdex = '2') )
											  AND DATE_FORMAT(p.pay_period,'%Y') = '".$_POST['year']."' 
											  AND e.residencyTrainingProgram<>'ROD'
								";// && e.ndex=828";
										
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
	
	//  continue :   escape employee resign between dec 1 to 31 for the year selected.
	/*
		script here
		if employee exist in payroll register on dec of the year selected 
			continue;   meaning escape from the generated list
	*/
	
	$var++;
  	$ctr1s++;
	$ln++;
	$lk++;
	$tlk++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	/*
	$leave = mysql_fetch_assoc(mysql_query("SELECT SUM(leaveLimit) allowedLeave FROM employee_leave_limit WHERE employeeId='".$r['empid']."' && year='".$_POST['PayrollYear']."' && leaveId='10'"));  // ALLOWED NO OF SICK LEAVE
	$usedLeave = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) usedLeave FROM employee_leave WHERE employeeId='".$r['empid']."' && YEAR(startDate)='".$_POST['PayrollYear']."' && leaveId='10'"));		// USED  SICK LEAVE
	//echo "SELECT COUNT(*) usedLeave FROM employee_leave WHERE employeeId='".$r['empid']."' && YEAR(startDate)='".$_POST['PayrollYear']."' && leaveId='10'";
	
	if ($r['payTypeNdex'] == 1){
		$dailyRate = payPerSpecificTime($r['basicPay'],'day');
	} else {
		$dailyRate = payPerSpecificTimeDaily($r['basicPay'],'day');
	}
	$unusedLeave = $leave['allowedLeave'] - $usedLeave['usedLeave'];
	$convertedLeaveAmount = $dailyRate * $unusedLeave;
	$excessOf10DaysLeave = $unusedLeave > 10 ? ($unusedLeave - 10) : 0;
	$taxableLeaveAmount = $excessOf10DaysLeave * $dailyRate;
	$itw = $convertedLeaveAmount * 0.1;
	$totalAmount = $convertedLeaveAmount - $itw;

	// SICK LEAVE 

	//$convertedLeaveAmountTotal += $convertedLeaveAmount; 
	//$taxableLeaveAmountTotal += $taxableLeaveAmount;
	$itwTotal += $itw;
	$totalAmountTotal += $totalAmount;
	*/
	
	//
	$rDec = mysql_fetch_assoc(mysql_query("SELECT SUM( d_whtax  ) AS decTaxDue FROM `payroll` WHERE DATE_FORMAT( `pay_period` , '%Y' ) = '".$_POST['year']."-12' && `empid` = '".$r['empid']."'"));

	$rPayroll = mysql_fetch_assoc(mysql_query("SELECT basicpay AS basicPay, pay_type FROM `payroll` WHERE pay_period = '".$_POST['year']."' && `empid` = '".$r['empid']."' "));
	//echo "SELECT basicpay AS basicPay, pay_type FROM `payroll` WHERE pay_period = '".$_POST['year']."-12-31' && `empid` = '".$r['empid']."'";
	if ($rPayroll['pay_type'] == 1){
		$dailyRate = payPerSpecificTime($rPayroll['basicPay'],'day');
	} else {
		$dailyRate = payPerSpecificTimeDaily($rPayroll['basicPay'],'day');
	}

	$dependents = noOfDependents($r['empid']) == 0 ? '' : noOfDependents($r['empid']);
	$cStatus = $r['civilStatus'] == 'SINGLE' ? 'S' : 'M';
	
	$convertedLeaveAmount = $dailyRate * UnusedLeave($r['empid'],$_POST['year']);

	$fourA = AnnualGrossPay($r['empid'],$_POST['year']);
	$fourB = Annual13thMothPay($r['empid'],$_POST['year']) <= 30000 ? Annual13thMothPay($r['empid'],$_POST['year']) : 30000;
	$fourD = AnnualSSSDeduction($r['empid'],$_POST['year']) + AnnualPHICDeduction($r['empid'],$_POST['year']) + AnnualPagibigDeduction($r['empid'],$_POST['year']) + AnnualUnionDuesDeduction($r['empid'],$_POST['year']);
	$fourF = $fourB + $fourC + $fourD + $fourE;
	$fourG = $fourA - $fourF;
	$fourH = $fourB > 30000 ? Annual13thMothPay($r['empid'],$_POST['year']) - 30000 : 0;
	$fourI = $convertedLeaveAmount;
	$fourJ = $fourG + $fourH + $fourI;
	$fiveA = $cStatus.$dependents;
	$fiveB = 50000 + ($dependents * 25000);
	$six = 0;
	$seven = ($fourJ - $fiveB -$six) < 0 ? 0 : ($fourJ - $fiveB -$six);
	//$eight = AnnualTaxDue($r['empid'],$_POST['year']);
	$eight = netTaxableCompensation ($seven);
	$nine = AnnualTaxDue($r['empid'],$_POST['year']) - $rDec['decTaxDue'];
	$tenA = ($eight - $nine) < 0 ? 0 : ($eight - $nine);
	$tenB = ($nine - $eight) < 0 ? 0 : ($nine - $eight);
	$eleven = $nine + $tenA - $tenB;
	
	
	$totalFourA += $fourA;
	$totalFourB += $fourB;
	$totalFourD += $fourD;
	$totalFourF += $fourF;
	$totalFourG += $fourG;
	$totalFourH += $fourH;
	$totalFourI += $fourI;
	$totalFourJ += $fourJ;
	$totalFiveA += $fiveA;
	$totalFiveB += $fiveB;
	$totalSix += $six;
	$totalSeven += $seven;
	$totalEight += $eight;
	$totalNine += $nine;
	$totalTenA += $tenA;
	$totalTenB += $tenB;
	$totalEleven += $eleven;
	
	
	$data.="<tr bgcolor='".$bgclr1s."' align='right'>
			<td align='left'>".$ln."</td>
	 		<td align='left'>".$r['employeeNo']."</td>
			<td align='left'>".$r['tin']."</td>
	     	<td align='left'>".$r['lastName'].", ".$r['firstName']."</td>
			<td >".number_format($fourA,2)."</td>
			<td >".number_format($fourB,2)."</td>
	      	<td >".$leave['allowedLeave']."</td>
			<td >".number_format($fourD,2)."</td>
			<td >".$unusedLeave."</td>
			<td >".number_format($fourF,2)."</td>
			<td >".number_format($fourG,2)."</td>
			<td >".number_format($fourH,2)."</td>
			<td >".number_format($fourI,2)."</td>
			<td >".number_format($fourJ,2)."</td>
			<td align='left'>".$fiveA."</td>
			<td >".number_format($fiveB,2)."</td>
			<td >".number_format($six,2)."</td>
			<td >".number_format($seven,2)."</td>
			<td >".number_format($eight,2)."</td>
			<td >".number_format($nine,2)."</td>
			<td >".number_format($tenA,2)."</td>
			<td >".number_format($tenB,2)."</td>
			<td >".number_format($eleven,2)."</td>
				     </tr>";
	//$data .= "<tr><td colspan='24'>&nbsp;</td></tr>";

	//<td >".number_format($taxableLeaveAmount,2)."</td>
			//<td >".$excessOf10DaysLeave."</td>
}



?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="birform1604cfschedule73.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:11px;">
	 <thead>
	  <tr>
	    <td align="LEFT" style="font-size:13px;" colspan="10">
		   BIR FORM 1604CF - SCHEDULE 7.5<br>
		   ALPHALIST OF EMPLOYEES AS OF DECEMBER 31 WITH NO PREVIOUS EMPLOYERS WITHIN THE YEAR<br>
		   AS OF DECEMBER 31, <?php echo $_POST['year'];?><br><br>
		   
		   TIN: 005985874-0000<br>
		   WITHHOLDING AGENT'S NAME: DAVAO DOCTORS HOSPITAL (CLINICAL HILARIO INC)
		</td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
	  		<td>SEQ<br>NO</td>
			<td>ID NO</td>
			<td>TAXPAYER<br>IDENTIFICATION<br>NUMBER</td>
			<td>NAME OF EMPLOYEES<br>(Last Name, First Name, Middle Name)</td>
	     	<td>GROSS<br>COMPENSATION<br>INCOME</td>
	      	<td>13TH MONTHPAY<br>& OTHER BENEFITS</td>
			<td>DE MINIMIS <br> BENEFITS</td>
			<td>SSS, GSIS, PHIC &<br>PAG-IBIG CONTRIBUTIONS<br>AND UNION DUES</td>
			<td>SALARIES & OTHER<br>FORMS OF<br>COMPENSATION</td>
			<td>TOTAL<br>NON-TAXABLE/EXEMPT<br>COMPENSATION INCOME</td>
			<td>BASIC<br>SALARY</td>
			<td>13TH MONTH PAY<br>& OTHER BENEFITS</td>
			<td>SALARIES & OTHER<br>FORMS OF<br>COMPENSATION</td>
			<td>TOTAL<br>TAXABLE<br>COMPENSATION INCOME</td>
			<td>CODE</td>
			<td>AMOUNT</td>
			<td>PREMIUM PAID<br>ON HEALTH<br>AND/OR HOSPITAL<br>INSURANCE</td>
			<td>NET TAXABLE<br>COMPENSATION<br>INCOME</td>
			<td>TAX DUE<br>(Jan. - Dec.)</td>
			
			<td>TAX WITHHELD<br>(Jan. - Nov.)</td>
			<td>AMT WITHHELD<br>& PAID FOR IN<br>DECEMBER</td>
			<td>OVER<br>WITHHELD TAX<br>EMPLOYEE</td>
			<td>AMOUNT OF TAX<br>WITHHELD AS<br>ADJUSTED</td>
			<td>SUBSTITUTED FILING?<br>YES/NO</td>
	  </tr>
	  <tr valign="bottom" align="center">
	  		<td>(&nbsp;0&nbsp;)</td>
			<td>(&nbsp;1&nbsp;)</td>
			<td>(&nbsp;2&nbsp;)</td>
			<td>(&nbsp;3&nbsp;)</td>
	     	<td>4(a)</td>
	      	<td>4(b)</td>
			<td>4(c)</td>
			<td>4(d)</td>
			<td>4(e)</td>
			<td>4(f)</td>
			<td>4(g)</td>
			<td>4(h)</td>
			<td>4(i)</td>
			<td>4(j)</td>
			<td>5(a)</td>
			<td>5(b)</td>
			<td>(&nbsp;6&nbsp;)</td>
			<td>(&nbsp;7&nbsp;)</td>
			<td>(&nbsp;8&nbsp;)</td>
			
			<td>(&nbsp;9&nbsp;)</td>
			<td>(10a)=(8)-(9)</td>
			<td>(10b)=(9)-(8)</td>
			<td>(11)=(9+10a)or(9-10b)</td>
			<td>(&nbsp;12&nbsp;)</td>
	  </tr>
	  <tr><td colspan="27"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  <tr><td colspan="27"><hr></td></tr>
	  <tr valign="bottom" align="center">
	  		<td></td>
			<td></td>
			<td></td>
			<td></td>
	     	<td align='right'><?php echo number_format($totalFourA,2);?></td>
	      	<td align='right'><?php echo number_format($totalFourB,2);?></td>
			<td></td>
			<td align='right'><?php echo number_format($totalFourD,2);?></td>
			<td align='right'><?php echo number_format($totalFourE,2);?></td>
			<td align='right'><?php echo number_format($totalFourF,2);?></td>
			<td align='right'><?php echo number_format($totalFourG,2);?></td>
			<td align='right'><?php echo number_format($totalFourH,2);?></td>
			<td align='right'><?php echo number_format($totalFourI,2);?></td>
			<td align='right'><?php echo number_format($totalFourJ,2);?></td>
			<td align='right'><?php echo number_format($totalFiveA,2);?></td>
			<td align='right'><?php echo number_format($totalFiveB,2);?></td>
			<td align='right'><?php echo number_format($totalSix,2);?></td>
			<td align='right'><?php echo number_format($totalSeven,2);?></td>
			<td align='right'><?php echo number_format($totalEight,2);?></td>
			
			<td align='right'><?php echo number_format($totalNine,2);?></td>
			<td align='right'><?php echo number_format($totalTenA,2);?></td>
			<td align='right'><?php echo number_format($totalTenB,2);?></td>
			<td align='right'><?php echo number_format($totalEleven,2);?></td>
			<td align='right'>(&nbsp;12&nbsp;)</td>
	   </tbody>
	  <!-- <tr><td colspan="13"><hr></td></tr>
	  <tr><td colspan="9">Grand Total</td> -->
	  
	  <!--OLD <td colspan="2"><?php echo number_format($convertedLeaveAmountTotal,2);?></td>
	  <td><?php echo number_format($taxableLeaveAmountTotal,2);?></td> -->
	 
	  <!-- <td><?php echo number_format($itwTotal,2);?></td>
	  <td><?php echo number_format($totalAmountTotal,2);?></td>
	  </tr>
	  <tr><td colspan="27"><hr></td></tr>  -->
      </table>
	  <?php include("../rptfooter.php");?>




