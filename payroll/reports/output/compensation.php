<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../scripts/scripts.php");
include ("../../../myfunctions.php");
include ("../../../employeefunctions.php");

$cutoff = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll order by ndex desc limit 1"));
if($_POST['dep']!='on'){
	$sql = "select c.*,e.*, e.ndex employeeId, d.name departmentName from  employee e
													left join employee_compensation c on e.ndex=c.employeeId 
													LEFT JOIN dept d ON d.ndex = e.deptId	
															where e.isActive=1  && e.residencyTrainingProgram='' && c.basicPay<>0
																	";
	if ($_POST['mbtcCompany'] == 1){
		$sql .= " && e.level IN (0)";
		$reportTitle = 'TEMPORARY';
	} elseif ($_POST['mbtcCompany'] == 2) {
		$sql .= " && e.level IN (1,2)";
		$reportTitle = 'RANK & FILE';
	} elseif ($_POST['mbtcCompany'] == 3) {
		$sql .= " && e.level IN (3,4,5,6,7,8,9)";
		$reportTitle = 'SECTION HEADS AND CONFI';
	} elseif ($_POST['mbtcCompany'] == 0) {
		$reportTitle = 'ALL EMPLOYEE';
	}
	
	$sql .= " order by d.name, e.lastName,e.firstName";
	$q=mysql_query($sql);
	$rowCount = mysql_num_rows($q);
	$ln = 0;
	$personnelcount = 0;
	while($r=mysql_fetch_object($q)){
		if($r->taxType==1){$tax='Normal';}elseif($r->taxType==2){$tax='10% on Gross';}
	  $ctr1s++;
		$ln++;
		
		
		$dependents = noOfDependents($r->employeeId) ? noOfDependents($r->employeeId) : '' ;
		
		if($r->departmentName != $prevDepartment){
			if($ln != 1){
				
				$data .= "<tr><td colspan='4'></td><td colspan='9'><hr></td></tr>";
				$data .= " <tr>
											<td colspan='2'> Personnel-> ".$personnelcount."</td>
											<td colspan='3'> Sub Total</td>
											<td align='right'>".number_format($subTotalBasicPayM,2)."</td>
											<td align='right'>".number_format($subTotalBasicPayD,2)."</td>
											<td align='right'>".number_format($subTotalColaM,2)."</td>
											<td align='right'>".number_format($subTotalColaD,2)."</td>
											<td align='right'>".number_format($subTotalAllowance,2)."</td>
											<td align='right'>".number_format($subTotalincentive,2)."</td>
											<td align='right'>".number_format($subTotalHonorarium,2)."</td>
											<td align='right'>".number_format($subTotalHazardPay,2)."</td>
										</tr>";
				$data .= "<tr><td colspan='8'>&nbsp;</td></tr>";
				$subTotalBasicPayM = 0;
				$subTotalBasicPayD = 0;
				$subTotalColaM = 0;
				$subTotalColaD = 0;
				$subTotalAllowance = 0;
				$subTotalIncentive = 0;
				$subTotalHonorarium = 0;
				$subTotalHazardPay = 0;
				$personnelcount = 0;
			}
			$data .= " <tr><td colspan='8' align='left' style='font-size:14px;font-weight:bold;'>".$r->departmentName."</td></tr>";
		}
		$personnelcount++;
		if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		
		if ($r->employmentStatus == 'Regular'){
			$empStatDisplay = '';
		} elseif ($r->employmentStatus == 'Probationary'){
			$empStatDisplay = 'P';
		} elseif ($r->employmentStatus == 'Temporary') {
			$empStatDisplay = 'T';
		}
		
		if ($r->payTypeNdex == 1){ 
			$MPay = $r->basicPay; 
			$DPay = payPerSpecificTime($r->basicPay,'day'); 
			$MCola = $r->cola;
			$DCola = payPerSpecificTime($r->cola,'day');
		} else { 
			$MPay = 0; 
			$DPay = $r->basicPay; 
			$MCola = 0;
			$DCola = $r->cola;
		}
		
		
			$data.="<tr bgcolor='".$bgclr1s."'>
								<td>".getID($r->employmentStatus,$r->employeeNo)."</td>
								<td>".$r->lastName.",".$r->firstName." ".$r->middleName."</td>
								<td>".$empStatDisplay."</td>
								<td>".date('m/d/Y',strtotime($r->dateHired))."</td>
								<td align='center'>".substr($r->civilStatus,0,1).$dependents."</td>
								<td align='right'>".number_format($MPay,2)."</td>
								<td align='right'>".number_format($DPay,2)."</td>
								<td align='right'>".number_format($MCola,2)."</td>
								<td align='right'>".number_format($DCola,2)."</td>
								<td align='right'>".$r->allowance."</td>
								<td align='right'>".$r->incentive."</td>
								<td align='right'>".$r->honorarium."</td>
								<td align='right'>".$r->hazardPay."</td>
							</tr>";
							
		$prevDepartment = $r->departmentName;
		$subTotalBasicPayM += $MPay;
		$subTotalBasicPayD += $DPay;
		$subTotalColaM += $MCola;
		$subTotalColaD += $DCola;
		$subTotalAllowance += $r->allowance;
		$subTotalIncentive += $r->incentive;
		$subTotalHonorarium += $r->honorarium;
		$subTotalHazardPay += $r->hazardPay;
		
		$grandTotalBasicPayM += $MPay;
		$grandTotalBasicPayD += $DPay;
		$grandTotalColaM += $MCola;
		$grandTotalColaD += $DCola;
		$grandTotalAllowance += $r->allowance;
		$grandTotalIncentive += $r->incentive;
		$grandTotalHonorarium += $r->honorarium;
		$grandTotalHazardPay += $r->hazardPay;
		
		if ($ln == $rowCount){
			$data .= "<tr><td colspan='4'></td><td colspan='9'><hr></td></tr>";
			$data .= " <tr>
										<td colspan='2'> Personnel-> ".$personnelcount."</td>
										<td colspan='3'> Sub Total</td>
										<td align='right'>".number_format($subTotalBasicPayM,2)."</td>
										<td align='right'>".number_format($subTotalBasicPayD,2)."</td>
										<td align='right'>".number_format($subTotalColaM,2)."</td>
										<td align='right'>".number_format($subTotalColaD,2)."</td>
										<td align='right'>".number_format($subTotalAllowance,2)."</td>
										<td align='right'>".number_format($subTotalIncentive,2)."</td>
										<td align='right'>".number_format($subTotalHonorarium,2)."</td>
										<td align='right'>".number_format($subTotalHazardPay,2)."</td>
									</tr>";
			$data .= "<tr><td colspan='4'></td><td colspan='9'><hr></td></tr>";
			$data .= " <tr>
										<td colspan='2'> Total Personnel-> ".$rowCount."</td>
											<td colspan='3'> Grand Total</td>
										<td align='right'>".number_format($grandTotalBasicPayM,2)."</td>
										<td align='right'>".number_format($grandTotalBasicPayD,2)."</td>
										<td align='right'>".number_format($grandTotalColaM,2)."</td>
										<td align='right'>".number_format($grandTotalColaD,2)."</td>
										<td align='right'>".number_format($grandTotalAllowance,2)."</td>
										<td align='right'>".number_format($grandTotalIncentive,2)."</td>
										<td align='right'>".number_format($grandTotalHonorarium,2)."</td>
										<td align='right'>".number_format($grandTotalHazardPay,2)."</td>
									</tr>";
		}
	}
}
elseif($_POST['dep']=='on'){
	
}
?>
<HTML>
<HEAD>
</HEAD>

<BODY>
     <?php
if($_POST['eksel']=='on'){
		$filename ="compensationMasterFile.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="95%" style="font-family:Arial;font-size:11px;">
	 <thead>
	  <tr>
	       <td colspan="13" align="center" style="font-size:11px;font-weight:bold;">Compensation Master File<br> <?php echo $reportTitle;?><br>
		   		<?php echo date('F d, Y',strtotime($cutoff['pay_period']));?>
		   </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr align='center' style="font-weight:bold;color:blue;">
			<td>Employee No.</td>
			<td>Name</td>
			<td>ES</td></td>
			<td>Date Hired</td>
			<td>TS</td>
			<td>M-Basic Pay</td>
			<td>D-Basic Pay</td>
			<td>M-cola</td>
			<td>D-cola</td>
			<td>Allowance</td>
			<td>Incentive</td>
			<td>Honorarium</td>
			<td>Hazard Pay</td>
	  </tr>
	  <tr><td colspan="13"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
</BODY>
</HTML>
     
	  <?php include("../rptfooter.php");?>




