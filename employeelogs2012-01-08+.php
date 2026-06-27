<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include("scripts/scripts.php");
include ("employeefunctions.php");
include ("myfunctions.php");

$arr=0;
$optmonths="
	<option value='01'>January
	<option value='02'>February
	<option value='03'>March
	<option value='04'>April
	<option value='05'>May
	<option value='06'>June
	<option value='07'>July
	<option value='08'>August
	<option value='09'>September
	<option value='10'>October
	<option value='11'>November
	<option value='12'>December
";
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
		$apot=mysql_query("update dailytimesummary set approvedOvertime='".$_POST['aot'.$datelog]."',approvedOvertimeNightPremium='".$_POST['anpot'.$datelog]."' where employeeId='".$_GET['id']."' and date='".$datelog."'");
		reprocess_timelogs($_GET['id'],$datelog);
		
		
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
	$log=mysql_fetch_object(mysql_query("select * from dailytimesummary where employeeId=".$_GET['id']." and date='".$datelog."'"));
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
	if($log->days_absent>0 && $log->leaveId!=5 && $log->leaveId!=13){ 
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
		
	
	$data.="<tr>
				<td colspan='10'>
					<div id='".$datelog."' style='display:none;background-color:#E0FFFF;'>
					<table style='font-family:Arial;font-size:12px;font-weight:bold;'>
						<tr><td colspan='10' align='center'>".$datelog."(".date('D',$datelog).")&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
						<tr>
							<td valign='top'>
								<div style='width:350px;webkit-border-radius: 8px; -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;background-image: url(images/leavebg.png);	background-repeat: repeat-x;background-color: #57E964;'>
									<table style='font-family:Arial;font-size:11px;'>
										<tr><td colspan='4' style='color:maroon'><u>Delete/Update Time Logs </u></td></tr>
										".$logsdata."
									</table>
								</div>
							</td>
							<td valign='top'>
								<div style='width:200px;webkit-border-radius: 8px; -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;background-image: url(images/leavebg.png);	background-repeat: repeat-x;background-color: #52D017;'>
									<table style='font-family:Arial;font-size:11px;' border='0'>
										<tr><td colspan='4' style='color:maroon'><u>Approve Overtime </u></td></tr>
										<tr><td>Unapproved Overtime: </td><td><font color='blue'>".number_format($overtym,2)."</font></td></tr>
										<tr><td>Approved Overtime:</td><td><input type='text' name='aot".$datelog."' value='".$log->approvedOvertime."' size='5' style='text-align:right;'></td></tr>
										<tr><td>Approved NP Overtime:</td><td><input type='text' name='anpot".$datelog."' value='".$log->approvedOvertimeNightPremium."' size='5' style='text-align:right;'></td></tr>
										<tr><td></td></tr>
									</table>
								</div>
							</td>
							<td valign='top'>
								<div style='width:350px;webkit-border-radius: 8px; -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;background-image: url(images/leavebg.png);	background-repeat: repeat-x;background-color: #87F717;'>
									<table style='font-family:Arial;font-size:11px;'>
										<tr><td colspan='4' style='color:maroon'><u>Add Time Logs(Not yet available) </u></td></tr>
										<tr style='color:blue;font-size:12px;font-weight:bold;'>
											<td>Date</td>
											<td>Time</td>
											<td>Type</td>
										</tr>
										<tr><td colspan='3'><hr></td></tr>
										<tr>			
											<td>Date:<input type='Text' disabled name='dated".$dayvar."' id='dated".$dayvar."' size='8'><a href=\"javascript:show_calendar('wee.dated".$dayvar."');\" onMouseOver=\"window.status='Date Picker'; overlib(''); return true;\" onMouseOut=\"window.status=''; nd(); return true;\"><img src='b_calendar.png' width='19' border='0'></a></td>
											<td><input type='text' disabled name='xinpx".$dayvar."' size='8' style='text-align:right;' onFocus=\"this.value='';\" onClick=\"this.value='';\"></td>	
											<td><select name='selx".$dayvar."' disabled>
													<option value='0'>Time In
													<option value='1'>Break Out
													<option value='0'>Break In
													<option value='1'>Time Out
											</select></td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
						<tr><td colspan='10' align='center'><input type='submit'></td></tr>
						
					</table>
					</div>
				</td>
			</tr>";
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
</tr>";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Employee Logs</title>
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
</head>
<body>
<table style="font-family:Arial;font-size:12px;" width="100%">
	<tr><td><?php echo $msg;?></td></tr>
	<tr><td align="center"><u><h1>Employee Time Logs</u><br><font size="-1"><?php echo $startdate." to ".$enddate;?></font></h1></td></tr>	
</table>
<form name="frmcompo" action="employeelogs.php?act=adnew&id=<?php echo $_GET['id'];?>" method="post">
<table style="font-family:Arial;font-size:12px;">
	<tr><td colspan="2" style="color:maroon;"><strong><?php echo getID($emp->employmentStatus,$emp->employeeNo);?>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo $emp->lastName.",".$emp->firstName." ".$emp->middleName;?></strong></td></tr>
	<tr><td>Select Cutoff:<select name="mant" id="mant" onchange="document.frmcompo.submit();"><?php echo $defopt;?><?php echo $coopt;?></select>
		<input type="submit" value="GO">
	</td></tr>
	
</table>
</form>
<form name="wee" id="wee" action="employeelogs.php?act=ot&mants=<?php echo $defco;?>&id=<?php echo $_GET['id'];?>" method="post">
<table style="font-family:Arial;font-size:12px;" width="100%">

	<tr><td colspan="5" style="font-weight:bold;"><font color="maroon">Legend:</font> Black = IN &nbsp;&nbsp;&nbsp;&nbsp;<font color="blue">Blue = OUT </font></td></tr>
	<?php echo $hdr;?>
	<tr><td colspan="17"><hr></td></tr>
	<?php echo $data;?>
	
</table>
</form>
</body>
</html>
<?php ob_end_flush();//alert(document.getElementById('sel369078').value?>