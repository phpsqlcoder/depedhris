<?php
global $conn;
$conn=mysql_connect("localhost","root","pangitka");
mysql_select_db("hris",$conn);
//echo transfer_to_interface('2020-01-15');
//var_dump(process_payment(2426,"2022-03-31"));
//echo compute_payment('15155', '200003697', '2426', '2022-03-31');
$bills = get_payables('16489', '210002792', '4');
$paid = get_paid('16489', '210002792', '4');
echo $bills." xx ".$paid;
// receiver
function process_payment($empid, $cutoff_date){
	global $conn;
	$total_payment = 0;
	$billings = get_billings($empid, $cutoff_date);

	if($billings == 0)
		return 0;
	else{

		foreach($billings as $bill){

			$payment = compute_payment($bill['Batch_No'], $bill['AR_No'], $empid, $cutoff_date);
			$total_payment += $payment;
		}

	}

	return $total_payment;
}

function get_billings($empid, $cutoff_date){
	global $conn;	

	$sql = mysql_query("select Batch_No,AR_No,employeeId,sum(Amount) as bills 
		from ar_hospital_ee_trx where employeeId='".$empid."' and Status='Active' and trxDate<='".$cutoff_date."'
		group by Batch_No,AR_No,employeeId");
	while($results = mysql_fetch_array($sql)){
		$billings[] = $results;
	}

	if(!isset($billings))
		return 0;
	else
		return $billings;
}

function compute_payment($Batch_No, $AR_No, $empid, $cutoff_date){
	global $conn;

	$amortization = get_amortization($Batch_No, $AR_No, $empid);
	$balance = 	get_balance($Batch_No, $AR_No, $empid);
	
	
	if($amortization == 0 )
		return 0;
	if($balance == 0)
		return 0;
	
	
	$process_payment = save_payment($Batch_No, $AR_No, $empid, $amortization, $cutoff_date);
	
	return $process_payment;
}

function get_amortization($Batch_No, $AR_No, $empid){
	global $conn;	
	$amortization = mysql_fetch_array(mysql_query("select * from hospital_deduction_amortization where employeeId='".$empid."' and AR_No = '".$AR_No."' and Batch_No='".$Batch_No."' and start_date<='".date('Y-m-d',strtotime(date('Y-m-d')." + 6 days"))."' and Status='Active'"));

	if(!isset($amortization))
		return 0;
	else
		return $amortization['amortization'];
}

function get_balance($Batch_No,$AR_No, $empid){

	$bills = get_payables($Batch_No,$AR_No, $empid);
	$paid = get_paid($Batch_No,$AR_No, $empid);

	$balance = $bills - $paid;
	if($balance <= 0)
		return 0;
	else
		return $balance;
}

function get_payables($Batch_No,$AR_No, $empid){
	global $conn;
	$total_bill = mysql_fetch_array(mysql_query("select sum(Amount) as bills from ar_hospital_ee_trx where employeeId='".$empid."'  and AR_No = '".$AR_No."' and Batch_No='".$Batch_No."'"));

	$total_refund = mysql_fetch_array(mysql_query("select sum(amountPaid) as refund from ar_hospital_ee_refund_ledger where employeeId='".$empid."'  and AR_No = '".$AR_No."' and Batch_No='".$Batch_No."' and Status='POSTED'"));

	if(!$total_bill)
		return 0;
	else
		return $total_bill['bills'] + $total_refund['refund'];
}

function get_paid($Batch_No,$AR_No, $empid){
	global $conn;
	$total_paid = mysql_fetch_array(mysql_query("select sum(amountPaid) as paid from ar_hospital_ee_payment_ledger where employeeId='".$empid."'  and AR_No = '".$AR_No."' and Batch_No='".$Batch_No."'"));

	if(!$total_paid)
		return 0;
	else
		return $total_paid['paid'];
}

function save_payment($Batch_No, $AR_No, $empid, $amortization, $cutoff_date){	
	global $conn;	
	$running_balance = $amortization;
	$amount_to_pay = 0;
	$bills =  mysql_query("select * from ar_hospital_ee_trx 
		where employeeId='".$empid."' and AR_No = '".$AR_No."' and Batch_No='".$Batch_No."' and Status='Active' order by priorityNo");

	while($bill = mysql_fetch_array($bills)){
		$total_refund = mysql_fetch_array(mysql_query("select sum(amountPaid) as refund from ar_hospital_ee_refund_ledger where ar_hospital_ee_trx_Id='".$bills['id']."' and Status='POSTED'"));
		if($running_balance > 0){

			$balance_per_transaction = ($bill['Amount'] + $total_refund['refund']) - get_paid_per_transactionId( $bill['ndex'] );
			
			if($balance_per_transaction > $running_balance){
				$amount_to_pay = $running_balance;
				$running_balance = 0;
				$line_balance = $balance_per_transaction - $amount_to_pay;
				
			}
			else{
				$amount_to_pay = $balance_per_transaction;
				$running_balance = $running_balance - $balance_per_transaction;
				$line_balance = 0;
				$tag_as_paid = tag_as_paid($bill['ndex']);
			}
			$insert_payment = mysql_query("INSERT INTO `ar_hospital_ee_payment_ledger`(`ar_hospital_ee_trx_Id`, `Batch_No`, `AR_No`, `employeeId`, `paymentDate`, `amountPaid`, `lineBalance`, `paymentType`, `remarks`, `isUploaded`) 
				VALUES ('".$bill['ndex']."', '".$bill['Batch_No']."', '".$bill['AR_No']."', '".$bill['employeeId']."', '".$cutoff_date."', '".$amount_to_pay."', '".$line_balance."', 'Payroll', '','')");

		}
	}
	return $amount_to_pay;

}

function get_paid_per_transactionId($ndex){
	global $conn;
	$total_paid = mysql_fetch_array(mysql_query("select sum(amountPaid) as paid from ar_hospital_ee_payment_ledger where ar_hospital_ee_trx_Id='".$ndex."'"));

	if(!$total_paid)
		return 0;
	else
		return $total_paid['paid'];
}

function tag_as_paid($ndex){
	global $conn;
	$tas_as_paid = mysql_query("update ar_hospital_ee_trx set Status='Done' where ndex='".$ndex."'");

	return;
}

function transfer_to_interface($cutoff){
	global $conn;
	mysql_select_db("hris",$conn);
	$log = mysql_fetch_array(mysql_query("select * from hospital_logs"));
	$q = mysql_query("select p.*,h.Pat_No,h.Status as transaction_status, h.Trx_type, h.doctorId, h.priorityNo, h.employeeId as ee from ar_hospital_ee_payment_ledger p left join ar_hospital_ee_trx h on h.ndex=p.ar_hospital_ee_trx_Id
	 where p.cancelled_date>'".$log['last_upload']."' OR p.paymentDate>='".$cutoff."'");
	
	while($r = mysql_fetch_array($q)){
		mysql_select_db("hris",$conn);
		$amort = mysql_fetch_array(mysql_query("select * from hospital_deduction_amortization where employeeId='".$r['ee']."' and AR_No = '".$r['AR_No']."' and Batch_No='".$r['Batch_No']."' and Status='Active'"));

		mysql_select_db("hr_interface_db",$conn);

		$chk = mysql_fetch_array(mysql_query("select * from ar_hospital_ee_payment_ledger where AR_No = '".$r['AR_No']."' and Batch_No='".$r['Batch_No']."' and employeeId='".$r['employeeId']."' and Pat_No='".$r['Pat_No']."' and Trx_type='".$r['Trx_type']."'" ));

		if($chk['ndex']>0){
			$upd=mysql_query("");
		}
		else{
			
			$insert = mysql_query("insert into ar_hospital_ee_payment_ledger (`ar_hospital_ee_trx_Id`, `Batch_No`, `AR_No`, `employeeId`, `paymentDate`, `amountPaid`, `lineBalance`, `paymentType`, `remarks`, `isUploaded`, `or_number`, `payment_type`, `status`, `cancelled_by`, `cancelled_date`, `Pat_No`, `transaction_status`, `Trx_type`, `amortization`, `doctorId`, `priorityNo`) values (
			'".$r['ar_hospital_ee_trx_Id']."','".$r['Batch_No']."','".$r['AR_No']."','".$r['employeeId']."','".$r['paymentDate']."','".$r['amountPaid']."','".$r['lineBalance']."','".$r['paymentType']."','".$r['remarks']."','".$r['isUploaded']."','".$r['or_number']."','".$r['payment_type']."','".$r['status']."','".$r['cancelled_by']."','".$r['cancelled_date']."','".$r['Pat_No']."','".$r['transaction_status']."','".$r['Trx_type']."','".$amort['amortization']."','".$r['doctorId']."','".$r['priorityNo']."'
			)");
		
		}

	}
}


?>