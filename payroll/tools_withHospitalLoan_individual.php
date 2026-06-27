<?php
ob_start();
session_start();
//if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");
include ("payrollfunctions.php");
include ("hospital_deduction_functions.php");
include("../employeefunctions.php");

$data = '';

$opt_qry = mysql_query("select distinct e.ndex as endex,e.firstName,e.lastName,e.middleName,d.name as deptname,count(h.ndex) as total_trx from ar_hospital_ee_trx h left join employee e on e.ndex=h.employeeId
	left join dept d on d.ndex=e.deptId
	group by e.ndex,e.firstName,e.lastName,e.middleName,d.name
	order by e.lastName,e.firstName,e.middleName
	");
$opt = '';
while($optr = mysql_fetch_array($opt_qry)){
	$opt.='<option value="'.$optr['endex'].'">'.$optr['lastName'].', '.$optr['firstName'].' '.$optr['middleName'].'</option>';
}


$qry = mysql_query("select e.ndex as endex,e.firstName,e.lastName,e.middleName,e.employmentStatus,e.employeeNo,d.name as deptname, h.AR_No, h.Batch_No, h.status, h.trx_type, h.Amount, h.amortization, h.priorityNo, h.ndex as hnd, h.patType, h.Pat_No, h.doctorName, h.TrxDate from ar_hospital_ee_trx h left join employee e on e.ndex=h.employeeId
	left join dept d on d.ndex=e.deptId  where e.ndex='".$_GET['id']."'
	");
$ctr1s=0;
$total_amt =0;
$total_paid =0;
$total_balance =0;
$total_hospital =0;
while($r = mysql_fetch_array($qry)){
	$amort = mysql_fetch_array(mysql_query("select * from hospital_deduction_amortization where employeeId='".$r['endex']."' and AR_No = '".$r['AR_No']."' and Batch_No='".$r['Batch_No']."' and Status='Active'"));
	$refund = mysql_fetch_array(mysql_query("select sum(amountPaid) as refunded,sum(payment) as refund_payment  from ar_hospital_ee_refund_ledger where employeeId='".$r['endex']."' and AR_No = '".$r['AR_No']."' and Batch_No='".$r['Batch_No']."' and ar_hospital_ee_trx_Id='".$r['hnd']."' and Status='POSTED'"));
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}

	$paid = get_paid_per_transactionId($r['hnd']) + $refund['refund_payment'];
	$bal = ($r['Amount'] + $refund['refunded']) - $paid;
	$total_amt +=$r['Amount'] + $refund['refunded'];
	$total_hospital +=$r['Amount'];
	$total_paid +=$paid;
	$total_balance +=$bal;
	$add_btn = '<a href="tools_withHospitalLoan_addpayment.php?id='.$r['hnd'].'">Add</a>';
	if($bal<=0){
		$add_btn = '';
	}
	$data.='<tr style="background-color:'.$bgclr1s.'">
				<td align="left">'.getID($r['employmentStatus'],$r['employeeNo']).'</td>
				<td>'.$r['Batch_No'].'</td>
				<td>'.$r['AR_No'].'</td>
				<td>'.$r['TrxDate'].'</td>
				<td>'.$r['trx_type'].'</td>
			   	<td>'.$r['PatType'].'</td>
			   	<td>'.$r['Pat_No'].'</td>
			   	<td>'.$r['lastName'].', '.$r['firstName'].' '.$r['middleName'].'</td>
			   	
			   	<td>'.$r['doctorName'].'</td>
			   	<td align="right">'.number_format($r['Amount'],2).'</td>
				<td align="right">'.number_format(($r['Amount'] + $refund['refunded']),2).'</td>
				<td align="right">'.$r['priorityNo'].'</td>
				<td align="right">'.$amort['no_of_deduction'].'</td>
				<td align="right">'.number_format($amort['amortization'],2).'</td>

				<td align="right">'.number_format($paid,2).'</td>

				<td align="right">'.number_format($bal,2).'</td>
<td align="right"><a href="#" onclick=\'window.open("emp_whospital_payments.php?id='.$r['hnd'].'","displayWindow","toolbar=no,scrollbars=yes,width=900,height=800")\';>View</a></td>				
<td align="center">'.$add_btn.'</td>
<td align="center"><a href="tools_withHospitalLoan_addrefund.php?id='.$r['hnd'].'">Add</a></td>

				
	</tr>';
	$fullname = $r['lastName'].', '.$r['firstName'].' '.$r['middleName'];
	$dept = $r['deptname'];
}
$data.='<tr><td colspan="19"><hr></td></tr><tr style="background-color:'.$bgclr1s.';font-weight:bold">
				<td>Total</td>	
				<td align="right" colspan="9">'.number_format($total_hospital,2).'&nbsp;&nbsp;</td>			
				<td align="right">'.number_format($total_amt,2).'&nbsp;&nbsp;</td>
					
				<td align="right" colspan="4">'.number_format($total_paid,2).'&nbsp;&nbsp;</td>

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
<div id="main_content_wrap" class="container_12" style="width:90%">
     <h2>Employee Hospital Deductions</h2> 
     <?php
			if(isset($_GET['success'])){
				echo "<h3 style='color:blue;'>You have successfully created new payment</h3><br>";
			}
		?> 
     	<form action="tools_withHospitalLoan.php" method="get" id="search_form">
		    <table width="100%">
		    	<tr>
		    		<td>
		    			<select name="emp" id="emp" required="required" class="form-control" onchange="document.getElementById('search_form').submit()">
		    				<option value=""> - Select Employee- </option>
		    				<option value="all"> - All Employee - </option>
		    				<?php echo $opt;?>
		    			</select>
		    			<br><br>
		    		</td>    	
		    	</tr>
		    </table> 
		</form> 


		<h3><?php echo $fullname; ?> (<?php echo $dept;?>)</h3>
		<br>
		<table width="100%" style="font-size:12px">
			<thead>
				<tr>
					<th>ID</th>
					<th>Batch No.</th>
					<th>AR No.</th>
					<th>Trx Date</th>
					<th>Type</th>
					<th>Description</th>
					<th>Patient<br>No</th>
					<th>Patient<br>Name</th>
					<th>Doctors<br>Name</th>
					<th>Hospital<br>Amount</th>
					<th>Total<br>Amount</th>
					<th>Priority</th>
					<th>No. Deductions</th>
					<th>Deduction<br>per payday</th>					
					<th>Payments</th>
					<th>Balance</th>
					<th>Ledger</th>
					<th>Payment</th>
					<th>Refund</th>
				</tr>
			</thead>
			<tbody>
				<tr><td colspan="18"><hr></td></tr>
				<?php echo $data;?>
			</tbody>
		</table>
		<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>