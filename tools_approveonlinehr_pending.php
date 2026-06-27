<?php
ob_start();
session_start();
$count_overtime=0;
$count_leave=0;
$count_log=0;
$count_schedule=0;
$count_restday=0;
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include ("employeefunctions.php");
$dar=explode(",",$_SESSION['deptId']);
//$dd=mysql_Fetch_object(mysql_query("select * from dept where ndex in (".$_SESSION['deptId'].")"));
function removear($are){
	$remar="";
	foreach($are as $nare){
		if($nare!=73){
			$remar.=$nare.",";
		}
	}
	$remar=rtrim($remar,",");
return $remar;
}
$coopt='';
$coqry=mysql_query("select * from cutoffdates order by payrollDate DESC");
while($co=mysql_fetch_object($coqry)){
	$coopt.="<option value='".$co->ndex."'>".$co->payrollDate."";
}

if($_GET['act']=='aprob'){
	$aqry="SELECT k.*,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.ndex as empid,e.employmentStatus,e.employeeNo from kiosk_request k left join employee e on e.ndex=k.empid left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId where isPending=1";
	$aexec=mysql_query($aqry);
	$var=0;
	while($ar=mysql_fetch_array($aexec)){
		$_GET['emp']=$ar['empid'];
		$jj=$ar['ndex'];
		$det=$ar['date'];
		$_GET[$det]=$ar['date'];
		if($_POST['ck'.$jj]=="on"){
			//echo "update kiosk_request set approve1=1 where ndex='".$jj."'";
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
						$ins=mysql_query("insert into employee_leave (`employeeId`, `leaveId`, `startDate`, `endDate`, `approvedBy`, `approvedDate`)
					 		 VALUES ('".$_GET['emp']."','".$req[2]."','".$det."','".$det."','".$_SESSION['nym']."','".date('Y-m-d')."')");
					}
				
				}
			}
			if($ar['tayp']=='Overtime'){

				$exp = explode("|",$ar['request']);
				$chk = mysql_fetch_array(mysql_query("select * from dailytimesummary where employeeId=".$ar['empid']." and date='".$ar['date']."'"));
				if($chk['ndex']>0){
					$upd=mysql_query("update dailytimesummary set approvedOvertime='".$exp[0]."',approvedOvertimeNightPremium='".$exp[1]."',overtimeRemarks='".$ar['remarks']."'
				 where employeeId=".$ar['empid']." and date='".$ar['date']."'");
				}
				else{
					$upd=mysql_query("insert into dailytimesummary (approvedOvertime,approvedOvertimeNightPremium,overtimeRemarks,employeeId,date) 
						VALUES ('".$exp[0]."','".$exp[1]."','".$ar['remarks']."',".$ar['empid'].",'".$ar['date']."') ");
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
				
				$upd=mysql_query("update dailytimesummary set hoursDuty='".$hours."',drdRemarks='".$remarks."',otRestDay='".$ot."',night_prem='".$np."',overtimeRemarks='".$remarks."'
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
			$upd=mysql_query("update kiosk_request set approve2=1 where ndex='".$jj."'");
			date_default_timezone_set("Asia/Manila");
			$logs = mysql_query("insert into kiosk_request_logs (action,timelog,request_id,user) VALUES ('Approve Request (HR)','".date('Y-m-d H:i:s')."','".$jj."','".$_SESSION['nym']."')");
		}
	}
}

	if(!$_GET['mant']){
		$cutoffq=mysql_fetch_array(mysql_query("SELECT * FROM  `cutoffdates` WHERE isLock =0 AND payrollDate >=  '2018-01-10' ORDER BY payrollDate LIMIT 1"));
		$_GET['startDate']=$cutoffq['cutoffDateStart'];
		$_GET['endDate']=$cutoffq['cutoffDateEnd'];
		$_GET['aStatus']='0';
		$_GET['depsel']='all';
	}
	else{
		$cutoffq=mysql_fetch_array(mysql_query("SELECT * FROM  `cutoffdates` WHERE ndex='".$_GET['mant']."'"));
		$_GET['startDate']=$cutoffq['cutoffDateStart'];
		$_GET['endDate']=$cutoffq['cutoffDateEnd'];
		
	}
	$defopt="<option value='".$cutoffq['ndex']."' selected='selected'>".$cutoffq['payrollDate']."";
	date_default_timezone_set("Asia/Manila");
	$start = strtotime($_GET['startDate']);
	$end = strtotime($_GET['endDate']);

	$types = array('Overtime','drd','log','Schedule','leave');
	foreach($types as $type){
		$data[$type]="";
		if($_GET['depsel']!='all'){
			$adqry=" and d.ndex=".$_GET['depsel']."";
			$dip=$_GET['depsel'];
		}
		else{
			$adqry="";
			$dip=$_SESSION['deptId'];
		}

		$qry="SELECT k.*,e.biometricNo,e.firstName,e.lastName,e.middleName,p.name as position,e.dateHired,d.name as dept,e.ndex as empid,e.employmentStatus,e.employeeNo from kiosk_request k left join employee e on e.ndex=k.empid left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
		
		$qry.=" where k.approve1=1 and k.tayp='".$type."' and k.approve2='".$_GET['aStatus']."'
			and k.deptApprovedDate>='".$_GET['startDate']."' and k.deptApprovedDate<='".$_GET['endDate']." 23:59:59' and k.isPending=1
		";
		//$qry.=" ORDER BY k.approve2,d.ndex,e.lastName,e.firstName";
		$qry.=" ORDER BY k.ndex DESC";
		//die($qry);
		//echo $qry;
		$exec=mysql_query($qry);
		$var=0;
		while($r=mysql_fetch_object($exec)){
			$var++;
			//$is_cb = ($r->approve2 == '1' ? "Approved" : "<input type='checkbox' style='zoom:2.0' name='ck".$r->ndex."'>");
			if($r->approve2==1){
				$is_cb = "Approved";
			}
			else{
				$is_cb = '
							<a title="Approve" onclick="approve_req('.$r->ndex.')" class="btn btn-sm green"> <i class="fa fa-check-square-o"></i></a>&nbsp;
							<a title="Forward Back to Active" onclick="back_req('.$r->ndex.')"  class="btn btn-sm red"> <i class="fa fa-mail-reply"></i></a>
				';
			}

			$addr = '';
			if($type=='Overtime'){
				$link="overtime_edit.php";
				$shifts=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$r->empid." and approvedDate<>'0000-00-00 00:00:00' and '".$r->date."' between startDate and endDate"));
				if($shifts->shiftingId){
					$getshift=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shifts->shiftingId.""));
				}
				$inlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$r->date."' and in_out=0 ORDER BY log LIMIT 0,1"));
    			$outlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$r->date."' and in_out=1 ORDER BY log LIMIT 0,1"));
    			$exp = explode("|",$r->request);
    			$r->request = "
    				Overtime = ".$exp[0]." hrs<br>
    				Night Premium = ".$exp[1]." hrs<br>
    				Time In = ".date('H:i:s',strtotime($inlog->log))." <br>
    				Time Out = ".date('H:i:s',strtotime($outlog->log))." <br>
    				Shift In = ".$getshift->timeIn." <br>
    				Shift Out = ".$getshift->timeOut." <br>
    			";
    			if($r->approve2 == '0') { $count_overtime++; }
			}
			if($type=='drd'){
				$link="drd_edit.php";
				$inlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$r->date."' and in_out=0 ORDER BY log LIMIT 0,1"));
    			$outlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$r->date."' and in_out=1 ORDER BY log LIMIT 0,1"));
    			
    			$exp = explode("|",$r->request);
    			$r->request =
    			"Hours = ".$exp[0]." Hrs<br>
    			Overtime = ".$exp[1]." Hrs<br>
    			Night Premium = ".$exp[2]." Hrs<br>
    			Time In = ".date('H:i:s',strtotime($inlog->log))." <br>
    			Time Out = ".date('H:i:s',strtotime($outlog->log))." <br>
    			";
    			if($r->approve2 == '0') { $count_restday++; }
			}
			if($type=='log'){
				$link="log_edit.php";
				$exp = explode("|",$r->request);
    			$r->request =
    			"Type = Time ".strtoupper($exp[0])."<br>
    			Time = ".$exp[1].":".$exp[2]." ".$exp[3]." <br>
    			
    			";
    			if($r->approve2 == '0') { $count_log++; }
			}
			if($type=='Schedule'){
				$link="sched_edit.php";
				$shift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$r->empid." and '".$r->date."' between startDate and endDate"));
				if($r->request=='OFF'){
					
					$n= 'OFF';
				}
				else{
					$s = mysql_fetch_object(mysql_query("select 
						CASE timeIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeIn),'%H:%i %p') END AS tymIn, 
						CASE breakOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakOut),'%H:%i %p') END as brekOut,
						CASE breakIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakIn),'%H:%i %p') END as brekIn,
						CASE timeOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeOut),'%H:%i %p') END as tymOut,breakMinutes,ndex
					 from shifting where ndex='".str_replace('s', '', $r->request)."'"));

					
					$n = $s->tymIn." - ".$s->brekOut." - ".$s->brekIn." - ".$s->tymOut;
					
				}
				if($shift->shiftingId){
					$o = mysql_fetch_object(mysql_query("select 
						CASE timeIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeIn),'%H:%i %p') END AS tymIn, 
						CASE breakOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakOut),'%H:%i %p') END as brekOut,
						CASE breakIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakIn),'%H:%i %p') END as brekIn,
						CASE timeOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeOut),'%H:%i %p') END as tymOut,breakMinutes,ndex
					 from shifting where ndex='".$shift->shiftingId."'"));
	    			$r->request =
	    			"Old = ".$o->tymIn." - ".$o->brekOut." - ".$o->brekIn." - ".$o->tymOut."<br>
	    			New = ".$n."		
	    			";
    			}
    			else{
    				$r->request =
	    			"Old = OFF<br>
	    			New = ".$n."		
	    			";
    			}
    			if($r->approve2 == '0') { $count_schedule++; }
			}
			if($type=='leave'){
				$link="leave_edit.php";
				$exp = explode("|",$r->request);
				$l=mysql_fetch_object(mysql_query("select * from `leave` where ndex='".$exp[2]."'"));
    			
    			$r->request =
    			"Type = ".$l->code."<br>
    			Start = ".$exp[0]."<br>
    			End = ".$exp[1]."<br>    			
    			";
    			if($r->approve2 == '0') { $count_leave++; }
			}
			$log = mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$r->ndex."' and action='Create Request'"));
			if($log['ndex']){
			    $date_created=$log['timelog'];
			  }
			  else{
			    $date_created='';
			  }

			$log = mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$r->ndex."' and action='Approve Request'"));
			if($log['ndex']){
			    $date_approved=$log['timelog'];
			  }
			  else{
			    $date_approved='';
			  }

			  $hrlog = mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$r->ndex."' and action='Approve Request (HR)'"));
			if($hrlog['ndex']){
			    $date_approved_hr="(".$hrlog['timelog'].")";
			  }
			  else{
			    $date_approved_hr='';
			  }

			$data[$type].="<tr style='color:black;' id='cdiv".$r->ndex."'>
			<td id='cbdiv".$r->ndex."'>".$is_cb."<br>".$date_approved_hr."</td>
			<td>".$date_created."</td>
			<td>".$date_approved."</td>
			<td>".getID($er->employmentStatus,$r->employeeNo)."</td>
			<td>".$r->lastName." , ".$r->firstName." ".$r->middleName." <br>(".$r->dateHired.")</td>
			<td>".$r->dept."</td>
			<td>".$r->tayp."</td>
			<td>".$r->date."</td>
			<td>".$r->request."</td>
			<td>".$r->remarks."</td>
			<td>
			<a href='#'	class='btn btn-xs red' onclick=\"window.open('online_apps/".$link."?id=".$r->ndex."','displayWindow','toolbar=no,scrollbars=yes,width=1200,height=500')\";> Edit</a></td>
			";
			date_default_timezone_set("Asia/Manila");

			$data[$type].="</tr>";
			
		}
	}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>DTR System - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
    	<link href="kiosk1/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Global styles END --> 
   
  <!-- Page level plugin styles START -->
  <link href="kiosk1/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet">
  <link href="kiosk1/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
 

  <!-- Theme styles START -->
  <link href="kiosk1/assets/global/css/components.css" rel="stylesheet">
  <link href="kiosk1/assets/frontend/layout/css/style.css" rel="stylesheet">
  <link href="kiosk1/assets/frontend/layout/css/style-responsive.css" rel="stylesheet">
  <link href="kiosk1/assets/frontend/layout/css/themes/red.css" rel="stylesheet" id="style-color">
