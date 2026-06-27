<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");
include ("payrollfunctions.php");
if ($_SESSION["ndex"] == 422 || $_SESSION["ndex"] == 437){
die('restricted');
}
function get_paid_per_transactionId($ndex){
	include_once("../dbcon.php");
	$total_paid = mysql_fetch_array(mysql_query("select sum(amountPaid) as paid from ar_hospital_ee_payment_ledger where ar_hospital_ee_trx_Id='".$ndex."'"));

	if(!$total_paid)
		return 0;
	else
		return $total_paid['paid'];
}
function tag_as_paid($ndex){
	include_once("../dbcon.php");
	$tas_as_paid = mysql_query("update ar_hospital_ee_trx set Status='Done' where ndex='".$ndex."'");

	return;
}
$l = mysql_fetch_array(mysql_query("select e.ndex as endex,e.firstName,e.lastName,e.middleName,d.name as deptname, h.AR_No, h.Batch_No, h.status, h.trx_type, h.Amount, h.amortization, h.priorityNo, h.ndex as hnd, h.TrxDate from ar_hospital_ee_trx h left join employee e on e.ndex=h.employeeId
	left join dept d on d.ndex=e.deptId  where h.ndex='".$_GET['id']."'
	"));

$fullname = $l['lastName'].', '.$l['firstName'].' '.$l['middleName'];
$dept = $l['deptname'];

if(isset($_GET['submit'])){

		$refund = $_POST['amountPaid'];
		$insert_payment = mysql_query("INSERT INTO `ar_hospital_ee_refund_ledger`(`ar_hospital_ee_trx_Id`, `Batch_No`, `AR_No`, `employeeId`, `paymentDate`, `amountPaid`, `lineBalance`, `paymentType`, `remarks`, `isUploaded`,`or_number`,`payment_type`) 
						VALUES ('".$l['hnd']."', '".$l['Batch_No']."', '".$l['AR_No']."', '".$l['endex']."', '".$_POST['paymentDate']."', '".$refund."', '0', 'Refund', '".$_POST['remarks']."','', '".$_POST['or']."', '".$_POST['payment_type']."')");
		header("location:tools_withHospitalLoan_individual.php?id=".$l['endex']);

}




	$total_bill = mysql_fetch_array(mysql_query("select sum(Amount) as bills from ar_hospital_ee_trx where employeeId='".$l['endex']."'  and AR_No = '".$l['AR_No']."' and Batch_No='".$l['Batch_No']."' and ndex='".$_GET['id']."'"));



	$total_paid = mysql_fetch_array(mysql_query("select sum(amountPaid) as paid from ar_hospital_ee_payment_ledger where employeeId='".$l['endex']."'  and AR_No = '".$l['AR_No']."' and Batch_No='".$l['Batch_No']."' and ar_hospital_ee_trx_Id='".$_GET['id']."'"));

	$balance = $total_bill['bills'] - $total_paid['paid'];

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
	<div id="row">
		<div class="col-md-6">
		 <h2>Add Hospital Refund</h2>   

		<h3><?php echo $fullname; ?> (<?php echo $dept;?>)</h3>
		</div>		
		
	</div>
	<br><br>
     <form action="tools_withHospitalLoan_addrefund.php?submit=go&id=<?php echo $_GET['id']; ?>" method="POST">
    <table width="50%" cellspacing="10" cellpadding="10">
		<tr>
			<td>Refund Date:</td>
			<td><input type="date" name="paymentDate" value="<?php echo date('Y-m-d');?>"><br><br></td>
		</tr>
		<tr>
			<td>OR#:</td>
			<td><input type="text" name="or" value=""><br><br></td>
		</tr>	
		<tr>
			<td>Payment Type:</td>
			<td>
				<select name="payment_type" id="payment_type" onchange="ptype();" required="required">
					<option> - Select -</option>
					
					<option value="Cash">Cash</option>
					<option value="Check">Check</option>
					<option value="Others">Others</option>
				</select><br><br>
							<span id="note" style="color:red;display:none;" >Encode in Remarks below the actual payment type<br><br></span>
			</td>
		</tr>	

		<tr>
			<td>Amount:</td>
			<td><input type="number" min="1" required="required" step="0.01" name="amountPaid" value="0.00"><br>
	
			</td>
			
		</tr>	
		<tr valign="top">
			<td valign="top">Remarks:</td>
			<td><textarea cols="10" rows="3" name="remarks"></textarea></td>
		</tr>	
		<tr valign="top">
			<td>&nbsp</td>
			<td><input type="submit" /></td>
		</tr>	

	</table>
	</form>
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
<script>
	function ptype(){

		d = document.getElementById("payment_type").value;
    	if(d == 'Financial Assistance - Others' || d == 'Others'){
    		document.getElementById('note').style.display = 'block';
    	}
    	else{
    		document.getElementById('note').style.display = 'none';
    	}
	}

</script>