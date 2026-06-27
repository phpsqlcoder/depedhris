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
$dd=mysql_Fetch_object(mysql_query("select * from dept where ndex in (".$_SESSION['deptId'].")"));
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
if($_GET['act']=='aprob'){
	$aqry="SELECT k.*,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.ndex as empid,e.employmentStatus,e.employeeNo from kiosk_request k left join employee e on e.ndex=k.empid left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId where k.date>='".$_GET['startDate']."' and k.date<='".$_GET['endDate']."' and k.tayp='".$_GET['type']."'";
	//echo $aqry;
	$aexec=mysql_query($aqry);
	$var=0;
	while($ar=mysql_fetch_array($aexec)){
		$jj=$ar['ndex'];
	//	echo $jj."<br>";
		if(isset($_POST['ck'.$jj]) && $_POST['ck'.$jj]=="on"){
			//echo "update kiosk_request set approve1=1 where ndex='".$jj."'";
			$upd=mysql_query("update kiosk_request set approve1=1 where ndex='".$jj."'");
			date_default_timezone_set("Asia/Manila");
			$logs = mysql_query("insert into kiosk_request_logs (action,timelog,request_id,user) VALUES ('Approve Request','".date('Y-m-d H:i:s')."','".$jj."','".$_SESSION['nym']."')");
		}
	}
}

