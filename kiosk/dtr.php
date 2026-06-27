<?php
ob_start();
session_start();
if(!$_SESSION['ndex']){header("location:login.php");}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../employeefunctions.php");
include ("../myfunctions.php");
/*$yr=date('Y-m');
if($_POST['startDate']){
	$start=$_POST['startDate'];
	$end=$_POST['endDate'];
}
else{
	$start=$yr."-1";
	$nxtmonth=date('Y-m',strtotime('1 month',strtotime(date('Y-m-d'))));
	$end=date('Y-m-d',strtotime('-1 days',strtotime(date($nxtmonth."-1"))));
}
//echo $start;
$arr=0;
$emp=mysql_fetch_object(mysql_query("select * from employee where ndex='".$_SESSION['ndex']."'"));
$e=mysql_query("select datelog,count(*) as cnt from hrinterface where datelog>='".$start."' and datelog<='".$end."' and dtrid=".$emp->biometricNo." group by datelog");
	while($rs=mysql_fetch_object($e)){
	$arr++;
	$num[$arr]=$rs->cnt;
	$check_duplicate_record=0;
	//echo $num[$arr - 1]."<br>";
	if($arr>=2){if($rs->cnt>=$num[$arr - 1]){$top=$rs->cnt;}else{$top=$num[$arr - 1];}}else{$top=$rs->cnt;}
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	$data.="<tr style='background-color:".$bgclr1s.";'><td>".$rs->datelog."</td>";
	$qr=mysql_query("select * from hrinterface where datelog='".$rs->datelog."' and dtrid='".$emp->biometricNo."' order by hrint_id");
		while($s=mysql_fetch_object($qr)){
			$ar++;
			$inout[$ar]=$s->in_out;
			if($inout[$ar]==$inout[$ar - 1]){$check_duplicate_record=1;} // If nagduplicate and IN or OUT
			else{
				if($s->in_out==0){ // Select IN records only
					if($emp->ndex){
						$shift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$emp->ndex." and '".$s->datelog."' between startDate and endDate"));
					}
					if($shift->shiftingId){
						$out=mysql_fetch_object(mysql_query("SELECT * FROM `hrinterface` where in_out=1 and hrint_id> ".$s->hrint_id." and dtrid=".$s->dtrid." limit 0,1")); // Get OUT record
						if($out->log){
							$hours_duty=dailyTimeTotal($s->log,$out->log,$shift->shiftingId);
							$late=lateTotalinMinutes($s->log,$shift->shiftingId); // Late
							$undertime=undertimeTotalinMinutes($out->log,$shift->shiftingId); // Undertime
							$overtime=overtimeTotalinMinutes($out->log,$shift->shiftingId); // Overtime
						}
						else{
							$check_duplicate_record=2; // No Out
						}
					}
					else{
						$check_duplicate_record=3; // No Shifting 
					}
				}
			}
			if($s->in_out==1){$clr='blue';}else{$clr='black';}
			$data.="
						<td style='color:".$clr.";'>".substr($s->log,11)."</td>
				   ";
		}
		if($check_duplicate_record==1){$rem="Duplicate Logs";}
		elseif($check_duplicate_record==2){$rem="No Log Out";}
		elseif($check_duplicate_record==3){$rem="No Shifting";}
		else{$rem="";}
		$f=$top-$rs->cnt;
		if($f!=0){
			for($h=$rs->cnt+1;$h<=$top;$h++){
				$data.="<td>&nbsp;</td>";
			}
		}
		$data.="
				<td>".$rem."</td>";
		if($check_duplicate_record!=1 && $check_duplicate_record!=2 && $check_duplicate_record!=3){
		$data.="<td>".number_format($hours_duty,2)."</td>
				<td>".number_format($late,2)."</td>
				<td>".number_format($undertime,2)."</td>
				<td>".number_format($overtime,2)."</td>";
		}
		$data.="</tr>";
}
$hdr.="<tr style='color:maroon;font-weight:bold;'><td>Date</td>";
for($j=1;$j<=$top;$j++){$hdr.="<td>&nbsp;</td>";}
$hdr.="<td>Remarks</td>
	   <td>Total<br>Hours</td>
	   <td>Late<br>(min)</td>
	   <td>Undertime<br>(min)</td>
	   <td>Overtime<br>(min)</td>

</tr>";
*/
$_GET['id']=$_SESSION['ndex'];
$coqry=mysql_query("select * from cutoffdates order by payrollDate DESC");
while($co=mysql_fetch_object($coqry)){
	$coopt.="<option value='".$co->ndex."'>".$co->payrollDate."";
}
if($_POST['mant']){
	$getco=mysql_fetch_object(mysql_query("select * from cutoffdates where ndex=".$_POST['mant'].""));
	$startdate=$getco->cutoffDateStart;
	$enddate=$getco->cutoffDateEnd;
	$defco=$getco->ndex;
	$defopt="<option value='".$getco->ndex."' selected='selected'>".$getco->payrollDate."";
	/*$startdate=date('Y')."-".$_POST['mant']."-01";
	$enddate=date('Y-m-d', strtotime('-1 second',strtotime('+1 month',strtotime($startdate))));*/
}
else{
	if($_GET['mants']){
		$getco=mysql_fetch_object(mysql_query("select * from cutoffdates where ndex=".$_GET['mants'].""));
		$startdate=$getco->cutoffDateStart;
		$enddate=$getco->cutoffDateEnd;
		$defco=$getco->ndex;
		$defopt="<option value='".$getco->ndex."' selected='selected'>".$getco->payrollDate."";
		/*$startdate=$_GET['mants'];
		$enddate=date('Y-m-d', strtotime('-1 second',strtotime('+1 month',strtotime($startdate))));*/
	}
	else{
		$getco=mysql_fetch_object(mysql_query("select * from cutoffdates order by payrollDate DESC"));
		$startdate=$getco->cutoffDateStart;
		$enddate=$getco->cutoffDateEnd;
		$defco=$getco->ndex;
		$defopt="<option value='".$getco->ndex."' selected='selected'>".$getco->payrollDate."";
		/*$startdate=date('Y-m'."-1");
		$enddate=date('Y-m-d', strtotime('-1 second',strtotime('+1 month',strtotime($startdate))));*/
	}
}
date_default_timezone_set("Asia/Manila");
$start = strtotime($startdate);
$end = strtotime($enddate);
$timeLogs="";
$emp=mysql_fetch_object(mysql_query("select * from employee where ndex='".$_GET['id']."'"));
for ( $i = $start; $i <= $end; $i += 86400 ){
	$datelog=date('Y-m-d',$i);
	$dayvar=date('d',strtotime($datelog));
	####################################################### Start Updates ##############################################
	if($_GET['act']=='ot'){	
		// start update action
		$ees2=mysql_query("select * from hrinterface where dtrid='".$emp->biometricNo."' and datelog>='".$startdate."' and datelog<='".$enddate."'");
		while($ls2=mysql_fetch_object($ees2)){
			
			if($_POST['cb'.$ls2->hrint_id]=='on'){
				$insert_to_history=mysql_query("insert into `hrinterface_deleted` (`dtrid`, `datelog`, `log`, `in_out`, `isProcessed`, `user`, `dateDeleted`)
				VALUES ('".$ls2->dtrid."','".$ls2->datelog."','".$ls2->log."','".$ls2->in_out."','".$ls2->isProcessed."','".$_SESSION['fullName']."','".date('Y-m-d H:i:s')."')
				");
				$deletelogs=mysql_query("delete from hrinterface where hrint_id='".$ls2->hrint_id."'");
			}
			else{
				//echo $ls2->hrint_id." ______ ".$_POST['sel'.$ls2->hrint_id]."<br>";
				$updatelogs=mysql_query("update hrinterface set log='".$ls2->datelog." ".$_POST['inp'.$ls2->hrint_id]."',in_out='".$_POST['sel'.$ls2->hrint_id]."' WHERE hrint_id='".$ls2->hrint_id."'");
				/*if($ls2->hrint_id=='369078'){
					echo "update hrinterface set log='".$ls2->datelog." ".$_POST['inp'.$ls2->hrint_id]."',in_out='".$_POST['sel'.$ls2->hrint_id]."' WHERE hrint_id='".$ls2->hrint_id."'";
				}*/
			}
		}
		
		//update ot
		//$apot=mysql_query("update dailytimesummary set approvedOvertime='".$_POST['aot'.$datelog]."',approvedOvertimeNightPremium='".$_POST['anpot'.$datelog]."' where employeeId='".$_GET['id']."' and date='".$datelog."'");
		//reprocess_timelogs($_GET['id'],$datelog);
		
		
			//header("location:employeelogs.php?mants=".$_GET['mants']."&id=".$_GET['id']."");
	}
	// end ot
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	$holiday=mysql_fetch_object(mysql_query("select * from holiday where `date`='".$datelog."'"));
	if($holiday->ndex){
		if($holiday->isSpecial==0){$hol='LEGAL HOLIDAY';}else{$hol='SPECIAL HOLIDAY';}
	}
	else{
		$hol='';
	}
	$log=mysql_fetch_object(mysql_query("select * from dailytimesummary where employeeId='".$_GET['id']."' and date='".$datelog."'"));
	$shiftsked=mysql_fetch_object(mysql_query("select e.*,s.name as shft from employee_shifting e left join shifting s on s.ndex=e.shiftingId where e.employeeId=".$_GET['id']." and e.approvedDate<>'0000-00-00 00:00:00' and '".$datelog."' between e.startDate and e.endDate"));
	$nxtday_timeIn=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$emp->biometricNo."' and datelog>'".$datelog."' and in_out=0 ORDER BY log LIMIT 0,1"));
	if(!$nxtday_timeIn->hrint_id){
		$tlqry=mysql_query("select * from hrinterface where dtrid='".$emp->biometricNo."' and datelog>='".$datelog."' ORDER BY log");
	}
	else{
		$tlqry=mysql_query("select * from hrinterface where dtrid='".$emp->biometricNo."' and datelog>='".$datelog."' and log<'".$nxtday_timeIn->log."' ORDER BY log");
	}
	$timeLogs="";
	$var=0;
	$outtym="";
	$hrinterfaceid="0,";
	while($tl=mysql_fetch_object($tlqry)){
		$var++;
		
	//	$timeLogs="";
		if($tl->in_out==1){$clr='blue;font-weight:bold;';}else{$clr='black:font-weight:normal;';}
		if($var==1 && $tl->in_out==1){
			$timeLogs="";
		}
		else{
			$hrinterfaceid.=$tl->hrint_id.",";
			$timeLogs.="<font  style='color:".$clr."'>".substr($tl->log,11)."</font>&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		$outtym=$tl->log;
	}
	$hrinterfaceid=rtrim($hrinterfaceid,",");
	if($log->isBirthday==1){
			$bd="<strong style='color:maroon;'><img src='images/candle.png' size='20' height='20'>&nbsp;BIRTHDAY&nbsp;<img src='images/candle.png' size='20' height='20'></strong>";
		}
		else{
			$bd="";
		}
	$overtym=overtimeTotalinMinutes($outtym,$shiftsked->shiftingId)/60;
	if($log->leaveId>0){
		$leave=mysql_fetch_object(mysql_query("select * from `leave` where ndex='".$log->leaveId."'"));
		$data.="<tr style='background-color:".$bgclr1s.";' onclick=\"Effect.toggle('".$datelog."', 'blind', { duration: 1.0 });\">
					<td>".$datelog."(".date('D',strtotime($datelog)).")</td>
					<td align='left'>".$leave->name." &nbsp;&nbsp;&nbsp;".$hol."</td>
					<td>".$timeLogs." ".$bd."</td>
		</tr>";
	}
	if($log->days_absent>0 && $log->leaveId!=5 && $log->leaveId!=13 && $log->isError!=3){ 
		$data.="<tr style='background-color:".$bgclr1s.";' onclick=\"Effect.toggle('".$datelog."', 'blind', { duration: 1.0 });\">
					<td>".$datelog."(".date('D',strtotime($datelog)).")</td>
					<td>".$shiftsked->shft."&nbsp;&nbsp;&nbsp;".$hol."</td>
					<td>".$timeLogs." ".$bd."</td>
					<td colspan='5' align='center' style='color:maroon;font-weight:bold;'>ABSENT</td>
		</tr>";
	}
	if($log->isDayOff>0){ 
		$data.="<tr style='background-color:".$bgclr1s.";' onclick=\"Effect.toggle('".$datelog."', 'blind', { duration: 1.0 });\">
					<td>".$datelog."(".date('D',strtotime($datelog)).")</td>
					<td>".$shiftsked->shft."&nbsp;&nbsp;&nbsp;".$hol."</td>
					<td>".$timeLogs." ".$bd."</td>
					<td colspan='5' align='center' style='color:maroon;font-weight:bold;'>OFF</td>
		</tr>";
	}
	//<td colspan='5' align='center'>ABSENT</td>
	if($log->isError>0 && $log->isDayOff==0){
		if($log->isError==1){$rem="Duplicate Logs";}
		elseif($log->isError==2){$rem="No Log Out";}
		elseif($log->isError==3){$rem="No Shifting";}
		else{$rem="";}
		$data.="<tr style='background-color:".$bgclr1s.";' onclick=\"Effect.toggle('".$datelog."', 'blind', { duration: 1.0 });\">
					<td>".$datelog."(".date('D',strtotime($datelog)).")</td>
					<td>".$shiftsked->shft."&nbsp;&nbsp;&nbsp;".$hol."</td>
					<td>".$timeLogs." ".$bd."</td>
					<td colspan='5' align='center'>".$rem."</td>
		</tr>";
	}
	if($log->isError==0 && $log->days_absent==0 && $log->leaveId==0 && $log->isDayOff==0 && $log->leaveId!=5 && $log->leaveId!=13){
		$data.="<tr style='background-color:".$bgclr1s.";' onclick=\"Effect.toggle('".$datelog."', 'blind', { duration: 1.0 });\">
					<td>".$datelog."(".date('D',strtotime($datelog)).")</td>
					<td>".$shiftsked->shft."&nbsp;&nbsp;&nbsp;".$hol."</td>
					<td>".$timeLogs." ".$bd."</td>
					<td align='right'>".$log->hoursDuty."</td>
					<td align='right'>".$log->minutesLate."</td>
					<td align='right'>".$log->undertime."</td>
					<td align='right'>".$log->approvedOvertime."</td>
					<td align='right'>".$log->night_prem."</td>
		</tr>";
	}
	// start update dtr logs
	$logsdata="<tr>
				<td>Delete</td>
				<td>Date</td>
				<td>Time</td>
				<td>Type</td>
	</tr><tr><td colspan='4'><hr></td></tr>";
	$ee=mysql_query("select * from hrinterface where dtrid=".$emp->biometricNo." and hrint_id in (".$hrinterfaceid.")");
	while($l=mysql_fetch_object($ee)){
		if($l->in_out==0){$lin="selected='selected'";$lout="";}else{$lin="";$lout="selected='selected'";}
		$logsdata.="<tr style='font-size:12px;background-color:".$bgclr1s."'>
					<td><input type='Checkbox' name='cb".$l->hrint_id."'></td>
					<td>".$l->datelog."</td>
					<td><input type='text' size='8' name='inp".$l->hrint_id."' style='text-align:right;' value='".substr($l->log,11)."'></td>	
					<td><select name='sel".$l->hrint_id."' id='sel".$l->hrint_id."'>
							<option value='0' ".$lin.">Time In
							<option value='1' ".$lout.">Time Out
					</select></td>
		</tr>";
	}
		
	
	
	// end dtr logs
}

