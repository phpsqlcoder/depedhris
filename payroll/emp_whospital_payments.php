<?php
ob_start();
session_start();
//if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");
include ("payrollfunctions.php");
//include ("hospital_deduction_functions.php");
$data = '';
$data_refund = '';
$l = mysql_fetch_array(mysql_query("select e.ndex as endex,e.firstName,e.lastName,e.middleName,d.name as deptname, h.AR_No, h.Batch_No, h.status, h.Trx_type, h.Amount, h.amortization, h.priorityNo, h.ndex as hnd, h.TrxDate from ar_hospital_ee_trx h left join employee e on e.ndex=h.employeeId
	left join dept d on d.ndex=e.deptId  where h.ndex='".$_GET['id']."'
	"));

$qry = mysql_query("select e.ndex as endex,e.firstName,e.lastName,e.middleName,d.name as deptname, h.AR_No, h.Batch_No, h.paymentDate, h.lineBalance, h.AmountPaid, h.paymentType, h.remarks, h.ndex as hnd, h.or_number, h.remarks, h.payment_type from ar_hospital_ee_payment_ledger h left join employee e on e.ndex=h.employeeId 
	left join dept d on d.ndex=e.deptId  where h.ar_hospital_ee_trx_Id='".$_GET['id']."' order by h.paymentDate 
	");
$fullname = $l['lastName'].', '.$l['firstName'].' '.$l['middleName'];
$dept = $l['deptname'];
$ctr1s=0;
$num = 0;
$total_paid =0;

$amt = $l['Amount'];
$data.='<tr style="background-color:'.$bgclr1s.';display:none;">	
				<td>Beginning Balance</td>			
				<td>'.$l['TrxDate'].'</td>				
				<td colspan="3">'.$l['Trx_Type'].'</td>
				<td align="right">0.00</td>				
				<td align="right">'.number_format($l['Amount'],2).'</td>			
	</tr>';
while($r = mysql_fetch_array($qry)){	
	$ctr1s++;
	$num++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}

	$amt = $amt - $r['AmountPaid'];


	$total_paid +=$r['AmountPaid'];
	

	$data.='<tr style="background-color:'.$bgclr1s.'">	
				<td>'.$r['paymentType'].'</td>			
				<td>'.$r['paymentDate'].'</td>
				<td>'.$r['or_number'].'</td>
				<td>'.$r['payment_type'].'</td>				
				<td>'.$r['remarks'].'</td>
				<td align="right">'.number_format($r['AmountPaid'],2).'</td>				
				<td align="right" style="display:none;">'.number_format($amt,2).'</td>			
	</tr>';
	
}
$data.='<tr><td colspan="9"><hr></td></tr><tr style="font-weight:bold;">	
				<td colspan="5">Total</td>			
				<td align="right">'.number_format($total_paid,2).'</td>			
	</tr>';




	$qry_refund = mysql_query("select e.ndex as endex,e.firstName,e.lastName,e.middleName,d.name as deptname, h.AR_No, h.Batch_No, h.paymentDate, h.lineBalance, h.AmountPaid, h.paymentType, h.remarks, h.ndex as hnd, h.or_number, h.remarks, h.payment_type, h.payment from ar_hospital_ee_refund_ledger h left join employee e on e.ndex=h.employeeId 
	left join dept d on d.ndex=e.deptId  where h.ar_hospital_ee_trx_Id='".$_GET['id']."' order by h.paymentDate 
	");

$ctr1s=0;
$total_refund =0;

$amt = $l['Amount'];			
$total_paid_refund = 0;
while($r = mysql_fetch_array($qry_refund)){	
	$ctr1s++;
	
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}


	$total_refund +=$r['AmountPaid'];
	$total_paid_refund +=$r['payment'];

	$data_refund.='<tr style="background-color:'.$bgclr1s.'">	
				<td>'.$r['paymentType'].'</td>			
				<td>'.$r['paymentDate'].'</td>
				<td>'.$r['or_number'].'</td>
				<td>'.$r['payment_type'].'</td>				
				<td>'.$r['remarks'].'</td>
				<td align="right">'.number_format($r['AmountPaid'],2).'</td>				
					
	</tr>';
	
}
$data_refund.='<tr><td colspan="8"><hr></td></tr><tr style="font-weight:bold;">	
				<td colspan="5">Total</td>			
				<td align="right">'.number_format($total_refund,2).'</td>			
	</tr>';



$amort = mysql_fetch_array(mysql_query("select * from hospital_deduction_amortization where employeeId='".$l['endex']."' and AR_No = '".$l['AR_No']."' and Batch_No='".$l['Batch_No']."' and Status='Active'"));
if($amort['amortization'] > 0){
	$remain = ceil($amt / $amort['amortization']);
}
else{
	$remain = 0;
}
?>

<style>
	h2{
		font: 20px Arial, sans-serif;
	}
	h3,table{
		font: 15px Arial, sans-serif;
	}
</style>
	<table width="100%">
		<tr>
			<td colspan="2" align="center"><h2>Employee Payment Ledger - <?php echo $l['Trx_type'];?></h2><h3><?php echo $fullname; ?> (<?php echo $dept;?>) </h3>
			<h3><span>Batch#: <?php echo $l['Batch_No']; ?></span> &nbsp;&nbsp;&nbsp;<span>AR#: <?php echo $l['AR_No']; ?></span></h3></td>
		</tr>
		<tr>
			
			<td width="60%">
				<table width="100%">
					<tr>
						<td>Current Deduction per Payroll:</td>
						<td><?php echo number_format($amort['amortization'],2); ?></td>
					</tr>
					<tr>
						<td>Total Hospital Bill:</td>
						<td><?php echo number_format($l['Amount'],2); ?></td>
					</tr>
					<tr>
						<td>Total Refund:</td>
						<td><?php echo number_format($total_refund,2); ?></td>
					</tr>
					<tr>
						<td>Total Payments:</td>
						<td><?php echo number_format($total_paid,2); ?></td>
					</tr>
					<tr>
						<td>Current Balance:</td>
						<td><?php echo number_format(($amt + $total_refund) - ($total_paid + $total_paid_refund),2); ?></td>
					</tr>
					<tr>
						<td>Total No. of Deductions:</td>
						<td><?php echo $amort['no_of_deduction']; ?></td>
					</tr>
					<tr>
						<td>Total No. of Payments:</td>
						<td><?php echo (int) $num ; ?></td>
					</tr>
					<tr>
						<td>Remaining Number of Deduction:</td>
						<td><?php //echo (int) $remain;
							echo $amort['no_of_deduction'] - ((int) $num);
						 ?></td>
					</tr>
					<tr>
						<td>Deduction As of:</td>
						<td><?php echo date('F d, Y'); ?></td>
					</tr>
				</table>
			</td>
			<td width="60%" style="vertical-align:top;">
				&nbsp;
				
			</td>
		</tr>
	</table>
        

		
		<br>
		<h2>Payments</h2>
		<table width="100%">
			<thead>
				<tr>
					<th>Type</th>
					<th>Date</th>
					<th>OR No.</th>
					<th>Payment Type</th>
					<th>Remarks</th>
					<th>Amount</th>
					<th style="display:none;">Running Balance</th>					
				</tr>
			</thead>
			<tbody>
				<tr><td colspan="9"><hr></td></tr>
				<?php echo $data;?>
			</tbody>
		</table>

		<h2>Refund</h2>
		<table width="100%">
			<thead>
				<tr>
					<th>Type</th>
					<th>Date</th>
					<th>OR No.</th>
					<th>Payment Type</th>
					<th>Remarks</th>
					<th>Amount</th>				
				</tr>
			</thead>
			<tbody>
				<tr><td colspan="8"><hr></td></tr>
				<?php echo $data_refund;?>
			</tbody>
		</table>
		<h2>&nbsp;</h2>

  