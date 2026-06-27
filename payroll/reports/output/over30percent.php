<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");

$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid, p.*
								FROM employee e 
									left join employee_compensation c on e.ndex=c.employeeId
									LEFT JOIN payroll p ON p.empid = e.ndex 
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE p.pay_period='".$_POST['PayrollCutoff']."' && p.residencyTrainingProgram='' && c.basicPay<>0 && p.holdSalary<>'1'" ;// && e.ndex=828";
									
$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));		
$sql.=" ORDER BY  d.name, e.lastName,e.firstName";
//echo $sql;	
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$countPerDepartment = 0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
		
		$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['payOTExc'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'] - $r['payUndertime'] +  $r['adj_other']  +  $r['hazardPay']  +  $r['incentive'];
		
		
		$totalDeduction = $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['pagibigloan'] + $r['pagibigloanh'] + $r['d_unionDues'] + $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal'] + $r['financialAssistance'] + $r['pagibigSavings'];
		$netPay = $grossPay - $totalDeduction;
		$thirthyPercentOfGross = ($grossPay * 0.30);
	
	  $var++;
	  
		$ln++;
		
		// extract cola undertime on payUndertime

		$colaUndertimeAmount = $r['undertime'] != 0 && $r['days_work'] != 0? ((($r['cola'] / $r['days_work']) / 8) * $r['undertime']) : 0;
	
	if($netPay < $thirthyPercentOfGross){
		$ctr1s++;
    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
    $data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 							<td align='left'>".getID($r['employmentStatus'],$r['employeeNo'])."&nbsp; </td>
					      <td align='left'>".$r['lastName'].", ".$r['firstName']."&nbsp; </td>
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
	}
	
}
?>
   
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:11px;">
	 <thead>
	  <tr>
	       <td colspan="25" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br /><?php echo "Less Than 30% Net";?><br>
				 				<?php echo date('M d, Y',strtotime($cutoffDate['cutoffDateStart']))." to ".date('M d, Y',strtotime($cutoffDate['cutoffDateEnd']));?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
				<td>IDNUM</td>
	     	<td>NAME</td>
	      <td>BASIC</td>
		   	<td>NETBASIC</td>
	      <td>COLA</td>
				<td>ALLOW</td>
				<td>INCENTIVE</td>
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
	  <tr><td colspan="25"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




