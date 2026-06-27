<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");



//echo $_POST['PayrollYear']." ".$_POST['mbtcCompany'];
//$lastDateJan = date('t',date($_POST['PayrollYear'].'-m-d'));

$sql = "SELECT e.ndex empid, e.lastName, e.firstName, e.middleName, e.employeeNo 
								FROM employee e 
								LEFT JOIN payroll p ON p.empid=e.ndex
										WHERE DATE_FORMAT(p.pay_period,'%Y') = '".$_POST['PayrollYear']."' ";// && e.ndex=828";
									
//$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));												

if ($_POST['mbtcCompany'] == 1){
	$sql .= " && p.level IN (0)";
	$level = " && level IN (0)";
	$reportTitle = 'TEMPORARY';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && p.level IN (1,2)";
	$level = " && level IN (1,2)";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && p.level IN (3,4,5,6,7,8,9)";
	$level = " && level IN (3,4,5,6,7,8,9)";
	$reportTitle = 'SECTION HEADS AND CONFI';
} elseif ($_POST['mbtcCompany'] == 0) {
	$reportTitle = 'ALL EMPLOYEE';
}

$sql.=" GROUP BY e.ndex, e.lastName, e.firstName, e.middleName, e.employeeNo
		ORDER BY  e.lastName, e.firstName";
