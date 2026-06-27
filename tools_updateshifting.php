<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");

if($_GET['act']=='sabmet'){
	$e=mysql_query("select * from shifting order by ndex");
	while($rs=mysql_fetch_object($e)){
		if ($_POST['ck'.$rs->ndex]=='on'){$chk2="1";} else { $chk2="0";}
		$upd=mysql_query("update shifting set name='".$_POST['shift'.$rs->ndex]."',status=".$chk2."
				 where ndex=".$rs->ndex."");
		$msg="Successfully Update Shifting name!";
	}
}
$emp=mysql_query("select * from shifting order by ndex");
$var=0;
while($r=mysql_fetch_object($emp)){
$var++;
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	if ($r->status==1){$chk="checked='checked'";} else { $chk="";}
	$d.="<tr style='font-size:12px;background-color:".$bgclr1s."'>
			<td>".$var."</td>
			<td>".$r->timeIn."&nbsp;&nbsp;&nbsp;".$r->breakOut."&nbsp;&nbsp;&nbsp;".$r->breakIn."&nbsp;&nbsp;&nbsp;".$r->timeOut."</td>
			<td><input type='text' value='".$r->name."' size='5' name='shift".$r->ndex."'></td>
			<td><input type='checkbox' name='ck".$r->ndex."' ".$chk."></td>
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
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Update Shifting Name</h2>   
    <div class="clearfix">
	<form name="frmemp" action="tools_updateshifting.php?act=sabmet" method="post">
		<table width="500">
			<tr style="color:red;font-size:14px;font-weight:bold;"><td colspan='3'><?php echo $msg;?></td></tr>
			<tr style="color:blue;font-size:12px;font-weight:bold;">
				<td>Seq#</td>
				<td>Shift</td>
				<td>Name</td>
				<td>Hide</td>
			</tr>
			<tr><td colspan='6'><hr></td></tr>
			<tr><td colspan="6" align="right"><input type="Submit" value="Update"></td></tr>
			<?php echo $d;?>
			<tr><td colspan="6" align="right"><input type="Submit" value="Update"></td></tr>
		</table>
	</form>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
