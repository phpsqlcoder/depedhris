<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
if($_GET['act']=='go'){
$interface=mysql_query("select * from hrinterface where dtrid<>'' and datelog>='".$_POST['startdate']."' and datelog<='".$_POST['enddate']."' and in_out=0 ORDER BY dtrid,log");
	while($r=mysql_fetch_object($interface)){
	$e=mysql_fetch_object(mysql_query("select e.*,d.name as dep from employee e left join dept d on d.ndex=e.deptId where e.biometricNo='".$r->dtrid."'"));	
		if($e->ndex){
		$a++;
		$shiftsked=mysql_fetch_object(mysql_query("select * from employee_shifting  where employeeId='".$e->ndex."' and approvedDate<>'0000-00-00 00:00:00' and '".$r->datelog."' between startDate and endDate"));
			if(!$shiftsked->ndex){
					$data.="<tr>
								
								<td>".$e->lastName.",&nbsp;".$e->firstName."</td>
								<td>".$e->dep."</td>
								<td>".$r->datelog."</td>
							</tr>";
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
    <div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Check Shifting</h2>   
    <div class="clearfix">
	<form name="frmempee" action="tools_checkshifting.php?act=go" method="post">
	<table width="100%" style="font-size:11px;">			
			<tr style="color:blue;font-size:11px;font-weight:bold;">
			<td>Start Date:&nbsp;&nbsp;<input type="Text" name="startdate" id="startdate" size="15"><a href="javascript:show_calendar('frmempee.startdate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
			<td>End Date:&nbsp;&nbsp;<input type="Text" name="enddate" id="enddate" size="15"><a href="javascript:show_calendar('frmempee.enddate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
			<td><input type="Submit" value="GO"></td>
		</tr></table>
	</form>
	<?php if($_GET['act']=='go'){ ?>
	<table width="100%" style="font-size:11px;">	
		<tr>
			<td>Name</td>
			<td>Dept</td>
			<td>Date</td>
		</tr>
		<tr><td colspan="5"><hr></td></tr>
		<?php echo $data;?>
	</table>
	<?php } ?>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
