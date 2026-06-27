<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
$sql = "SELECT e.employmentStatus,e.employeeNo,e.lastName, e.firstName,e.payType, e.middleName, p.undertime, p.*, 
							 d.name departmentName
									FROM 
payroll p left join employee e on e.ndex=p.empid
										
										LEFT JOIN dept d ON d.ndex = e.deptId 
												WHERE  p.pay_period='".$_POST['PayrollCutoff']."' && e.residencyTrainingProgram=''";

$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));		
if ($_POST['mbtcCompany'] == 1){
	$sql .= " && p.level IN (0)";
	$reportTitle = 'TEMPORARY';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && p.level IN (1,2)";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && p.level IN (3,4,5)";
	$reportTitle = 'SECTION HEADS AND CONFI';
} elseif ($_POST['mbtcCompany'] == 0) {
	$reportTitle = 'ALL EMPLOYEE';
}
if ($_POST['division']){$sql .= " && e.divisionId='".$_POST['division']."'";}
//echo $division." --".$PayrollCutoff."<br>".$sql.mysql_num_rows(mysql_query($sql));
$sql.=" ORDER BY d.name, e.lastName,e.firstName";
$exec=mysql_query($sql);
$var=0;
$var2=0;
$total_emp=0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_object($exec)){
     $var++;
	 $var2++;
     $ctr1s++;
		 $ln++;
		 
		 if($r->departmentName != $prevDepartment){
		 		$data .= "<tr><td colspan='2'></td><td colspan='20'><hr></td></tr>";
				if($ln == 1){$var2=$var2-1; $total_emp=$total_emp + $var2;}
				if($ln != 1){
					$total_emp=$total_emp + $var2;
					$data.="<tr>
							       <td colspan='3'>Personnel = ".$var2."</td>
								   <td align='right'>".number_format($subTotalDaysWork,2)."</td>
							      <td align='right'>".number_format($subTotalDaysAbsent,2)."</td>
								  <td align='right'>".number_format($subTotalUndertime,2)."</td>
										<td align='right'>".number_format($subTotalDutyRd,2)."</td>
										<td align='right'>".number_format($subTotalOvertime,2)."</td>
										<td align='right'>".number_format($subTotalOvertimeexc,2)."</td>
										
										<td align='right'>".number_format($subTotalOvertimeLegalHoliday,2)."</td>
										<td align='right'>".number_format($subTotalOvertimeSpecialHoliday,2)."</td>
										<td align='right'>".number_format($subTotalOvertimeRestday,2)."</td>
										<td align='right'>".number_format($subTotalOvertimeRestdayLegalHoliday,2)."</td>
										<td align='right'>".number_format($subTotalOvertimeRestdaySpecialHoliday,2)."</td>
										
										<td align='right'>".number_format($subTotalLegHoliday,2)."</td>
										<td align='right'>".number_format($subTotalSpecHoliday,2)."</td>
										<td align='right'>".number_format($subTotalBirthdayLeave,2)."</td>
										<td align='right'>".number_format($subTotalNightPremium,2)."</td>
										<td align='right'>".number_format($subTotalNightPremiumadj,2)."</td>
										<td align='right'>".number_format($subTotalAdjDaysWork,2)."</td>
										<td align='right'>".number_format($subTotalAdjUndertime,2)."</td>
										<td align='right'>".number_format($subTotalAdjLeave,2)."</td>
						     </tr>";
		 			$var2=0;
					$subTotalOvertimeLegalHoliday = 0;
					$subTotalOvertimeSpecialHoliday = 0;
					$subTotalOvertimeRestday = 0;
					$subTotalOvertimeRestdayLegalHoliday = 0;
					$subTotalOvertimeRestdaySpecialHoliday = 0;
					
					$subTotalUndertime = 0;
					$subTotalDaysAbsent = 0;
					$subTotalDutyRd = 0;
					$subTotalOvertime = 0;
					$subTotalOvertimeexc = 0;
					$subTotalOvertimeholiday = 0;
					$subTotalSpecHoliday = 0;
					$subTotalLegHoliday = 0;
					$subTotalOtherLeave = 0;
					$subTotalSickLeave = 0;
					$subTotalVacLeave = 0;
					$subTotalNightPremium = 0;
					$subTotalNightPremiumadj = 0;
					$subTotalDaysWork = 0;
					$subTotalAdjDaysWork = 0;
					$subTotalAdjUndertime = 0;
					$subTotalAdjLeave = 0;
					$subTotalBirthdayLeave = 0;
					
					
				}
		 		$data .= " <tr><td colspan='13' align='left' style='font-size:14px;font-weight:bold;'>".$r->departmentName."</td></tr>";
		 }
		 
		 
		 
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
     $data.="<tr bgcolor='".$bgclr1s."'>
	       <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
	       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
		   <td>".substr($r->payType,0,1)."</td>
		   <td align='right'>".number_format($r->days_work,2)."</td>
	      <td align='right'>".number_format($r->days_absent,2)."</td>
		  <td align='right'>".number_format(($r->undertime),2)."</td>
				<td align='right'>".number_format(($r->duty_rd + $r->adj_duty_rd),2)."</td>
				<td align='right'>".number_format($r->ot_reg,2)."</td>
				<td align='right'>".number_format($r->ot_exc,2)."</td>
				
				<td align='right'>".number_format($r->otLHoliday,2)."</td>
				<td align='right'>".number_format($r->otSHoliday,2)."</td>
				<td align='right'>".number_format($r->otRestDay,2)."</td>
				<td align='right'>".number_format($r->otRDLHoliday,2)."</td>
				<td align='right'>".number_format($r->otRDSHoliday,2)."</td>
				
				<td align='right'>".number_format($r->lholiday,2)."</td>
				<td align='right'>".number_format($r->spholiday,2)."</td>
				<td align='right'>".number_format($r->bday_lve,2)."</td>
				<td align='right'>".number_format($r->night_prem,2)."</td>
				<td align='right'>".number_format($r->adj_night_prem,2)."</td>
				<td align='right'>".number_format($r->adj_days_work,2)."</td>
				<td align='right'>".number_format($r->adj_undertime,2)."</td>
				<td align='right'>".number_format($r->adj_sick_lve + $r->adj_vac_lve + $r->adj_official_lve + $r->adj_bday_lve,2)."</td>
				
     </tr>";
		 //<td align='right'>".$r->payrollRemarks."</td>
		$prevDepartment = $r->departmentName;
		 
		$subTotalUndertime += $r->undertime;
		$subTotalDaysAbsent += $r->days_absent;
		$subTotalDutyRd += $r->duty_rd + $r->adj_duty_rd;
		$subTotalOvertime += $r->ot_reg;
		$subTotalOvertimeexc += $r->ot_exc;
		$subTotalAdjUndertime += $r->adj_undertime;
		$subTotalOvertimeLegalHoliday += $r->otLHoliday;
		$subTotalOvertimeSpecialHoliday +=  $r->otSHoliday;
		$subTotalOvertimeRestday += $r->otRestDay;
		$subTotalOvertimeRestdayLegalHoliday += $r->otRDLHoliday;
		$subTotalOvertimeRestdaySpecialHoliday += $r->otRDSHoliday;
		
		$subTotalBirthdayLeave += $r->bday_lve;
		$subTotalSpecHoliday += $r->spholiday;
		$subTotalLegHoliday += $r->lholiday;
		$subTotalOtherLeave += 0;
		$subTotalSickLeave += $r->sick_lve;
		$subTotalVacLeave += $r->man_lve;
		$subTotalNightPremium += $r->night_prem;
		$subTotalNightPremiumadj += $r->adj_night_prem;
		$subTotalDaysWork += $r->days_work;
		$subTotalAdjDaysWork += $r->adj_days_work;
		$subTotalAdjLeave += $r->adj_sick_lve + $r->adj_vac_lve + $r->adj_official_lve + $r->adj_bday_lve;
		
		$GTotalUndertime += $r->undertime;
		$GTotalDaysAbsent += $r->days_absent;
		$GTotalDutyRd += $r->duty_rd + $r->adj_duty_rd;
		$GTotalOvertime += $r->ot_reg;
		$GTotalOvertimeexc += $r->ot_exc;
		
		$GTotalBirthdayLeave += $r->bday_lve;
		$GTotalOvertimeLegalHoliday += $r->otLHoliday;
		$GTotalOvertimeSpecialHoliday +=  $r->otSHoliday;
		$GTotalOvertimeRestday += $r->otRestDay;
		$GTotalOvertimeRestdayLegalHoliday += $r->otRDLHoliday;
		$GTotalOvertimeRestdaySpecialHoliday += $r->otRDSHoliday;
		
		$GTotalSpecHoliday += $r->spholiday;
		$GTotalLegHoliday += $r->lholiday;
		$GTotalOtherLeave += 0;
		$GTotalSickLeave += $r->sick_lve;
		$GTotalVacLeave += $r->man_lve;
		$GTotalNightPremium += $r->night_prem;
		$GTotalNightPremiumadj += $r->adj_night_prem;
		$GTotalDaysWork += $r->days_work;
		$GTotalAdjDaysWork += $r->adj_days_work;
		$GTotalAdjUndertime += $r->adj_undertime;
		$GTotalAdjLeave += $r->adj_sick_lve + $r->adj_vac_lve + $r->adj_official_lve + $r->adj_bday_lve;
		
		if ($ln == $rowCount){
			$var2++;
			$total_emp=$total_emp + $var2;
			$data .= "<tr><td colspan='2'></td><td colspan='20'><hr></td></tr>";
			$data.="<tr>
							       <td colspan='3'>Personnel = ".$var2."</td>
								   <td align='right'>".number_format($subTotalDaysWork,2)."</td>
							      <td align='right'>".number_format($subTotalDaysAbsent,2)."</td>
								  <td align='right'>".number_format($subTotalUndertime,2)."</td>
										<td align='right'>".number_format($subTotalDutyRd,2)."</td>
										<td align='right'>".number_format($subTotalOvertime,2)."</td>
										<td align='right'>".number_format($subTotalOvertimeexc,2)."</td>
										
										<td align='right'>".number_format($subTotalOvertimeLegalHoliday,2)."</td>
										<td align='right'>".number_format($subTotalOvertimeSpecialHoliday,2)."</td>
										<td align='right'>".number_format($subTotalOvertimeRestday,2)."</td>
										<td align='right'>".number_format($subTotalOvertimeRestdayLegalHoliday,2)."</td>
										<td align='right'>".number_format($subTotalOvertimeRestdaySpecialHoliday,2)."</td>
										
										
										<td align='right'>".number_format($subTotalLegHoliday,2)."</td>
										<td align='right'>".number_format($subTotalSpecHoliday,2)."</td>
										<td align='right'>".number_format($subTotalBirthdayLeave,2)."</td>
										<td align='right'>".number_format($subTotalNightPremium,2)."</td>
										<td align='right'>".number_format($subTotalNightPremiumadj,2)."</td>
										<td align='right'>".number_format($subTotalAdjDaysWork,2)."</td>
										<td align='right'>".number_format($subTotalAdjUndertime,2)."</td>
										<td align='right'>".number_format($subTotalAdjLeave,2)."</td>
						     </tr>";
				$data .= "<tr><td colspan='2'></td><td colspan='20'><hr></td></tr>";
				$data.="<tr>
							       <td colspan='3'><strong>Personnel = ".$total_emp."</strong></td>
								   <td align='right'>".number_format($GTotalDaysWork,2)."</td>
							      <td align='right'>".number_format($GTotalDaysAbsent,2)."</td>
								  <td align='right'>".number_format($GTotalUndertime,2)."</td>
										<td align='right'>".number_format($GTotalDutyRd,2)."</td>
										<td align='right'>".number_format($GTotalOvertime,2)."</td>
										<td align='right'>".number_format($GTotalOvertimeexc,2)."</td><br>
										
										<td align='right'>".number_format($GTotalOvertimeLegalHoliday,2)."</td>
										<td align='right'>".number_format($GTotalOvertimeSpecialHoliday,2)."</td>
										<td align='right'>".number_format($GTotalOvertimeRestday,2)."</td>
										<td align='right'>".number_format($GTotalOvertimeRestdayLegalHoliday,2)."</td>
										<td align='right'>".number_format($GTotalOvertimeRestdaySpecialHoliday,2)."</td>
										
										<td align='right'>".number_format($GTotalLegHoliday,2)."</td>
										<td align='right'>".number_format($GTotalSpecHoliday,2)."</td>
										<td align='right'>".number_format($GTotalBirthdayLeave,2)."</td>
										<td align='right'>".number_format($GTotalNightPremium,2)."</td>
										<td align='right'>".number_format($GTotalNightPremiumadj,2)."</td>
										<td align='right'>".number_format($GTotalAdjDaysWork,2)."</td>
										<td align='right'>".number_format($GTotalAdjUndertime,2)."</td>
										<td align='right'>".number_format($GTotalAdjLeave,2)."</td>
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
     <table width="95%" style="font-family:Tahoma, Arial;font-size:12px;">
	 <thead style="display: table-header-group;">
	  <tr>
	       <td colspan="14" align="center" style="font-size:14px;font-weight:bold;">Payroll Time Summary Report<br><?php echo $reportTitle;?><br>
				 				<?php echo date('F d, Y',strtotime($cutoffDate['cutoffDateStart']))." to ".date('F d, Y',strtotime($cutoffDate['cutoffDateEnd']));?> </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	
	  <tr>
	     	<td>ID</td>
	      <td>Name</td>
		  <td>Type</td>
		  <td>Days Work</td>
		   	
	      <td>Absent</td>
		  <td>Undertime</td>
				<td>Duty<br> Restday</td>
				<td>OT REG</td>
				<td>OT EXC</td>
				<td>OT LHOL</td>
				<td>OT SHOL</td>
				<td>OT <br>RESTDAY</td>
				<td>OT <br>RESTDAY LHOL</td>
				<td>OT <br>RESTDAY SHOL</td>
				<td>LEGAL<br> HOL</td>
				<td>SPECIAL <br> HOL</td>
				<td>B-DAY<br> Leave</td>
				<td>Night <br> Premium</td>
				<td>Adj Night <br> Premium</td>
				<td>Adj<br> Days Work</td>
				<td>Adj<br> Undertime</td>
				<td>Adj<br> Leave</td>
				
	  </tr>
	  <tr><td colspan="14"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




