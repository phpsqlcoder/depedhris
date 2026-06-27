<?php
ob_start();
session_start();
if(!$_SESSION['ndex']){header("location:index.php");}
	include("../dbcon.php");
	include("../employeefunctions.php");
if($_POST['startDate']){	
	$emp=mysql_fetch_object(mysql_query("select * from employee where ndex='".$_SESSION['ndex']."'"));
	$sqry=mysql_query("select employeeId,date,
			CASE timeIn WHEN '00:00:00' THEN '-' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeIn),'%H:%i %p') END AS tymIn, 
			CASE breakOut WHEN '00:00:00' THEN '-' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakOut),'%H:%i %p') END as brekOut,
			CASE breakIn WHEN '00:00:00' THEN '-' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakIn),'%H:%i %p') END as brekIn,
			CASE timeOut WHEN '00:00:00' THEN '-' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeOut),'%H:%i %p') END as tymOut
	from timelogs where employeeId='".$_SESSION['ndex']."' ORDER BY date");
	while($r=mysql_fetch_object($sqry)){
		$ctr1s++;
		if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		$data.="<tr style='background-color:".$bgclr1s.";font-size:15px;'>
					<td>".$r->date."</td>
					<td>".$r->tymIn."</td>
					<td>".$r->brekOut."</td>
					<td>".$r->brekIn."</td>
					<td>".$r->tymOut."</td>
					
		</tr>";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Davao Doctors Hospital</title>
	<script type="text/javascript" src="../scripts/shortcuts.js"></script>
	<script>
		shortcut.add("F12",function() {			
			window.location.href='menu.php';
		});
	</script>
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
</head>

<body bgcolor="#74FE18" bottommargin="0" topmargin="0" rightmargin="0" leftmargin="0" onload="document.getElementById('txtid').focus();">
<table border="0" width="100%">
	<tr><td>&nbsp;</td></tr>
	<tr><td align="center"><img src="../images/kiosk.png" width="400" height="120"></td></tr>
	<tr><td align="center"><img src="../images/kioskbuttons/menu.png" width="150" height="50" onclick="window.location.href='menu.php';"></td></tr>
	<tr><td height="20" align="center">&nbsp;<font style="color:red;font-size:40px;font-weight:bold;"><?php echo $msg;?></font></td></tr>
	<tr>
		<td align="center">
			<div style=" -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;width:800px;background-color:#FFFC17;">
				<table style="font-family:Arial Rounded MT Bold;font-size:25px;color:maroon;">
					<tr><td>DTR</td></tr>
					
				</table>
				<form name="frm" id="frm" action="dtr.php" method="post">
				<table style="font-family:Arial;color:blue;">
					<tr><td><strong>Start Date:</strong>&nbsp;&nbsp;</td><td><input type="Text" name="startDate" id="startDate" size="15"><a href="javascript:show_calendar('frm.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
					<td><strong>End Date:</strong>&nbsp;&nbsp;</td><td><input type="Text" name="endDate" id="endDate" size="15"><a href="javascript:show_calendar('frm.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
					<td align="center" colspan="2"><input type="submit" value="GO"></td></tr>
	
				</table>
				</form>
				<?php
				if($_POST['startDate']){?>
				<table style="font-family:Arial;font-size:12px;" width="100%">
					<tr style="color:maroon;font-weight:bold;">
						<td>Date</td>
						<td>TimeIn</td>
						<td>Break Out</td>
						<td>Break In</td>
						<td>Time Out</td>
						
					</tr>
					<tr><td colspan="5"><hr></td></tr>
					<?php echo $data;?>
				</table>
				<?php } ?>
			</div>
		</td>
	</tr>
	<tr><td height="100" colspan="2">&nbsp;</td></tr>
	
</table>


</body>
</html>
