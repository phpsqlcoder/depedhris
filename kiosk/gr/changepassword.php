<?php
ob_start();
session_start();
if(!$_SESSION['ndex']){header("location:index.php");}
	include("../dbcon.php");
	include("../employeefunctions.php");
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
	</script>
</head>

<body bgcolor="#74FE18" bottommargin="0" topmargin="0" rightmargin="0" leftmargin="0" onload="document.getElementById('oldpword').focus();">
<table border="0" width="100%">
	<tr><td>&nbsp;</td></tr>
	<tr><td align="center"><img src="../images/kiosk.png" width="600" height="200"></td></tr>
	<tr><td align="center"><img src="../images/kioskbuttons/menu.png" width="150" height="50" onclick="window.location.href='menu.php';"></td></tr>
	<tr><td height="50" align="center">&nbsp;<font style="color:red;font-size:40px;font-weight:bold;"><?php echo $msg;?></font></td></tr>
	<tr>
		<td align="center">
			<div style=" -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;width:500px;background-color:#FFFC17;">
				<table style="font-family:Arial Rounded MT Bold;font-size:35px;color:maroon;">
					<tr><td>CHANGE PASSWORD</td></tr>
					<tr><td>&nbsp;</td></tr>
				</table>
				<table style="font-weight:bold;font-size:18px;color:brown;">
					<tr><td>Old Password:</td><td><input type="password" id="oldpword" name="oldpword" style="font-size:20px;"></td></tr>
					<tr><td>New Password:</td><td><input type="password" name="newpword" style="font-size:20px;"></td></tr>
					<tr><td>Confirm Password:</td><td><input type="password" name="confirmpword" style="font-size:20px;"></td></tr>
					<tr><td colspan="2" align="center"><input type="submit" value="CHANGE" style="font-size:20px;"></td></tr>
				</table>
			</div>
		</td>
	</tr>
	<tr><td height="100" colspan="2">&nbsp;</td></tr>
	
</table>


</body>
</html>
