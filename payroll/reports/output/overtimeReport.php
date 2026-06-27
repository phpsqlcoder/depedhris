<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");
include ("../../../myfunctions.php");
//$rr = mysql_query("set names utf8_encode;");

$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, e.ndex empId, ec.payTypeNdex payTypeNdex, ec.basicPay basicPay
								FROM employee e 
									LEFT JOIN payroll p ON p.empid = e.ndex 
									LEFT JOIN employee_compensation ec ON ec.employeeId=e.ndex
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE e.isActive='1' && p.pay_period='".$_POST['PayrollCutoff']."' && e.residencyTrainingProgram=''";
										
$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));												
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
//echo $sql;

$sql.=" ORDER BY  d.name, e.lastName,e.firstName";
$exec = mysql_query($sql);
$var = 0;
$ln = 0;
$rowCount = mysql_num_rows($exec);
while($r = mysql_fetch_assoc($exec)){
	$countRes = mysql_num_rows(mysql_query("SELECT * FROM dailytimesummary 
				WHERE date BETWEEN '".$cutoffDate['cutoffDateStart']."' AND '".$cutoffDate['cutoffDateEnd']."' && employeeId='".$r['empId']."'
					  && (approvedOvertime<>0 || approvedOvertimeNightPremium<>0)"));
	
	if ($countRes){
	  $var++;
   	$ctr1s++;
		$ln++;

		if ($r['payTypeNdex'] == '1'){
			$basicpayPerHour = payPerSpecificTime($r['basicPay'],'hour');
			$percentRd = 0.3;
			$percentOTRd = 0.69;
		} elseif ($r['payTypeNdex'] == '2') {
			$basicpayPerHour = payPerSpecificTimeDaily($r['basicPay'],'hour');
			$percentRd = 1.3;
			$percentOTRd = 1.69;
		}
		
    //if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		if($r['departmentName'] != $prevDepartment){
			if($ln != 1){
				$data.="<tr bgcolor='".$bgclr1s."'></tr>
								<td align='right'>SUBTOTAL ".$r['departmentName']."</td>
					     	<td align='right'>".number_format($depsubtotalOT,2)."</td>
					      <td align='right'>".number_format($depsubtotalOTAmount,2)."</td>
						   	<td align='right'>".number_format($depsubtotalOTExc,2)."</td>
					      <td align='right'>".number_format($depsubtotalOTExcAmount,2)."</td>
								<td align='right'>".number_format($depsubtotalOTSHol,2)."</td>
								<td align='right'>".number_format($depsubtotalOTSHolAmount,2)."</td>
								<td align='right'>".number_format($depsubtotalOTLHol,2)."</td>
								<td align='right'>".number_format($depsubtotalOTLHolAmount,2)."</td>
								<td align='right'>".number_format($depsubtotalDutyRd,2)."</td>
								<td align='right'>".number_format($depsubtotalDutyRdAmount,2)."</td>
								<td align='right'>".number_format($depsubtotalOTDutyRd,2)."</td>
								<td align='right'>".number_format($depsubtotalOTDutyRdAmount,2)."</td>
								<td align='right'>".number_format($depsubtotalOTNP,2)."</td>
								<td align='right'>".number_format($depsubtotalOTNPAmount,2)."</td>
								<td>".$d['overtimeRemarks']."</td>
					  </tr>";
				$depsubtotalOT = 0;
				$depsubtotalOTAmount = 0;
				$depsubtotalOTExc = 0;
				$depsubtotalOTExcAmount = 0;
				$depsubtotalOTSHol = 0;
				$depsubtotalOTSHolAmount = 0;
				$depsubtotalOTLHol = 0;
				$depsubtotalOTLHolAmount = 0;

				$depsubtotalDutyRd = 0;
				$depsubtotalDutyRdAmount = 0;
				$depsubtotalOTDutyRd = 0;
				$depsubtotalOTDutyRdAmount = 0;
				$depsubtotalOTNP = 0;
				$depsubtotalOTNPAmount = 0;		
				
			}
			$data.="
						<tr bgcolor='".$bgclr1s."'><td colspan='10'><br></td></tr>
						<tr bgcolor='".$bgclr1s."'>
								<td width='30' colspan='11'><b>** ".$r['departmentName']."</b></td>
					  </tr>";
		}		
		$data.="<tr bgcolor='".$bgclr1s."'><td colspan='16'><br></td></tr>
						<tr bgcolor='".$bgclr1s."'>
					<td colspan='11'><b>* ".$r['lastName']." , ".$r['firstName']." ".$r['middleName']."</b></td>
			  </tr>";
		$prevDepartment = $r['departmentName'];
		$subtotalOT = 0;
		$subtotalOTAmount = 0;
		$subtotalOTExc = 0;
		$subtotalOTExcAmount = 0;
		$subtotalOTSHol = 0;
		$subtotalOTSHolAmount = 0;

		$subtotalOTLHol = 0;
		$subtotalOTLHolAmount = 0;
		$subtotalOTNP = 0;
		$subtotalOTNPAmount = 0;
		$subtotalDutyRd = 0;
		$subtotalDutyRdAmount = 0;
		$subtotalOTDutyRd = 0;
		$subtotalOTDutyRdAmount = 0;
		
		$sql1 = "SELECT * FROM dailytimesummary 
				WHERE date BETWEEN '".$cutoffDate['cutoffDateStart']."' AND '".$cutoffDate['cutoffDateEnd']."' && employeeId='".$r['empId']."'
					  && (approvedOvertime<>0 || approvedOvertimeNightPremium<>0 || duty_rd<>0)";
		//echo $sql1."<br>";
		//$otHoliday = 0;
		//$otRegular = 0;
		$rs1 = mysql_query($sql1);
		while($d = mysql_fetch_assoc($rs1)){
	
	 		if ($d['holiday']){
				$otRegular = 0;
				if ($d['holiday'] == 'S'){
					$otSHoliday = $d['approvedOvertime'];
					$otLHoliday = 0;
				} elseif($d['holiday'] == 'L'){
					$otSHoliday = 0;
					$otLHoliday = $d['approvedOvertime'];
				}
				
	 		}	else {
	 			$otRegular = $d['ot_reg'];
				$otHoliday = 0;
				$otSHoliday = 0;
				$otLHoliday = 0;
	 		}

	 		$data.="<tr bgcolor='".$bgclr1s."'></tr>
								<td align='right'>".$d['date']."</td>
					     	<td align='right'>".number_format($otRegular,2)."</td>
					      <td align='right'>".number_format(($otRegular * $basicpayPerHour) * 1.25,2)."</td>
						   	<td align='right'>".number_format($d['ot_exc'],2)."</td>
					      <td align='right'>".number_format($d['ot_exc'] * ($basicpayPerHour * 1.625),2)."</td>
								<td align='right'>".number_format($otSHoliday,2)."</td>
								<td align='right'>".number_format(($otSHoliday * ($basicpayPerHour * 1.69)),2)."</td>
								<td align='right'>".number_format($otLHoliday,2)."</td>
								<td align='right'>".number_format(($otLHoliday * ($basicpayPerHour * 2.60)),2)."</td>
								<td align='right'>".number_format($d['duty_rd'],2)."</td>								
								<td align='right'>".number_format(($d['duty_rd']) * $basicpayPerHour * $percentRd,2)."</td>
								<td align='right'>".number_format($d['otRestDay'],2)."</td>								
								<td align='right'>".number_format(($d['otRestDay']) * $basicpayPerHour * $percentOTRd,2)."</td>
								<td align='right'>".number_format($d['approvedOvertimeNightPremium'],2)."</td>
								<td align='right'>".number_format(($d['approvedOvertimeNightPremium'] * $basicpayPerHour)* 0.10,2)."</td>
								<td>".$d['overtimeRemarks']."</td>
					  </tr>";
					  
			$subtotalOT += $otRegular;
			$subtotalOTAmount += ($otRegular * $basicpayPerHour) *1.25;
			$subtotalOTExc += $d['ot_exc'];
			$subtotalOTExcAmount += $d['ot_exc'] * ($basicpayPerHour * 1.625);

			$subtotalOTSHol += $otSHoliday;
			$subtotalOTSHolAmount += ($otSHoliday * $basicpayPerHour) * 1.69;
			$subtotalOTLHol += $otLHoliday;
			$subtotalOTLHolAmount += ($otLHoliday * $basicpayPerHour) * 2.60;


			$subtotalDutyRd += $d['duty_rd'];
			$subtotalDutyRdAmount += ($d['duty_rd']) * $basicpayPerHour * $percentRd;

			$subtotalOTDutyRd += $d['otRestDay'];
			$subtotalOTDutyRdAmount += ($d['otRestDay']) * $basicpayPerHour * $percentOTRd;

			$subtotalOTNP += $d['approvedOvertimeNightPremium'];
			$subtotalOTNPAmount += ($d['approvedOvertimeNightPremium'] * $basicpayPerHour) * 0.10;
		}	
		$data.="<tr bgcolor='".$bgclr1s."'><td colspan='16'><hr></td></tr>
						<tr bgcolor='".$bgclr1s."'></tr>
								<td align='center' >TOTAL</td>
					     	<td align='right'>".number_format($subtotalOT,2)."</td>
					      <td align='right'>".number_format($subtotalOTAmount,2)."</td>
						   	<td align='right'>".number_format($subtotalOTExc,2)."</td>
					      <td align='right'>".number_format($subtotalOTExcAmount,2)."</td>
								<td align='right'>".number_format($subtotalOTSHol,2)."</td>
								<td align='right'>".number_format($subtotalOTSHolAmount,2)."</td>
								<td align='right'>".number_format($subtotalOTLHol,2)."</td>
								<td align='right'>".number_format($subtotalOTLHolAmount,2)."</td>
								<td align='right'>".number_format($subtotalDutyRd,2)."</td>
								<td align='right'>".number_format($subtotalDutyRdAmount,2)."</td>
								<td align='right'>".number_format($subtotalOTDutyRd,2)."</td>
								<td align='right'>".number_format($subtotalOTDutyRdAmount,2)."</td>
								<td align='right'> ".number_format($subtotalOTNP,2)."</td>
								<td align='right'>".number_format($subtotalOTNPAmount,2)."</td>
								<td>".$d['overtimeRemarks']."</td>
					  </tr>";
						
			$depsubtotalOT += $subtotalOT;
			$depsubtotalOTAmount += $subtotalOTAmount ;
			$depsubtotalOTExc += $subtotalOTExc;
			$depsubtotalOTExcAmount += $subtotalOTExcAmount;
			$depsubtotalOTSHol += $subtotalOTSHol;
			$depsubtotalOTSHolAmount += $subtotalOTSHolAmount;
			$depsubtotalOTLHol += $subtotalOTLHol;
			$depsubtotalOTLHolAmount += $subtotalOTLHolAmount;
			$depsubtotalDutyRd += $subtotalDutyRd;
			$depsubtotalDutyRdAmount += $subtotalDutyRdAmount;
			$depsubtotalOTDutyRd += $subtotalOTDutyRd;
			$depsubtotalOTDutyRdAmount += $subtotalOTDutyRdAmount;			
			$depsubtotalOTNP += $subtotalOTNP;
			$depsubtotalOTNPAmount += $subtotalOTNPAmount;
						
			$grandtotalOT += $subtotalOT;
			$grandtotalOTAmount += $subtotalOTAmount ;
			$grandtotalOTExc += $subtotalOTExc;
			$grandtotalOTExcAmount += $subtotalOTExcAmount;

			$grandtotalOTSHol += $subtotalOTSHol;
			$grandtotalOTSHolAmount += $subtotalOTSHolAmount;
			$grandtotalOTLHol += $subtotalOTLHol;
			$grandtotalOTLHolAmount += $subtotalOTLHolAmount;


			$grandtotalDutyRd += $subtotalDutyRd;
			$grandtotalDutyRdAmount += $subtotalDutyRdAmount;
			$grandtotalOTDutyRd += $subtotalOTDutyRd;
			$grandtotalOTDutyRdAmount += $subtotalOTDutyRdAmount;

			$grandtotalOTNP += $subtotalOTNP;
			$grandtotalOTNPAmount += $subtotalOTNPAmount;
	} // end count result
	
	
}
$data.="<tr bgcolor='".$bgclr1s."'><td colspan='16'><hr></td></tr>
						<tr bgcolor='".$bgclr1s."'></tr>
								<td align='center' >GRAND TOTAL</td>
					     	<td align='right'>".number_format($grandtotalOT,2)."</td>
					      <td align='right'>".number_format($grandtotalOTAmount,2)."</td>
						   	<td align='right'>".number_format($grandtotalOTExc,2)."</td>
					      <td align='right'>".number_format($grandtotalOTExcAmount,2)."</td>
								<td align='right'>".number_format($grandtotalOTSHol,2)."</td>
								<td align='right'>".number_format($grandtotalOTSHolAmount,2)."</td>
								<td align='right'>".number_format($grandtotalOTLHol,2)."</td>
								<td align='right'>".number_format($grandtotalOTLHolAmount,2)."</td>

								<td align='right'>".number_format($grandtotalDutyRd,2)."</td>
								<td align='right'>".number_format($grandtotalDutyRdAmount,2)."</td>
								<td align='right'>".number_format($grandtotalOTDutyRd,2)."</td>
								<td align='right'>".number_format($grandtotalOTDutyRdAmount,2)."</td>
								<td align='right'>".number_format($grandtotalOTNP,2)."</td>
								<td align='right'>".number_format($grandtotalOTNPAmount,2)."</td>
								<td>".$d['overtimeRemarks']."</td>
					  </tr>";
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
	       <td colspan="24" align="center" style="font-size:14px;font-weight:bold;">OVERTIME REPORT<br /><?php echo $reportTitle;?><br>
				 		<?php echo date('F d, Y',strtotime($cutoffDate['cutoffDateStart']))." to ".date('F d, Y',strtotime($cutoffDate['cutoffDateEnd']));?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
				<td align="center">DATE</td>
	     	<td align="center">OT REG</td>
	      <td align="center">OT REG<br>AMNT</td>
		   	<td align="center">OT EXC</td>
	      <td align="center">OT EXC<br>AMNT</td>
				<td align="center">OT SHOL</td>
				<td align="center">OT SHOL<br>AMNT</td>
				<td align="center">OT LHOL</td>
				<td align="center">OT LHOL<br>AMNT</td>
				<td align="center">DUTY <br>RESTDAY</td>
	      			<td align="center">DUTY <br>RESTDAY<br>AMNT</td>
				<td align="center">OT <br>RESTDAY</td>
	      			<td align="center">OT <br>RESTDAY<br>AMNT</td>
				<td align="center">NP</td>
				<td align="center">NP<br>AMNT</td>
				<td align="center">REMARKS</td>
	  </tr>
	  <tr><td colspan="24"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




