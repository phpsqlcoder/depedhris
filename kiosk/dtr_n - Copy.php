<?php
ob_start();
session_start();
if(!$_SESSION['ndex']){header("location:login.php");}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../employeefunctions.php");
include ("../myfunctions.php");
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
	$shiftsked=mysql_fetch_object(mysql_query("select e.*,s.name as shft,s.timeIn as s_in,s.timeOut as s_out from employee_shifting e left join shifting s on s.ndex=e.shiftingId where e.employeeId=".$_GET['id']." and e.approvedDate<>'0000-00-00 00:00:00' and '".$datelog."' between e.startDate and e.endDate"));
	$shiftnn="";
	if($shiftsked->shft){
		$shiftnn="(".date('h:i A',strtotime($shiftsked->s_in))." - ".date('h:i A',strtotime($shiftsked->s_out)).")";
	}
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
					<td>".$shiftsked->shft." ".$shiftnn."&nbsp;&nbsp;&nbsp;".$hol."</td>
					<td>".$timeLogs." ".$bd."</td>
					<td colspan='5' align='center' style='color:maroon;font-weight:bold;'>ABSENT</td>
		</tr>";
	}
	if($log->isDayOff>0){ 
		$data.="<tr style='background-color:".$bgclr1s.";' onclick=\"Effect.toggle('".$datelog."', 'blind', { duration: 1.0 });\">
					<td>".$datelog."(".date('D',strtotime($datelog)).")</td>
					<td>".$shiftsked->shft." ".$shiftnn."&nbsp;&nbsp;&nbsp;".$hol."</td>
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
					<td>".$shiftsked->shft." ".$shiftnn."&nbsp;&nbsp;&nbsp;".$hol."</td>
					<td>".$timeLogs." ".$bd."</td>
					<td colspan='5' align='center'>".$rem."</td>
		</tr>";
	}
	if($log->isError==0 && $log->days_absent==0 && $log->leaveId==0 && $log->isDayOff==0 && $log->leaveId!=5 && $log->leaveId!=13){
		$data.="<tr style='background-color:".$bgclr1s.";' onclick=\"Effect.toggle('".$datelog."', 'blind', { duration: 1.0 });\">
					<td>".$datelog."(".date('D',strtotime($datelog)).")</td>
					<td>".$shiftsked->shft." ".$shiftnn."&nbsp;&nbsp;&nbsp;".$hol."</td>
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
				$leavedates.="".date('Y-m-d',$la)."<br>";
			}
		}
		$levremaining=$levlimit->leaveLimit - $levconsume;
		// - $levconsume
		$levdata.="<tr style='font-size:12px;'>
						<td></td>
						<td style='background-color:".$bgclr1s.";'>".$lev->name."</td>
						<td style='background-color:".$bgclr1s.";' align='center'>".$levremaining."</td>
						<td style='background-color:".$bgclr1s.";'>".rtrim($leavedates,',')."</td>
					</tr>
					";
	}


include("newheader.php");
?>
<table width="100%">
	<tr>
		<td>
			<table>
			<tr><td align="center" style="color:maroon;"><h1>Daily Time Record</h1></td></tr>
			<tr>
				<td align="center">
					<div style=" -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;width:800px;background-color:#FFFC17;">
						<form name="frm" id="frm" action="dtr_n.php" method="post">
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
		</table></td>
		<td valign="top"><table style="font-family:Arial;font-size:12px;">
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
			</table></td>
	</tr>
	
	</table>
	<br><br><br>

<?php include("newfooter.php");?>