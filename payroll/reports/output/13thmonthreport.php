<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");
$a13MonthCutoff = $_POST['a13MonthCutoff'];

/*
$q = mysql_query("select * from payroll13thmonth where cutOffDate='".$a13MonthCutoff."'");
while($r = mysql_fetch_array($q)){
	$e = mysql_fetch_array(mysql_query("select * from employee where ndex='".$r['empNo']."'"));
	$u = mysql_query("update payroll13thmonth set emp_level='".$e['level']."' where ndex = '".$r['ndex']."'");
}

die();
*/
//echo $_POST['a13MonthCutoff']."sadfj".$a13MonthCutoff;


$sql = "SELECT e.ndex empid, e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, e.bankAccountNo, e.level, e.dateHired, e.payType, p.basicPay
								FROM 
									payroll13thmonth p LEFT JOIN employee e on e.ndex=p.empNo
									LEFT JOIN employee_compensation ec ON ec.employeeId = e.ndex 
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE e.employmentStatus IN ('Regular','Temporary','Probationary')  && e.residencyTrainingProgram='' 
										 && p.cutOffDate='".$a13MonthCutoff."'";
										// && e.dateHired <= '".$a13MonthCutoff."'";

if ($_POST['mbtcCompany'] == 1){
	$sql .= " && p.emp_level IN (0)";
	$reportTitle = 'TEMPORARY';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && p.emp_level IN (1,2)";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && p.emp_level IN (3,4,5,6,7,8,9)";
	$reportTitle = 'SECTION HEADS AND CONFI';
} elseif ($_POST['mbtcCompany'] == 0) {
	$reportTitle = 'ALL EMPLOYEE';
}

$sql.=" ORDER BY  d.name, e.lastName, e.firstName";
//echo $sql;
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$lk=0;
$tlk=0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
	//$finalzz = mysql_fetch_assoc(mysql_query("select * from payroll13thmonth where empNo='".$r['empid']."' and cutOffDate='".$a13MonthCutoff."'"));
	//if($finalzz['ndex']){
	$var++;
  	 $ctr1s++;
	$ln++;
	$lk++;
	$tlk++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	if($r['departmentName'] != $prevDepartment){
		if($ln != 1){
			$lk = $lk - 1;
			//$data .= "<tr><td colspan='2'>No. of Personnel: $countPerDepartment</td><td colspan='22'><hr></td></tr>";
			$countPerDepartment = 0;
		   	$data.="<tr bgcolor='".$bgclr1s."' align='right'>
		 							<td align='left' colspan='4'><strong>".$lk."</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SUBTOTAL</td>
							  <td>".number_format($subTotalBasicPay,2)."</td>
						      <td>".number_format($subTotalBasicgross,2)."</td>
							  <td>".number_format($subTotalBasicded,2)."</td>
							  <td>".number_format($subTotalBasicnet,2)."</td>
					     </tr>";
			$data .= "<tr><td colspan='24'>&nbsp;</td></tr>";
			$subTotalBasicPay = 0;
			$subTotalBasicPayHalf = 0;
			$subTotalBasicgross = 0;
	$subTotalBasicded = 0;
	$subTotalBasicnet = 0;
		}
		$data .= " <tr><td colspan='5' align='left' style='font-size:11px;'>".$r['departmentName']."</td></tr>";
		$lk=1;
	}
/*
	if ($r['payType'] == 'Daily'){
		//  $a13MonthCutoff
		//$niagiko = "SELECT SUM(netBasic) netBasic FROM payroll where empid='".$r['empid']."' && pay_period>='2013-01-01' && pay_period<='".$a13MonthCutoff."'";
		$totalNetBasic = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic) netBasic FROM payroll where empid='".$r['empid']."' && pay_period>='2014-01-01' && pay_period<='".$a13MonthCutoff."'"));
		$r['basicPay'] = $totalNetBasic['netBasic'] / 3;
	}*/ 
	$final = mysql_fetch_assoc(mysql_query("select * from payroll13thmonth where empNo='".$r['empid']."' and cutOffDate='".$a13MonthCutoff."'"));
	$totalDeduction = $final['wtax'] + $final['cashAdvance'] + $final['hospitalBill'] + $final['otherDeduction'];
	$final_amount = $final['amount13thMonth'] - $totalDeduction;
	//getID($empStatus,$empNo)
	$data.="<tr bgcolor='".$bgclr1s."' align='right'>
		 					  <td align='left'>".$lk."</td>
							  <td align='left'>".getID($r['employmentStatus'],$r['employeeNo'])."</td>
						      <td align='left'>".$r['lastName'].", ".$r['firstName']." </td>
							  <td align='left'>".$r['dateHired']." </td>
							  <td>".number_format($final['basicPay'],2)."</td>
							  <td>".number_format($final['amount13thMonth'],2)."</td>
							  <td>".number_format($totalDeduction,2)."</td>
						      <td>".number_format($final_amount,2)."</td>
							  <td>1</td>
					     </tr>";
	$subTotalBasicPay += number_format($r['basicPay'],2,'.','');
	$subTotalBasicgross += number_format($final['amount13thMonth'],2,'.','');
	$subTotalBasicded += number_format($totalDeduction,2,'.','');
	$subTotalBasicnet += number_format($final_amount,2,'.','');

	$grandTotalBasicPay += number_format($r['basicPay'],2,'.','');
	$grandTotalBasicgross += number_format($final['amount13thMonth'],2,'.','');
	$grandTotalBasicded += number_format($totalDeduction,2,'.','');
	$grandTotalBasicnet += number_format($final_amount,2,'.','');

	$prevDepartment = $r['departmentName'];

	//}
}

?>
     <?php
	if($_POST['eksel']=='eksel'){
		$filename ="reportbyposition.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php //include("../rptheader.php");?>
     <table width="70%" style="font-family:Arial;font-size:12px;">
	  <thead>
	  <tr>
	       <td colspan="10" align="center" style="font-size:14px;">
		   		DAVAO DOCTORS HOSPITAL<br />
				<?php echo $reportTitle;?> <br />
				13TH MONTH REPORT <br />
				<?php echo date('F Y',strtotime(date($a13MonthCutoff)));?>
				
				 </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
		<td></td>
		<td>ID NO</td>
	    <td>NAME</td>
		<td>DATE HIRED</td>
		<td>BASICPAY</td>
		<td>GROSS</td>
		<td>DEDUCTION</td>
	  	<td>NET 13TH MONTH</td>
		<td>WITH MED LOAN</td>
	  </tr>
	  <tr><td colspan="10"><hr></td></tr>
	  </thead>
	  <tbody>
	 	<?php echo $data;?>
	  </tbody>
		<tr valign="bottom" align="center">
				<td colspan="2"></td>
		   	<td align="right"><hr></td>
			<td align="right"><hr></td>
	  </tr>
		<tr><td><strong><?php echo $tlk;?></strong></td>
			<td colspan="4" align="right"><?php echo number_format($grandTotalBasicPay,2);?></td>
			<td align="right"><?php echo number_format($grandTotalBasicgross,2);?></td>
			<td align="right"><?php echo number_format($grandTotalBasicded,2);?></td>
			<td align="right"><?php echo number_format($grandTotalBasicnet,2);?></td></tr>
			<tr valign="bottom" align="center">
				<td colspan="2"></td>
		   	<td align="right"><hr><hr></td>
			<td align="right"><hr><hr></td>
	  </tr>
      </table>
	  <?php include("../rptfooter.php");?>




