<?php
ob_start();
session_start();
set_time_limit ( 99666300 );
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
	include ("myfunctions.php");
	//echo substr('133.23',0,-3);	die();
if($_GET['act']=='process'){
	$e=mysql_query("select dtrid,datelog,count(*) as cnt from hrinterface where datelog>='".$_POST['startDate']."' and datelog<='".$_POST['endDate']."' group by dtrid,datelog");
		while($rs=mysql_fetch_object($e)){
				if($rs->cnt>=1){		
					$ee=mysql_fetch_object(mysql_query("select * from employee where biometricNo='".$rs->dtrid."' and isActive=1"));		
					$qr=mysql_query("select * from hrinterface where in_out=0 and datelog='".$rs->datelog."' and dtrid='".$rs->dtrid."' order by log limit 0,1");
					$ar=0;
					$check_duplicate_record=0;
					$total_break_Undertime=0;
					$late=0; // Late
					$undertime=0; // Undertime
					$overtime=0; // Overtime
					$nytdifferential=0;
					$holidays='';
					$hours_duty=0;
					$timeIn='';
					$timeOut='';
					$shiftId='';
					$breaklogs=array();
					while($s=mysql_fetch_object($qr)){
						//if($ee->ndex){
							$ar++;
							$er[$ar]=$s->hrint_id;
							$inout[$ar]=$s->in_out;
							$chkduble=checkIfDuplicateLog($s->hrint_id);
							if($chkduble==1){ // If nagduplicate and IN or OUT
								$check_duplicate_record=1;
							} 
							else{
								if($ee->ndex){
									$shift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$ee->ndex." and '".$s->datelog."' between startDate and endDate"));
								}
								if($shift->shiftingId){
									$y="SELECT * FROM `hrinterface` where in_out=1 and log > '".$s->log."' and dtrid=".$s->dtrid." ORDER BY log limit 0,1";
									$nxtday_timeIn=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$s->dtrid."' and datelog>'".$s->datelog."' and in_out=0 ORDER BY log LIMIT 0,1"));
									if(!$nxtday_timeIn->hrint_id){
										$out=mysql_fetch_object(mysql_query("SELECT * FROM `hrinterface` where in_out=1 and log > '".$s->log."' and dtrid='".$s->dtrid."' ORDER BY log DESC limit 0,1")); // Get OUT record
									}
									else{
										$out=mysql_fetch_object(mysql_query("SELECT * FROM `hrinterface` where in_out=1 and log > '".$s->log."' and log<'".$nxtday_timeIn->log."' and dtrid='".$s->dtrid."' ORDER BY log DESC limit 0,1")); // Get OUT record
									}
									if($out->log){
										$shiftId=$shift->shiftingId;
										$timeIn=$s->log;
										$timeOut=$out->log;
										$check_duplicate_record=0;
									}
									else{
										$check_duplicate_record=2; // No Out
									}
								}
								else{
									$daylogs=date('w',strtotime($s->datelog));
									$chkrestday=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId='".$ee->ndex."' and '".$s->datelog."' between startDate and endDate"));
									if($chkrestday>restday!=$daylogs){
										$check_duplicate_record=3; // No Shifting 
									}
									else{
										$check_duplicate_record=100; // duty restday
									}
								}
							}
							if($check_duplicate_record==0){ // if no error found
								$hours_duty=dailyTimeTotal($timeIn,$timeOut,$shiftId);
								$total_break_Undertime=getBreakUndertime($timeIn,$timeOut,$shift->shiftingId,$ee->ndex,$s->dtrid);
								$late=lateTotalinMinutes($timeIn,$shift->shiftingId); // Late
								$undertime=undertimeTotalinMinutes($timeOut,$shiftId) + $total_break_Undertime; // Undertime
								$overtime=overtimeTotalinMinutes($timeOut,$shiftId); // Overtime
								$nytdifferential=nightDifferential($timeIn,$timeOut,$shiftId);
								$holidays=getHoliday($s->datelog);
							}
							else{
								$hours_duty=0;
								$total_break_time=0;
								$late=0; // Late
								$undertime=0; // Undertime
								$overtime=0; // Overtime
								$nytdifferential=0;
								$holidays="";
							}
						//}
					}
						if($hours_duty>0){$daysWork=1;}else{$daysWork=0;}
						$chkifexist=mysql_num_rows(mysql_query("select * from dailytimesummary where employeeId='".$ee->ndex."' and date='".$rs->datelog."'"));
						if($chkifexist>=1){
							if($check_duplicate_record==100){
								$insert=mysql_query("update `dailytimesummary` set `isError`=".$check_duplicate_record.",`undertime`=".$undertime.",`overtime`='".number_format($overtime,2)."',`minutesLate`=".$late.",`night_prem`=".$nytdifferential.",`holiday`='".$holidays."',`days_work`='".$daysWork."' where `employeeId`='".$ee->ndex."' and `date`='".$rs->datelog."'");
							}
							else{
								$insert=mysql_query("update `dailytimesummary` set `hoursDuty`=".$hours_duty.",`isError`=".$check_duplicate_record.",`undertime`=".$undertime.",`overtime`='".number_format($overtime,2)."',`minutesLate`=".$late.",`night_prem`=".$nytdifferential.",`holiday`='".$holidays."',`days_work`='".$daysWork."' where `employeeId`='".$ee->ndex."' and `date`='".$rs->datelog."'");
							}
						}
						else{
								$insert=mysql_query("insert into dailytimesummary
									    (`employeeId`, `date`, `days_absent`, `undertime`, `ot_reg`, `ot_exc`, `days_work`, `spholiday`, `lholiday`, `duty_rd`, `vac_lve`, `man_lve`, `sick_lve`, `night_prem`, `isError`, `hoursDuty`,`minutesLate`,`overtime`,holiday)
									 VALUES
									    ('".$ee->ndex."','".$rs->datelog."',0,".$undertime.",0,0,".$daysWork.",0,0,0,0,0,0,".$nytdifferential.",".$check_duplicate_record.",".$hours_duty.",".$late.",".$overtime.",'".$holiday."')");
						
						}
						if($check_duplicate_record==0){
								$changeisProcess=mysql_query("update hrinterface set isProcessed=1 where dtrid='".$rs->dtrid."' and datelog='".$rs->datelog."'");
						}
					
			}
		}
		//die();
		$abs=0;
		$start = strtotime($_POST['startDate']);
		$end = strtotime($_POST['endDate']);
		for ( $i = $start; $i <= $end; $i += 86400 ){
			$datelog=date('Y-m-d',$i);
			$employee_qry=mysql_query("select * from employee");  ///// on testing change ndex
			while($em=mysql_fetch_object($employee_qry)){
				$employeeId=$em->ndex;
				$daylog=date('w',strtotime($datelog));
				$restday=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$employeeId." and '".$datelog."' between startDate and endDate"));
				//birthday
				if(date('m-d',strtotime($em->birthDate))==date('m-d',strtotime($datelog))){
					$isbday=1;
				}
				else{
					$isbday=0;
				}
				//
				// Start Holiday
					$absentholiday=getHoliday($datelog);
					$yesterday=date('Y-m-d',strtotime('-1 days',strtotime(date($datelog))));
					$ckiflogexist=mysql_num_rows(mysql_query("SELECT * FROM `hrinterface` where in_out=0 and datelog = '".$datelog."' and dtrid='".$em->biometricNo."'"));
					if($absentholiday=='L'){
						if($ckiflogexist==0){
							$yesterday=date('Y-m-d',strtotime('-1 days',strtotime(date($datelog))));
							$daybeforeholiday=checkDayIfAbsent($yesterday,$employeeId);
							if($daybeforeholiday!=0){  $holidayrecord='L';	}
							else{  $holidayrecord='';	}
						}
						else{
							$holidayrecord='L';
						}
					}
					elseif($absentholiday=='S'){
						if($ckiflogexist==0){
							$daybeforeholiday=checkDayIfAbsent($yesterday,$employeeId);
							if($daybeforeholiday!=0){ $holidayrecord='S';	}
							else{ $holidayrecord=''; }
						}
						else{
							$holidayrecord='S';
						}
					}
					else{
						$holidayrecord='';
					}
					// End Holiday
				$restdayarr=explode(',',$restday->restday);
				if(in_array($daylog,$restdayarr)){
					//echo "ss";
					$r=mysql_fetch_object(mysql_query("select * from dailytimesummary where date='".$datelog."' and employeeId=".$employeeId.""));
					if(!$r->ndex){
						$updaterestday=mysql_query("INSERT INTO  `dailytimesummary` (`employeeId` ,`date` ,`days_absent` ,`undertime` ,`ot_reg` ,`ot_exc` ,`days_work` ,`spholiday` ,`lholiday` ,`duty_rd` ,`vac_lve` ,`man_lve` ,`sick_lve` ,`night_prem` ,`isError` ,`hoursDuty` ,`minutesLate` ,`overtime` ,`holiday`,`leaveId`,`isDayOff`,`isBirthday`)
							VALUES ('".$employeeId."',  '".$datelog."',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '',  '',  '',  '',   '".$holidayrecord."', '0','1','".$isbday."')");
					}
					else{
						$updaterestday=mysql_query("update dailytimesummary set days_absent='0.00',isDayOff=1,holiday='".$holidayrecord."',leaveId=0,isBirthday='".$isbday."' where ndex=".$r->ndex."");
					}
				}	
				else{
					$leave=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId=".$employeeId." and '".$datelog."' between startDate and endDate"));
					if(!$leave->ndex){
						$leaveId=0;
					}
					else{
						$leaveId=$leave->leaveId;
					}
					$r=mysql_fetch_object(mysql_query("select * from dailytimesummary where date='".$datelog."' and employeeId=".$employeeId.""));
					//echo "select * from dailytimesummary where date='".$datelog."' and employeeId=".$employeeId."<br><br>";
					if(!$r->ndex){
						$abs++;
						if($leaveId==0){
							$insert=mysql_query("INSERT INTO  `dailytimesummary` (`employeeId` ,`date` ,`days_absent` ,`undertime` ,`ot_reg` ,`ot_exc` ,`days_work` ,`spholiday` ,`lholiday` ,`duty_rd` ,`vac_lve` ,`man_lve` ,`sick_lve` ,`night_prem` ,`isError` ,`hoursDuty` ,`minutesLate` ,`overtime` ,`holiday`,`leaveId`,`isBirthday`)
							VALUES ('".$employeeId."',  '".$datelog."',  '1.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '',  '',  '',  '',   '".$holidayrecord."', '".$leaveId."','".$isbday."')");
						}
						else{
							$insert=mysql_query("INSERT INTO  `dailytimesummary` (`employeeId` ,`date` ,`days_absent` ,`undertime` ,`ot_reg` ,`ot_exc` ,`days_work` ,`spholiday` ,`lholiday` ,`duty_rd` ,`vac_lve` ,`man_lve` ,`sick_lve` ,`night_prem` ,`isError` ,`hoursDuty` ,`minutesLate` ,`overtime` ,`holiday`,`leaveId`,`isBirthday`)
							VALUES ('".$employeeId."',  '".$datelog."',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '0.00',  '',  '',  '',  '',   '".$holidayrecord."', '".$leaveId."','".$isbday."')");
						}
					}
					
					else{
						if($r->hoursDuty=='0.00' && $r->isError==0 && $leaveId==0 && $holidayrecord=='' && $isbday==0){
							$abs++;					
								$update=mysql_query("update dailytimesummary set days_absent='1.00',isError=0,isDayOff=0,isBirthday=0 where ndex=".$r->ndex."");
						}
						else {
							if($leaveId==5 || $leaveId==13){
								$update=mysql_query("update dailytimesummary set days_absent='1.00',isError=0,isDayOff=0,isBirthday=".$isbday." where ndex=".$r->ndex."");
							}
							else{
								$update=mysql_query("update dailytimesummary set days_absent='0.00',isDayOff=0,isBirthday=".$isbday." where ndex=".$r->ndex."");
							}
						}
						if($leaveId>=1){
							$updateleave=mysql_query("update dailytimesummary set `isError`=0,
										`hoursDuty`=0,`isError`=0,`undertime`=0,`overtime`=0,`minutesLate`=0,`night_prem`=0,`holiday`=0,`days_work`=0,isBirthday=".$isbday."
										 where ndex=".$r->ndex."");
						}
						$update_leave=mysql_query("update dailytimesummary set leaveId='".$leaveId."',holiday='".$holidayrecord."',isDayOff=0,isBirthday=".$isbday." where ndex=".$r->ndex."");
					}
				}
			}
		}
		$msg="Successfully process timelogs from ".$_POST['startDate']." to ".$_POST['endDate']."<br>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
	<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
</head>

<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Process Time Logs</h2>
	<font color="#ff0000" style="font-weight:bold;"><?php echo $msg; ?></font>    
    <div class="clearfix">
<form action="tools_processtimelogs2.php?act=process" name="tlfrm" method="post">
	<table>
		<tr>
			<td>Start Date:</td><td><input type="Text" name="startDate" id="startDate" size="15"><a href="javascript:show_calendar('tlfrm.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
			<td>End Date:&nbsp;&nbsp;</td><td><input type="Text" name="endDate" id="endDate" size="15"><a href="javascript:show_calendar('tlfrm.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="Submit" value="Process"></td>
		</tr>
	</table>
</form>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>
