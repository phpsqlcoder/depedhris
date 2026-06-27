<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");

if($_GET['act']=='sabmet'){
	$emp=mysql_query("select * from employee where isActive=1 order by lastName,firstName");
	while($r=mysql_fetch_object($emp)){
		$lv=mysql_query("select * from employee_leave_limit where employeeId=".$r->ndex." order by leaveId");
		while($l=mysql_Fetch_object($lv)){
			$upd=mysql_query("update employee_leave_limit set leaveLimit='".$_POST[$l->ndex]."'
				 where ndex=".$l->ndex."");
		}
	}
	$msg="Successfully Update Leave Limit!";
}
$emp=mysql_query("select * from employee where isActive=1 order by lastName,firstName");
while($r=mysql_fetch_object($emp)){
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	$d.="<tr style='font-size:12px;background-color:".$bgclr1s."'>
		<td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>";
	$lv=mysql_query("select * from employee_leave_limit where employeeId=".$r->ndex." order by leaveId");
	while($l=mysql_Fetch_object($lv)){
		$d.="<td><input type='text' value='".$l->leaveLimit."' size='5' name='".$l->ndex."'></td>";
	}
	$d.="</tr>";
}
	$hh=mysql_query("select * from `leave` where ndex not in(5,13,6,14) order by ndex");
	$dh="<tr style='color:blue;font-size:12px;font-weight:bold;'><td>Name</td>";
	while($h=mysql_fetch_object($hh)){
		$dh.="<td>".$h->code."</td>";
	}
	$dh.="</tr>";
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
     <h2>Update Leave Limit</h2>   
    <div class="clearfix">
	<form name="frmemp" action="tools_updateleavelimit.php?act=sabmet" method="post">
		<table width="100%">
			<tr style="color:red;font-size:14px;font-weight:bold;"><td colspan='3'><?php echo $msg;?></td></tr>
			<tr><td colspan="16" align="right"><input type="Submit" value="Update"></td></tr>
			<?php echo $dh;?>
			<tr><td colspan='16'><hr></td></tr>
			
			<?php echo $d;?>
			<tr><td colspan="16" align="right"><input type="Submit" value="Update"></td></tr>
		</table>
	</form>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
