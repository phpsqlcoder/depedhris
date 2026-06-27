<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");


//$cutOffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']."'",$conn)); //(e.isActive='1' || p.holdSalary = '1' ) &&
$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));
$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.*
								FROM employee e 
									left join employee_compensation c on e.ndex=c.employeeId
									LEFT JOIN payroll p ON p.empid = e.ndex 
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE  p.pay_period='".$_POST['PayrollCutoff']."' && e.residencyTrainingProgram='' &&  c.basicPay<>0 && p.holdSalary<>'1'";
if ($_POST['division']){$sql .= " && e.divisionId='".$_POST['division']."'";}

if ($_POST['mbtcCompany'] == 1){
	$sql .= " && e.level IN (0)";
	$reportTitle = 'TEMPORARY';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && e.level IN (1,2)";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && e.level IN (3,4,5)";
	$reportTitle = 'SECTION HEADS AND CONFI';
} elseif ($_POST['mbtcCompany'] == 0) {
	$reportTitle = 'ALL EMPLOYEE';
}

//echo $division." --".$PayrollCutoff."<br>".$sql.mysql_num_rows(mysql_query($sql));
$sql.=" ORDER BY  e.lastName,e.firstName";
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
		
		$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payOTExc'] - $r['payUndertime'] +  $r['adj_other'] ;
		$totalDeduction = $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['pagibigloan'] + $r['pagibigloanh'] + $r['d_unionDues'] + $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal'] + $r['financialAssistance'] + $r['pagibigSavings'];
		$netPay = $grossPay - $totalDeduction;
    $var++;
    $ctr1s++;
		$ln++;
		/*
		if($r['departmentName'] != $prevDepartment){
			if($ln != 1){
				$data .= "<tr><td colspan='2'></td><td colspan='22'><hr></td></tr>";
				$data .= " <tr>
											<td colspan='2'> Sub Total</td>
											<td align='right'>".number_format($subTotalWTax,2)."</td>
											<td align='right'>".number_format($subTotalSSS,2)."</td>
											<td align='right'>".number_format($subTotalPhilhealth,2)."</td>
											<td align='right'>".number_format($subTotalDedHospital,2)."</td>
											<td align='right'>".number_format($subTotalDedCashAdvance,2)."</td>
											<td align='right'>".number_format($subTotalDedUnionDues,2)."</td>
											<td align='right'>".number_format($subTotalSSSLoan,2)."</td>
											<td align='right'>".number_format($subTotalPagibigLoan,2)."</td>
											<td align='right'>".number_format($subtotalMortuary,2)."</td>
											<td align='right'>".number_format($subtotalParkingFee,2)."</td>
											<td align='right'>".number_format($subtotalPnb,2)."</td>
											<td align='right'>".number_format($subTotalOthers,2)."</td>
											<td align='right'>".number_format($subTotalTotalDed,2)."</td>
										</tr>";
										
				$data .= "<tr><td colspan='24'>&nbsp;</td></tr>";
				$subTotalWTax = 0;
				$subTotalSSS = 0;
				$subTotalPhilhealth = 0;
				$subTotalDedHospital = 0;
				$subTotalDedCashAdvance = 0;
				$subTotalDedUnionDues = 0;
				$subTotalSSSLoan = 0;
				$subTotalPagibigLoan = 0;
				$subtotalMortuary = 0;
				$subtotalParkingFee = 0;
				$subtotalPnb = 0;
				$subTotalOthers = 0;
				$subTotalTotalDed = 0;
		
			}
			$data .= " <tr><td colspan='24' align='left' style='font-size:14px;font-weight:bold;'>".$r['departmentName']."</td></tr>";
		}		
		*/
    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
    $data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 							<td align='left'>".getID($r['employmentStatus'],$r['employeeNo'])."</td>
					      <td align='left'>".$r['lastName'].", ".$r['firstName']."</td>
					      <td>".$r['d_whtax']."</td>
						   	<td>".$r['d_sss']."</td>
					      <td>".$r['d_philhealth']."</td>
						  <td>".number_format($r['pagibig'] + $r['pagibigSavings'],2)."</td>
								<td>".number_format($r['d_hospital'],2)."</td>
								<td>".number_format($r['d_cashAdvance'],2)."</td>
								<td>".number_format($r['d_unionDues'],2)."</td>
								<td>".number_format(($r['d_sssloan']),2)."</td>
								<td>".number_format($r['pagibigloanh'],2)."</td>
								<td>".number_format($r['pagibigloan'],2)."</td>
								<td>".number_format($r['d_mortuary'],2)."</td>	
								<td>".number_format($r['d_parkingFee'],2)."</td>	
								<td>".number_format(($r['d_pnb']),2)."</td>
								<td>".number_format(($r['d_other'] + $r['financialAssistance'] ),2)."</td>
								<td>".number_format(($r['d_coopTotal']),2)."</td>
								<td>".number_format($totalDeduction,2)."</td>
				     </tr>";
		$prevDepartment = $r['departmentName'];
		$subTotalWTax += $r['d_whtax'];
		$subTotalSSS += $r['d_sss'];
		$subTotalPhilhealth += $r['d_philhealth'];
		$subTotalDedHospital += $r['d_hospital'];
		$subTotalDedCashAdvance += $r['d_cashAdvance'];
		$subTotalDedUnionDues += $r['d_unionDues'];
		$subTotalSSSLoan += $r['d_sssloan'];
		
		$subTotalPagibig += $r['pagibig'] + $r['pagibigSavings'];
		$subTotalPagibigHousingLoan += $r['pagibigloanh'];
		$subTotalPagibigSalaryLoan += $r['pagibigloan'];
		
		$subtotalMortuary += $r['d_mortuary'];
		$subtotalParkingFee += $r['d_parkingFee'];
		$subtotalPnb += $r['d_pnb'];
		$subTotalOthers += $r['d_other'] + $r['financialAssistance'];
		$subTotalcoop += $r['d_coopTotal'];
		$subTotalTotalDed += $totalDeduction;
		$subTotalNetPay += $netPay;
		
		//GRAND TOTAL
		$grandTotalWTax += $r['d_whtax'];
		$grandTotalSSS += $r['d_sss'];
		$grandTotalPhilhealth += $r['d_philhealth'];
		$grandTotalHospital += $r['d_hospital'];
		$subTotalDedCashAdvance += $r['d_cashAdvance'];
		$grandTotalUnionDues += $r['d_unionDues'];
		$grandTotalSSSLoan += $r['d_sssloan'];
		
		$grandTotalPagibig += $r['pagibig'] + $r['pagibigSavings'];
		$grandTotalPagibigHousingLoan += $r['pagibigloanh'];
		$grandTotalPagibigSalaryLoan += $r['pagibigloan'];
		
		$subTotalPNBLoan += $r['pagibigloan'];
		$grandTotalMortuary += $r['d_mortuary'];
		$grandTotalParkingFee += $r['d_parkingFee'];
		$grandTotalPnb += $r['d_pnb'];
		$grandTotalOthers += $r['d_other'] + $r['financialAssistance'];
		$grandTotalCoop += $r['d_coopTotal'];
		$grandTotalTotalDed += $totalDeduction;
		$grandTotalNetPay += $netPay;
		
		if ($ln == $rowCount){
		/*
			$data .= "<tr><td colspan='2'></td><td colspan='22'><hr></td></tr>";
				$data .= " <tr>
											<td colspan='2'> Sub Total</td>
											<td align='right'>".number_format($subTotalWTax,2)."</td>
											<td align='right'>".number_format($$subTotalSSS,2)."</td>
											<td align='right'>".number_format($subTotalPhilhealth,2)."</td>
											<td align='right'>".number_format($subTotalDedHospital,2)."</td>
											<td align='right'>".number_format($subTotalDedCashAdvance,2)."</td>
											<td align='right'>".number_format($subTotalDedUnionDues,2)."</td>
											<td align='right'>".number_format($subTotalSSSLoan,2)."</td>
											<td align='right'>".number_format($subTotalPagibigLoan,2)."</td>
											<!-- <td align='right'>".number_format($subTotalPNBLoan,2)."</td> -->
											<td align='right'>".number_format($subtotalMortuary,2)."</td>
											<td align='right'>".number_format($subtotalParkingFee,2)."</td>
											<td align='right'>".number_format($subtotalPnb,2)."</td>
											<td align='right'>".number_format($subTotalOthers,2)."</td>
											<td align='right'>".number_format($subTotalTotalDed,2)."</td>
										</tr>";
									*/
				$data .= "<tr><td colspan='24'>&nbsp;</td></tr>";
				
				$data .= " <tr>
											<td colspan='2'> Grand Total</td>
											<td align='right'>".number_format($grandTotalWTax,2)."</td>
											<td align='right'>".number_format($grandTotalSSS,2)."</td>
											<td align='right'>".number_format($grandTotalPhilhealth,2)."</td>
											<td align='right'>".number_format($grandTotalPagibig,2)."</td>
											<td align='right'>".number_format($grandTotalHospital,2)."</td>
											<td align='right'>".number_format($subTotalDedCashAdvance,2)."</td>
											<td align='right'>".number_format($grandTotalUnionDues,2)."</td>
											<td align='right'>".number_format($grandTotalSSSLoan,2)."</td>
											<td align='right'>".number_format($grandTotalPagibigHousingLoan,2)."</td>
											<td align='right'>".number_format($grandTotalPagibigSalaryLoan,2)."</td>
											<!-- <td align='right'>".number_format($grandTotalPNBLoan,2)."</td> -->
											<td align='right'>".number_format($grandTotalMortuary,2)."</td>
											<td align='right'>".number_format($grandTotalParkingFee,2)."</td>
											<td align='right'>".number_format($grandTotalPnb,2)."</td>
											<td align='right'>".number_format($grandTotalOthers,2)."</td>
											<td align='right'>".number_format($grandTotalCoop,2)."</td>
											<td align='right'>".number_format($grandTotalTotalDed,2)."</td>
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
     <table width="95%" style="font-family:Arial;font-size:12px;">
	 <thead>
	  <tr>
	       <td colspan="24" align="center" style="font-size:14px;font-weight:bold;">DAVAO DOCTORS HOSPITAL<br>DEDUCTION REPORT<br /><?php echo $reportTitle;?><br>
				 				<?php echo date('F d, Y',strtotime($cutoffDate['cutoffDateStart']))." to ".date('F d, Y',strtotime($cutoffDate['cutoffDateEnd']));?></td> </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
				<td>IDNUM</td>
	     		<td>NAME</td>
	     		<td>TAX</td>
		   		<td>SSS </td>
	      		<td>PHIC</td>
		  		<td>HDMF</td>
				<td>HOSPITAL</td>
				<td>CASH ADV</td>
				<td>UNION</td>
				<td>SSS LOAN</td>
				<td>HDMF <br> HOUSING LOAN</td>
				<td>HDMF <br> SALARY LOAN</td>
				<!-- <td>PNB LOAN</td> -->
				<td>MORTUARY</td>
				<td>PARKING <br> FEE</td>
				<td>PNB <br> LOAN</td>
				<td>OTHER</td>
				<td>COOP DED</td>
				<td>TOTAL DED</td>
	  </tr>
	  <tr><td colspan="24"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




