<?php
ob_start();
session_start();
if(!$_SESSION['ndex']){header("location:index.php");}
	include("../dbcon.php");
	include("../employeefunctions.php");
	$e=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_SESSION['ndex'].""));
	if($e->picture){
		$img="<img src='picture/".$e->picture."' height='60' width='30'>";
	}
	else{
		$img="";
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>HRIS - Davao Doctors Hospital</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../scripts/shortcuts.js"></script>
	<script>/*
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
		});*/
	</script>
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
	<table width="100%">
		<tr><td align="center" style="color:maroon;"><h1><font color="black"><br>Welcome </font><?php echo $e->firstName." ".$e->lastName;?></h1></td></tr>
		<tr><td height="50" align="center">&nbsp;<font style="color:red;font-size:40px;font-weight:bold;"><?php echo $img;?></font></td></tr>
	<tr><td height="100" colspan="2">&nbsp;</td></tr>
	</table>
</div>
</body>
</html>