if($_GET['startDate']){
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
		$qry="SELECT k.*,e.biometricNo,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.ndex as empid,e.employmentStatus,e.employeeNo from kiosk_request k left join employee e on e.ndex=k.empid left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
		if (in_array("73", $dar)){
			if(count($dar)>2){
				$addqry=" and (e.approvingOfficer=".$_SESSION['ndex']." OR e.deptId in (".removear($dar)."))";
			}
			else{
				$addqry=" and (e.approvingOfficer=".$_SESSION['ndex']." OR e.authorizer=".$_SESSION['ndex'].")";
			}
			$qry.=" WHERE e.isActive=1 ".$adqry." ".$addqry."";
		}
		else{
			$qry.=" WHERE e.isActive=1 ".$adqry." and (d.ndex in (".$_SESSION['deptId'].") OR e.authorizer=".$_SESSION['ndex'].") and e.approvingOfficer='0'";
		}
		$qry.=" and k.tayp='".$type."' and k.date>='".$_GET['startDate']."' and k.date<='".$_GET['endDate']."'";
		$qry.=" ORDER BY k.approve1,d.ndex,e.lastName,e.firstName";

		$exec=mysql_query($qry);
		$var=0;
		while($r=mysql_fetch_object($exec)){
			$var++;
			$is_cb = ($r->approve1 == '1' ? "Approved" : "<input type='checkbox' name='ck".$r->ndex."'>");

			$addr = '';
			$link="";
			if($type=='Overtime'){
				$link="overtime_edit.php";
				$inlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$r->date."' and in_out=0 ORDER BY log LIMIT 0,1"));
    			$outlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$r->date."' and in_out=1 ORDER BY log LIMIT 0,1"));
    			$exp = explode("|",$r->request);
    			$r->request = "
    				Overtime = ".$exp[0]." hrs<br>
    				Night Premium = ".$exp[1]." hrs<br>
    				Time In = ".date('H:i:s',strtotime($inlog->log))." <br>
    				Time Out = ".date('H:i:s',strtotime($outlog->log))." <br>
    			";
    			if($r->approve1 == '0') { $count_overtime++; }
			}
			if($type=='drd'){
				$link="drd_edit.php";
				$inlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$r->date."' and in_out=0 ORDER BY log LIMIT 0,1"));
    			$outlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$r->date."' and in_out=1 ORDER BY log LIMIT 0,1"));
    			$exp = explode("|",$r->request);
    			$r->request =
    			"Hours = ".$exp[0]." Hr/s<br>
    			Overtime = ".$exp[1]." Hr/s<br>
    			Night Premium = ".$exp[2]." Hr/s<br>
    			Time In = ".date('H:i:s',strtotime($inlog->log))." <br>
    			Time Out = ".date('H:i:s',strtotime($outlog->log))." <br>
    			";
    			if($r->approve1 == '0') { $count_restday++; }
			}
			if($type=='log'){
				$link="log_edit.php";
				$exp = explode("|",$r->request);
    			$r->request =
    			"Type = Time ".strtoupper($exp[0])."<br>
    			Time = ".$exp[1].":".$exp[2]." ".$exp[3]." <br>
    			
    			";
    			if($r->approve1 == '0') { $count_log++; }
			}
			if($type=='Schedule'){
				$link="sched_edit.php";
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

					$shift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$r->empid." and '".$r->date."' between startDate and endDate"));
					$n = $s->tymIn." - ".$s->brekOut." - ".$s->brekIn." - ".$s->tymOut;
				}
				
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
    			if($r->approve1 == '0') { $count_schedule++; }
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
    			if($r->approve1 == '0') { $count_leave++; }
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
			  $hist=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$r->ndex."' order by ndex desc limit 1"));
			$data[$type].="<tr style='color:black;'>
			<td>".$is_cb."</td>
			<td>".$date_created."</td>
			<td>".$date_approved."</td>
			
			<td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
		
	
			<td>".$r->date."</td>
			<td>".$r->request."</td>
			<td>".$r->remarks."</td>
			<td>".$hist['remarks']."</td>
			<td>
			<a href='#'	class='btn btn-xs red' onclick=\"window.open('online_apps/".$link."?id=".$r->ndex."','displayWindow','toolbar=no,scrollbars=yes,width=1200,height=500')\";> Edit</a></td>
			";

			date_default_timezone_set("Asia/Manila");

			$data[$type].="</tr>";
		}
	}
}
$dept=mysql_query("SELECT * FROM dept WHERE status<>1 and ndex in (".$_SESSION['deptId'].") order by name");
while($rsdept=mysql_fetch_object($dept)){
	$optiondepts.="<option value='".$rsdept->ndex."'>".$rsdept->name."";
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
 

  <!-- Theme styles START -->
  <link href="kiosk1/assets/global/css/components.css" rel="stylesheet">
  <link href="kiosk1/assets/frontend/layout/css/style.css" rel="stylesheet">
  <link href="kiosk1/assets/frontend/layout/css/style-responsive.css" rel="stylesheet">
  <link href="kiosk1/assets/frontend/layout/css/themes/red.css" rel="stylesheet" id="style-color">
</head>

<body>
	<?php include "headerperdept.php";?>
	<div id="main_content_wrap" class="container_12" style="width:90%;">

		<div id="rcont">
			<h2><strong>Approve Applications</strong><small class="pull-right"><span class="badge badge-danger">1</span>&nbsp;= Unapprove Applications</small></h2>
			<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
			<?php include "calendar.inc"; ?>
			<?php

			?>
			<?php
			if(!$_GET['startDate']){
				?>
				<form action="tools_approveapplications.php" method="get" name="sadsa">
					<table width="100%" style="font-family:Arial;font-size:12px;">

						<tr>
							<td colspan="5">
								<table><tr>
									<td>Start Date:<input type="Text" name="startDate" id="startDate" size="15" value="<?php echo $_GET['startDate'];?>"><a href="javascript:show_calendar('sadsa.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
									<td>End Date:<input type="Text" name="endDate" id="endDate" size="15" value="<?php echo $_GET['endDate'];?>"><a href="javascript:show_calendar('sadsa.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
									<td>Select Dept: <select name="depsel" style="width:150px;"><option value="all">-All Assigned Dept-<?php echo $optiondepts;?></select></td>
										<td><input type="Submit" value="View"></td>
									</tr></table>
								</td>
							</tr>
							<tr><td>&nbsp;</td></tr>
						</table>
					</form>
					<?php } else {?>
					<?php
					if($_GET['eksels']=='on'){
						$filename =$dd->name."schedule.xls";
						header('Content-type: application/ms-excel');
						header('Content-Disposition: attachment; filename='.$filename);
					}
					?>
					
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
						              	<form name="frmaproveshfting" action="tools_approveapplications.php?type=Overtime&act=aprob&startDate=<?php echo $_GET['startDate'];?>&endDate=<?php echo $_GET['endDate'];?>&depsel=<?php echo $_GET['depsel'];?>" method="post">
											<input type="Hidden" name="act" value="aprob">
											<input type="Hidden" name="startDate" value="<?php echo $_GET['startDate'];?>">
											<input type="Hidden" name="endDate" value="<?php echo $_GET['endDate'];?>">
											<input type="Hidden" name="depdep" value="<?php echo $dip;?>">
											<table width="100%" style="font-family:Arial;font-size:12px;" class="table table-striped">
												<thead>
													<tr style='color:blue;font-weight:bold;'>
														<th>&nbsp;</th>
														<th>Created</th>
														<th>Approved Date</th>
														
														<th>Name</th>
													
														<th>Date</th>
														<th width="200">Request</th>
														<th>Reason</th><th>Comment</th>
												</thead>
												<tbody>
						              			<?php echo $data['Overtime']; ?>
						              			<tr><td><input type="Submit" value="Approve Selected"></td></tr>
						              			</tbody>
						              		</table>
										</form>
						              </div>
						            </div>


						            <div id="tab-2" class="tab-pane row fade">              
						              <div class="col-md-12">
						              	<form name="frmaproveshfting" action="tools_approveapplications.php?type=drd&act=aprob&startDate=<?php echo $_GET['startDate'];?>&endDate=<?php echo $_GET['endDate'];?>&depsel=<?php echo $_GET['depsel'];?>" method="post">
											<input type="Hidden" name="act" value="aprob">
											<input type="Hidden" name="startDate" value="<?php echo $_GET['startDate'];?>">
											<input type="Hidden" name="endDate" value="<?php echo $_GET['endDate'];?>">
											<input type="Hidden" name="depdep" value="<?php echo $dip;?>">
											<table width="100%" style="font-family:Arial;font-size:12px;" class="table">
												<thead>
													<tr style='color:blue;font-weight:bold;'>
														<th>&nbsp;</th>
														<th>Created</th>
														<th>Approved Date</th>
													
														<th>Name</th>
												
														<th>Date</th>
														<th>Request</th>
														<th>Reason</th><th>Comment</th>
												</thead>
												<tbody>
						              			<?php echo $data['drd']; ?>
						              			<tr><td><input type="Submit" value="Approve Selected"></td></tr>
						              			</tbody>
						              		</table>
										</form>
						              	
						              </div>
						            </div>


						            <div id="tab-3" class="tab-pane row fade">              
						              <div class="col-md-12">

						              	<form name="frmaproveshfting" action="tools_approveapplications.php?type=log&act=aprob&startDate=<?php echo $_GET['startDate'];?>&endDate=<?php echo $_GET['endDate'];?>&depsel=<?php echo $_GET['depsel'];?>" method="post">
											<input type="Hidden" name="act" value="aprob">
											<input type="Hidden" name="startDate" value="<?php echo $_GET['startDate'];?>">
											<input type="Hidden" name="endDate" value="<?php echo $_GET['endDate'];?>">
											<input type="Hidden" name="depdep" value="<?php echo $dip;?>">
											<table width="100%" style="font-family:Arial;font-size:12px;" class="table">
												<thead>
													<tr style='color:blue;font-weight:bold;'>
														<th>&nbsp;</th>
														<th>Created</th>
														<th>Approved Date</th>
														
														<th>Name</th>
														
														<th>Date</th>
														<th>Request</th>
														<th>Reason</th><th>Comment</th>
												</thead>
												<tbody>
						              			<?php echo $data['log']; ?>
						              			<tr><td><input type="Submit" value="Approve Selected"></td></tr>
						              			</tbody>
						              		</table>
										</form>

						              </div>
						            </div>


						            <div id="tab-4" class="tab-pane row fade">              
						              <div class="col-md-12">
						              	<form name="frmaproveshfting" action="tools_approveapplications.php?type=Schedule&act=aprob&startDate=<?php echo $_GET['startDate'];?>&endDate=<?php echo $_GET['endDate'];?>&depsel=<?php echo $_GET['depsel'];?>" method="post">
											<input type="Hidden" name="act" value="aprob">
											<input type="Hidden" name="startDate" value="<?php echo $_GET['startDate'];?>">
											<input type="Hidden" name="endDate" value="<?php echo $_GET['endDate'];?>">
											<input type="Hidden" name="depdep" value="<?php echo $dip;?>">
											<table width="100%" style="font-family:Arial;font-size:12px;" class="table">
												<thead>
													<tr style='color:blue;font-weight:bold;'>
														<th>&nbsp;</th>
														<th>Created</th>
														<th>Approved Date</th>
													
														<th>Name</th>
														<th>Date</th>
														
														<th>Request</th>
														<th>Reason</th><th>Comment</th>
												</thead>
												<tbody>
						              			<?php echo $data['Schedule']; ?>
						              			<tr><td><input type="Submit" value="Approve Selected"></td></tr>
						              			</tbody>
						              		</table>
										</form>
						              </div>
						            </div>


						            <div id="tab-5" class="tab-pane row fade">              
						              <div class="col-md-12">
						              	<form name="frmaproveshfting" action="tools_approveapplications.php?type=leave&act=aprob&startDate=<?php echo $_GET['startDate'];?>&endDate=<?php echo $_GET['endDate'];?>&depsel=<?php echo $_GET['depsel'];?>" method="post">
											<input type="Hidden" name="act" value="aprob">
											<input type="Hidden" name="startDate" value="<?php echo $_GET['startDate'];?>">
											<input type="Hidden" name="endDate" value="<?php echo $_GET['endDate'];?>">
											<input type="Hidden" name="depdep" value="<?php echo $dip;?>">
											<table width="100%" style="font-family:Arial;font-size:12px;" class="table">
												<thead>
													<tr style='color:blue;font-weight:bold;'>
														<th>&nbsp;</th>
														<th>Created</th>
														<th>Approved Date</th>
														
														<th>Name</th>
														
														<th>Date</th>
														<th>Request</th>
														<th>Reason</th><th>Comment</th>
												</thead>
												<tbody>
						              			<?php echo $data['leave']; ?>
						              			<tr><td><input type="Submit" value="Approve Selected"></td></tr>
						              			</tbody>
						              		</table>
										</form>
						              </div>
						            </div>
						          </div>

					        </div>
					    </div>


						<?php } ?>


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
    </script>