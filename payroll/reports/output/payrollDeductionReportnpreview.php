<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");
include("../../payrollfunctions.php");
//echo getDeductionData(1876,'current balance'); die();
//$cutOffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']."'",$conn)); //(e.isActive='1' || p.holdSalary = '1' ) &&
$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));
$sql = "SELECT e.ndex as empId,e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName
								FROM employee e 
									
									
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE e.isActive=1 && e.residencyTrainingProgram='' &&  e.ndex not in (select employeeId from loanpayments_freeze where cutoffDate='".$_POST['PayrollCutoff']."')";
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
//echo $sql;
$hdeductionsqry=mysql_query("select * from loandeductionmaintenance order by ndex");
$data.="<tr><td></td>";
while($hded=mysql_fetch_object($hdeductionsqry)){	
	$data.="<td>".$hded->name."</td>";
}
$data.="<td>TOTAL</td></tr>";
$exec=mysql_query($sql);
$var=0;
$ln = 0;
//$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_object($exec)){
	//echo $r->lastName."<br>";
	$deductionsqry=mysql_query("select * from loandeductionmaintenance order by ndex");
	$data.="<tr><td>".$r->lastName.", ".$r->firstName." ".$r->middleName."</td>";
	$total=0;
	while($ded=mysql_fetch_object($deductionsqry)){	
		$val=mysql_fetch_object(mysql_query("select * from loan_employee where loanId=".$ded->ndex." and employeeId=".$r->empId." and dedDateStart>='".$_POST['PayrollCutoff']."'"));
		$bl=number_format(getDeductionData($val->ndex,'current balance'),2);
		if($bl>=.1){
			//$deduc=$val->loanAmount / $val->nOfDeduction;
			$deduc=getDeductionData($val->ndex,'Currect Deduction Amount');
		}
		else {
			$deduc=0;
		}
		$data.="<td>".number_format($deduc,2)."</td>";
		$total+=$deduc;
	}
	$data.="<td>".number_format($total,2)."</td>
		</tr>";
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
	
	  <tr><td colspan="24"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




