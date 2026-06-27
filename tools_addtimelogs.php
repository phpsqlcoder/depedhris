<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
if($_SESSION['ndex']!=12 && $_SESSION['ndex']!=15 && $_SESSION['ndex']!=16 && $_SESSION['ndex']!=17 && $_SESSION['ndex']!=14 && $_SESSION['ndex']!=21 && $_SESSION['ndex']!=22 && $_SESSION['ndex']!=336 && $_SESSION['ndex']!=273) {  echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
	include ("myfunctions.php");
if($_GET['ser']){
	$o=mysql_query("SELECT * from employee where isActive=1 and (lastName like '%".$_GET['stxt']."%' or firstName like '%".$_GET['stxt']."%')");
	while($pp=mysql_fetch_object($o)){
		$d=mysql_fetch_object(mysql_query("select * from dept where ndex=".$pp->deptId.""));
		$da.="<a href='#' onclick=\"window.location.href='tools_addtimelogs.php?emp=".$pp->ndex."'\"><font color='blue'>".getID($pp->employmentStatus,$pp->employeeNo)." - ".$pp->lastName.", ".$pp->firstName." - ".$d->name."<br></font></a>";
	}
	echo $da;
}
else{

if($_GET['act']=='sabmet'){
	$re=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_GET['emp'].""));
	$chk=0;
	for($b=1;$b<=20;$b++){
		if(strlen($_POST['inp'.$b])!=8){
			$chk++;
		}
	}
	if($chk>0){
		echo "Invalid time Format! Press back to continue";
		die();
	}
	for($a=1;$a<=20;$a++){
		if($_POST['dated'.$a]!=''){
			$upd=mysql_query("insert into hrinterface (`dtrid`, `datelog`, `log`, `in_out`)
			VALUES('".$re->biometricNo."','".$_POST['dated'.$a]."','".$_POST['dated'.$a]." ".$_POST['inp'.$a]."','".$_POST['sel'.$a]."')");
			/*echo "insert into hrinterface hrinterface(`dtrid`, `datelog`, `log`, `in_out`,`isProcessed`)
			VALUES('".$re->biometricNo."','".$_POST['dated'.$a]."','".$_POST['dated'.$a]." ".$_POST['inp'.$a]."','".$_POST['sel'.$a]."',0)";*/
		}
	}
	header("Location:tools_addtimelogs.php?emp=".$_GET['emp']."");
	$msg="Successfully Updated Time Logs!";
}
if($_GET['emp']){
	$r=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_GET['emp'].""));
	for($a=1;$a<=20;$a++){
		//$ee=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid=".$r->biometricId." and date='".$det."'"));
		$ctr1s++;
		if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		$data.="<tr style='font-size:12px;background-color:".$bgclr1s."'>			
					<td>Date:<input type='Text' name='dated".$a."' id='dated".$a."' size='15'><a href=\"javascript:show_calendar('frmemp.dated".$a."');\" onMouseOver=\"window.status='Date Picker'; overlib(''); return true;\" onMouseOut=\"window.status=''; nd(); return true;\"><img src='b_calendar.png' width='19' border='0'></a></td>
					<td><input type='text' name='inp".$a."' value='00:00:00' style='text-align:right;' onFocus=\"this.value='';\" onClick=\"this.value='';\"><font style='color:red;text-style:italic;font-size:11px;'>Use Military Time</font></td>	
					<td><select name='sel".$a."'>
							<option value='0'>Time In
							<option value='1'>Break Out
							<option value='0'>Break In
							<option value='1'>Time Out
					</select></td>
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
		new Ajax.Updater('listitem','tools_addtimelogs.php?ser=searching',{
			method: 'get',
			parameters: mynodes
		});	
	}
    </script>
    
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Update Time Logs</h2>   
    <div class="clearfix">
		<form name="frmitem" id="frmitem">
<table>

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
	<form name="frmemp" action="tools_addtimelogs.php?act=sabmet&startDate=<?php echo $_GET['startDate'];?>&endDate=<?php echo $_GET['endDate'];?>&emp=<?php echo $_GET['emp'];?>" method="post">
		<table width="70%">
			<tr><td>&nbsp;</td></tr>
			<tr style='color:maroon;font-weight:bold;'>
				<td>Name: <?php echo $r->lastName.", ".$r->firstName;?><input type="Hidden" name="deptselect" value="<?php echo $d->ndex;?>"></td>
				<td><br><br></td>
			</tr>
			<tr style="color:red;font-size:14px;font-weight:bold;"><td colspan='3'><?php echo $msg;?></td></tr>
			<tr style="color:blue;font-size:12px;font-weight:bold;">
				<td>Date</td>
				<td>Time</td>
				<td>Type</td>
			</tr>
			<tr><td colspan='6'><hr></td></tr>
			<tr><td colspan="6" align="right"><input type="Submit" value="Update"></td></tr>
			<?php echo $data;?>
			<tr><td colspan="6" align="right"><input type="Submit" value="Update"></td></tr>
		</table>
	</form>
	<?php }?>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>
<?php }?>