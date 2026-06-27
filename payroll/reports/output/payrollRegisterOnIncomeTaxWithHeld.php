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
	
	$janA = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-01-15')))."' && empid='".$r['empid']."'"));
	$janB = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-01-01')))."' && empid='".$r['empid']."'"));
	$febA = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-02-15')))."' && empid='".$r['empid']."'"));
	$febB = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-02-01')))."' && empid='".$r['empid']."'"));
	$marA = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-03-15')))."' && empid='".$r['empid']."'"));
	$marB = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-03-01')))."' && empid='".$r['empid']."'"));
	$aprA = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-04-15')))."' && empid='".$r['empid']."'"));
	$aprB = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-04-01')))."' && empid='".$r['empid']."'"));
	$mayA = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-05-15')))."' && empid='".$r['empid']."'"));
	$mayB = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-05-01')))."' && empid='".$r['empid']."'"));
	$junA = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-06-15')))."' && empid='".$r['empid']."'"));
	$junB = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-06-01')))."' && empid='".$r['empid']."'"));
	$julA = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-07-15')))."' && empid='".$r['empid']."'"));
	$julB = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-07-01')))."' && empid='".$r['empid']."'"));
	$augA = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-08-15')))."' && empid='".$r['empid']."'"));
	$augB = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-08-01')))."' && empid='".$r['empid']."'"));
	$sepA = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-09-15')))."' && empid='".$r['empid']."'"));
	$sepB = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-09-01')))."' && empid='".$r['empid']."'"));
	$octA = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-10-15')))."' && empid='".$r['empid']."'"));
	$octB = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-10-01')))."' && empid='".$r['empid']."'"));
	$novA = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-11-15')))."' && empid='".$r['empid']."'"));
	$novB = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-11-01')))."' && empid='".$r['empid']."'"));
	$decA = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-d',strtotime(date($_POST['PayrollYear'].'-12-15')))."' && empid='".$r['empid']."'"));
	$decB = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll WHERE pay_period='".date('Y-m-t',strtotime(date($_POST['PayrollYear'].'-12-01')))."' && empid='".$r['empid']."'"));

	// SICK LEAVE 

	$totalV = $janA['d_whtax'] + 
			  $janB['d_whtax'] +
			  $febA['d_whtax'] + 
			  $febB['d_whtax'] +
			  $marA['d_whtax'] + 
			  $marB['d_whtax'] +
			  $aprA['d_whtax'] + 
			  $aprB['d_whtax'] +
			  $mayA['d_whtax'] + 
			  $mayB['d_whtax'] +
			  $junA['d_whtax'] + 
			  $junB['d_whtax'] +
			  $julA['d_whtax'] + 
			  $julB['d_whtax'] +
			  $augA['d_whtax'] + 
			  $augB['d_whtax'] +
			  $sepA['d_whtax'] + 
			  $sepB['d_whtax'] +
			  $octA['d_whtax'] + 
			  $octB['d_whtax'] +
			  $novA['d_whtax'] + 
			  $novB['d_whtax'] +
			  $decA['d_whtax'] + 
			  $decB['d_whtax'];
	$grandTotal += $totalV;
	$janATotal += $janA['d_whtax']; 
	$janBTotal += $janB['d_whtax'];
	$febATotal += $janA['d_whtax']; 
	$febBTotal += $janB['d_whtax'];
	$marATotal += $janA['d_whtax']; 
	$marBTotal += $janB['d_whtax'];
	$aprATotal += $janA['d_whtax']; 
	$aprBTotal += $janB['d_whtax'];
	$mayATotal += $janA['d_whtax']; 
	$mayBTotal += $janB['d_whtax'];
	$junATotal += $janA['d_whtax']; 
	$junBTotal += $janB['d_whtax'];
	$julATotal += $janA['d_whtax']; 
	$julBTotal += $janB['d_whtax'];
	$augATotal += $janA['d_whtax']; 
	$augBTotal += $janB['d_whtax'];
	$sepATotal += $janA['d_whtax']; 
	$sepBTotal += $janB['d_whtax'];
	$octATotal += $janA['d_whtax']; 
	$octBTotal += $janB['d_whtax'];
	$novATotal += $janA['d_whtax']; 
	$novBTotal += $janB['d_whtax'];
	$decATotal += $janA['d_whtax']; 
	$decBTotal += $janB['d_whtax'];
	
	$data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 		<td>".$r['employeeNo']."</td>
	     	<td align='left'>".$r['lastName'].", ".$r['firstName']."</td>
	      	<td>".number_format(($janA['d_whtax'] != 0 ? $janA['d_whtax'] : '0' ),2)."</td>
		   	<td>".number_format(($janB['d_whtax'] != 0 ? $janB['d_whtax'] : '0' ),2)."</td>
	      	<td>".number_format(($febA['d_whtax'] != 0 ? $febA['d_whtax'] : '0' ),2)."</td>
		   	<td>".number_format(($febB['d_whtax'] != 0 ? $febB['d_whtax'] : '0' ),2)."</td>
			<td>".number_format(($marA['d_whtax'] != 0 ? $marA['d_whtax'] : '0' ),2)."</td>
		   	<td>".number_format(($marB['d_whtax'] != 0 ? $marB['d_whtax'] : '0' ),2)."</td>
	      	<td>".number_format(($aprA['d_whtax'] != 0 ? $aprB['d_whtax'] : '0' ),2)."</td>
		   	<td>".number_format(($aprB['d_whtax'] != 0 ? $aprB['d_whtax'] : '0' ),2)."</td>
			<td>".number_format(($mayA['d_whtax'] != 0 ? $mayA['d_whtax'] : '0' ),2)."</td>
		   	<td>".number_format(($mayB['d_whtax'] != 0 ? $mayB['d_whtax'] : '0' ),2)."</td>
	      	<td>".number_format(($junA['d_whtax'] != 0 ? $junA['d_whtax'] : '0' ),2)."</td>
		   	<td>".number_format(($junB['d_whtax'] != 0 ? $junB['d_whtax'] : '0' ),2)."</td>
			<td>".number_format(($julA['d_whtax'] != 0 ? $julA['d_whtax'] : '0' ),2)."</td>
		   	<td>".number_format(($julB['d_whtax'] != 0 ? $julB['d_whtax'] : '0' ),2)."</td>
	      	<td>".number_format(($augA['d_whtax'] != 0 ? $augA['d_whtax'] : '0' ),2)."</td>
		   	<td>".number_format(($augB['d_whtax'] != 0 ? $augB['d_whtax'] : '0' ),2)."</td>
			<td>".number_format(($sepA['d_whtax'] != 0 ? $sepA['d_whtax'] : '0' ),2)."</td>
		   	<td>".number_format(($sepB['d_whtax'] != 0 ? $sepB['d_whtax'] : '0' ),2)."</td>
	      	<td>".number_format(($octA['d_whtax'] != 0 ? $octA['d_whtax'] : '0' ),2)."</td>
		   	<td>".number_format(($octB['d_whtax'] != 0 ? $octB['d_whtax'] : '0' ),2)."</td>
			<td>".number_format(($novA['d_whtax'] != 0 ? $novA['d_whtax'] : '0' ),2)."</td>
		   	<td>".number_format(($novB['d_whtax'] != 0 ? $novB['d_whtax'] : '0' ),2)."</td>
	      	<td>".number_format(($decA['d_whtax'] != 0 ? $decA['d_whtax'] : '0' ),2)."</td>
		   	<td>".number_format(($decB['d_whtax'] != 0 ? $decB['d_whtax'] : '0' ),2)."</td>
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
	       <td colspan="27" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>PAYROLL REGISTER ON IINCOME TAX WITHHELD<br /><?php echo $reportTitle;?><br>
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




