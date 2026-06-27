<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include ("employeefunctions.php");
$shifting=mysql_query("SELECT 
		CASE timeIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeIn),'%H:%i %p') END AS tymIn, 
		CASE breakOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakOut),'%H:%i %p') END as brekOut,
		CASE breakIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakIn),'%H:%i %p') END as brekIn,
		CASE timeOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeOut),'%H:%i %p') END as tymOut,breakMinutes,ndex,name
		FROM shifting order by name");
		$optionshifting="<option value=''> - Select Shift -";
		while($rsshifting=mysql_fetch_object($shifting)){
		$ctr1s++;
		if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
			$sheft1.="<tr style='background-color:".$bgclr1s.";'>
						<td><strong style='color:maroon;'>".$rsshifting->name."&nbsp;&nbsp;</strong></td>
						<td>".$rsshifting->tymIn."</td>
						<td>".$rsshifting->brekOut."</td>
						<td>".$rsshifting->brekIn."</td>
						<td>".$rsshifting->tymOut."</td>
			</tr>";
		}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Product Composition</title>
	<script type="text/javascript">
	window.onkeyup = function (event) {
		if (event.keyCode == 27) {
			window.close ();
		}
		
	}
</script>
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
</head>
<body>
<table style="font-family:Arial;font-size:12px;" width="100%">
	<tr><td align="center"><h1>Shifting Table</h1></td></tr>	
</table>
<table style="font-family:Arial;font-size:12px;" width="100%">
	<?php echo $sheft1;?>
</table>
</body>
</html>
<?php ob_end_flush();?>