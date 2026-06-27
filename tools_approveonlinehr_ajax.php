<?php
ob_start();
session_start();

include("dbcon.php");
include ("employeefunctions.php");

if($_GET['act']=='approve'){
	$id = $_GET['id'];
	//die($id);

		$ar = mysql_fetch_array(mysql_query("select * from kiosk_request where ndex='".$id."'"));
		$_GET['emp']=$ar['empid'];
		$jj=$ar['ndex'];
		$det=$ar['date'];
		$_GET[$det]=$ar['date'];

			if($ar['tayp']=='leave'){
				$req=explode("|",$ar['request']);
				$start =strtotime($req[0]);
				$end =strtotime($req[1]);
				for ( $a = $start; $a <= $end; $a += 86400 ){
					$det=date('Y-m-d',$a);				
					$ck_rd = mysql_fetch_array(mysql_query("select * from employee_restday where employeeId='".$_GET['emp']."' and `startDate` = '".$det."'"));
					if($ck_rd['ndex']>0){
						
					}
					else{
						$delete_shift = mysql_query("delete from employee_shifting where employeeId='".$_GET['emp']."' and `startDate` = '".$det."'");
						$delete_restday = mysql_query("delete from employee_restday where employeeId='".$_GET['emp']."' and `startDate` = '".$det."'");

						$ch = mysql_fetch_array(mysql_query("select * from employee_leave where employeeId='".$_GET['emp']."'
							and leaveId='".$req[2]."' and startDate='".$det."'
						"));

						if($ch['ndex']>0){

						}
						else{
						$ins=mysql_query("insert into employee_leave (`employeeId`, `leaveId`, `startDate`, `endDate`, `approvedBy`, `approvedDate`)
					 		 VALUES ('".$_GET['emp']."','".$req[2]."','".$det."','".$det."','".$_SESSION['nym']."','".date('Y-m-d')."')");
						}
						
					}
				
				}
			}
			if($ar['tayp']=='Overtime'){

				$exp = explode("|",$ar['request']);
				$chk = mysql_fetch_array(mysql_query("select * from dailytimesummary where employeeId=".$ar['empid']." and date='".$ar['date']."'"));
				if($chk['ndex']>0){
					$upd=mysql_query("update dailytimesummary set approvedOvertime='".$exp[0]."',approvedOvertimeNightPremium='".$exp[1]."',overtimeRemarks='".mysql_real_escape_string($ar['remarks'])."'
				 where employeeId=".$ar['empid']." and date='".$ar['date']."'");
				}
				else{
					$upd=mysql_query("insert into dailytimesummary (approvedOvertime,approvedOvertimeNightPremium,overtimeRemarks,employeeId,date) 
						VALUES ('".$exp[0]."','".$exp[1]."','".mysql_real_escape_string($ar['remarks'])."',".$ar['empid'].",'".$ar['date']."') ");
				}
				
			}
			if($ar['tayp']=='log'){

				$req=explode("|",$ar['request']);
				$in_out = ($req[0] == 'out' ? '1' : '0');
				$hr = $req[1];
				if($req[3]=='PM'){
					$hr = $hr + 12;
				}
				$ee = mysql_fetch_array(mysql_query("select * from employee where ndex='".$ar['empid']."'"));
				$upd=mysql_query("insert into hrinterface (`dtrid`, `datelog`, `log`, `in_out`)
			VALUES('".$ee['biometricNo']."','".$ar['date']."','".$ar['date']." ".$hr.":".$req[2].":00','".$in_out."')");

			}
			if($ar['tayp']=='drd'){
				$exp = explode("|",$ar['request']);
				$hours = $exp[0];
				$ot = $exp[1];
				$np = $exp[2];
				$remarks = $ar['remarks'];
				
				$upd=mysql_query("update dailytimesummary set hoursDuty='".$hours."',drdRemarks='".$remarks."',otRestDay='".$ot."',night_prem='".$np."',overtimeRemarks='".mysql_real_escape_string($remarks)."'
				 where employeeId=".$ar['empid']." and date='".$ar['date']."'");
				
			}
			if($ar['tayp']=='Schedule'){
				if($ar['request'] == 'OFF'){
					$daylog=date('w',strtotime($ar['date']));
					$delete = mysql_query("delete from employee_shifting where employeeId='".$_GET['emp']."' and `startDate` = '".$ar['date']."'");
					$ins=mysql_query("insert into employee_restday (`employeeId`, `restday`, `startDate`, `endDate`, `approvedBy`, `approvedDate`)
				 		 VALUES ('".$_GET['emp']."','".$daylog."','".$ar['date']."','".$ar['date']."','".$_SESSION['nym']."','".date('Y-m-d')."')");
				}
				else{
					
					$delete = mysql_query("delete from employee_restday where employeeId='".$_GET['emp']."' and `startDate` = '".$ar['date']."'");
					$delete2 = mysql_query("delete from employee_shifting where employeeId='".$_GET['emp']."' and `startDate` = '".$ar['date']."'");
					$ins=mysql_query("insert into employee_shifting (`employeeId`, `shiftingId`, `startDate`, `endDate`, `approvedBy`, `approvedDate`)
				 		 VALUES ('".$_GET['emp']."','".str_replace('s', '', $ar['request'])."','".$ar['date']."','".$ar['date']."','".$_SESSION['nym']."','".date('Y-m-d')."')");
					// $ins=mysql_query("insert into employee_shifting (`employeeId`, `shiftingId`, `startDate`, `endDate`)
				 // 		 VALUES (".$_GET['emp'].",'".substr($ar['request'], 1)."','".$det."','".$det."')");
				}
				
				 
			}
			date_default_timezone_set("Asia/Manila");
			$upd=mysql_query("update kiosk_request set approve2=1,hrApprovedDate='".date('Y-m-d H:i:s')."',hrApprovedBy='".$_SESSION['nym']."' where ndex='".$jj."'");
			
			$logs = mysql_query("insert into kiosk_request_logs (action,timelog,request_id,user) VALUES ('Approve Request (HR)','".date('Y-m-d H:i:s')."','".$jj."','".$_SESSION['nym']."')");
	

}

if($_GET['act']=='pending'){
	$id = $_GET['id'];
	//echo $_GET['remark'];
	$update = mysql_query("update kiosk_request set isPending='1' where ndex='".$id."'");
	$logs = mysql_query("insert into kiosk_request_logs (action,timelog,request_id,user,remarks) VALUES ('Pending Request','".date('Y-m-d H:i:s')."','".$id."','".$_SESSION['nym']."','".$_GET['remark']."')");
}

if($_GET['act']=='back'){
	$id = $_GET['id'];
	//echo $_GET['remark'];
	$update = mysql_query("update kiosk_request set isPending='0' where ndex='".$id."'");
	$logs = mysql_query("insert into kiosk_request_logs (action,timelog,request_id,user,remarks) VALUES ('Return to Active','".date('Y-m-d H:i:s')."','".$id."','".$_SESSION['nym']."','Return to Active')");
}