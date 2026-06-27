<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
	include ("myfunctions.php");
	date_default_timezone_set("Asia/Manila");
if($_GET['ser']){
	$o=mysql_query("SELECT * from employee where isActive=1 and (lastName like '%".$_GET['stxt']."%' or firstName like '%".$_GET['stxt']."%')");
	while($pp=mysql_fetch_object($o)){
		$d=mysql_fetch_object(mysql_query("select * from dept where ndex=".$pp->deptId.""));
		$da.="<a href='#' onclick=\"window.location.href='tools_approveovertime2.php?emp=".$pp->ndex."&startDate=".$_GET['startDate']."&endDate=".$_GET['endDate']."'\"><font color='blue'>".getID($pp->employmentStatus,$pp->employeeNo)." - ".$pp->lastName.", ".$pp->firstName." - ".$d->name."<br></font></a>";
	}
	echo $da;
}
else{

if($_GET['act']=='sabmet'){
	/*$st=date('d',strtotime($_GET['startDate']));
	$en=date('d',strtotime($_GET['endDate']));
	$yr=date('Y-m',strtotime($_GET['endDate']));
	for($a=$st;$a<=$en;$a++){
	$det=$yr."-".$a;*/
	date_default_timezone_set("Asia/Manila");
	$start = strtotime($_GET['startDate']);
	$end = strtotime($_GET['endDate']);
	for ( $a = $start; $a <= $end; $a += 86400 ){
		$det=date('Y-m-d',$a);
	$ee=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_GET['emp'].""));
	$qr=mysql_fetch_object(mysql_query("select * from hrinterface where in_out=0 and datelog='".$det."' and dtrid='".$ee->biometricNo."' order by log limit 0,1"));
	$shift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$ee->ndex." and approvedDate<>'0000-00-00 00:00:00' and '".$det."' between startDate and endDate"));
	//echo "select * from employee_shifting where employeeId=".$ee->ndex." and '".$det."' between startDate and endDate";
	$out=mysql_fetch_object(mysql_query("SELECT * FROM `hrinterface` where in_out=1 and log > '".$qr->log."' and dtrid=".$ee->biometricNo." ORDER BY log  limit 0,1"));
		//$det=$yr."-".$a;
		$upd=mysql_query("update dailytimesummary set approvedOvertime='".$_POST[$det]."',approvedOvertimeNightPremium='".$_POST['np'.$det]."',overtimeRemarks='".$_POST['rem'.$det]."'
				 where employeeId=".$_GET['emp']." and date='".$det."'");
	}
	$msg="Successfully Approved Overtime!";
}
if($_GET['emp']){
	$r=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_GET['emp'].""));
	/*$st=date('d',strtotime($_GET['startDate']));
	$en=date('d',strtotime($_GET['endDate']));
	$yr=date('Y-m',strtotime($_GET['endDate']));
	for($a=$st;$a<=$en;$a++){
		$det=$yr."-".$a;*/
		
	$start = strtotime($_GET['startDate']);
	$end = strtotime($_GET['endDate']);
	$totalot=0;
	$totalnp=0;
	for ( $a = $start; $a <= $end; $a += 86400 ){
		$det=date('Y-m-d',$a);
		//echo $det."<br>";
		$aot=mysql_fetch_object(mysql_query("select * from dailytimesummary where employeeId=".$r->ndex." and date='".$det."'"));
		$ctr1s++;
		if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		$daylog=date('w',strtotime($det));
		$restday=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$r->ndex." and '".$det."' between startDate and endDate"));
		$tymlogs="";
		$tymlogs2="";
		$advot=0;
		if($restday->restday!=$daylog){
			$shifts=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$r->ndex." and approvedDate<>'0000-00-00 00:00:00' and '".$det."' between startDate and endDate"));
			if($shifts->shiftingId){
				$getshift=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shifts->shiftingId.""));
				$inlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$det."' and in_out=0 ORDER BY log LIMIT 0,1"));
				$nxtday_timeIn=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog>'".$det."' and in_out=0 ORDER BY log LIMIT 0,1"));
				$out=mysql_fetch_object(mysql_query("SELECT * FROM `hrinterface` where in_out=1 and datelog >= '".$det."' and log<'".$nxtday_timeIn->log."' and dtrid='".$r->biometricNo."' ORDER BY log DESC limit 0,1")); // Get OUT record
				$tymlogs=$getshift->timeOut." &nbsp;&nbsp;to &nbsp;&nbsp;".$out->log;
				$tymlogs2=$inlog->log." &nbsp;&nbsp;to &nbsp;&nbsp;".$getshift->timeIn;
				$datein=date('Y-m-d',strtotime($inlog->log));
				$shiftdatein=$datein." ".$getshift->timeIn;
				$advot=timeDiffinSeconds($inlog->log,$shiftdatein)/3600;
				if($advot<0){$advot=0;}
				//$advot=$inlog->log." - ".$shiftdatein;
			}
		}
		$totalot+=$aot->approvedOvertime;
		$totalnp+=$aot->approvedOvertimeNightPremium;
		$data.="<tr style='font-size:12px;background-color:".$bgclr1s."'>
					<td>".$det."</td>
					<td>".$tymlogs2."</td>
					<td>".number_format($advot,2)."</td>
					<td>".$tymlogs."</td>
					<td>".number_format($aot->overtime/60,2)."</td>
					<td><input type='text' size='5' name='".$det."' onkeyup=\"if(parseInt(this.value)>15){alert('Exceed Overtime Limit!'); this.value='".$aot->approvedOvertime."';}\" value='".$aot->approvedOvertime."' style='text-align:right;' onFocus='this.select()' onClick='this.select()'></td>	
					<td align='right'><input type='text' size='5' onkeyup=\"if(parseInt(this.value)>15){alert('Exceed Overtime Limit!'); this.value='".$aot->approvedOvertimeNightPremium."';}\" name='np".$det."' value='".$aot->approvedOvertimeNightPremium."' style='text-align:right;' onFocus='this.select()' onClick='this.select()'></td>
					<td><input type='text' name='rem".$det."' value='".$aot->overtimeRemarks."'></td>
		</tr>";
	}
	$data.="<tr>
				<td>Total</td>
				<td colspan='5' align='right'>".number_format($totalot,2)."</td>
				<td align='right'>".number_format($totalnp,2)."</td>
	</tr>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
