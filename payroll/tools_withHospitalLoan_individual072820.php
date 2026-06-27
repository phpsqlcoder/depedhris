<?php
ob_start();
session_start();
//if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");
include ("payrollfunctions.php");
include ("hospital_deduction_functions.php");
$data = '';


$qry = mysql_query("select e.ndex as endex,e.firstName,e.lastName,e.middleName,d.name as deptname, h.AR_No, h.Batch_No, h.status, h.trx_type, h.Amount, h.amortization, h.priorityNo, h.ndex as hnd, h.patType, h.Pat_No, h.doctorName from ar_hospital_ee_trx h left join employee e on e.ndex=h.employeeId
	left join dept d on d.ndex=e.deptId  where e.ndex='".$_GET['id']."'
	");
$ctr1s=0;
$total_amt =0;
$total_paid =0;
$total_balance =0;
while($r = mysql_fetch_array($qry)){
	$amort = mysql_fetch_array(mysql_query("select * from hospital_deduction_amortization where employeeId='".$r['endex']."' and AR_No = '".$r['AR_No']."' and Batch_No='".$r['Batch_No']."' and Status='Active'"));
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	$paid = get_paid_per_transactionId($r['hnd']);
	$bal = $r['Amount'] - $paid;
	$total_amt +=$r['Amount'];
	$total_paid +=$paid;
	$total_balance +=$bal;
	$add_btn = '<a href="tools_withHospitalLoan_addpayment.php?id='.$r['hnd'].'">Add</a>';
	if($bal<=0){
		$add_btn = '';
	}
	$data.='<tr style="background-color:'.$bgclr1s.'">
				<td>'.$r['Batch_No'].'</td>
				<td>'.$r['AR_No'].'</td>
				<td>'.$r['trx_type'].'</td>
			   	<td>'.$r['PatType'].'</td>
			   	<td>'.$r['Pat_No'].'</td>
			   	<td>'.$r['lastName'].', '.$r['firstName'].' '.$r['middleName'].'</td>
			   	
			   	<td>'.$r['doctorName'].'</td>
				<td align="right">'.number_format($r['Amount'],2).'</td>
				<td align="right">'.$r['priorityNo'].'</td>
				<td align="right">'.$amort['no_of_deduction'].'</td>
				<td align="right">'.number_format($amort['amortization'],2).'</td>				
				<td align="right">'.number_format($paid,2).'</td>
				<td align="right">'.number_format($bal,2).'</td>
				<td align="right"><a href="#" onclick=\'window.open("emp_whospital_payments.php?id='.$r['hnd'].'","displayWindow","toolbar=no,scrollbars=yes,width=900,height=800")\';>View</a></td>
				<td align="center">'.$add_btn.'</td>
	</tr>';
	$fullname = $r['lastName'].', '.$r['firstName'].' '.$r['middleName'];
	$dept = $r['deptname'];
}
$data.='<tr><td colspan="15"><hr></td></tr><tr style="background-color:'.$bgclr1s.';font-weight:bold">
				<td>Total</td>				
				<td align="right" colspan="7">'.number_format($total_amt,2).'</td>						
				<td align="right" colspan="4">'.number_format($total_paid,2).'</td>
				<td align="right">'.number_format($total_balance,2).'</td>
				<td colspan="2">&nbsp;</td>
				
	</tr>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- <title>HRIS - Davao Doctors Hospital</title> -->
    <link href="../css/styles.css" rel="stylesheet" type="text/css" />
    <link href="../css/facebox.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Employee Hospital Deductions</h2>   

		<h3><?php echo $fullname; ?> (<?php echo $dept;?>)</h3>
		<br>
		<table width="100%">
			<thead>
				<tr>
					<th>Batch No.</th>
					<th>AR No.</th>
					<th>Type</th>
					<th>Description</th>
					<th>Patient No</th>
					<th>Patient Name</th>
					<th>Doctors Name</th>
					<th>Total Amount</th>
					<th>Priority</th>
					<th>No. Deductions</th>
					<th>Deduction per payday</th>					
					<th>Payments</th>
					<th>Balance</th>
					<th>Ledger</th>
					<th>Add Payment</th>
				</tr>
			</thead>
			<tbody>
				<tr><td colspan="15"><hr></td></tr>
				<?php echo $data;?>
			</tbody>
		</table>
		<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>