<?php
ob_start();
session_start();
if(!$_SESSION['kiosk_hris']){
	if($last_url!='index.php'){
  header("location:login.php");
}
}
	include("../dbcon.php");
	include("../employeefunctions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>HRIS - Davao Doctors Hospital</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style.css" rel="stylesheet" type="text/css" />
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
	<div id="AF">
      <h2><a href="editemployee.php">Update 201</a> </h2>
      <a href="editemployee.php">&nbsp;</a> </div>
 </div>
</div>
<div id="bodyPan">
<?php
	if($_GET['act']=='change'){
		$ch=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_SESSION['ndex']." and (password='".$_POST['oldpword']."' OR password='".substr($_POST['oldpword'],2)."')"));
		if($ch->ndex){
			if($_POST['newpword']==$_POST['confirmpword']){
				$upd=mysql_query("update employee set password='".$_POST['newpword']."' where ndex=".$_SESSION['ndex']."");
				$msg="You have successfully changed your password!";
			}
			else{
				$msg="The new password and confirmation password did match!";
			}
		}
		else{
			$msg="The old password that you entered did not match to your current password!";
		}
	}
?>
<form action="changepassword.php?act=change" method="post">
	<table width="100%">
		<tr><td align="center" style="color:maroon;"><h1>Change Password</h1></td></tr>
		<tr>
		<tr><td>&nbsp;</td></tr>
		<td align="center">
			<div style=" -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;width:500px;background-color:#FFFC17;">
				<table style="font-weight:bold;font-size:18px;color:brown;">
					<tr><td>Old Password:</td><td><input type="password" id="oldpword" name="oldpword" style="font-size:20px;"></td></tr>
					<tr><td>New Password:</td><td><input type="password" name="newpword" style="font-size:20px;"></td></tr>
					<tr><td>Confirm Password:</td><td><input type="password" name="confirmpword" style="font-size:20px;"></td></tr>
					<tr><td colspan="2" align="center"><input type="submit" value="CHANGE" style="font-size:20px;"></td></tr>
				</table>
			</div>
		</td>
	</table>
	<table width=100%>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr align="center" style="color:red;font-size:18px;font-weight:bold;"><td><?php echo $msg;?></td></tr>
	</table>
</form>
</div>
</body>
</html>