</head>

<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12" style="width:90%;">

<div id="rcont">
  <h2><strong>Pending Applications</strong></h2>
			<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
			<?php include "calendar.inc"; ?>
			<?php

			?>
			
				<form action="tools_approveonlinehr_pending.php" method="get" name="sadsa">
					<table width="100%" style="font-family:Arial;font-size:12px;">

						<tr>
							<td colspan="5">
								<table><tr>
									<td>Status:<select name="aStatus">
										<option value="0" <?php if($_GET['aStatus']==0) echo 'selected="selected"'; ?>>For Approval</option>
										<option value="1" <?php if($_GET['aStatus']==1) echo 'selected="selected"'; ?>>Approved</option>
									</select></td>
									<td>Select Cutoff:<select name="mant" id="mant"><?php echo $defopt;?><?php echo $coopt;?></select></td>
									<td>Select Dept: <select name="depsel" style="width:150px;"><option value="all">-All Assigned Dept-<?php echo $optiondepts;?></select></td>
										<td><input type="Submit" value="View"></td>
									</tr></table>
								</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
						</table>
					</form>
				
					
					
					<h4 class="pull-right"><?php echo date('F d',strtotime($_GET['startDate']))." - ".date('F d, Y',strtotime($_GET['endDate'])); ?></h4>
					
						<div class="row">
					        <div class="col-md-12 tab-style-1">
						          <ul class="nav nav-tabs">
						            <li class="active"><a data-toggle="tab" href="#tab-1">
						            	<span class="badge badge-danger"><?php echo $count_overtime; ?></span>&nbsp;Overtime</a></li>
						            <li class=""><a data-toggle="tab" href="#tab-2">
						            	<span class="badge badge-danger"><?php echo $count_restday; ?></span>&nbsp;Duty Restday</a></li>
						            <li class=""><a data-toggle="tab" href="#tab-3">
						            <span class="badge badge-danger"><?php echo $count_log; ?></span>&nbsp;Forgot to Log</a></li>
						            <li class=""><a data-toggle="tab" href="#tab-4">
						            	<span class="badge badge-danger"><?php echo $count_schedule; ?></span>&nbsp;Change Schedule</a></li>
						        	<li class=""><a data-toggle="tab" href="#tab-5">
						        	<span class="badge badge-danger"><?php echo $count_leave; ?></span>&nbsp;Leave</a></li>
						          </ul>
						          <div class="tab-content">
						            <div id="tab-1" class="tab-pane row fade active in">              
						              <div class="col-md-12">
						              	
											<input type="Hidden" name="startDate" value="<?php echo $_GET['startDate'];?>">
											<input type="Hidden" name="endDate" value="<?php echo $_GET['endDate'];?>">
											<input type="Hidden" name="depdep" value="<?php echo $dip;?>">
											<table width="100%" style="font-family:Arial;font-size:12px;" class="table">
												<thead>
													<tr style='color:blue;font-weight:bold;'>
														<th>&nbsp;</th>
														<th>Created</th>
														<th>Approved Date</th>
														<th>ID</th>
														<th>Name</th>
														<th>Dept</th>
														<th>Type</th>
														<th>Date</th>

														<th width="200">Request</th>
														<th>Remarks</th>
												</thead>
												<tbody>
						              			<?php echo $data['Overtime']; ?>
						            
						              			</tbody>
						              		</table>
								
						              </div>
						            </div>


						            <div id="tab-2" class="tab-pane row fade">              
						              <div class="col-md-12">
						              	
											<input type="Hidden" name="startDate" value="<?php echo $_GET['startDate'];?>">
											<input type="Hidden" name="endDate" value="<?php echo $_GET['endDate'];?>">
											<input type="Hidden" name="depdep" value="<?php echo $dip;?>">
											<table width="100%" style="font-family:Arial;font-size:12px;" class="table">
												<thead>
													<tr style='color:blue;font-weight:bold;'>
														<th>&nbsp;</th>
														<th>Created</th>
														<th>Approved Date</th>
														<th>ID</th>
														<th>Name</th>
														<th>Dept</th>
														<th>Type</th>
														<th>Date</th>
														<th>Request</th>
														<th>Remarks</th>
												</thead>
												<tbody>
						              			<?php echo $data['drd']; ?>
						              			<tr><td><input type="Submit" value="Approve Selected"></td></tr>
						              			</tbody>
						              		</table>
										
						              	
						              </div>
						            </div>


						            <div id="tab-3" class="tab-pane row fade">              
						              <div class="col-md-12">

						             
											<input type="Hidden" name="startDate" value="<?php echo $_GET['startDate'];?>">
											<input type="Hidden" name="endDate" value="<?php echo $_GET['endDate'];?>">
											<input type="Hidden" name="depdep" value="<?php echo $dip;?>">
											<table width="100%" style="font-family:Arial;font-size:12px;" class="table">
												<thead>
													<tr style='color:blue;font-weight:bold;'>
														<th>&nbsp;</th>
														<th>Created</th>
														<th>Approved Date</th>
														<th>ID</th>
														<th>Name</th>
														<th>Dept</th>
														<th>Type</th>
														<th>Date</th>
														<th>Request</th>
														<th>Remarks</th>
												</thead>
												<tbody>
						              			<?php echo $data['log']; ?>
					
						              			</tbody>
						              		</table>
								

						              </div>
						            </div>


						            <div id="tab-4" class="tab-pane row fade">              
						              <div class="col-md-12">
						              	
											<input type="Hidden" name="startDate" value="<?php echo $_GET['startDate'];?>">
											<input type="Hidden" name="endDate" value="<?php echo $_GET['endDate'];?>">
											<input type="Hidden" name="depdep" value="<?php echo $dip;?>">
											<table width="100%" style="font-family:Arial;font-size:12px;" class="table">
												<thead>
													<tr style='color:blue;font-weight:bold;'>
														<th>&nbsp;</th>
														<th>Created</th>
														<th>Approved Date</th>
														<th>ID</th>
														<th>Name</th>
														<th>Dept</th>
														<th>Type</th>
														<th>Date</th>
														<th>Request</th>
														<th>Remarks</th>
												</thead>
												<tbody>
						              			<?php echo $data['Schedule']; ?>
						              			
						              			</tbody>
						              		</table>
									
						              </div>
						            </div>


						            <div id="tab-5" class="tab-pane row fade">              
						              <div class="col-md-12">
						              	
										
											<input type="Hidden" name="startDate" value="<?php echo $_GET['startDate'];?>">
											<input type="Hidden" name="endDate" value="<?php echo $_GET['endDate'];?>">
											<input type="Hidden" name="depdep" value="<?php echo $dip;?>">
											<table width="100%" style="font-family:Arial;font-size:12px;" class="table">
												<thead>
													<tr style='color:blue;font-weight:bold;'>
														<th>&nbsp;</th>
														<th>Created</th>
														<th>Approved Date</th>
														<th>ID</th>
														<th>Name</th>
														<th>Dept</th>
														<th>Type</th>
														<th>Date</th>
														<th>Request</th>
														<th>Remarks</th>
												</thead>
												<tbody>
						              			<?php echo $data['leave']; ?>
						              		
						              			</tbody>
						              		</table>
									
						              </div>
						            </div>
						          </div>

					        </div>
					    </div>


						

		
        <div class="container_12"><!--  PLACEHOLDER FOR FLOT - REMOVE IF NOT REQUIRED --></div>
        
        <div class="clearfix">&nbsp;</div>

        <!-- NOTIFICATION - USES CLASS OF "WARNING" and the CANHIDE ENABLES CICK TO FADE AWAY-->
        <div class="container_12">
           
