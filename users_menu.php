<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
if($_SESSION['deptId']!=0){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.";die();}
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
     <h2>User Management</h2>   
    <div class="clearfix">
    	

	<table width="100%">
  <tr>
  	<td style="width:50px;">&nbsp;</td>
    <td>
      <table style="color:maroon;font-weight:bold;font-size:12px;">
     
	  <tr style="height:30px;font-size:14px;"><td align="center"><u>Users</u></td></tr>
	

	<tr style="height:30px"><td><a href="users.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;HR - Admin</a></td></tr>
	<tr style="height:30px"><td><a href="users_dept.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Dept Approvers</a></td></tr>
		<tr style="height:30px"><td><a href="users_kiosk.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Approvers</a></td></tr>
	</table>
</td>
<td>
      <table style="color:maroon;font-weight:bold;font-size:12px;">
     
	  <tr style="height:30px;font-size:14px;"><td align="center"><u>Approver Templates</u></td></tr>
	<tr style="height:30px"><td><a href="template_approval.php?id=1" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Clearance</a></td></tr>
	<tr style="height:30px"><td><a href="template_approval.php?id=2" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Certificate of Employment</a></td></tr>
		<tr style="height:30px"><td><a href="template_approval.php?id=3" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Resignation / Retirement</a></td></tr>
	<tr style="height:30px"><td><a href="template_roles.php?id=1" style="text-decoration:none;display:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Approver Roles</a></td></tr>
	
	</table>
</td>
</tr>
</table>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>


