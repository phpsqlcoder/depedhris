<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");




$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName,l.*,ld.name as loanname,l.ndex as lid FROM loan_employee l left join `loandeductionmaintenance` ld on ld.ndex=l.loanId
left join employee e on e.ndex=l.employeeId
LEFT JOIN dept d ON d.ndex = e.deptId
 where l.isDeleted='0' and l.dedDateStart>='".$_POST['startdate']."' and l.dedDateStart<='".$_POST['enddate']."'";


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

if ($_POST['employeeId']){$sql .= " && e.ndex='".$_POST['employeeId']."'";}

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
	$pq = mysql_fetch_array(mysql_query("select sum(amountPaid) as paid from loan_employee_payments where loanSetupId='".$r['lid']."'"));
	$paid = $pq['paid'];
	$bal = $r['loanAmount'] - $paid;

    $pqa = mysql_fetch_array(mysql_query("select sum(amountPaid) as paid from loan_employee_payments where loanSetupId='".$r['lid']."' and datePaid<='".date('Y-m-d',strtotime($_POST['enddate']))."'"));
	$paida = $pqa['paid'];
	$bala = $r['loanAmount'] - $paida;

     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
     $data.="<tr bgcolor='".$bgclr1s."' align='right'>
			<td align='left'>".getID($r['employmentStatus'],$r['employeeNo'])."</td>
	      	<td align='left'>".$r['lastName'].", ".$r['firstName']."</td>
	      	<td>".$r['departmentName']."</td>
	      	<td>".$r['loanname']."</td>
		   	<td>".number_format($r['loanAmount'],2)."</td>

            <td>".number_format($paida,2)."</td>           
		   	<td>".number_format($bala,2)."</td>
   
		   	<td>".number_format($paid,2)."</td>           
		   	<td>".number_format($bal,2)."</td>

		   	<td>".$r['dedStartDate']."</td>
		   	<td>".$r['nOfDeduction']."</td>
		 
		   	<td>".$r['remarks']."</td>
		
		</tr>";
		$tt += $r['loanAmount'];
		
				
	
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
	     		<td>DEPT</td>
		   		<td>LOAN TYPE</td>
	      		<td>AMOUNT</td>
	      		<td>PAID ao <?php echo date('Y-m-d',strtotime($_POST['enddate']));?><</td>
	      		<td>BALANCE ao <?php echo date('Y-m-d',strtotime($_POST['enddate']));?></td>
                <td>PAID ao today (<?php echo date('Y-m-d');?>)</td>  
                <td>BALANCE ao today (<?php echo date('Y-m-d');?>)</td>  
	      		<td>DEDUCTION START</td>
	      		<td># OF DEDUCTION</td>
	      		<td>REMARKS</td>

		  	
	  </tr>
	  <tr><td colspan="24"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




