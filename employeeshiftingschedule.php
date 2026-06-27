<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include ("employeefunctions.php");
if($_GET['act']=='adnew'){
	$start = strtotime($_POST['startDate']);
	$end = strtotime($_POST['endDate']);
	for ( $a = $start; $a <= $end; $a += 86400 ){
		$det=date('Y-m-d',$a);
		//echo $det."<br>";
		$ins=mysql_query("insert into employee_shifting (`employeeId`, `shiftingId`, `startDate`, `endDate`,`approvedBy`,`approvedDate`) VALUES ('".$_GET['id']."','".$_POST['shift']."','".$det."','".$det."','".$_SESSION['fullName']."','".date('Y-m-d H:i:s')."')");
		//echo "insert into employee_shifting (`employeeId`, `shiftingId`, `startDate`, `endDate`,`approvedBy`,`approvedDate`) VALUES ('".$_GET['id']."','".$_POST['shift']."','".$_POST['startDate']."','".$_POST['endDate']."','".$_SESSION['fullName']."','".date('Y-m-d H:i:s')."')<br>";
	}
	header("location:employeeshiftingschedule.php?aa=saved&id=".$_GET['id']."");
}
if($_GET['act']=='delete'){
	$del=mysql_query("delete from employee_shifting where ndex=".$_GET['rdid']."");
	$msg='<font color="red" size="+2">Shifting successfully deleted!</font>';
}
if($_GET['aa']=='saved'){
	$msg='<font color="red" size="+2">Shift has been successfully set!</font>';
}
$emp=mysql_fetch_object(mysql_query("select * from employee where ndex='".$_GET['id']."'"));
$sqry=mysql_query("select s.ndex as ndx,s.startDate,s.endDate,
		CASE t.timeIn WHEN '00:00:00' THEN '-' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',t.timeIn),'%H:%i %p') END AS tymIn, 
		CASE t.breakOut WHEN '00:00:00' THEN '-' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',t.breakOut),'%H:%i %p') END as brekOut,
		CASE t.breakIn WHEN '00:00:00' THEN '-' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',t.breakIn),'%H:%i %p') END as brekIn,
		CASE t.timeOut WHEN '00:00:00' THEN '-' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',t.timeOut),'%H:%i %p') END as tymOut,breakMinutes
from employee_shifting s left join shifting t on t.ndex=s.shiftingId where s.employeeId='".$_GET['id']."' ORDER BY s.startDate DESC");
while($r=mysql_fetch_object($sqry)){
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	$data.="<tr style='background-color:".$bgclr1s.";'>
				<td>
				<img src='images/delete.png' width='13' height='13' onclick=\"window.location.href='employeeshiftingschedule.php?act=delete&id=".$_GET['id']."&rdid=".$r->ndx."'\";>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				".$r->startDate."</td>
				<td>".$r->endDate."</td>
				<td>".$r->tymIn."</td>
				<td>".$r->brekOut."</td>
				<td>".$r->brekIn."</td>
				<td>".$r->tymOut."</td>
				<td>".$r->breakMinutes."</td>
	</tr>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Shifting</title>
	<script type="text/javascript">
	window.onkeyup = function (event) {
		if (event.keyCode == 27) {
			opener.location.reload();
			window.close ();
		}
	}
</script>
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
</head>
<body>
<table style="font-family:Arial;font-size:12px;" width="100%">
	<tr><td><?php echo $msg;?></td></tr>
	<tr><td align="center"><h1><u style="color:maroon;">Shifting Schedule</u><font size="-1">/<a href="employeerestday.php?id=<?php echo $_GET['id'];?>">Restday</a></font></h1></td></tr>	
</table>
<form name="frmcompo" action="employeeshiftingschedule.php?act=adnew&id=<?php echo $_GET['id'];?>" method="post">
<table style="font-family:Arial;font-size:12px;">
	<tr><td colspan="2" style="color:maroon;"><strong><?php echo getID($emp->employmentStatus,$emp->employeeNo);?>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo $emp->lastName.",".$emp->firstName." ".$emp->middleName;?></strong></td></tr>
	<tr><td><strong>Shift:</strong></td><td><select name="shift"><?php echo $optionshifting;?></select></td></tr>
	<tr><td><strong>Start Date:</strong>&nbsp;&nbsp;</td><td><input type="Text" name="startDate" id="startDate" size="15"><a href="javascript:show_calendar('frmcompo.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
	<tr><td><strong>End Date:</strong>&nbsp;&nbsp;</td><td><input type="Text" name="endDate" id="endDate" size="15"><a href="javascript:show_calendar('frmcompo.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
	<tr><td align="center" colspan="2"><input type="submit" value="SAVE"></td></tr>
	<tr><td colspan="5"><hr></td></tr>
</table>
</form>
<table style="font-family:Arial;font-size:12px;" width="100%">
	<tr style="color:blue;font-weight:bold;">
		<td>Start</td>
		<td>End</td>
		<td>Time In</td>
		<td>Break Out</td>
		<td>Break In</td>
		<td>Time Out</td>
		<td>Break (min)</td>
	</tr>
	<tr><td colspan="7"><hr></td></tr>
	<?php echo $data;?>
</table>
</body>
</html>
<?php ob_end_flush();?>