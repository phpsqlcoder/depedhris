<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");


//$cutOffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']."'",$conn)); //(e.isActive='1' || p.holdSalary = '1' ) &&
$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));
$sql = "SELECT e.ndex as empid,e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.*
								FROM employee e 
									left join employee_compensation c on e.ndex=c.employeeId
									LEFT JOIN payroll p ON p.empid = e.ndex 
									LEFT JOIN dept d ON d.ndex = e.deptId
										WHERE  p.pay_period='".$_POST['PayrollCutoff']."' && e.residencyTrainingProgram='' &&  c.basicPay<>0 && p.holdSalary<>'1'";
if ($_POST['division']){$sql .= " && e.divisionId='".$_POST['division']."'";}

if ($_POST['mbtcCompany'] == 1){
	$sql .= " && e.level IN (0)";
	$reportTitle = 'TEMPORARY';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && e.level IN (1,2)";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && e.level IN (3,4,5,6,7,8,9)";
	$reportTitle = 'SECTION HEADS AND CONFI';
} elseif ($_POST['mbtcCompany'] == 0) {
	$reportTitle = 'ALL EMPLOYEE';
}

//echo $division." --".$PayrollCutoff."<br>".$sql.mysql_num_rows(mysql_query($sql));
$sql.=" ORDER BY  e.lastName,e.firstName";
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$rowCount = mysql_num_rows($exec);

while($r=mysql_fetch_assoc($exec)){
    
    $deductionqry=mysql_query("select * from loandeductionmaintenance where ndex not in(2,3) order by name");
    while($d=mysql_fetch_object($deductionqry)){
           // $ddata.="&nbsp;&nbsp;&nbsp;<input type='checkbox' name='d".$d->ndex."'>".$d->name."</br>";
        $empi=$r['empid'];
        $dd=mysql_fetch_object(mysql_query("select sum(p.amountPaid) as amountPaid from loan_employee_payments p left join loan_employee e on e.ndex=p.loanSetupId where p.datePaid='".$_POST['PayrollCutoff']."' and e.employeeId='".$r['empid']."' and e.loanId='".$d->ndex."'"));
       // echo "select p.* from loan_employee_payments p left join loan_employee e on e.ndex=p.loanSetupId where p.datePaid='".$_POST['PayrollCutoff']."' and e.employeeId='".$r['empid']."' and e.loanId='".$d->ndex."'<br>";
                  
            if($_POST['d'.$d->ndex]=='on'){
                $deta[$empi].="<td>".number_format($dd->amountPaid,2)."</td>";
                $tot[$empi]+=$dd->amountPaid;
            }
        
    }
    $tot[$empi]+=$r['d_sssloan']+$r['pagibigloan']+$r['d_coopTotal'];
    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
    $data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 							<td align='left'>".getID($r['employmentStatus'],$r['employeeNo'])."</td>
                                                                <td align='left'>".$r['lastName'].", ".$r['firstName']."</td>   
								
								<td>".number_format(($r['d_sssloan']),2)."</td>
								<td>".number_format($r['pagibigloan'],2)."</td>
								<td>".number_format($r['d_coopTotal'],2)."</td>
                                                                    ".$deta[$empi]."
								<td>".number_format($tot[$empi],2)."</td>
                                                                
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
<?php 
$deductionqry2=mysql_query("select * from loandeductionmaintenance where ndex not in (2,3) order by name");
while($d2=mysql_fetch_object($deductionqry2)){
    if($_POST['d'.$d2->ndex]=='on'){
	$ddatah.="<td>".$d2->name."</td>";	
    }
}
?>
     <table width="95%" style="font-family:Arial;font-size:12px;">
	 <thead>
	  <tr>
	       <td colspan="24" align="center" style="font-size:14px;font-weight:bold;">DAVAO DOCTORS HOSPITAL<br>DEDUCTION REPORT<br /><?php echo $reportTitle;?><br>
				 				<?php echo date('F d, Y',strtotime($cutoffDate['cutoffDateStart']))." to ".date('F d, Y',strtotime($cutoffDate['cutoffDateEnd']));?></td> </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
				<td>IDNUM</td>
	     		<td>NAME</td>
	     		
		   		<td>SSS </td>
	      		<td>HDMF SALARY LOAN</td>
				<td>COOP</td>
		  		<?php echo $ddatah;?>
				<td>TOTAL DED</td>
	  </tr>
	  <tr><td colspan="24"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




