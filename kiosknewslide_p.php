<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include("scripts/scripts.php");
include ("employeefunctions.php");
include ("myfunctions.php");


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Employee Logs</title>
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
<script>
function changeurl(a,b){
	window.location.href='employeelogs.php?id='+a+'&mants='+b;
}
</script>
</head>
<body>
<?php
$des=mysql_fetch_array(mysql_query("select * From kiosk_slide where id='".$_GET['id']."'"));
	?>
<table style="font-family:Arial;font-size:14px;" width="100%">
	<tr><td align="center"><?php echo $des['Descriptioned'] ?></td></tr>
</table>
<br><br>
<table style="font-family:Arial;font-size:12px;" width="100%">
	<tr><td style="font-weight:bold;">#</td>
<td style="font-weight:bold;">Name</td>
<td style="font-weight:bold;">Dept</td>
<td style="font-weight:bold;">Registration Date</td></tr>
	<?php 
$x=0;
	$q=mysql_query("select distinct x.empid,(select regDate from kiosk_registered d where d.empid=x.empid and d.slideId='".$_GET['id']."' limit 1) as regDate from kiosk_registered x where x.slideId='".$_GET['id']."'");
while($r=mysql_fetch_array($q)){
$x++;
$e=mysql_fetch_array(mysql_query("select e.*,d.name as dept from employee e left join dept d on d.ndex=e.deptId where e.ndex='".$r['empid']."'"));
	echo '<tr>
		<td>'.$x.'</td>
		<td>'.$e['lastName'].', '.$e['firstName'].' '.$e['middleName'].' </td>
		<td>'.$e['dept'].'</td>
		<td>'.$r['regDate'].'</td>
		</tr>';
}
?>
</table>


</body>
</html>
<?php ob_end_flush();//alert(document.getElementById('sel369078').value?>