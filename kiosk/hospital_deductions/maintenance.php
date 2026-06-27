<?php
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
if($_SESSION['deptId']!=0){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.";die();}
ob_start();
include("dbcon.php");
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
<div id="rcont">
  <h2>Maintenance</h2>
 <div id="icondock" class="grid_12">	
   <ul>
   	<li><a href="supplier.php"><img src="images/supplier.png" width="26" height="26" /><br>Employee<br>Class</a></li>
	<li><a href="division.php"><img src="images/category.png" width="26" height="26" /><br>Division</a></li>
	<li><a href="holiday.php"><img src="images/category.png" width="26" height="26" /><br>Holidays</a></li>
	<li><a href="dept.php"><img src="images/68.png" width="26" height="26" /><br>Department</a></li>
	<li><a href="unit.php"><img src="images/30.png" width="26" height="26" /><br>Unit</a></li>
	<li><a href="position.php"><img src="images/costcenter.png" width="26" height="26" /><br>Position</a></li>
	<li><a href="shifting.php"><img src="images/shiftingsked.png" width="26" height="26" /><br>Shifting<br>Schedules</a></li>
	<li><a href="leave.php"><img src="images/User_48.png" width="26" height="26" /><br>Employee<br>Leave</a></li>
	<li><a href="sapdept.php"><img src="images/30.png" width="26" height="26" /><br>SAP Dept</a></li>
       </ul>  
  <p>&nbsp;</p>
   </div> 
  <h2>&nbsp;</h2>		
    <?php include "footer.php";?>
    
  </div>


</body>
</html>


