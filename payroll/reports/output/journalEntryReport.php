<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");
include("../../../scripts/scripts.php");
include ("../../../myfunctions.php");
include ("../../payrollfunctions.php");

$sql = "SELECT *
				FROM sap_payroll_journal_entry
						WHERE payrolDate >='".$_POST['PayrollDate']."' ";// && e.ndex=163";&& d.name LIKE '%basicPay2%'
					
//$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));		
$cutoffDatestart = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollDate']	."'",$conn));		
if ($_POST['mbtcCompany'] == 1){
	$sql .= " && groupLevel='TEMPORARY'";
	$reportTitle = 'TEMPORARY';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && groupLevel='RANK & FILE'";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && groupLevel='SECTION HEADS AND CONFI'";
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
$sql.=" ORDER BY  department";

//echo $sql;	
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
    $var++;
    $ctr1s++;
		$ln++;
    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
				$data .= " <tr bgcolor='".$bgclr1s."'>
											<td> ".$ln."</td>
											<td> ".date('M d, Y',strtotime($r['payrolDate']))."</td>
											<td> ".$r['bpCode']."</td>
											<td> ".$r['bpName']."</td>
											<td> ".$r['controlAccount']."</td>
											<td align='right'> ".$r['debit']."</td>
											<td align='right'> ".$r['credit']."</td>
											<td> ".$r['project']."</td>
											<td> ".$r['organization']."</td>
											<td> ".$r['department']."</td>
											<td> ".$r['profitCenter']."</td>
											<td> ".$r['section']."</td>
											<td> ".$r['IPOP']."</td>
											<td> ".$r['remarks']."</td>
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
     <table width="100%" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:11px;">
	 <thead>
	  <tr>
	       <td colspan="26" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>JOURNAL ENTRY REPORTS<br /><?php echo $reportTitle;?><br>
				 				<?php echo date('F d, Y',strtotime($cutoffDatestart['cutoffDateStart']))." to ".date('F d, Y',strtotime($cutoffDateend['cutoffDateEnd']));?> </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
			<td>#</td>
			<td>Payroll Period</td>
			<td>G/L Acct/BP Code</td>
			<td>G/L Acct/BP Name</td>
			<td>Control Acct</td>
			<td>DEBIT</td>
			<td>CREDIT</td>
			<td>PROJECT</td>
			<td>ORGANIZATION</td>
			<td>DEPARTMENT</td>
			<td>PROFIT CENTER</td>
			<td>SECTION</td>
			<td>IPOP</td>
			<td>REMARKS</td>
	  </tr>
	  <tr><td colspan="26"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