//echo $sql;
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$lk=0;
$tlk=0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
	$var++;
  	$ctr1s++;
	$ln++;
	$lk++;
	$tlk++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	
	$janA = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-01-15')))."' && empid='".$r['empid']."' {$level}"));
	$janB = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-01-01')))."' && empid='".$r['empid']."' {$level}"));
	$febA = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-02-15')))."' && empid='".$r['empid']."' {$level}"));
	$febB = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-02-01')))."' && empid='".$r['empid']."' {$level}"));
	$marA = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-03-15')))."' && empid='".$r['empid']."' {$level}"));
	$marB = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-03-01')))."' && empid='".$r['empid']."' {$level}"));
	$aprA = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-04-15')))."' && empid='".$r['empid']."' {$level}"));
	$aprB = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-04-01')))."' && empid='".$r['empid']."' {$level}"));
	$mayA = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-05-15')))."' && empid='".$r['empid']."' {$level}"));
	$mayB = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-05-01')))."' && empid='".$r['empid']."' {$level}"));
	$junA = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-06-15')))."' && empid='".$r['empid']."' {$level}"));
	$junB = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-06-01')))."' && empid='".$r['empid']."' {$level}"));
	$julA = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-07-15')))."' && empid='".$r['empid']."' {$level}"));
	$julB = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-07-01')))."' && empid='".$r['empid']."' {$level}"));
	$augA = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-08-15')))."' && empid='".$r['empid']."' {$level}"));
	$augB = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-08-01')))."' && empid='".$r['empid']."' {$level}"));
	$sepA = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-09-15')))."' && empid='".$r['empid']."' {$level}"));
	$sepB = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-09-01')))."' && empid='".$r['empid']."' {$level}"));
	$octA = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-10-15')))."' && empid='".$r['empid']."' {$level}"));
	$octB = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-10-01')))."' && empid='".$r['empid']."' {$level}"));
	$novA = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-11-15')))."' && empid='".$r['empid']."' {$level}"));
	$novB = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-11-01')))."' && empid='".$r['empid']."' {$level}"));
	$decA = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-12-15')))."' && empid='".$r['empid']."' {$level}"));
	$decB = mysql_fetch_assoc(mysql_query("SELECT *  FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-12-01')))."' && empid='".$r['empid']."' {$level}"));

	// SICK LEAVE 
	//$d_philhealth = SUM(netBasic + cola + allowance + honorarium + payNightPremium + payOTReg + payOTExc + oth_income + onCallOvertime + payDutyRd + paySpHoliday + payLHoliday + otRDLHolidayPay + otRDSHolidayPay + otLHolidayPay + otSHolidayPay + otRestDayPay - payUndertime +  adj_other)
	
	$totalV = $janA['d_philhealth'] + 
			  $janB['d_philhealth'] +
			  $febA['d_philhealth'] + 
			  $febB['d_philhealth'] +
			  $marA['d_philhealth'] + 
			  $marB['d_philhealth'] +
			  $aprA['d_philhealth'] + 
			  $aprB['d_philhealth'] +
			  $mayA['d_philhealth'] + 
			  $mayB['d_philhealth'] +
			  $junA['d_philhealth'] + 
			  $junB['d_philhealth'] +
			  $julA['d_philhealth'] + 
			  $julB['d_philhealth'] +
			  $augA['d_philhealth'] + 
			  $augB['d_philhealth'] +
			  $sepA['d_philhealth'] + 
			  $sepB['d_philhealth'] +
			  $octA['d_philhealth'] + 
			  $octB['d_philhealth'] +
			  $novA['d_philhealth'] + 
			  $novB['d_philhealth'] +
			  $decA['d_philhealth'] + 
			  $decB['d_philhealth'];
	$grandTotal += $totalV;
	$janATotal += $janA['d_philhealth']; 
	$janBTotal += $janB['d_philhealth'];
	$febATotal += $janA['d_philhealth']; 
	$febBTotal += $janB['d_philhealth'];
	$marATotal += $janA['d_philhealth']; 
	$marBTotal += $janB['d_philhealth'];
	$aprATotal += $janA['d_philhealth']; 
	$aprBTotal += $janB['d_philhealth'];
	$mayATotal += $janA['d_philhealth']; 
	$mayBTotal += $janB['d_philhealth'];
	$junATotal += $janA['d_philhealth']; 
	$junBTotal += $janB['d_philhealth'];
	$julATotal += $janA['d_philhealth']; 
	$julBTotal += $janB['d_philhealth'];
	$augATotal += $janA['d_philhealth']; 
	$augBTotal += $janB['d_philhealth'];
	$sepATotal += $janA['d_philhealth']; 
	$sepBTotal += $janB['d_philhealth'];
	$octATotal += $janA['d_philhealth']; 
	$octBTotal += $janB['d_philhealth'];
	$novATotal += $janA['d_philhealth']; 
	$novBTotal += $janB['d_philhealth'];
	$decATotal += $janA['d_philhealth']; 
	$decBTotal += $janB['d_philhealth'];
	
	$data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 		<td>".$r['employeeNo']."</td>
	     	<td align='left'>".$r['lastName'].", ".$r['firstName']."</td>
	      	<td>".number_format(($janA['d_philhealth'] != 0 ? $janA['d_philhealth'] : '0' ),2)."</td>
		   	<td>".number_format(($janB['d_philhealth'] != 0 ? $janB['d_philhealth'] : '0' ),2)."</td>
	      	<td>".number_format(($febA['d_philhealth'] != 0 ? $febA['d_philhealth'] : '0' ),2)."</td>
		   	<td>".number_format(($febB['d_philhealth'] != 0 ? $febB['d_philhealth'] : '0' ),2)."</td>
			<td>".number_format(($marA['d_philhealth'] != 0 ? $marA['d_philhealth'] : '0' ),2)."</td>
		   	<td>".number_format(($marB['d_philhealth'] != 0 ? $marB['d_philhealth'] : '0' ),2)."</td>
	      	<td>".number_format(($aprA['d_philhealth'] != 0 ? $aprA['d_philhealth'] : '0' ),2)."</td>
		   	<td>".number_format(($aprB['d_philhealth'] != 0 ? $aprB['d_philhealth'] : '0' ),2)."</td>
			<td>".number_format(($mayA['d_philhealth'] != 0 ? $mayA['d_philhealth'] : '0' ),2)."</td>
		   	<td>".number_format(($mayB['d_philhealth'] != 0 ? $mayB['d_philhealth'] : '0' ),2)."</td>
	      	<td>".number_format(($junA['d_philhealth'] != 0 ? $junA['d_philhealth'] : '0' ),2)."</td>
		   	<td>".number_format(($junB['d_philhealth'] != 0 ? $junB['d_philhealth'] : '0' ),2)."</td>
			<td>".number_format(($julA['d_philhealth'] != 0 ? $julA['d_philhealth'] : '0' ),2)."</td>
		   	<td>".number_format(($julB['d_philhealth'] != 0 ? $julB['d_philhealth'] : '0' ),2)."</td>
	      	<td>".number_format(($augA['d_philhealth'] != 0 ? $augA['d_philhealth'] : '0' ),2)."</td>
		   	<td>".number_format(($augB['d_philhealth'] != 0 ? $augB['d_philhealth'] : '0' ),2)."</td>
			<td>".number_format(($sepA['d_philhealth'] != 0 ? $sepA['d_philhealth'] : '0' ),2)."</td>
		   	<td>".number_format(($sepB['d_philhealth'] != 0 ? $sepB['d_philhealth'] : '0' ),2)."</td>
	      	<td>".number_format(($octA['d_philhealth'] != 0 ? $octA['d_philhealth'] : '0' ),2)."</td>
		   	<td>".number_format(($octB['d_philhealth'] != 0 ? $octB['d_philhealth'] : '0' ),2)."</td>
			<td>".number_format(($novA['d_philhealth'] != 0 ? $novA['d_philhealth'] : '0' ),2)."</td>
		   	<td>".number_format(($novB['d_philhealth'] != 0 ? $novB['d_philhealth'] : '0' ),2)."</td>
	      	<td>".number_format(($decA['d_philhealth'] != 0 ? $decA['d_philhealth'] : '0' ),2)."</td>
		   	<td>".number_format(($decB['d_philhealth'] != 0 ? $decB['d_philhealth'] : '0' ),2)."</td>
			<td>".number_format($totalV, 2)."</td>
				     </tr>";
	$data .= "<tr><td colspan='24'>&nbsp;</td></tr>";

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
	       <td colspan="27" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>PAYROLL REGISTER ON PHILHEALTH DEDUCTION<br /><?php echo $reportTitle;?><br>
				 				<?php echo "YEAR ".$_POST['PayrollYear']; //echo date('M d, Y',strtotime($PayrollYear.'-01-01'))." to ".date('M d, Y',strtotime($PayrollYear.'-12-31'));?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
			<td>IDNUM</td>
	     	<td>NAME</td>
	      	<td>15-Jan</td>
		   	<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-01-d')));?>-Jan</td>
	      	<td>15-Feb</td>
			<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-02-d')));?>-Feb</td>
			<td>15-Mar</td>
		   	<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-03-d')));?>-Mar</td>
	      	<td>15-Apr</td>
			<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-04-01')));?>-Apr</td>
			<td>15-May</td>
		   	<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-05-d')));?>-May</td>
	      	<td>15-Jun</td>
			<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-06-01')));?>-Jun</td>
			<td>15-Jul</td>
		   	<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-07-d')));?>-Jul</td>
	      	<td>15-Aug</td>
			<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-08-01')));?>-Aug</td>
			<td>15-Sep</td>
		   	<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-09-d')));?>-Sep</td>
	      	<td>15-Oct</td>
			<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-10-01')));?>-Oct</td>
			<td>15-Nov</td>
		   	<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-11-d')));?>-Nov</td>
	      	<td>15-Dec</td>
			<td><?php echo date('t',strtotime(date($_POST['PayrollYear'].'-12-01')));?>-Dec</td>
			<td>TOTAL</td>
	  </tr>
	  <tr><td colspan="27"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
	  <tr><td colspan="27"><hr></td></tr>
	  <tr><td colspan="2">Grand Total</td>
	  <td><?php echo number_format($janATotal,2);?></td>
	  <td><?php echo number_format($janBTotal,2);?></td>
	  <td><?php echo number_format($febATotal,2);?></td>
	  <td><?php echo number_format($febBTotal,2);?></td>
	  <td><?php echo number_format($marATotal,2);?></td>
	  <td><?php echo number_format($marBTotal,2);?></td>
	  <td><?php echo number_format($aprATotal,2);?></td>
	  <td><?php echo number_format($aprBTotal,2);?></td>
	  <td><?php echo number_format($mayATotal,2);?></td>
	  <td><?php echo number_format($mayBTotal,2);?></td>
	  <td><?php echo number_format($junATotal,2);?></td>
	  <td><?php echo number_format($junBTotal,2);?></td>
	  <td><?php echo number_format($julATotal,2);?></td>
	  <td><?php echo number_format($julBTotal,2);?></td>
	  <td><?php echo number_format($augATotal,2);?></td>
	  <td><?php echo number_format($augBTotal,2);?></td>
	  <td><?php echo number_format($sepATotal,2);?></td>
	  <td><?php echo number_format($sepBTotal,2);?></td>
	  <td><?php echo number_format($octATotal,2);?></td>
	  <td><?php echo number_format($octBTotal,2);?></td>
	  <td><?php echo number_format($novATotal,2);?></td>
	  <td><?php echo number_format($novBTotal,2);?></td>
	  <td><?php echo number_format($decATotal,2);?></td>
	  <td><?php echo number_format($decBTotal,2);?></td>
	  <td><?php echo number_format($grandTotal,2);?></td>
	  </tr>
	  <tr><td colspan="27"><hr></td></tr>
      </table>
	  <?php include("../rptfooter.php");?>




