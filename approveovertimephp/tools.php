<?php
ob_start();
session_start();
if(!$_SESSION['ndex']){header("location:login.php");}
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
	<table width="100%">
  <tr>
  	<td style="width:50px;">&nbsp;</td>
    <td>
      <table style="color:maroon;font-weight:bold;font-size:12px;">
	<tr style="height:30px"><td><a href="tools_updateemployeenumber.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Update Employee Number</a></td></tr>
	<tr style="height:30px"><td><a href="tools_updateshifting.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Update Shifting Name</a></td></tr>
	<tr style="height:30px"><td><a href="tools_processtimelogs.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Process TimeLogs</a></td></tr>
	<tr style="height:30px"><td><a href="tools_dtrerror.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;DTR Errors</a></td></tr>
	<tr style="height:30px"><td><a href="tools_setshifting.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Set Shifting</a></td></tr>
	<tr style="height:30px"><td><a href="tools_approveovertime.php" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Approve Overtime</a></td></tr>
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


