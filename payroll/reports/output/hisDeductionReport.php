<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");


//$cutOffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']."'",$conn)); //(e.isActive='1' || p.holdSalary = '1' ) &&
$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));
$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.*, pp.Pat_No, pp.doctorName, pp.Trx_type
								FROM employee e 
									left join ar_hospital_ee_payment_ledger p on e.ndex=p.employeeId	
									left join ar_hospital_ee_trx pp on pp.ndex=p.ar_hospital_ee_trx_Id					
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE p.paymentDate>='".$_POST['startdate']."' and p.paymentDate<='".$_POST['enddate']."' && e.residencyTrainingProgram=''";

if ($_POST['patType'] == 'IP'){
	$sql .= " && pp.PatType = 'IP'";
}
elseif ($_POST['patType'] == 'OP'){
	$sql .= " && pp.PatType = 'OP'";
}
if ($_POST['tayp'] == 'Hospital'){
	$sql .= " && pp.Trx_type = 'Hospital'";
}
elseif ($_POST['tayp'] == 'Doctor'){
	$sql .= " && pp.Trx_type = 'Doctor'";
}

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

$paymenttype='';
if(isset($_POST['payroll'])){
	$paymenttype.="'Payroll',";
}
if(isset($_POST['manual'])){
	$paymenttype.="'Manual',";
}
$paymenttype="(".rtrim($paymenttype,',').")";
$sql .= " && p.paymentType IN ".$paymenttype;
//echo $division." --".$PayrollCutoff."<br>".$sql.mysql_num_rows(mysql_query($sql));
$sql.=" ORDER BY  e.lastName,e.firstName";
//echo $sql;
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$tt=0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
		
		
    $var++;
    $ctr1s++;
		$ln++;
	
    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
    $data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 							<td align='left'>".getID($r['employmentStatus'],$r['employeeNo'])."</td>
					      <td align='left'>".$r['lastName'].", ".$r['firstName']."</td>
					      <td>".$r['Batch_No']."</td>
						   	<td>".$r['AR_No']."</td>
						   	<td>".$r['Pat_No']."</td>
						   	<td>".$r['Trx_type']."</td>
						   	<td>".$r['doctorName']."</td>
						   	<td>".number_format(($r['amountPaid']),2)."</td>
						   	<td>".$r['paymentType']."</td>
						   	<td>".($r['paymentType'] == 'Payroll' ? $r['paymentDate']:'')."</td>
						   	<td>".$r['paymentDate']."</td>
						   	<td align='center'>".$r['payment_type']."</td>
						   	<td align='left'>".$r['remarks']."</td>
						   	
					     
				
								
				     </tr>";
		$tt += $r['amountPaid'];
		
				
	
}
$data .= "<tr><td colspan='5'>&nbsp;</td></tr>";
				
				$data .= " <tr>
											<td colspan='4'> Grand Total</td>
										
											<td align='right'>".number_format($tt,2)."</td>
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
	       <td colspan="24" align="center" style="font-size:14px;font-weight:bold;">DAVAO DOCTORS HOSPITAL<br>DEDUCTION REPORT<br /><?php echo $reportTitle;?><br>
				 				<?php echo date('F d, Y',strtotime($_POST['startdate']))." to ".date('F d, Y',strtotime($_POST['enddate']));?></td> </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
				<td>IDNUM</td>
	     		<td>NAME</td>
	     		<td>BATCH NO</td>
		   		<td>AR NO </td>
		   		<td>PATIENT NO </td>
		   		<td>TYPE </td>
		   		<td>DOCTOR </td>
	      		<td>AMOUNT</td>
	      		<td>CATEGORY</td>
	      		<td>PAYROLL PERIOD</td>
	      		<td>PAYMENT DATE</td>
	      		<td>PAYMENT TYPE</td>
	      		<td>REMARKS</td>

		  	
	  </tr>
	  <tr><td colspan="24"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




