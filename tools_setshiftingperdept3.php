<?php
ob_start();
session_start();
date_default_timezone_set("Asia/Manila");
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
$cutoff=mysql_fetch_object(mysql_query("select * from cutoffdates where isLock=1 order by ndex DESC LIMIT 0,1"));
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
$dar=explode(",",$_SESSION['deptId']);
if($_GET['ser']){	
	if (in_array("73", $dar)){
		if(count($dar)>2){
			$addqry=" and (scheduler=".$_SESSION['ndex']." OR deptId in (".removear($dar)."))";
		}
		else{
			$addqry=" and scheduler=".$_SESSION['ndex']."";
		}
		$depqry="SELECT * from employee where (lastName like '%".$_GET['stxt']."%' or firstName like '%".$_GET['stxt']."%') and isActive=1 ".$addqry."";
	}
	else{
		$depqry="SELECT * from employee where (lastName like '%".$_GET['stxt']."%' or firstName like '%".$_GET['stxt']."%') and deptId in (".$_SESSION['deptId'].") and isActive=1";
	}
	
	$o=mysql_query($depqry);
	while($pp=mysql_fetch_object($o)){
		$d=mysql_fetch_object(mysql_query("select * from dept where ndex=".$pp->deptId.""));
		$da.="<a href='#' onclick=\"window.location.href='tools_setshiftingperdept.php?emp=".$pp->ndex."&startDate=".$_GET['startDate']."&endDate=".$_GET['endDate']."'\"><font color='blue'>".$pp->lastName.", ".$pp->firstName." - ".$d->name."<br></font></a>";
	}
	echo $da;
}
else{
$leave=mysql_query("SELECT name,ndex,
		CASE timeIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeIn),'%H:%i %p') END AS tymIn, 
		CASE breakOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakOut),'%H:%i %p') END as brekOut,
		CASE breakIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakIn),'%H:%i %p') END as brekIn,
		CASE timeOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeOut),'%H:%i %p') END as tymOut,breakMinutes
		FROM shifting where status<>1 order by name");
