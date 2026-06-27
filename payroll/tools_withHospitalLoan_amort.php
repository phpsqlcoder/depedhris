<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");
include ("payrollfunctions.php");
if(isset($_GET['update'])){
	$check_if_exist = mysql_fetch_array(mysql_query("select * from hospital_deduction_amortization where AR_No='".$_POST['AR_No']."' and Batch_No='".$_POST['Batch_No']."' and employeeId='".$_POST['employeeId']."'"));
	
	if(empty($check_if_exist['id'])){
		$rec = mysql_fetch_array(mysql_query("select * from ar_hospital_ee_trx where AR_No='".$_POST['AR_No']."' and Batch_No='".$_POST['Batch_No']."' and employeeId='".$_POST['employeeId']."'"));

		$totalamt = mysql_fetch_array(mysql_query("select sum(amount) as amtt from ar_hospital_ee_trx where AR_No='".$_POST['AR_No']."' and Batch_No='".$_POST['Batch_No']."' and employeeId='".$_POST['employeeId']."'"));

		$ins = mysql_query("insert into hospital_deduction_amortization (`employeeId`, `Batch_No`, `AR_No`, `status`, `total_amount`, `amortization`, `start_date`, `created_by`, `created_at`, `no_of_deduction`) 
			values ('".$_POST['employeeId']."','".$rec['Batch_No']."','".$rec['AR_No']."','Active','".$totalamt['amtt']."','".$_POST['new_amort']."','".$_POST['start_date']."','".$_SESSION['ndex']."','".date('Y-m-d H:i:s')."','".$_POST['noco']."')");


	}
	else{
		$upd = mysql_query("update hospital_deduction_amortization set amortization='".$_POST['new_amort']."',no_of_deduction='".$_POST['noco']."'
	,start_date='".$_POST['start_date']."' where AR_No='".$_POST['AR_No']."' and Batch_No='".$_POST['Batch_No']."' and employeeId='".$_POST['employeeId']."'");
		
	}
	
}

$data = '';
$ctr1s=0;
$x=0;
$qry = mysql_query("select e.ndex as endex,e.firstName,e.lastName,e.middleName,d.name as deptname,h.AR_No, h.Batch_No,h.amortization,h.no_of_deduction,h.start_date,h.employeeId, sum(Amount) as total_amount from ar_hospital_ee_trx h left join employee e on e.ndex=h.employeeId
	left join dept d on d.ndex=e.deptId 
	
	group by e.ndex,e.firstName,e.lastName,e.middleName,d.name,h.AR_No, h.Batch_No,h.amortization,h.no_of_deduction,h.start_date,h.employeeId
	order by e.lastName,e.firstName,e.middleName
	");
while($r = mysql_fetch_array($qry)){
	$ctr1s++;
	$x++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	$aa = mysql_fetch_array(mysql_query("select * from hospital_deduction_amortization where Batch_No='".$r['Batch_No']."' and AR_No='".$r['AR_No']."' and employeeId='".$r['endex']."'"));
	if($aa['amortization'] < 1){
		$amort = 0;
		$start_date = '';
		$nod=0;
	}
	else{
		$amort = number_format($aa['amortization'],2, '.', '');
		$start_date = $aa['start_date'];
		$nod=$aa['no_of_deduction'];
	}

	$paid = mysql_fetch_array(mysql_query("select sum(amountPaid) as paid from ar_hospital_ee_payment_ledger where employeeId='".$r['endex']."' and Batch_No='".$r['Batch_No']."' and AR_No='".$r['AR_No']."'"));
	$balance = $r['total_amount'] - $paid['paid'];
	if($balance>0){
		$data.='<form action="tools_withHospitalLoan_amort.php?update=go" method="post"><tr style="background-color:'.$bgclr1s.';font-size:11px;">
					<td style="height:35px;vertical-align: middle;">'.$r['lastName'].', '.$r['firstName'].' '.$r['middleName'].'</td>
					
					<td>'.$r['AR_No'].'</td>
					<td>'.$r['Batch_No'].'</td>				
					<td align="right">'.number_format($r['total_amount'],2).'</td>
					<td align="right">'.number_format($paid['paid'],2).'</td>
					<td align="right">'.number_format($balance,2).'</td>
					<td align="center">					
						<input type="hidden" name="AR_No" value="'.$r['AR_No'].'">
						<input type="hidden" name="Batch_No" value="'.$r['Batch_No'].'">
						<input type="hidden" name="employeeId" value="'.$r['employeeId'].'">
						<input type="hidden" name="recndex" value="'.$r['endex'].'">
						<input type="hidden" id="amt'.$x.'" value="'.$balance.'">
						<input type="text" required="required" size="6" onkeyup="noco_change('.$x.')" name="noco"  id="noco'.$x.'" value="'.$nod.'">
														
					</td>
					<td align="center">	
						<input type="text" required="required" onkeyup="amort_change('.$x.')" id="amort'.$x.'" size="6" name="new_amort" value="'.$amort.'">
								
					</td>
					<td align="center">	
						<input type="date" required="required" size="8" name="start_date" value="'.$start_date.'">							
					</td>
					<td><input type="submit" value="Update"></td>
				</form>
		</tr>';
	}
}

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
	<script
  src="https://code.jquery.com/jquery-1.12.4.min.js"
  integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
  crossorigin="anonymous"></script>
	<script>
		function amort_change(x){
			var noco = parseFloat($('#amt'+x).val())/parseFloat($('#amort'+x).val());
			$('#noco'+x).val(Math.ceil(parseFloat(noco)));
		}
		function noco_change(x){
			if(parseFloat($('#noco'+x).val()) > 0){
				var amort = parseFloat($('#amt'+x).val())/parseFloat($('#noco'+x).val());
				$('#amort'+x).val(parseFloat(amort.toFixed(2)));
			}
		}
	</script>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
	<div id="row">
		<div class="col-md-6">
			<h2>Employee with Hospital Deductions</h2>
		</div>		
		
	</div>
      
   <table width="100%">
			<thead>
				<tr>
					<th align="left">Name</th>
				
					<th align="center">AR</th>
					<th align="center">Batch</th>
					<th align="center">Total Amount</th>
					<th align="center">Payment</th>
					<th align="center">Balance</th>
					<th align="center">No. of deductions</th>
					<th align="center">Deduction <br>per Payday</th>	
					<th align="center">Start</th>					
					<th align="center">Update</th>
				</tr>
			</thead>
			<tbody>
				<tr><td colspan="10"><hr></td></tr>
				<?php echo $data;?>
			</tbody>
		</table>
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>