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
		shortcut.add("F1",function() {
			window.location.href='logout.php';
		});
		shortcut.add("F2",function() {
			window.location.href='changepassword.php';
		});
		shortcut.add("F3",function() {
			window.location.href='dtr.php';
		});
		shortcut.add("F4",function() {
			window.location.href='payslip.php';
		});
		shortcut.add("F5",function() {
			window.location.href='leave.php';
		});
		shortcut.add("F6",function() {			
			window.location.href='comments.php';
		});
	</script>
</head>

<body bgcolor="#74FE18" bottommargin="0" topmargin="0" rightmargin="0" leftmargin="0" onload="document.getElementById('txtid').focus();">
<table border="0" width="100%">
	<tr><td>&nbsp;</td></tr>
	<tr><td align="center"><img src="../images/kiosk.png" width="600" height="200"></td></tr>
	<tr><td height="50" align="center">&nbsp;<font style="color:red;font-size:40px;font-weight:bold;"><?php echo $msg;?></font></td></tr>
	<tr>
		<td align="center" width="100%">
				<table style="font-family:Arial Rounded MT Bold;font-size:35px;color:maroon;" width="90%">
					<tr>
						<td><img src="../images/kioskbuttons/logout.png" width="300" height="100" onclick="window.location.href='logout.php';"></td>
						<td><img src="../images/kioskbuttons/changepassword.png" width="300" height="100" onclick="window.location.href='changepassword.php';"></td>
						<td><img src="../images/kioskbuttons/dtr.png" width="300" height="100" onclick="window.location.href='dtr.php';"></td>
					</tr>
					<tr>
						<td><img src="../images/kioskbuttons/payslip.png" width="300" height="100" onclick="window.location.href='payslip.php';"></td>
						<td><img src="../images/kioskbuttons/leave.png" width="300" height="100" onclick="window.location.href='leave.php';"></td>
						<td><img src="../images/kioskbuttons/comments.png" width="300" height="100" onclick="window.location.href='comments.php';"></td>
					</tr>
				</table>
		</td>
	</tr>
	<tr><td height="100" colspan="2">&nbsp;</td></tr>
	
</table>


</body>
</html>
