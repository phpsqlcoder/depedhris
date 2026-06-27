<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");

$qr="";
if($_POST['startdate']){$qr.=" and d.date>='".$_POST['startdate']."'";}
if($_POST['enddate']){$qr.=" and d.date<='".$_POST['enddate']."'";}
$emp=mysql_query("select e.lastName,e.firstName,e.middleName,d.* from dailytimesummary d left join employee e on e.ndex=d.employeeId where d.isError>0 and d.isError<100 ".$qr." order by e.lastName,e.firstName,e.middleName,d.date");
$var=0;
while($r=mysql_fetch_object($emp)){
$var++;
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	if($r->isError==1){$er='Duplicate Logs';}
	elseif($r->isError==2){$er='No TimeOut';}
	elseif($r->isError==3){$er='No Shifting';}
	$d.="<tr style='font-size:12px;background-color:".$bgclr1s."'>
			<td>".$var."</td>
			<td>".$r->lastName.",".$r->firstName."&nbsp;".$r->middleName."</td>
			<td>".$r->date."</td>
			<td>".$er."</td>
	</tr>";
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
     <h2>DTR Error</h2>   
    <div class="clearfix">
	<form name="frmemp" action="tools_dtrerror.php?act=sabmet" method="post">
		<table width="800">
			<tr>
				<td><input type="Text" name="startdate" id="startdate" size="15"><a href="javascript:show_calendar('frmemp.startdate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
				<td><input type="Text" name="enddate" id="enddate" size="15"><a href="javascript:show_calendar('frmemp.enddate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
				<td><input type="Submit" value="Search"></td></tr>
			<tr style="color:blue;font-size:12px;font-weight:bold;">
				<td>Seq#</td>
				<td>Name</td>
				<td>Date</td>
				<td>Error</td>
			</tr>
			<tr><td colspan='6'><hr></td></tr>
			
			<?php echo $d;?>
		</table>
	</form>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