<script type="text/javascript">
	function searchitems(){
	//alert();
		mynodes=frmitem.serialize();	
		new Ajax.Updater('listitem','tools_approveovertime2.php?ser=searching',{
			method: 'get',
			parameters: mynodes
		});	
	}
    </script>
    
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Approve Overtime</h2>   
    <div class="clearfix">
		<form name="frmitem" id="frmitem">
<table>
	<tr>
		<td>Start Date:<input type="Text" name="startDate" id="startDate" size="15" value="<?php echo $_GET['startDate'];?>"><a href="javascript:show_calendar('frmitem.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		<td>End Date:<input type="Text" name="endDate" id="endDate" size="15" value="<?php echo $_GET['endDate'];?>"><a href="javascript:show_calendar('frmitem.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
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
		</form>
	<?php if($_GET['emp']){
	$ds=mysql_fetch_object(mysql_query("select * from dept where ndex=".$r->deptId.""));
	?>
	<form name="frmemp" action="tools_approveovertime2.php?act=sabmet&startDate=<?php echo $_GET['startDate'];?>&endDate=<?php echo $_GET['endDate'];?>&emp=<?php echo $_GET['emp'];?>" method="post">
		<table width="100%">
			<tr><td>&nbsp;</td></tr>
			<tr style='color:maroon;font-weight:bold;'>
				<td colspan="3">Name: <?php echo getID($r->employmentStatus,$r->employeeNo)." - ".$r->lastName.", ".$r->firstName." - ".$ds->name;?><input type="Hidden" name="deptselect" value="<?php echo $d->ndex;?>"></td>
				<td><br><br></td>
			</tr>
			<tr style="color:red;font-size:14px;font-weight:bold;"><td colspan='3'><?php echo $msg;?></td></tr>
			<tr style="color:blue;font-size:12px;font-weight:bold;">
				<td>Date</td>
				<td>Time In - ShiftIn</td>
				<td>Advance OT</td>
				<td>ShiftOut - Time Out</td>
				<td>Unapprove<br>Overtime (hr)</td>
				<td>Overtime</td>
				<td>OT Night Premium</td>
				<td>Remarks</td>
			</tr>
			<tr><td colspan='16'><hr></td></tr>
			<tr><td colspan="6" align="right"><input type="Submit" value="Update"></td></tr>
			<?php echo $data;?>
			<tr><td colspan="6" align="right"><input type="Submit" value="Update"></td></tr>
		</table>
	</form>
	<?php }?>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>
<?php }?>