<!--START NOTIFICATIONS  --><!-- INFORMATION - USES CLASS OF "IN2FORMATION" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- WARNING - USES CLASS OF "WARNING" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- SUCCESS - USES CLASS OF "SUCCESS" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- FAILURE - USES CLASS OF "FAILURE" and the CANHIDE ENABLES CICK TO FADE AWAY--></div>   
  	<!--END NOTIFICATIONS  -->
        
        
	<div class="clearfix">&nbsp;</div>
    
    
    
    
    </div>

<!-- START TABULAR DATA EXAMPLE -->
  <div class="container_12">
  
	<h2>&nbsp;</h2>

    <!-- END TABULAR DATA EXAMPLE -->

    <div class="clearfix">&nbsp;</div>
           
           
              
          
</div>

<div class="clearfix">&nbsp;</div>
<div class="container_12">
     


<?php include "footer.php";?>     
  </div><!-- end content wrap -->


</body>
</html>


<script src="kiosk1/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>

    <script src="kiosk1/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>      
  
    <script type="text/javascript">
    jQuery.noConflict();
        jQuery(document).ready(function() {
              //Metronic.init(); // init metronic core components

        });

    function approve_req(x){
    	jQuery.ajax({
    		method: "GET",
    		url: "tools_approveonlinehr_ajax.php?act=approve&id="+x
    	})
    	.done(function(data){
    		jQuery('#cbdiv'+x).html('Approve');
    		jQuery('#cdiv'+x).hide(1000,'swing');
    		//alert(data);
    	});
    }

    function back_req(x){
    	jQuery.ajax({
    		method: "GET",
    		url: "tools_approveonlinehr_ajax.php?act=back&id="+x
    	})
    	.done(function(data){
    		jQuery('#cbdiv'+x).html('Approve');
    		jQuery('#cdiv'+x).hide(1000,'swing');
    		//alert(data);
    	});
    }
    </script>