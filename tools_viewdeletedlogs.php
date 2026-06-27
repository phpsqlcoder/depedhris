<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
	include ("myfunctions.php");
if($_GET['ser']){
	$o=mysql_query("SELECT * from employee where lastName like '%".$_GET['stxt']."%' or firstName like '%".$_GET['stxt']."%'");
	while($pp=mysql_fetch_object($o)){
		$da.="<a href='#' onclick=\"window.location.href='tools_viewdeletedlogs.php?emp=".$pp->ndex."&startDate=".$_GET['startDate']."&endDate=".$_GET['endDate']."'\"><font color='blue'>".$pp->lastName.", ".$pp->firstName."<br></font></a>";
	}
	echo $da;
}
else{

if(isset($_GET['act'])){
	$r=mysql_fetch_array(mysql_query("select * from hrinterface_deleted where ndex='".$_GET['id']."'"));
	$restore=mysql_query("insert into hrinterface (dtrid,datelog,log,in_out)values('".$r['dtrid']."','".$r['datelog']."',
'".$r['log']."','".$r['in_out']."')");
	//header("location:tools_viewdeletedlogs.php?");
}
if($_GET['emp']){
	$r=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_GET['emp'].""));
	$delet=mysql_query("select * from hrinterface_deleted where dtrid='".$r->biometricNo."' and datelog>='".$_GET['startDate']."' and datelog<='".$_GET['endDate']."'");
	while($d=mysql_fetch_object($delet)){
		if($d->in_out==0){$rema='IN';}else{$rema='OUT';}
		$data.="<tr style='font-size:12px;background-color:".$bgclr1s."'>
					<td>".$d->log."</td>
					<td>".$rema."</td>
					<td>".$d->user."</td>
					<td>".$d->dateDeleted."</td>
					<td><a href='tools_viewdeletedlogs.php?act=restore&id=".$d->ndex."&emp=".$_GET['emp']."&startDate=".$_GET['startDate']."&endDate=".$_GET['endDate']."'>restore</a></td>
		</tr>";
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
<script type="text/javascript">
	function searchitems(){
	//alert();
		mynodes=frmitem.serialize();	
		new Ajax.Updater('listitem','tools_viewdeletedlogs.php?ser=searching',{
			method: 'get',
			parameters: mynodes
		});	
	}
    </script>
    
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>View Deleted Logs</h2>   
    <div class="clearfix">
		<form name="frmitem" id="frmitem">
<table>
	<tr>
		<td>Start Date:<input type="Text" name="startDate" id="startDate" size="15" value="<?php echo $_GET['startDate'];?>"><a href="javascript:show_calendar('frmitem.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		<td>End Date:<input type="Text" name="endDate" id="endDate" size="15" value="<?php echo $_GET['endDate'];?>"><a href="javascript:show_calendar('frmitem.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
	</tr>
	<tr>
		
		<td colspan="2">Search: <input type="text" name="stxt" id="stxt" onkeyup="searchitems();">&nbsp;&nbsp;<font color="#ff0000"><i>Enter any part of last or first name.</i></font></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan=5><div id="listitem"></div></td>
				</tr>
	<tr valign="top"><td>&nbsp;</td></tr>
</table>
		</form>
		<?php if($_GET['emp']){?>
			<table width="100%">
			<tr><td>&nbsp;</td></tr>
			<tr style='color:maroon;font-weight:bold;'>
				<td colspan="3">Name: <?php echo $r->lastName.", ".$r->firstName;?><input type="Hidden" name="deptselect" value="<?php echo $d->ndex;?>"></td>
				<td><br><br></td>
			</tr>
			<tr style="color:red;font-size:14px;font-weight:bold;"><td colspan='3'><?php echo $msg;?></td></tr>
			<tr style="color:blue;font-size:12px;font-weight:bold;">
				<td>Log</td>
				<td>Type</td>
					<td>User</td>
					<td>Date Deleted</td>
					<td>Restore Log</td>
				
			</tr>
			<tr><td colspan='6'><hr></td></tr>
		
			<?php echo $data;?>

		</table>
		<?php }?>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>
<?php }?>