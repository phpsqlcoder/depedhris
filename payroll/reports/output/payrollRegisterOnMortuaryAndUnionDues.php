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
	$sql .= " && p.level IN (3,4,5)";
	$level = " && level IN (3,4,5)";
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
	
	$janA = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-01-15')))."' && empid='".$r['empid']."' {$level}"));
	$janB = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-01-01')))."' && empid='".$r['empid']."' {$level}"));
	$febA = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-02-15')))."' && empid='".$r['empid']."' {$level}"));
	$febB = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-02-01')))."' && empid='".$r['empid']."' {$level}"));
	$marA = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-03-15')))."' && empid='".$r['empid']."' {$level}"));
	$marB = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-03-01')))."' && empid='".$r['empid']."' {$level}"));
	$aprA = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-04-15')))."' && empid='".$r['empid']."' {$level}"));
	$aprB = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-04-01')))."' && empid='".$r['empid']."' {$level}"));
	$mayA = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-05-15')))."' && empid='".$r['empid']."' {$level}"));
	$mayB = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-05-01')))."' && empid='".$r['empid']."' {$level}"));
	$junA = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-06-15')))."' && empid='".$r['empid']."' {$level}"));
	$junB = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-06-01')))."' && empid='".$r['empid']."' {$level}"));
	$julA = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-07-15')))."' && empid='".$r['empid']."' {$level}"));
	$julB = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-07-01')))."' && empid='".$r['empid']."' {$level}"));
	$augA = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-08-15')))."' && empid='".$r['empid']."' {$level}"));
	$augB = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-08-01')))."' && empid='".$r['empid']."' {$level}"));
	$sepA = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-09-15')))."' && empid='".$r['empid']."' {$level}"));
	$sepB = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-09-01')))."' && empid='".$r['empid']."' {$level}"));
	$octA = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-10-15')))."' && empid='".$r['empid']."' {$level}"));
	$octB = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-10-01')))."' && empid='".$r['empid']."' {$level}"));
	$novA = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-11-15')))."' && empid='".$r['empid']."' {$level}"));
	$novB = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-11-01')))."' && empid='".$r['empid']."' {$level}"));
	$decA = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-12-15')))."' && empid='".$r['empid']."' {$level}"));
	$decB = mysql_fetch_assoc(mysql_query("SELECT SUM(d_unionDues + d_mortuary) mortuaryUnionDues FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-12-01')))."' && empid='".$r['empid']."' {$level}"));

	// SICK LEAVE 

	$totalV = $janA['mortuaryUnionDues'] + 
			  $janB['mortuaryUnionDues'] +
			  $febA['mortuaryUnionDues'] + 
			  $febB['mortuaryUnionDues'] +
			  $marA['mortuaryUnionDues'] + 
			  $marB['mortuaryUnionDues'] +
			  $aprA['mortuaryUnionDues'] + 
			  $aprB['mortuaryUnionDues'] +
			  $mayA['mortuaryUnionDues'] + 
			  $mayB['mortuaryUnionDues'] +
			  $junA['mortuaryUnionDues'] + 
			  $junB['mortuaryUnionDues'] +
			  $julA['mortuaryUnionDues'] + 
			  $julB['mortuaryUnionDues'] +
			  $augA['mortuaryUnionDues'] + 
			  $augB['mortuaryUnionDues'] +
			  $sepA['mortuaryUnionDues'] + 
			  $sepB['mortuaryUnionDues'] +
			  $octA['mortuaryUnionDues'] + 
			  $octB['mortuaryUnionDues'] +
			  $novA['mortuaryUnionDues'] + 
			  $novB['mortuaryUnionDues'] +
			  $decA['mortuaryUnionDues'] + 
			  $decB['mortuaryUnionDues'];
	$grandTotal += $totalV;
	$janATotal += $janA['mortuaryUnionDues']; 
	$janBTotal += $janB['mortuaryUnionDues'];
	$febATotal += $janA['mortuaryUnionDues']; 
	$febBTotal += $janB['mortuaryUnionDues'];
	$marATotal += $janA['mortuaryUnionDues']; 
	$marBTotal += $janB['mortuaryUnionDues'];
	$aprATotal += $janA['mortuaryUnionDues']; 
	$aprBTotal += $janB['mortuaryUnionDues'];
	$mayATotal += $janA['mortuaryUnionDues']; 
	$mayBTotal += $janB['mortuaryUnionDues'];
	$junATotal += $janA['mortuaryUnionDues']; 
	$junBTotal += $janB['mortuaryUnionDues'];
	$julATotal += $janA['mortuaryUnionDues']; 
	$julBTotal += $janB['mortuaryUnionDues'];
	$augATotal += $janA['mortuaryUnionDues']; 
	$augBTotal += $janB['mortuaryUnionDues'];
	$sepATotal += $janA['mortuaryUnionDues']; 
	$sepBTotal += $janB['mortuaryUnionDues'];
	$octATotal += $janA['mortuaryUnionDues']; 
	$octBTotal += $janB['mortuaryUnionDues'];
	$novATotal += $janA['mortuaryUnionDues']; 
	$novBTotal += $janB['mortuaryUnionDues'];
	$decATotal += $janA['mortuaryUnionDues']; 
	$decBTotal += $janB['mortuaryUnionDues'];
	
	$data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 		<td>".$r['employeeNo']."</td>
	     	<td align='left'>".$r['lastName'].", ".$r['firstName']."</td>
	      	<td>".number_format(($janA['mortuaryUnionDues'] != 0 ? $janA['mortuaryUnionDues'] : '0' ),2)."</td>
		   	<td>".number_format(($janB['mortuaryUnionDues'] != 0 ? $janB['mortuaryUnionDues'] : '0' ),2)."</td>
	      	<td>".number_format(($febA['mortuaryUnionDues'] != 0 ? $febA['mortuaryUnionDues'] : '0' ),2)."</td>
		   	<td>".number_format(($febB['mortuaryUnionDues'] != 0 ? $febB['mortuaryUnionDues'] : '0' ),2)."</td>
			<td>".number_format(($marA['mortuaryUnionDues'] != 0 ? $marA['mortuaryUnionDues'] : '0' ),2)."</td>
		   	<td>".number_format(($marB['mortuaryUnionDues'] != 0 ? $marB['mortuaryUnionDues'] : '0' ),2)."</td>
	      	<td>".number_format(($aprA['mortuaryUnionDues'] != 0 ? $aprB['mortuaryUnionDues'] : '0' ),2)."</td>
		   	<td>".number_format(($aprB['mortuaryUnionDues'] != 0 ? $aprB['mortuaryUnionDues'] : '0' ),2)."</td>
			<td>".number_format(($mayA['mortuaryUnionDues'] != 0 ? $mayA['mortuaryUnionDues'] : '0' ),2)."</td>
		   	<td>".number_format(($mayB['mortuaryUnionDues'] != 0 ? $mayB['mortuaryUnionDues'] : '0' ),2)."</td>
	      	<td>".number_format(($junA['mortuaryUnionDues'] != 0 ? $junA['mortuaryUnionDues'] : '0' ),2)."</td>
		   	<td>".number_format(($junB['mortuaryUnionDues'] != 0 ? $junB['mortuaryUnionDues'] : '0' ),2)."</td>
			<td>".number_format(($julA['mortuaryUnionDues'] != 0 ? $julA['mortuaryUnionDues'] : '0' ),2)."</td>
		   	<td>".number_format(($julB['mortuaryUnionDues'] != 0 ? $julB['mortuaryUnionDues'] : '0' ),2)."</td>
	      	<td>".number_format(($augA['mortuaryUnionDues'] != 0 ? $augA['mortuaryUnionDues'] : '0' ),2)."</td>
		   	<td>".number_format(($augB['mortuaryUnionDues'] != 0 ? $augB['mortuaryUnionDues'] : '0' ),2)."</td>
			<td>".number_format(($sepA['mortuaryUnionDues'] != 0 ? $sepA['mortuaryUnionDues'] : '0' ),2)."</td>
		   	<td>".number_format(($sepB['mortuaryUnionDues'] != 0 ? $sepB['mortuaryUnionDues'] : '0' ),2)."</td>
	      	<td>".number_format(($octA['mortuaryUnionDues'] != 0 ? $octA['mortuaryUnionDues'] : '0' ),2)."</td>
		   	<td>".number_format(($octB['mortuaryUnionDues'] != 0 ? $octB['mortuaryUnionDues'] : '0' ),2)."</td>
			<td>".number_format(($novA['mortuaryUnionDues'] != 0 ? $novA['mortuaryUnionDues'] : '0' ),2)."</td>
		   	<td>".number_format(($novB['mortuaryUnionDues'] != 0 ? $novB['mortuaryUnionDues'] : '0' ),2)."</td>
	      	<td>".number_format(($decA['mortuaryUnionDues'] != 0 ? $decA['mortuaryUnionDues'] : '0' ),2)."</td>
		   	<td>".number_format(($decB['mortuaryUnionDues'] != 0 ? $decB['mortuaryUnionDues'] : '0' ),2)."</td>
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
	       <td colspan="27" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>PAYROLL REGISTER ON MORTUARY AND UNION DUES<br /><?php echo $reportTitle;?><br>
				 				<?php echo date('M d, Y',strtotime($PayrollYear.'-01-01'))." to ".date('M d, Y',strtotime($PayrollYear.'-12-31'));?></td>
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




