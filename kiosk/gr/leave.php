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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Davao Doctors Hospital</title>
	<script type="text/javascript" src="../scripts/shortcuts.js"></script>
	<script>
		shortcut.add("F12",function() {			
			window.location.href='menu.php';
		});
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

<body bgcolor="#74FE18" bottommargin="0" topmargin="0" rightmargin="0" leftmargin="0" onload="document.getElementById('txtid').focus();">
<table border="0" width="100%">
	<tr><td>&nbsp;</td></tr>
	<tr><td align="center"><img src="../images/kiosk.png" width="600" height="200"></td></tr>
	<tr><td align="center"><img src="../images/kioskbuttons/menu.png" width="150" height="50" onclick="window.location.href='menu.php';"></td></tr>
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


</body>
</html>
