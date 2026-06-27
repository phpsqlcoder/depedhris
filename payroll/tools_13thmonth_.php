<?php
ob_start();
session_start();
include("../dbcon.php");
include("../employeefunctions.php");
include("payrollfunctions.php");
$start_Cutoff="2014-06-01";
$a13MonthCutoff='2014-12-31';

//echo "start cut-off: ".$start_Cutoff;
//echo "cut-off: ".$a13MonthCutoff;
//die();

if($_GET['act']=='submit'){
	$del=mysql_query("delete from payroll_13thmonth where dyt='".$a13MonthCutoff."'");
	$sql2 = mysql_query("SELECT e.ndex as empid,e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, e.bankAccountNo,e.payType, ec.basicPay
								FROM employee e 
									LEFT JOIN dept d ON d.ndex = e.deptId
									LEFT JOIN employee_compensation ec ON ec.employeeId = e.ndex 
										WHERE e.isActive='1' && e.employmentStatus='Regular' && e.dateHired <= '".$a13MonthCutoff."' && e.payType IN ('Monthly','Daily') ORDER BY  e.lastName,e.firstName");
	while($s=mysql_fetch_object($sql2)){
		$save=mysql_query("insert into payroll_13thmonth (`empid`, `grosspay`, `deduction`, `dyt`, `deductionId`) VALUES ('".$s->empid."','".$_POST['gr'.$s->empid]."','".$_POST['deduct'.$s->empid]."','".$a13MonthCutoff."','0')");
	}
}
$sql = "SELECT e.ndex as empid,e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, e.bankAccountNo,e.payType, ec.basicPay
								FROM employee e 
									LEFT JOIN dept d ON d.ndex = e.deptId
									LEFT JOIN employee_compensation ec ON ec.employeeId = e.ndex 
										WHERE e.isActive='1' && e.employmentStatus='Regular' && e.dateHired <= '".$a13MonthCutoff."' && e.payType IN ('Monthly','Daily')";
$sql.=" ORDER BY  e.lastName,e.firstName";
//echo $sql;
$exec=mysql_query($sql);
$var=0;
$ln = 0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_assoc($exec)){
	//deductions
	$ded="";
	$dedt=0;
	$dqr=mysql_query("select l.*,m.name as deduction from loan_employee l left join loandeductionmaintenance m on m.ndex=l.loanId where l.employeeId='".$r['empid']."' and l.posted='1'");
	//echo "select l.*,m.name as deduction from loan_employee l left join loandeductionmaintenance m on m.ndex=l.loanId where l.employeeId='".$r['empid']."' and l.posted='1'<br>";
	while($d=mysql_fetch_object($dqr)){
		if(getDeductionData($d->ndex,'current balance')>=1){
			$dedt+=getDeductionData($d->ndex,'Currect Deduction Amount');
			$ded.=$d->deduction."=".number_format(getDeductionData($d->ndex,'Currect Deduction Amount'),2)."<br>";
		}
	}
	$ded.="<strong>Total ".number_format($dedt,2)."</strong>";
	
	if ($r['payType'] == 'Daily'){		
		$totalNetBasic = mysql_fetch_assoc(mysql_query("SELECT SUM(netBasic) netBasic FROM payroll where empid='".$r['empid']."' && pay_period>='".date('Y')."-01-01' && pay_period<='".$a13MonthCutoff."'"));
		$r['basicPay'] = $totalNetBasic['netBasic'] / 3;
		//echo $r['lastName'].", ".$r['firstName']."<br>";
	} 
	$dv=mysql_fetch_object(mysql_query("select * from payroll_13thmonth where empId=".$r['empid']." and dyt='".$a13MonthCutoff."'"));
	
	if(!$dv->grosspay){
		$gross=($r['basicPay']/2);
	}
	else{
		$gross=$dv->grosspay;
	}
		$var++;
   	 	$ctr1s++;
		$ln++;
    	$data.="<tr bgcolor='".$bgclr1s."' align='right'>
	 							<td align='left'>".$ln."</td>
					      <td align='left'>".$r['lastName'].", ".$r['firstName']."</td>
					      <td><input type='Text' value='".$gross."' name='gr".$r['empid']."' style='text-align:right;'></td>
						  <td>".$ded."</td>
						  <td><input type='Text' value='".$dv->deduction."' name='deduct".$r['empid']."' style='text-align:right;'></td>
				     </tr>";
		$totalNetPay += ($r['basicPay']/2);
}

	//$payrollDateRange = '2014-01-01 to '.$a13MonthCutoff;
	$payrollDateRange = date('M. 1, Y',strtotime(date('Y').'-01-01'))." to ".date('M. 15, Y',strtotime($a13MonthCutoff));

?>

     <?php //include("../rptheader.php");?>
     <form action="tools_13thmonth.php?act=submit" method="post">
	 <table width="70%" style="font-family:Arial;font-size:12px;">
	  <thead>
	
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
				<td></td>
	     	<td>NAME</td>
	      <td>Gross 13th month Pay</td>
		  <td>Deduction</td>
		   	<td>Final Deduction</td>
	  </tr>
	  <tr><td colspan="6"><hr></td></tr>
	  </thead>
	  <tbody>
	  
	 	<?php echo $data;?>
	  </tbody>
		<tr>
				<td colspan="6"><input type="Submit" value="Submit"></td>
		   	<td align="right"><hr></td>
	  </tr>
		<tr><td colspan="3" align="right"><?php echo number_format($totalNetPay,2);?></td></tr>
			<tr valign="bottom" align="center">
				<td colspan="6"></td>
		   	<td align="right"><hr><hr></td>
	  </tr>

      </table>
	  </form>
	  <?php //include("../rptfooter.php");?>




