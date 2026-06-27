<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
    include ("myfunctions.php");
	if($_GET['act']=='go'){
	$a=mysql_query("select e.firstName,e.lastName,d.* from dailytimesummary d left join employee e on e.ndex=d.employeeId where d.date>='".$_POST['startdate']."' and d.date<='".$_POST['enddate']."' and (d.hoursDuty<2 OR d.hoursDuty>8) and employeeId>0 and leaveId=0 and isDayoff=0 and e.isActive=1 and hoursDuty>0 and e.residencyTrainingProgram<>'ROD' order by e.lastName,e.firstName,d.date");
	while($r=mysql_fetch_object($a)){
		$shiftsked=mysql_fetch_object(mysql_query("select e.*,s.name as shft from employee_shifting e left join shifting s on s.ndex=e.shiftingId where e.employeeId=".$r->employeeId." and e.approvedDate<>'0000-00-00 00:00:00' and '".$r->date."' between e.startDate and e.endDate"));
		$data.="<tr>
					<td>".$r->lastName.", ".$r->firstName."</td>
					<td>".$r->date."</td>
					<td>".$shiftsked->shft."</td>
					<td>".$r->hoursDuty."</td>
					<td>".$r->overtime."</td>
					<td>".$r->undertime."</td>
		</tr>";
	}
	}
	
?>
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
	<form name="frmempee" action="checkshifting.php?act=go" method="post">
	<table width="100%" style="font-size:11px;">			
			<tr style="color:blue;font-size:11px;font-weight:bold;">
	<td>Start Date:&nbsp;&nbsp;<input type="Text" name="startdate" id="startdate" size="15"><a href="javascript:show_calendar('frmempee.startdate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
			<td>End Date:&nbsp;&nbsp;<input type="Text" name="enddate" id="enddate" size="15"><a href="javascript:show_calendar('frmempee.enddate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
			<td><input type="Submit" value="GO"><br><br></td>
		</tr></table>
	</form>
<table style="font-family:Arial;font-size:12px" width="100%">
	<tr>
		<td>Name</td>
		<td>Date</td>
		<td>Shift</td>
		<td>Hours Duty</td>
		<td>Unapproved Overtime</td>
		<td>Undertime</td>
	</tr>
	<tr><td colspan="10"><hr></td></tr>
	<?php echo $data;?>
</table>