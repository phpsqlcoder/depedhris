<?php

$conn=mysql_connect("localhost","root","pangitka");
mysql_select_db("hris",$conn);




	$cutoff = '2020-06-15';
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
			mysql_select_db("hr_interface_db",$conn);
			$insert = mysql_query("insert into ar_hospital_ee_payment_ledger (`ar_hospital_ee_trx_Id`, `Batch_No`, `AR_No`, `employeeId`, `paymentDate`, `amountPaid`, `lineBalance`, `paymentType`, `remarks`, `isUploaded`, `or_number`, `payment_type`, `status`, `cancelled_by`, `cancelled_date`, `Pat_No`, `transaction_status`, `Trx_type`, `amortization`, `doctorId`, `priorityNo`,`isTransferred`) values (
			'".$r['ar_hospital_ee_trx_Id']."','".$r['Batch_No']."','".$r['AR_No']."','".$r['employeeId']."','".$r['paymentDate']."','".$r['amountPaid']."','".$r['lineBalance']."','".$r['paymentType']."','".$r['remarks']."','".$r['isUploaded']."','".$r['or_number']."','".$r['payment_type']."','".$r['status']."','".$r['cancelled_by']."','".$r['cancelled_date']."','".$r['Pat_No']."','".$r['transaction_status']."','".$r['Trx_type']."','".$amort['amortization']."','".$r['doctorId']."','".$r['priorityNo']."','0'
			)");
		
		}

	}


?>