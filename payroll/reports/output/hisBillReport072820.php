<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");
include ("../../hospital_deduction_functions.php");

//$cutOffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']."'",$conn)); //(e.isActive='1' || p.holdSalary = '1' ) &&
$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName,e.ndex as endex,p.ndex as hnd, p.*
								FROM ar_hospital_ee_trx p 
									left join employee e on e.ndex=p.employeeId							
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE   e.residencyTrainingProgram=''";
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

if ($_POST['patType'] == 'IP'){
	$sql .= " && p.PatType = 'IP'";
}
elseif ($_POST['patType'] == 'OP'){
	$sql .= " && p.PatType = 'OP'";
}
if ($_POST['tayp'] == 'Hospital'){
	$sql .= " && p.Trx_type = 'Hospital'";
}
elseif ($_POST['tayp'] == 'Doctor'){
	$sql .= " && p.Trx_type = 'Doctor'";
}


//echo $division." --".$PayrollCutoff."<br>".$sql.mysql_num_rows(mysql_query($sql));
$sql.=" ORDER BY  e.lastName,e.firstName";
//echo $sql; die();
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$tt=0;
$total_amt =0;
$total_paid =0;
$total_balance =0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
$paid = get_paid_per_transactionId($r['hnd']);
    $var++;
    $ctr1s++;
	$ln++;
	$bal = $r['Amount'] - $paid;
	$total_amt +=$r['Amount'];
	$total_paid +=$paid;
	$total_balance +=$bal;

	if($r['Amount'] > 0){
	    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	    $data.="<tr bgcolor='".$bgclr1s."' align='right'>
		 							<td align='left'>".getID($r['employmentStatus'],$r['employeeNo'])."</td>
						      <td align='left'>".$r['lastName'].", ".$r['firstName']."</td>
						      <td align='left'>".$r['departmentName']."</td>
						      <td>".$r['Batch_No']."</td>
							   	<td>".$r['AR_No']."</td>
							   	<td>".$r['PatType']."</td>
							   	<td>".$r['Pat_No']."</td>
							   	<td>".$r['doctorName']."</td>
							   	<td>".number_format($r['Amount'],2)."</td>
							   	<td>".number_format($paid,2)."</td>
							   	<td>".number_format($bal,2)."</td>				
					     </tr>";

		
	}			
	
}
$data .= "<tr><td colspan='5'>&nbsp;</td></tr>";
				
				$data .= " <tr>
											<td colspan='6'> Grand Total</td>
										
											<td align='right'>".number_format($total_amt,2)."</td>
											<td align='right'>".number_format($total_paid,2)."</td>
											<td align='right'>".number_format($total_balance,2)."</td>
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
	       <td colspan="24" align="center" style="font-size:14px;font-weight:bold;">DAVAO DOCTORS HOSPITAL<br>HOSPITAL BILL BALANCE REPORT<br /><?php echo $reportTitle;?><br>
				 				As of:<?php echo date('F d, Y');?></td> </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
				<td>IDNUM</td>
	     		<td>NAME</td>
	     		<td>DEPT</td>
	     		<td>BATCH NO</td>
		   		<td>AR NO </td>
		   		<td>DESCRIPTION</td>
	      		<td>PATIENT NO</td>
	      		<td>DOCTORS NAME</td>
	      		<td>TOTAL AMT</td>
	      		<td>PAYMENTS</td>
	      		<td>BALANCE</td>
		  	
	  </tr>
	  <tr><td colspan="24"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




