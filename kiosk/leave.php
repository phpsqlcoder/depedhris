<?php
ob_start();
session_start();
if(!$_SESSION['ndex']){header("location:index.php");}
	include("../dbcon.php");
	include("../employeefunctions.php");
if($_POST['startDate']){
	echo "wee";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>HRIS - Davao Doctors Hospital</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/shortcuts.js"></script>
	<script>
		function chksubmit(){
			startdate=document.getElementById('startDate').value;
			enddate=document.getElementById('endDate').value;
			if(startdate==''){
				alert('Please input correct value on start date. ex. 2012-12-31');
				return false;
			}
			if(enddate==''){
				alert('Please input correct value on end date. ex. 2012-12-31');
				return false;
			}
		}
		</script>
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
	<div id="AE">
      <h2><a href="leave.php">Apply Leave</a> </h2>
      <a href="leave.php">&nbsp;</a> </div>
	<div id="AF">
      <h2><a href="editemployee.php">Update 201</a> </h2>
      <a href="editemployee.php">&nbsp;</a> </div>
 </div>
</div>
<div id="bodyPan">
	<table width="100%">
		<tr><td align="center" style="color:maroon;"><h1>Leave Application</h1></td></tr>
		<tr><td height="20" align="center">&nbsp;<font style="color:red;font-size:40px;font-weight:bold;"><?php echo $msg;?></font></td></tr>
	<tr>
		<td align="center">
			<div style=" -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;width:500px;background-color:#FFFC17;">
				<form action="leave.php" method="post" name="frmleave" id="frmleave" onsubmit="return chksubmit();">
				<table style="font-family:Arial Rounded MT Bold;font-size:12px;color:maroon;">
					<tr><td><strong>Start Date:</strong>&nbsp;&nbsp;</td><td><input type="Text" name="startDate" id="startDate" size="15"><a href="javascript:show_calendar('frmleave.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
					</tr>
					<tr><td><strong>End Date:</strong>&nbsp;&nbsp;</td><td><input type="Text" name="endDate" id="endDate" size="15"><a href="javascript:show_calendar('frmleave.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
					<tr>
						<td>Type of leave:</td><td><select><?php echo $optionleave;?></select></td>
					</tr>
					<tr><td colspan="2" align="center"><input type="submit" value="APPLY"></td></tr>
				</table>
				</form>
			</div>
		</td>
	</tr>
	<tr><td height="100" colspan="2">&nbsp;</td></tr>
	
	</table>
</div>
</body>
</html>
