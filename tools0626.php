<?php
ob_start();
session_start();
	include("dbcon.php");
	include("scripts/scripts.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Tools</h2>   
    <div class="clearfix">
    	 	<?php if($_SESSION['ndex']==336) {

      	?>
      	<table width="100%">
      	<tr style="height:30px"><td><a href="tools_addtimelogs.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Add Time Logs</a></td></tr>
	<tr style="height:30px"><td><a href="tools_updatetimelogs.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Update Time Logs</a></td></tr>
	<tr style="height:30px"><td><a href="tools_viewdeletedlogs.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;View Deleted Time Logs</a></td></tr>
		<tr style="height:30px"><td><a href="tools_approvedrd.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Approve DRD</a></td></tr>
	<tr style="height:30px"><td><a href="tools_approveovertime2.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Approve Overtime</a></td></tr>
		<tr style="height:30px"><td><a href="tools_setshifting.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Set Shifting</a></td></tr>
		<tr style="height:30px"><td><a href="#"  onclick="window.open('tools_reportshiftingalldept.php','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=600')" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;View Shifting</a></td></tr>
	<tr style="height:30px"><td><a href="#"  onclick="window.open('tools_reportshifting.php','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=600')" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;View Employee per Shift</a></td></tr>
	<tr style="height:30px"><td><a href="tools_approveonlinehr.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Approve Online Applications</a></td></tr>
		<tr style="height:30px"><td><a href="tools_viewunapprovedshifting.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;View Unapprove Schedule</a></td></tr>
		<tr style="height:30px"><td><a href="tools_app_email.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Send Follow-up Email</a></td></tr>
</table>
	<?php } else { ?>

	<table width="100%">
  <tr>
  	<td style="width:50px;">&nbsp;</td>
    <td>
      <table style="color:maroon;font-weight:bold;font-size:12px;">
     
	  <tr style="height:30px;font-size:14px;"><td align="center"><u>TimeLogs Adjustment</u></td></tr>
	

	<tr style="height:30px"><td><a href="tools_dtrerror.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;DTR Errors</a></td></tr>
	<tr style="height:30px"><td><a href="tools_setshifting.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Set Shifting</a></td></tr>
		<tr style="height:30px"><td><a href="tools_approvedrd.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Approve DRD</a></td></tr>
	<tr style="height:30px"><td><a href="tools_approveovertime2.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Approve Overtime</a></td></tr>
	<tr style="height:30px"><td><a href="tools_setbdayleave.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Bday Leave (Batch file)</a></td></tr>
	
<?php 
if($_SESSION['ndex']!=15){
?>
	<tr style="height:30px"><td><a href="tools_addtimelogs.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Add Time Logs</a></td></tr>
	<tr style="height:30px"><td><a href="tools_updatetimelogs.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Update Time Logs</a></td></tr>
<tr style="height:30px"><td><a href="tools_ptsr_edit.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Update PTSR</a></td></tr>
<?php
}
?>
    <tr style="height:30px"><td><a href="tools_dumoylogs.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Process Dumoy Logs</a></td></tr>
	
	<tr style="height:30px"><td><a href="tools_viewdeletedlogs.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;View Deleted Time Logs</a></td></tr>
	<tr style="height:30px"><td><a href="tools_checkshifting.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Check Shifting</a></td></tr>
	<tr style="height:30px"><td><a href="tools_performance.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Employee Performance Record</a></td></tr>

	
	<tr style="height:30px"><td><a href="#"  onclick="window.open('tools_reportshiftingalldept.php','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=600')" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;View Shifting</a></td></tr>
	<tr style="height:30px"><td><a href="#"  onclick="window.open('tools_reportshifting.php','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=600')" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;View Employee per Shift</a></td></tr>
	<tr style="height:30px"><td><a href="tools_approveonlinehr.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Approve Online Applications</a></td></tr>
		<tr style="height:30px"><td><a href="tools_viewunapprovedshifting.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;View Unapprove Schedule</a></td></tr>
		<tr style="height:30px"><td><a href="tools_app_email.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Send Follow-up Email</a></td></tr>
		<tr style="height:30px"><td><a href="zk.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Download Lanang/Ecoland Timelogs</a></td></tr>
  </table>
	</td>
	<td>
		<table style="color:maroon;font-weight:bold;font-size:12px;">
		  	<tr style="height:30px;font-size:14px;"><td align="center"><u>Payroll</u></td></tr>
			<tr style="height:30px"><td><a href="tools_cutoff.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Payroll Cut-off</a></td></tr>
			<tr style="height:30px"><td><a href="tools_processtimelogs2.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Process TimeLogs</a></td></tr>
			<tr style="height:30px"><td><a href="tools_lockcutoff.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Lock CutOff</a></td></tr>
		</table>
	</td>
	<td>
		<table style="color:maroon;font-weight:bold;font-size:12px;">
		    <tr style="height:30px;font-size:14px;"><td align="center"><u>Maintenance</u></td></tr>
			<tr style="height:30px"><td><a href="tools_updateemployeenumber.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Update Employee Number</a></td></tr>
			<tr style="height:30px"><td><a href="tools_updateshifting.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Update Shifting Name</a></td></tr>
			<tr style="height:30px"><td><a href="tools_updatepis.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Update PIS</a></td></tr>
			<tr style="height:30px"><td><a href="tools_fixdtrerror.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Fix DTR Error</a></td></tr>
			<tr style="height:30px"><td><a href="tools_fixnoc2schedule.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Fix Noc2 Time summary</a></td></tr>
			<tr style="height:30px"><td><a href="checkshifting.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Check Shifting</a></td></tr>
			<tr style="height:30px"><td><a href="tools_setleave.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Set Employee Leave Limit</a></td></tr>
			<tr style="height:30px"><td><a href="tools_setmaker.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Set Employee Maker/Approver</a></td></tr>
			<tr style="height:30px"><td><a href="tools_setcutoff.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Set Cut Off</a></td></tr>
			<tr style="height:30px"><td><a href="tools_setmarketing.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Set Marketing Scheduler/Approver</a></td></tr>
			<tr style="height:30px"><td><a href="kiosknewslide.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Manage Evoice Advertisements</a></td></tr>
<tr style="height:30px"><td><a href="kiosknew.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Manage Evoice Memorandum</a></td></tr>
<?php if($_SESSION['ndex']==12 || $_SESSION['ndex']==14 || $_SESSION['ndex']==22){
?>
	<tr style="height:30px"><td><a href="bir.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;BIR 2316 Generator</a></td></tr>
	
<?php
}
?>
    	 </table>
	</td>
  </tr>
  
</table> 
<?php } ?>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>