$hdr.="<tr style='color:maroon;font-weight:bold;'><td>Date</td>";
for($j=1;$j<=$top;$j++){$hdr.="<td>&nbsp;</td>";}
$hdr.="
		<td>Shift</td>
		<td>Time Logs</td>
	   <td align='right'>Total<br>Hours</td>
	   <td align='right'>Late<br>(min)</td>
	   <td align='right'>Undertime<br>(min)</td>
	   <td align='right'>Overtime<br>(min)</td>
	   <td align='right'>Night<br>Premium</td>
</tr>";


$levqry=mysql_query("select * from `leave` where ndex not in(5,13) order by name");
	while($lev=mysql_fetch_object($levqry)){
		 $ctr1s++;
    	 if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F9ECCF';}
		$levtotalqry=mysql_query("SELECT * from employee_leave where employeeId='".$_GET['id']."' and leaveId='".$lev->ndex."' and startDate>='".date('Y'.'-01-01')."' and endDate<='".date('Y'.'-12-31')."' order by startDate");
		//echo "SELECT * from employee_leave where employeeId='".$_GET['id']."' and leaveId='".$lev->ndex."' and startDate>='".date('Y'.'-01-01')."' and endDate<='".date('Y'.'-12-31')."' order by startDate<br><br><br>";
		$levlimit=mysql_fetch_object(mysql_query("select * from employee_leave_limit where employeeId='".$_GET['id']."' and leaveId='".$lev->ndex."' and yer='".date('Y')."'"));
		//echo "select * from employee_leave_limit where employeeId='".$_GET['emp']."' and leaveId='".$lev->ndex."' and yer=".date('Y')."";
		$levconsume=0;
		$leavedates="";
		while($l=mysql_fetch_object($levtotalqry)){
		$lstart = strtotime($l->startDate);
		$lend = strtotime($l->endDate);
			for ( $la = $lstart; $la <= $lend; $la += 86400 ){
				$levconsume++;
				$leavedates.="".date('Y-m-d',$la).",";
			}
		}
		$levremaining=$levlimit->leaveLimit - $levconsume;
		// - $levconsume
		$levdata.="<tr style='font-size:12px;'>
						<td></td>
						<td style='background-color:".$bgclr1s.";'>".$lev->name."</td>
						<td style='background-color:".$bgclr1s.";' align='right'>".$levremaining."</td>
						<td style='background-color:".$bgclr1s.";'>".rtrim($leavedates,',')."</td>
					</tr>
					";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>HRIS - Davao Doctors Hospital</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style.css" rel="stylesheet" type="text/css" />
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
</head>
<body>
<div id="topPans" style="display:none;"><img src="../images/kiosk.png" title="Green Solutions" alt="Davao Doctors Hospital" border="0" style="width:304px; height:100px; padding:0 0 0 6px;"/>
<img src="images/hris.png" style="width:704px; height:100px; padding:0 0 0 6px;">
</div>
<div class="logo" style="width:100%;margin-left: auto; margin-right: auto;text-align:center;">
	<a href="#">
	<img src="images/newlogo.png" alt="" style="position:relative;top:23px;"/> 
	</a>
	<div style="display:inline;font-size:60px;position:relative;top:10px;color:white;">
	| 
	</div>
	<div style=" margin-top: 5px;    font: 35px arial, sans-serif;  color:white;display:inline;font-size:50px;position:relative;top:10px;">
	 Human Resource Information System
	</div>
</div>
<div id="headerPan">
  <div id="headerPanleft">
    <div id="AA">
      <h2><a href="logout.php">Logout</a></h2>
      <p><a href="logout.php">Logout</a></p>
      <a href="logout.php">&nbsp;</a> </div>
    <div id="AB">
      <h2><a href="changepassword.php">Change<br>Password</a> </h2>
      <a href="changepassword.php">&nbsp;</a> </div>
	<div id="AC">
      <h2><a href="dtr.php">DTR</a> </h2>
      <p><a href="dtr.php">Daily Time Record</a> </p>
      <a href="dtr.php">&nbsp;</a> </div>
	<div id="AD">
      <h2><a href="payslip.php">Pay Slip</a> </h2>
      <a href="payslip.php">&nbsp;</a> </div>
	<div id="AF">
      <h2><a href="editemployee.php">Update 201</a> </h2>
      <a href="editemployee.php">&nbsp;</a> </div>
 </div>
</div>
<div id="bodyPan">
	<table width="100%">
		<tr><td align="center" style="color:maroon;"><h1>Daily Time Record</h1></td></tr>
	<tr>
		<td align="center">
			<div style=" -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;width:800px;background-color:#FFFC17;">
				<form name="frm" id="frm" action="dtr.php" method="post">
				<table style="font-family:Arial;font-size:12px;">
					<tr><td>Select Cutoff:<select name="mant" id="mant" onchange="document.frm.submit();"><?php echo $defopt;?><?php echo $coopt;?></select>
						<input type="submit" value="GO">
					</td></tr>					
				</table>
				</form>
				<table style="font-family:Arial;font-size:12px;" width="100%">
					<tr><td colspan="5" style="font-weight:bold;"><font color="maroon">Legend:</font> Black = IN &nbsp;&nbsp;&nbsp;&nbsp;<font color="blue">Blue = OUT </font></td></tr>
					<?php echo $hdr;?>
					<tr><td colspan="17"><hr></td></tr>
					<?php echo $data;?>
				</table>
			</div>
		</td>
	</tr>
	</table>
	<table style="font-family:Arial;font-size:12px;">
				<tr><td>&nbsp;</td></tr>
				<tr><td colspan="4" align="center" style="font-weight:bold;font-size:15px;color:maroon;"><u>Leave Ledger</u></td></tr>				
				<tr style="color:blue;font-weight:bold;">
					<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>Description</td>
					<td>Unconsume</td>
					<td>Dates</td>
				</tr>
				<tr><td>&nbsp;</td><td colspan="3"><hr></td></tr>
				<?php echo $levdata;?>
			</table>
</div>
</body>
</html>
