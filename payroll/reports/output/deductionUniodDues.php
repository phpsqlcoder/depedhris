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
										WHERE p.pay_period='".$_POST['PayrollCutoff']."' && p.residencyTrainingProgram='' && c.basicPay<>0 && p.holdSalary<>'1'";// && e.ndex=828";
									
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

$sql.=" ORDER BY  d.name, e.lastName,e.firstName";

$exec=mysql_query($sql);
$var=0;
$ln = 0;
$coopTotal =0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
		
    $var++;
    $ctr1s++;
	$ln++;
	
	
    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
    $data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 							<td align='left'>".getID($r['employmentStatus'],$r['employeeNo'])."&nbsp; </td>
					      		<td align='left'>".$r['lastName'].", ".$r['firstName']."&nbsp; </td>
					      		<td>".number_format($r['d_unionDues'],2)."&nbsp; </td>
				     </tr>";
	$coopTotal += $r['d_unionDues'];

}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="unionduesdeductionreport.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="600" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:11px;">
	 <thead>
	  <tr>
	       <td colspan="24" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>UNION DEUES DEDUCTION<br /><?php echo $reportTitle;?><br>
				 				<?php echo date('M d, Y',strtotime($cutoffDate['cutoffDateStart']))." to ".date('M d, Y',strtotime($cutoffDate['cutoffDateEnd']));?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
			<td width="75">IDNUM</td>
	     	<td width="275">NAME</td>
	     	<td width="75">DEDUCTION</td>
	  </tr>
	  <tr><td colspan="3"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  <tr><td colspan="3"><hr></td></tr>
	   <tr valign="bottom" align="center">
			<td colspan="2">TOTAL</td>
	     	<td align="right"><?php echo number_format($coopTotal,2);?>&nbsp; </td>
	  </tr>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