while($rsleave=mysql_fetch_object($leave)){
	$stymin=substr($rsleave->tymIn,0,2);
	$sbreakout=substr($rsleave->brekOut,0,2);
	$sbreakin=substr($rsleave->brekIn,0,2);
	$stymout=substr($rsleave->tymOut,0,2);
	if($stymin>=13){$ltymin=($stymin-12).substr($rsleave->tymIn,2);}else{$ltymin=$rsleave->tymIn;}
	if($sbreakout>=13){$lbreakout=($sbreakout-12).substr($rsleave->brekOut,2);}else{$lbreakout=$rsleave->brekOut;}
	if($sbreakin>=13){$lbreakin=($sbreakin-12).substr($rsleave->brekIn,2);}else{$lbreakin=$rsleave->brekIn;}
	if($stymout>=13){$ltymout=($stymout-12).substr($rsleave->tymOut,2);}else{$ltymout=$rsleave->tymOut;}
	if($lbreakout!=''){$brk="&nbsp;&nbsp;&nbsp; ".$lbreakout."&nbsp;&nbsp;&nbsp; ".$lbreakin;}
	else{$brk="";}
	$optionsh.="<option value='s".$rsleave->ndex."'>".$rsleave->name."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$ltymin."".$brk."&nbsp;&nbsp;&nbsp; ".$ltymout."";
}
$leave2=mysql_query("SELECT * FROM `leave` where ndex=3 order by code");
while($rsleave2=mysql_fetch_object($leave2)){
	$optionsh.="<option value='l".$rsleave2->ndex."'>".$rsleave2->code."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$rsleave2->name;
}
$optionsh.="<option value='OFF' selected>OFF";
if($_GET['act']=='sabmet'){
	$start = strtotime($_GET['startDates']);
	$end = strtotime($_GET['endDates']);
	for ( $a = $start; $a <= $end; $a += 86400 ){
		$det=date('Y-m-d',$a);
		$var=$_GET['dyt'.$det];
		$ndx=substr($var,1);
		if($ndx>=1 || $ndx=='FF'){
			$val=substr($var,1);
			if(substr($var,0,1)=='s'){
				$ckleave=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				if($ckleave->ndex){
					$del=mysql_query("delete from employee_leave where ndex=".$ckleave->ndex."");
				}
				$ckrd=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				if($ckrd->ndex){
					$del=mysql_query("delete from employee_restday where ndex=".$ckrd->ndex."");
				}
				$ckshift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				if($ckshift->ndex){
					$del=mysql_query("delete from employee_shifting where ndex=".$ckshift->ndex."");
				}
				$ins=mysql_query("insert into employee_shifting (`employeeId`, `shiftingId`, `startDate`, `endDate`)
						 VALUES (".$_GET['emp'].",'".$val."','".$det."','".$det."')");
			}
			elseif(substr($var,0,1)=='l'){
				//echo "ll";
				$ckrd=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				if($ckrd->ndex){
					$del=mysql_query("delete from employee_restday where ndex=".$ckrd->ndex."");
				}
				$ckshift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				if($ckshift->ndex){
					$del=mysql_query("delete from employee_shifting where ndex=".$ckshift->ndex."");
				}
				$ckleave=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				
				if($ckleave->ndex){
					$del=mysql_query("delete from employee_leave where ndex=".$ckleave->ndex."");
				}
				$ins=mysql_query("insert into employee_leave (`employeeId`, `leaveId`, `startDate`, `endDate`)
						 VALUES (".$_GET['emp'].",'".$val."','".$det."','".$det."')");
			}
			elseif(substr($var,0,1)=='O'){
				//echo date('w',strtotime($det))."weee";
				$ckshift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				if($ckshift->ndex){
					$del=mysql_query("delete from employee_shifting where ndex=".$ckshift->ndex."");
				}
				$ckleave=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				
				if($ckleave->ndex){
					$del=mysql_query("delete from employee_leave where ndex=".$ckleave->ndex."");
				}
				$ckrd=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				if($ckrd->ndex){
					$del=mysql_query("delete from employee_restday where ndex=".$ckrd->ndex."");
				}
				$ins=mysql_query("insert into employee_restday (`employeeId`, `restday`, `startDate`, `endDate`)
						 VALUES (".$_GET['emp'].",'".date('w',strtotime($det))."','".$det."','".$det."')");
			}
		}
	}
}
if($_GET['emp']){
	$emp=mysql_fetch_object(mysql_query("SELECT e.ndex,e.employmentStatus,e.employeeNo,e.isTaxable,e.firstName,e.divisionId as divs,e.deptId as deptId,e.payType,e.lastName,e.middleName,p.name as position,d.name as c from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId where e.ndex='".$_GET['emp']."' and e.isActive=1 order by e.lastName,e.firstName"));
	//$st=date('d',strtotime($_GET['startDate']));
	//$en=date('d',strtotime($_GET['endDate']));
	//$yr=date('Y-m',strtotime($_GET['endDate']));
	$start = strtotime($_GET['startDate']);
	$end = strtotime($_GET['endDate']);
	for ( $a = $start; $a <= $end; $a += 86400 ){
		$det=date('Y-m-d',$a);
		$shift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$_GET['emp']." and '".$det."' between startDate and endDate"));
		$lve=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId=".$_GET['emp']." and '".$det."' between startDate and endDate"));
		//echo "select * from employee_leave where employeeId=".$_GET['emp']." and '".$det."' between startDate and endDate";
		$rd=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$_GET['emp']." and '".$det."' between startDate and endDate"));
		if($shift->shiftingId){
			$sc=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shift->shiftingId.""));
			$optionsh.="<option value='s".$sc->ndex."' selected> ".$sc->name."";
		}
		elseif($lve->leaveId){
			$lv=mysql_fetch_object(mysql_query("select * from `leave` where ndex=".$lve->leaveId.""));
			$optionsh.="<option value='l".$lv->ndex."' selected> ".$lv->code."";
		}
		elseif($rd->ndex){
			$optionsh.="<option value='OFF' selected> OFF";
		}
		else{
			$optionsh.="<option value='00' selected>- Select Option -";
		}
		if($det<=$cutoff->cutoffDateEnd || $shift->approvedBy!='' || $lve->approvedBy!='' || $rd->approvedBy!=''){
			$disable="disabled";
		}
		else{
			$disable="";
		}
		// || $shift->approvedBy!='' || $lve->approvedBy!='' || $rd->approvedBy!=''
		$d.="<tr style='font-size:12px;background-color:".$bgclr1s."'>
			<td><div id='b".$a."' style='color:black;font-weight:bold;'>".$det."&nbsp;&nbsp;&nbsp;&nbsp;</div><input type='hidden' name='emp' value='".$_GET['emp']."'>
			<input type='hidden' name='startDates' value='".$_GET['startDate']."'>
			<input type='hidden' name='endDates' value='".$_GET['endDate']."'>
			<input type='hidden' name='".$det."' value='".$det."'></td>
			<td><select ".$disable." name='dyt".$det."' id='dyt".$det."' onfocus=\"document.getElementById('b".$a."').style.color='red';\" onblur=\"document.getElementById('b".$a."').style.color='black';\">".$optionsh."</select></td>
		</tr>";
	}
	$levqry=mysql_query("select * from `leave` where ndex not in(5,13) order by name");
	while($lev=mysql_fetch_object($levqry)){
		 $ctr1s++;
    	 if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F9ECCF';}
		$levtotalqry=mysql_query("SELECT * from employee_leave where employeeId='".$_GET['emp']."' and leaveId='".$lev->ndex."' order by startDate");
		$levlimit=mysql_fetch_object(mysql_query("select * from employee_leave_limit where employeeId='".$_GET['emp']."' and leaveId='".$lev->ndex."'"));
		$levconsume=0;
		$leavedates="";
		while($l=mysql_fetch_object($levtotalqry)){
		$lstart = strtotime($l->startDate);
		$lend = strtotime($l->endDate);
			for ( $la = $lstart; $la <= $lend; $la += 86400 ){
				$levconsume++;
				$leavedates.="<tr><td colspan='2' align='center'>".date('Y-m-d',$la)."</td></tr>";
			}
		}
		$levremaining=$levlimit->leaveLimit - $levconsume;
		$levdata.="<tr onclick=\"Effect.toggle('".$lev->ndex."', 'blind', { duration: 1.0 });\" style='background-color:".$bgclr1s.";font-size:12px;'>
						<td>".$lev->name."</td>
						<td align='right'>".$levremaining."</td>
					</tr>
					<tr><td colspan='2' align='center'>
						<div id='".$lev->ndex."' style='display:none;'><table style='font-size:11px;color:maroon;'>".$leavedates."</table></div>
					</td></tr>
					";
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
    <script type="text/javascript">
	function searchitems(){
	//alert();
		mynodes=frmitem.serialize();	
		new Ajax.Updater('listitem','tools_setshiftingperdept.php?ser=searching',{
			method: 'get',
			parameters: mynodes
		});	
	}
    </script>

    <div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
</head>
<body>
<?php include "headerperdept.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Employee Schedule</h2>   
    <div class="clearfix">
	<form name="frmitem" id="frmitem">
<table width="80%">
	<tr>
		<td>Start Date:<input type="Text" name="startDate" id="startDate" size="15" value="<?php echo $_GET['startDate'];?>"><a href="javascript:show_calendar('frmitem.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		<td>End Date:<input type="Text" name="endDate" id="endDate" size="15" value="<?php echo $_GET['endDate'];?>"><a href="javascript:show_calendar('frmitem.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		<td><button onclick="window.open('shifting_list.php','displayWindow','toolbar=no,scrollbars=yes,width=410,height=600');">View Shiftings</button></td>
	</tr>
	<tr>
		<td colspan="2">Search: <input type="text" name="stxt" id="stxt" onkeyup="searchitems();">&nbsp;&nbsp;<font color="#ff0000"><i>Enter any part of last or first name.</i></font></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan=5><div id="listitem"></div></td>
				</tr>
	<tr valign="top"><td>&nbsp;</td></tr>
</table>
<?php if ($d){?>
<form method="post" action="tools_setshiftingperdept.php?act=sabmet">
<table>
	<tr>
		<td colspan="2" style="color:maroon;font-weight:bold;font-size:16px;letter-spacing:2px"><u><?php echo $emp->lastName.", ".$emp->firstName." ".$emp->middleName;?></u><br><br></td>
	</tr>
	<tr style="color:blue;font-weight:bold;">
		<td>Date</td>
		<td>Shift</td>
	</tr>
	<tr><td colspan="2"><hr></td></tr>
	<?php echo $d;?>
	<tr><td><input type="hidden" name="act" value="sabmet"> <input type="submit" value="SAVE"></td></tr>
</table>
</form>
<div style="display:none;width:350px;position:absolute;top:400px;right:300px;webkit-border-radius: 8px; -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;background-image: url(images/leavebg.png);	background-repeat: repeat-x;background-color: White;">
	<table width="100%">
		<tr><td colspan="2" align="center" style="font-weight:bold;font-size:15px;color:maroon;"><u>Leave Limit</u></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr style="color:blue;font-weight:bold;">
			<td>Description</td>
			<td>Unconsume</td>
		</tr>
		<tr><td colspan="2"><hr></td></tr>
		<?php echo $levdata; ?>
	</table>
</div>
<?php } ?>
    </div> 
	
	<?php // include "footer.php";?>
  </div>
</body>
</html>
<?php } ?>