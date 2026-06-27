<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include ("employeefunctions.php");
if($_GET['act']=='adnew'){
	$red="";
	foreach($_POST['restday'] as $rd){$red.=$rd.",";}
	$ins=mysql_query("insert into employee_restday (`employeeId`, `restday`, `startDate`, `endDate`) VALUES ('".$_GET['id']."','".rtrim($red,",")."','".$_POST['startDate']."','".$_POST['endDate']."')");
	header("location:employeerestday.php?aa=saved&id=".$_GET['id']."");
}
if($_GET['aa']=='saved'){
	$msg='<font color="red" size="+2">Restday has been successfully set!</font>';
}
if($_GET['act']=='delete'){
	$del=mysql_query("delete from employee_restday where ndex=".$_GET['rdid']."");
	$msg='<font color="red" size="+2">Restday successfully deleted!</font>';
}
$emp=mysql_fetch_object(mysql_query("select * from employee where ndex='".$_GET['id']."'"));
$sqry=mysql_query("select ndex,startDate,endDate,restday
from employee_restday where employeeId='".$_GET['id']."' ORDER BY startDate,endDate");
while($r=mysql_fetch_object($sqry)){
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	$arr=explode(",",$r->restday);
	$rd='';
	foreach($arr as $ar){
		if($ar=='0'){$rd.='SUNDAY,';}
		elseif($ar=='1'){$rd.='MONDAY,';}
		elseif($ar=='2'){$rd.='TUESDAY,';}
		elseif($ar=='3'){$rd.='WEDNESDAY,';}
		elseif($ar=='4'){$rd.='THURSDAY,';}
		elseif($ar=='5'){$rd.='FRIDAY,';}
		elseif($ar=='6'){$rd.='SATURDAY,';}
	}
	$rd=rtrim($rd,",");
	$data.="<tr style='background-color:".$bgclr1s.";'>
				<td><img src='images/delete.png' width='13' height='13' onclick=\"window.location.href='employeerestday.php?act=delete&id=".$_GET['id']."&rdid=".$r->ndex."'\";>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				".$r->startDate."</td>
				<td>".$r->endDate."</td>
				<td>".$rd."</td>
	</tr>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Product Composition</title>
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
	<tr><td align="center"><h1><u style="color:maroon;">Restday Schedule</u><font size="-1">/<a href="employeeshiftingschedule.php?id=<?php echo $_GET['id'];?>">Shifting</a></font></h1></td></tr>	
</table>
<form name="frmcompo" action="employeerestday.php?act=adnew&id=<?php echo $_GET['id'];?>" method="post">
<table style="font-family:Arial;font-size:12px;">
	<tr><td colspan="2" style="color:maroon;"><strong><?php echo getID($emp->employmentStatus,$emp->employeeNo);?>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo $emp->lastName.",".$emp->firstName." ".$emp->middleName;?></strong></td></tr>
	<tr><td><strong>Shift:</strong></td><td><select name="restday[]" multiple="multiple">
		<option value="0">SUNDAY
		<option value="1">MONDAY
		<option value="2">TUESDAY
		<option value="3">WEDNESDAY
		<option value="4">THURSDAY
		<option value="5">FRIDAY
		<option value="6">SATURDAY
	</select></td></tr>
	<tr><td><strong>Start Date:</strong>&nbsp;&nbsp;</td><td><input type="Text" name="startDate" id="startDate" size="15"><a href="javascript:show_calendar('frmcompo.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
	<tr><td><strong>End Date:</strong>&nbsp;&nbsp;</td><td><input type="Text" name="endDate" id="endDate" size="15"><a href="javascript:show_calendar('frmcompo.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
	<tr><td align="center" colspan="2"><input type="submit" value="SAVE"></td></tr>
	<tr><td colspan="5"><hr></td></tr>
</table>
</form>
<table style="font-family:Arial;font-size:12px;" width="100%">
	<tr style="color:blue;font-weight:bold;">
		<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Start</td>
		<td>End</td>
		<td>Restday</td>
	</tr>
	<tr><td colspan="7"><hr></td></tr>
	<?php echo $data;?>
</table>
</body>
</html>
<?php ob_end_flush();?>