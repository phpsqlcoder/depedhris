<?php
ob_start();
session_start();
//echo $_SESSION['deptId'];

if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}

if($_SESSION['deptId']!=0){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.";die();}
	include("../dbcon.php");
	include("scripts/scripts.php");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="../css/styles.css" rel="stylesheet" type="text/css" />
    <link href="../css/facebox.css" rel="stylesheet" type="text/css" />
</head>
<body> 	
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Welcome</h2>
    
    <div class="clearfix">
	All notifications here..
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>


