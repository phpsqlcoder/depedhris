<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include ("employeefunctions.php");
if($_GET['act']=='adnew'){
	$ins=mysql_query("insert into employeecod (`employeeId`, `dateOfIncident`, `details`, `typeOfOffense`) VALUES ('".$_GET['id']."','".$_POST['datehappen']."','".$_POST['details']."','".$_POST['cod']."')");
	header("location:employeecod.php?aa=saved&id=".$_GET['id']."");
}
if($_GET['aa']=='saved'){
	$msg='<font color="red" size="+1">Offense has been successfully saved!</font>';
}
if($_GET['act']=='editsave'){
	$upd=mysql_query("update employeecod set dateOfIncident='".$_POST['datehappen']."',`details`='".$_POST['details']."',`typeOfOffense`='".$_POST['cod']."' where ndex=".$_GET['codid']."");
	header("location:employeecod.php?aa=editsaved&id=".$_GET['id']."");
}
if($_GET['aa']=='editsaved'){
	$msg='<font color="red" size="+1">Offense has been successfully Updated!</font>';
}
$emp=mysql_fetch_object(mysql_query("select * from employee where ndex='".$_GET['id']."'"));
$sqry=mysql_query("SELECT * from employeecod where employeeId='".$_GET['id']."' ORDER BY dateOfIncident DESC");
while($r=mysql_fetch_object($sqry)){
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	$data.="<tr style='background-color:".$bgclr1s.";'>
				<td>".$r->dateOfIncident."</td>
				<td>".$r->typeOfOffense."</td>
				<td>".$r->details."</td>";
	if($_SESSION['ndex']=='19'){
		$data.="<td><a href='#' title='Edit Cod' onclick=\"window.location.href='employeecod.php?aa=edit&id=".$_GET['id']."&codid=".$r->ndex."'\";><img src=\"images/edit.png\" height='15' width='15'></a></td>";
	}
	$data.="</tr>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Product Composition</title>
	<script type="text/javascript">
	window.onkeyup = function (event) {
		if (event.keyCode == 27) {
			opener.location.reload();
			window.close ();
		}
	}
</script>
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
</head>
<body>
<?php if($_GET['aa']=='edit'){
$c=mysql_fetch_object(mysql_query("SELECT * from employeecod where ndex='".$_GET['codid']."'"));
?>
<table style="font-family:Arial;font-size:12px;" width="100%">
	<tr><td><?php echo $msg;?></td></tr>
	<tr><td align="center"><u><h1>Code of Discipline</h1></u></td></tr>	
</table>
<form name="frmcod" action="employeecod.php?act=editsave&id=<?php echo $_GET['id'];?>&codid=<?php echo $_GET['codid'];?>" method="post">
<table style="font-family:Arial;font-size:12px;">
	<tr><td colspan="2" style="color:maroon;"><strong><?php echo getID($emp->employmentStatus,$emp->employeeNo);?>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo $emp->lastName.",".$emp->firstName." ".$emp->middleName;?></strong></td></tr>
	<tr><td colspan="5"><hr></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td valign="top">
			<table style="font-family:Arial;font-size:12px;">			
				<tr><td><strong style="font-size:13px;">Type of Offense:</strong></td></tr>
				<tr><td><input type="Radio" name="cod" value="Minor" <?php if($c->typeOfOffense=='Minor'){echo 'checked';}?>>Minor Offense</td></tr>
				<tr><td><input type="Radio" name="cod" value="Major" <?php if($c->typeOfOffense=='Major'){echo 'checked';}?>>Major Offense</td></tr>
				<tr><td><input type="Radio" name="cod" value="Grave" <?php if($c->typeOfOffense=='Grave'){echo 'checked';}?>>Grave Offense</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td><strong style="font-size:13px;">Date of Incident:</strong></td></tr>
				<tr><td><input type="Text" name="datehappen" id="datehappen" size="15" value="<?php echo $c->dateOfIncident;?>"><a href="javascript:show_calendar('frmcod.datehappen');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
			</table>
		</td>
		<td valign="top">
			<table>
				<tr><td><strong style="font-size:13px;">Details</strong></td></tr>
				<tr><td><textarea cols="30" rows="8" name="details"><?php echo $c->details;?></textarea></td></tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="5" align="center"><input type="submit" value="SAVE"></td></tr>
</table>
</form>
<table style="font-family:Arial;font-size:12px;" width="100%">
	<tr style="color:blue;font-weight:bold;">
		<td>Date</td>
		<td>Type</td>
		<td>Details</td>
	</tr>
	<tr><td colspan="7"><hr></td></tr>
	<?php echo $data;?>
</table>


<?php } else {?>


<table style="font-family:Arial;font-size:12px;" width="100%">
	<tr><td><?php echo $msg;?></td></tr>
	<tr><td align="center"><u><h1>Code of Discipline</h1></u></td></tr>	
</table>
<form name="frmcod" action="employeecod.php?act=adnew&id=<?php echo $_GET['id'];?>" onsubmit="return confirm('Are you sure you want to submit this COD?');" method="post" <?php if($_SESSION['ndex']=='114') echo 'style="display:none;"' ?>>
<table style="font-family:Arial;font-size:12px;">
	<tr><td colspan="2" style="color:maroon;"><strong><?php echo getID($emp->employmentStatus,$emp->employeeNo);?>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo $emp->lastName.",".$emp->firstName." ".$emp->middleName;?></strong></td></tr>
	<tr><td colspan="5"><hr></td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td valign="top">
			<table style="font-family:Arial;font-size:12px;">			
				<tr><td><strong style="font-size:13px;">Type of Offense:</strong></td></tr>
				<tr><td><input type="Radio" name="cod" value="Minor">Minor Offense</td></tr>
				<tr><td><input type="Radio" name="cod" value="Major">Major Offense</td></tr>
				<tr><td><input type="Radio" name="cod" value="Grave">Grave Offense</td></tr>
<tr><td><input type="Radio" name="cod" value="Tardiness">Tardiness</td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td><strong style="font-size:13px;">Date of Memo:</strong></td></tr>
				<tr><td><input type="Text" name="datehappen" id="datehappen" size="15"><a href="javascript:show_calendar('frmcod.datehappen');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
			</table>
		</td>
		<td valign="top">
			<table>
				<tr><td><strong style="font-size:13px;">Details</strong></td></tr>
				<tr><td><textarea cols="30" rows="8" name="details"></textarea></td></tr>
			</table>
		</td>
	</tr>
	<tr><td colspan="5" align="center"><input type="submit" value="SAVE"></td></tr>
</table>
</form>
<table style="font-family:Arial;font-size:12px;" width="100%">
	<tr style="color:blue;font-weight:bold;">
		<td>Date</td>
		<td>Type</td>
		<td>Details</td>
	</tr>
	<tr><td colspan="7"><hr></td></tr>
	<?php echo $data;?>
</table>
<?php } ?>
</body>
</html>
<?php ob_end_flush();?>