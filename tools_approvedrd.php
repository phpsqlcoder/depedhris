<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
	include ("myfunctions.php");
if($_GET['ser']){
	$o=mysql_query("SELECT * from employee where lastName like '%".$_GET['stxt']."%' or firstName like '%".$_GET['stxt']."%'");
	while($pp=mysql_fetch_object($o)){
		$d=mysql_fetch_object(mysql_query("select * from dept where ndex=".$pp->deptId.""));
		$da.="<a href='#' onclick=\"window.location.href='tools_approvedrd.php?emp=".$pp->ndex."&startDate=".$_GET['startDate']."&endDate=".$_GET['endDate']."'\"><font color='blue'>".getID($pp->employmentStatus,$pp->employeeNo)." - ".$pp->lastName.", ".$pp->firstName." - ".$d->name."<br></font></a>";
	}
	echo $da;
}
else{

	if($_GET['act']=='sabmet'){
	
		$start = strtotime($_GET['startDate']);
		$end = strtotime($_GET['endDate']);
		for ( $a = $start; $a <= $end; $a += 86400 ){
			$det=date('Y-m-d',$a);
		
			$upd=mysql_query("update dailytimesummary set hoursDuty=".$_POST[$det].",night_prem=".$_POST['np'.$det].",drdRemarks='".$_POST['rem'.$det]."',otRestDay='".$_POST['otrd'.$det]."' where employeeId=".$_GET['emp']." and date='".$det."'");
		
		$msg="Successfully Approved DRD!";
	}
}
if($_GET['emp']){
	$r=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_GET['emp'].""));

	$start = strtotime($_GET['startDate']);
	$end = strtotime($_GET['endDate']);
	for ( $a = $start; $a <= $end; $a += 86400 ){
		$det=date('Y-m-d',$a);
		//echo $det."<br>";
		$aot=mysql_fetch_object(mysql_query("select * from dailytimesummary where employeeId=".$r->ndex." and date='".$det."' and isDayoff=1"));
		$inlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$det."' and in_out=0 ORDER BY log LIMIT 0,1"));
		$nxtday_timeIn=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog>'".$det."' and in_out=0 ORDER BY log LIMIT 0,1"));
		if(!$nxtday_timeIn->hrint_id){
			$out=mysql_fetch_object(mysql_query("SELECT * FROM `hrinterface` where in_out=1 and log > '".$det."' and dtrid='".$r->biometricNo."' ORDER BY log DESC limit 0,1")); // Get OUT record
			
		}
		else{
			$out=mysql_fetch_object(mysql_query("SELECT * FROM `hrinterface` where in_out=1 and log > '".$det."' and log<'".$nxtday_timeIn->log."' and dtrid='".$r->biometricNo."' ORDER BY log DESC limit 0,1")); // Get OUT record
			
		}
		//$out=mysql_fetch_object(mysql_query("SELECT * FROM `hrinterface` where in_out=1 and datelog >= '".$det."' and log<'".$nxtday_timeIn->log."' and dtrid='".$r->biometricNo."' ORDER BY log DESC limit 0,1")); // Get OUT record
		$tymlogs=$inlog->log." &nbsp;&nbsp; &nbsp;&nbsp;".$out->log;
		//echo 		$nxtday_timeIn->log." -- ".$out->log."<br>";
		//echo $tymlogs;
		$ctr1s++;
		if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		$daylog=date('w',strtotime($det));
		//$tymlogs="";		
		if($aot->ndex){
			$data.="<tr style='font-size:12px;background-color:".$bgclr1s."'>
						<td>".$det."</td>
						<td>".$tymlogs."</td>
						<td>".number_format($aot->hoursDuty,2)."</td>
						<td><input type='text' name='".$det."' value='".$aot->hoursDuty."' style='text-align:right;'></td>
						<td><input type='text' name='otrd".$det."' value='".$aot->otRestDay."' style='text-align:right;'></td>
						<td><input type='text' name='np".$det."' value='".$aot->night_prem."' style='text-align:right;'></td>
						<td><input type='text' name='rem".$det."' value='".$aot->drdRemarks."'></td>
			</tr>";
		}
	}
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
		new Ajax.Updater('listitem','tools_approvedrd.php?ser=searching',{
			method: 'get',
			parameters: mynodes
		});	
	}
    </script>
    
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Approve DRD</h2>   
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
	<?php if($_GET['emp']){?>
	<form name="frmemp" action="tools_approvedrd.php?act=sabmet&startDate=<?php echo $_GET['startDate'];?>&endDate=<?php echo $_GET['endDate'];?>&emp=<?php echo $_GET['emp'];?>" method="post">
		<table width="100%">
			<tr><td>&nbsp;</td></tr>
			<tr style='color:maroon;font-weight:bold;'>
				<td colspan="3">Name: <?php echo $r->lastName.", ".$r->firstName;?><input type="Hidden" name="deptselect" value="<?php echo $d->ndex;?>"></td>
				<td><br><br></td>
			</tr>
			<tr style="color:red;font-size:14px;font-weight:bold;"><td colspan='3'><?php echo $msg;?></td></tr>
			<tr style="color:blue;font-size:12px;font-weight:bold;">
				<td>Date</td>
				<td>Logs</td>
				<td>Hours Duty</td>
				<td>DRD Hours</td>
				<td>OT RestDay</td>
				<td>Night Premium</td>
				<td>Remarks</td>
			</tr>
			<tr><td colspan='6'><hr></td></tr>
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