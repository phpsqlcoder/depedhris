<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include("scripts/scripts.php");
include ("employeefunctions.php");
include ("myfunctions.php");
//echo $cutoffDate;



	
if ($_GET['pageact'] == "runCutoff"){
	mysql_select_db($_POST['bkfolder'],$conn);
	//echo "select * from hrinterface where dtrid='".$_POST['emp']."' and log>='".$_POST['startdates']."' and log<='".$_POST['enddates']."'";
	$h=mysql_Query("select * from hrinterface where dtrid='".$_POST['emp']."' and log>='".$_POST['startdates']." 00:00 AM' and log<='".$_POST['enddates']." 59:59 PM'");
	while($r=mysql_fetch_object($h)){
		//echo $r->log."<br>";
		mysql_select_db('hris',$conn);
		/*$u=mysql_query("select * from hrinterface where dtrid='".$_POST['emp']."' and log>='".$_POST['startdates']." 00:00 AM' and log<='".$_POST['enddates']." 59:59 PM'");
		while($q=mysql_fetch_object($u)){*/
			$upd=mysql_query("update hrinterface set `log`='".$r->log."' where hrint_id='".$r->hrint_id."' and dtrid='".$r->dtrid."'");
//echo "update hrinterface set `log`='".$r->log."' where hrint_id='".$r->hrint_id."' and dtrid='".$r->dtrid."'<br>";
		//}
	}
	$msg="Successfully Restored timelogs!";
}

$rs = mysql_query("SELECT * FROM employee where isActive=1",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['biometricNo']."'>".$dt['lastName']." ".$dt['firstName']." ".$dt['middleName']."</option>";
}
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
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Restore Logs</h2>   
    <div class="clearfix">
	<table>
	<tr><td style="font-size:15px;color:red;" colspan="center"><?php echo $msg;?></td></tr>
		<tr>
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=runCutoff" margin="0px;" name="myForm">
				<tr><td>Backup Folder:</td><td><input type="Text" name="bkfolder"></td></tr>
				<tr><td>Employee:</td><td><select name="emp"><?php echo $optionSelectPayrollCutoffDate;?></select></td></tr><tr>
				 <td>Start Date:</td><td><input type="Text" name="startdates" id="startdates" size="15"><a href="javascript:show_calendar('myForm.startdates');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
	</tr> <tr><td>End Date:</td><td><input type="Text" name="enddates" id="enddates" size="15"><a href="javascript:show_calendar('myForm.enddates');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
<tr>				<td><button>Restore Logs</button></td></tr>
		</tr>
	</table>
			</form>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
