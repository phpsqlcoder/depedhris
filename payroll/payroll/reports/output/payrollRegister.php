<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");

$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid, p.*
								FROM employee e 
									LEFT JOIN payroll p ON p.empid = e.ndex 
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE e.isActive='1' && p.pay_period='".$_POST['PayrollCutoff']."' && p.residencyTrainingProgram=''";// && e.ndex=163";
									
$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));												
if ($_POST['mbtcCompany'] == 1){
	$sql .= " && p.level IN (0)";
	$reportTitle = 'CONTRACTUAL';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && p.level IN (1,2)";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && p.level IN (3,4,5)";
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
//echo $sql;	
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
		$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payOTExc'] - $r['payUndertime'] +  $r['adj_other'];
		$totalDeduction = $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['pagibigloan'] + $r['pagibigloanh'] + $r['d_unionDues'] + $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal'] + $r['financialAssistance'];
		$netPay = $grossPay - $totalDeduction;
		if ($netPay < ($grossPay * 0.30)){
			if ($r['d_coopTotal'] > (($grossPay * 0.30) - $netPay)){
				$coopDedCurPayroll = $r['d_coopTotal'] - (($grossPay * 0.30) - $netPay);
				$updpayroll = mysql_query("UPDATE payroll SET d_coopTotal='".$coopDedCurPayroll."' WHERE empid='".$r['empid']."' && pay_period='".$_POST['PayrollCutoff']."'",$conn);
				//echo "UPDATE payroll SET d_coopTotal='".$coopDedCurPayroll."' WHERE empid='".$r['empid']."' && pay_period='".$_POST['PayrollCutoff']."'";
				$netPay += (($grossPay * 0.30) - $netPay);
				$r['d_coopTotal'] = $coopDedCurPayroll;
			}
		} 
    $var++;
    $ctr1s++;
		$ln++;
		if($r['departmentName'] != $prevDepartment){
			if($ln != 1){
				$data .= "<tr><td colspan='2'></td><td colspan='22'><hr></td></tr>";
				$data .= " <tr>
											<td colspan='2'> Sub Total</td>
											<td align='right'>".number_format($subTotalBasicPay,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalNetBasic,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalCola,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalAllowIncen,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalAllowHonorarium,2)."&nbsp; </td>
											<!-- <td align='right'>".$subTotalPayUndertime."</td> -->
											<td align='right'>".number_format($subTotalPayNightPremium,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalOvertime,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalAdjustment,2)."&nbsp; </td>
											<td align='right'>".number_format($subtotalHazardPay,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalGrossPay,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_whtax,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_sss,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_philhealth,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalpagibig,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalUnioMort,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_sssloan,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalpagibigloan,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_hospital,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_cashAdvance,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalCoop,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalOthers,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalTotalDed,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalNetPay,2)."&nbsp; </td>
										</tr>";
				$data .= "<tr><td colspan='24'>&nbsp;</td></tr>";
				
				$subTotalBasicPay = 0;
				$subTotalNetBasic = 0;
				$subTotalCola = 0;
				$subTotalAllowIncen = 0;
				$subTotalAllowHonorarium = 0;
				$subTotalPayUndertime = 0;
				$subTotalPayNightPremium = 0;
				$subTotalOvertime = 0;
				$subTotalAdjustment = 0;
				$subtotalHazardPay = 0;
				$subTotalGrossPay = 0;
				$subTotald_whtax = 0;
				$subTotald_sss = 0;
				$subTotald_philhealth = 0;
				$subTotalpagibig = 0;
				$subTotalUnioMort = 0;
				$subTotald_sssloan = 0;
				$subTotalpagibigloan = 0;
				$subTotald_hospital = 0;
				$subTotald_cashAdvance = 0;
				$subTotalCoop = 0;
				$subTotalOthers = 0;
				$subTotalTotalDed = 0;
				$subTotalNetPay = 0;
		
			}
			$data .= " <tr><td colspan='24' align='left' style='font-size:11px;'>".$r['departmentName']."</td></tr>";
		}		
    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
    $data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 							<td align='left'>".getID($r['employmentStatus'],$r['employeeNo'])."&nbsp;".$r['grossPay']." </td>
					      <td align='left'>".$r['lastName'].", ".$r['firstName']."&nbsp; </td>
					      <td>".number_format($r['basicpay'],2)."&nbsp; </td>
						   	<td>".number_format(($r['netBasic'] - $r['payUndertime']),2)."&nbsp; </td>
					      <td>".number_format($r['cola'],2)."</td>
								<td>".number_format($r['allowance'],2)."&nbsp; </td>
								<td>".number_format($r['honorarium'],2)."&nbsp; </td>
								<td>".number_format($r['payNightPremium'],2)."&nbsp; </td>
								<td>".number_format(($r['payOTReg'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payDutyRd']),2)."&nbsp; </td>
								<td>".number_format(($r['oth_income'] + $r['onCallOvertime']),2)."&nbsp; </td>
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
		$prevDepartment = $r['departmentName'];
		$subTotalBasicPay += $r['basicpay'];
		$subTotalNetBasic += $r['netBasic']  - $r['payUndertime'];
		$subTotalCola += $r['cola'];
		$subTotalAllowIncen += $r['allowance'];
		$subTotalAllowHonorarium += $r['honorarium'];
		//$subTotalPayUndertime += $r['payUndertime'];
		$subTotalPayNightPremium += $r['payNightPremium'];
		$subTotalOvertime += $r['payOTReg'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payDutyRd'];
		$subTotalAdjustment += $r['oth_income']  + $r['onCallOvertime'];
		$subtotalHazardPay += $r['hazardPay'];
		$subTotalGrossPay += $grossPay;
		$subTotald_whtax += $r['d_whtax'];
		$subTotald_sss += $r['d_sss'];
		$subTotald_philhealth += $r['d_philhealth'];
		$subTotalpagibig += $r['pagibig'];
		$subTotalUnioMort += $r['d_unionDues'] + $r['d_mortuary'];
		$subTotald_sssloan += $r['d_sssloan'];
		$subTotalpagibigloan += $r['pagibigloan'];
		$subTotald_hospital += $r['d_hospital'];
		$subTotald_cashAdvance += $r['d_cashAdvance'];
		$subTotalCoop += $r['d_coopTotal'];
		$subTotalOthers += $r['d_other'];
		$subTotalTotalDed += $totalDeduction;
		$subTotalNetPay += $netPay;
		
		//GRAND TOTAL
		$grandTotalBasicPay += $r['basicpay'];
		$grandTotalNetBasic += $r['netBasic'] - $r['payUndertime'];
		$grandTotalCola += $r['cola'];
		$grandTotalAllowIncen += $r['allowance'];
		$grandTotalAllowHonorarium += $r['honorarium'];
		//$grandTotalPayUndertime += $r['payUndertime'];
		$grandTotalPayNightPremium += $r['payNightPremium'];
		$grandTotalOvertime += $r['payOTReg'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payDutyRd'];
		$grandTotalAdjustment += $r['oth_income'] + $r['onCallOvertime'] ;
		$grandTotalHazardPay += $r['hazardPay'];
		$grandTotalGrossPay += $grossPay;
		$grandTotald_whtax += $r['d_whtax'];
		$grandTotald_sss += $r['d_sss'];
		$grandTotald_philhealth += $r['d_philhealth'];
		$grandTotalpagibig += $r['pagibig'];
		$grandTotalUnioMort += $r['d_unionDues'] + $r['d_mortuary'];
		$grandTotald_sssloan += $r['d_sssloan'];
		$grandTotalpagibigloan += $r['pagibigloan'];
		$grandTotald_hospital += $r['d_hospital'];
		$grandTotald_cashAdvance += $r['d_cashAdvance'];
		$grandTotalCoop += $r['d_coopTotal'];
		$grandTotalOthers += $r['d_other'];
		$grandTotalTotalDed += $totalDeduction;
		$grandTotalNetPay += $netPay;
		
		if ($ln == $rowCount){
			$data .= "<tr><td colspan='2'></td><td colspan='22'><hr></td></tr>";
				$data .= " <tr>
											<td colspan='2'> Sub Total</td>
											<td align='right'>".number_format($subTotalBasicPay,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalNetBasic,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalCola,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalAllowIncen,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalAllowHonorarium,2)."&nbsp; </td>
											<!-- <td align='right'>".number_format($subTotalPayUndertime,2)."&nbsp; </td> -->
											<td align='right'>".number_format($subTotalPayNightPremium,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalOvertime,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalAdjustment,2)."&nbsp; </td>
											<td align='right'>".number_format($subtotalHazardPay,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalGrossPay,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_whtax,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_sss,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_philhealth,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalpagibig,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalUnioMort,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_sssloan,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalpagibigloan,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_hospital,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotald_cashAdvance,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalCoop,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalOthers,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalTotalDed,2)."&nbsp; </td>
											<td align='right'>".number_format($subTotalNetPay,2)."&nbsp; </td>
										</tr>";
				$data .= "<tr><td colspan='24'>&nbsp;</td></tr>";
				$data .= " <tr>
											<td colspan='2'> Grand Total</td>
											<td align='right'>".number_format($grandTotalBasicPay,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalNetBasic,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalCola,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalAllowIncen,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalAllowHonorarium,2)."&nbsp; </td>
											<!-- <td align='right'>".number_format($grandTotalPayUndertime,2)."&nbsp; </td> -->
											<td align='right'>".number_format($grandTotalPayNightPremium,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalOvertime,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalAdjustment,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalHazardPay,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalGrossPay,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotald_whtax,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotald_sss,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotald_philhealth,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalpagibig,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalUnioMort,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotald_sssloan,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalpagibigloan,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotald_hospital,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotald_cashAdvance,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalCoop,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalOthers,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalTotalDed,2)."&nbsp; </td>
											<td align='right'>".number_format($grandTotalNetPay,2)."&nbsp; </td>
										</tr>";
		}
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
	       <td colspan="24" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>PAYROLL REGISTER<br /><?php echo $reportTitle;?><br>
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
				<td>HON</td>
				<!-- <td>UNDERTIME</td> -->
				<td>NIGHT <br />PREMIUM</td>
				<td>OT</td>
				<td>ADJ</td>
				<td>HAZZARD<br /> PAY</td>
				<td>GROSS <br />PAY</td>
				<td>W/TAX</td>
				<td>SSS</td>
				<td>PHIC</td>
				<td>PAGIBIG</td>
				<td>UNIO/MOR</td>
				<td>SSS LOAN</td>
				<td>P/LOAN</td>
				<td>HOSP</td>
				<td>CA</td>
				<td>COOP</td>
				<td>OTHERS</td>
				<td>TOTAL <br>DED</td>
				<td>NET PAY</td>
	  </tr>
	  <tr><td colspan="24"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




