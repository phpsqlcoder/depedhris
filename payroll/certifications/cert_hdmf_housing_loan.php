<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../../dbcon.php");
include("../../scripts/scripts.php");
include ("../../employeefunctions.php");


  function addOrdinalNumberSuffix($num) {
    if (!in_array(($num % 100),array(11,12,13))){
      switch ($num % 10) {
        // Handle 1st, 2nd, 3rd
        case 1:  return $num.'st';
        case 2:  return $num.'nd';
        case 3:  return $num.'rd';
      }
    }
    return $num.'th';
  }
//echo number_to_words(10111.23);
function getpayperiod($id){
	$payq=mysql_fetch_object(mysql_query("select * from cutoffDates where ndex=".$id.""));
	return $payq->payrollDate;
}

$pageTitle='HDMF Housing Loan Payments';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Employee Contract</title>
	<style>
		p {
    text-indent: 50px;
} 
	</style>
</head>
<body style="font-family:Arial;font-size:15px;">
<?php if($_GET['act']=='go'){

$or_field='hdmf_housing'; 
$header="
			<tr>
				<td>Payroll Date</td>
				<td>Amount</td>
				<td>OR No.</td>
				<td>Date</td>
			</tr>
			<tr><td colspan='6'><hr></td></tr>
";

$emp=mysql_fetch_object(mysql_query("select e.*,d.name as dep,p.name as pos,p.ndex as pnd from employee e left join dept d on d.ndex=e.deptId left join position p on p.ndex=e.position where e.ndex='".$_POST['id']."'"));
$p=mysql_fetch_object(mysql_query("select * from employee_compensation where employeeId=".$emp->ndex.""));
$basicpay=$p->basicPay + $p->cola + $p->honorarium;
$employeename="<strong>".$emp->lastName.",".$emp->firstName." ".$emp->middleName."</strong>";
$sssno="Pag-IBIG NO. ".$emp->pagibigNumber;
$pq=mysql_query("select * from payroll where pay_period>='".getpayperiod($_POST['st'])."' and pay_period<='".getpayperiod($_POST['en'])."' and empid='".$_POST['id']."' and days_work>0 and pagibigloanh>0");

while($p=mysql_fetch_object($pq)){		
	$cutoffid=mysql_fetch_object(mysql_query("select * from cutoffDates where payrollDate='".$p->pay_period."'"));
	$or=mysql_fetch_array(mysql_query("select * from payroll_or where cutoff='".$cutoffid->ndex."'"));
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#EEF0F0';}
	$data.="<tr style='background-color:".$bgclr1s.";'>
				<td>".$p->pay_period."</td>
				<td>".number_format($p->pagibigloanh,2)."</td>				
				<td>".$or[$or_field]."</td>
				<td>".$or['d_'.$or_field]."</td>
			</tr>";
}
?>
<table style="font-family:Arial;font-size:16px;" width="100%">
	<tr><td><br><br><br><br><br><br><br><br></td></tr>
	<tr><td colspan="6" align="center"><u><strong style="font-size:20px;">CERTIFICATE OF LOAN PAYMENTS</strong></u><br><br></td></tr>
	<tr><td colspan="6"><?php echo $_POST['h1']?><br><br></td></tr>
	<tr><td colspan="6"><p><?php echo str_replace("<sss no>",$sssno,str_replace("<employee>",$employeename,$_POST['h2']));?></p><br></td></tr>
	<?php echo $header.$data;?>
	<tr><td colspan="6"><br><br><p><?php echo $_POST['h3']?></p><br></td></tr>
	<tr><td colspan="6"><p><?php echo $_POST['h4']?></p><br><br></td></tr>
	<tr><td colspan="3">&nbsp;</td><td colspan="4">DAVAO DOCTORS HOSPITAL<br>Pag-IBIG No. <?php echo $_POST['h5']?><br><br><br></td></tr>
	<tr><td colspan="3">&nbsp;</td><td colspan="4">By:<br><?php echo $_POST['h6']?><br><?php echo $_POST['h7']?><br></td></tr>
</table>
<?php
}
	else 
{
?>
<table style="font-family:Arial;font-size:12px;" width="100%">
	
	<tr><td align="center"><u><h1><?php echo $pageTitle;?></h1></u></td></tr>	
</table>
<form name="frmcompo" action="cert_hdmf_housing_loan.php?act=go" method="post">
<table style="font-family:Arial;font-size:12px;">
	<tr><td>Employee:</td><td><select name="id"><?php echo $optionemployee_all;?></select></td></tr>
	<tr><td>Start: </td><td><select name="st"><?php echo $optioncutoff;?></select></td></tr>
	<tr><td>End: </td><td><select name="en"><?php echo $optioncutoff;?></select></td></tr>
	<tr><td>Header Text:</td><td><textarea name="h1" rows="3" cols="50">To Whom It May Concern:</textarea></td></tr>
	<tr><td>Top Paragraph:</td><td><textarea name="h2" rows="3" cols="60">Herewith are the Housing Loan payments of <employee> with <sss no> follows:</textarea></td></tr>
	<tr><td>Bottom Paragraph:</td><td><textarea name="h3" rows="3" cols="60">This certification is issued upon the request of the above-named for Housing Loan Payments purposes only.</textarea></td></tr>
	<tr><td>Footer Paragraph:</td><td><textarea name="h4" rows="3" cols="60">Issued this <?php echo addOrdinalNumberSuffix(date('d'));?> day of <?php echo date('F');?> in the year <?php echo date('Y');?> at Davao City, Philippines.</textarea></td></tr>
	<tr><td>DDH Pag-IBIG No.:</td><td><textarea name="h5" rows="3" cols="60">202-9559000-09</textarea></td></tr>
	<tr><td>SIGNATORY:</td><td><textarea name="h6" rows="3" cols="60">JOAN B. PELICANO</textarea></td></tr>
	<tr><td>POSITION:</td><td><textarea name="h7" rows="3" cols="60">COMPENSATION OFFICER</textarea></td></tr>
	<tr><td align="center" colspan="2"><input type="submit" value="GO"></td></tr>
</table>
</form>
<?php } ?>
</body>
</html>
<?php ob_end_flush();?>