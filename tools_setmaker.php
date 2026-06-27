<?php
//ini_set("memory_limit","200M");
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
	include ("myfunctions.php");

$sync=mysql_query("select * from employee where isActive=1");
while($s=mysql_fetch_object($sync)){
		$ck=mysql_num_rows(mysql_query("select * from employee_scheduler where employeeId=".$s->ndex.""));
		if($ck==0){
			$def=mysql_query("insert into employee_scheduler (`employeeId`, `maker`, `approver`) VALUES (".$s->ndex.",0,0)");
		}
}

if($_GET['act']=='sabmet'){
	$q=mysql_query("select * from employee_scheduler order by ndex");
	while($a=mysql_fetch_object($q)){
		$save=mysql_query("update employee_scheduler set maker=".$_POST['m'.$a->ndex].",approver=".$_POST['a'.$a->ndex]." where ndex=".$a->ndex."");
		//echo "update employee_scheduler set maker=".$_POST['m'.$a->ndex].",approver=".$_POST['a'.$a->ndex]." where ndex=".$a->ndex."<br>";
	}
}
	$musersqry=mysql_query("select * from users order by fullName");
	$moptuser.="<option value='0' selected='selected'>-Select Maker -";
	while($muser=mysql_fetch_object($musersqry)){
		$moptuser.="<option value='".$muser->ndex."'>".$muser->fullName." - ".$muser->nym."";
	}
	$ausersqry=mysql_query("select * from users order by fullName");
	$aoptuser.="<option value='0' selected='selected'>- Select Approver -";
	while($auser=mysql_fetch_object($ausersqry)){
		$aoptuser.="<option value='".$auser->ndex."'>".$auser->fullName." - ".$auser->nym."";
	}
$sql=mysql_query("select s.*,e.lastName,e.firstName,e.middleName,d.name as dep,p.name as position from employee_scheduler s left join employee e on e.ndex=s.employeeId left join dept d on d.ndex=e.deptId left join position p on p.ndex=e.position where e.isActive=1 order by d.name");
while($r=mysql_fetch_object($sql)){	
	$maker=mysql_fetch_object(mysql_query("select * from `users` where ndex=".$r->maker.""));
	$approver=mysql_fetch_object(mysql_query("select * from `users` where ndex=".$r->approver.""));
	if($r->maker>=1){
		$optm="<option value='".$maker->ndex."' selected='selected'>".$maker->fullName."";
	}
	else{
		$optm="";
	}
	if($r->approver>=1){
		$opta="<option value='".$approver->ndex."' selected='selected'>".$approver->fullName."";
	}
	else{
		$opta="";
	}
	$empdata.="<tr style='font-size:12px;font-family:Arial;color:maroon;font-weight:bold;'>
					<td>".$r->lastName.", ".$r->firstName." ".$r->middleName."</td>
					<td>".$r->position."</td>
					<td>".$r->dep."</td>
					<td>".$maker->fullName."</td>
					<td>".$approver->fullName."</td>
					<td><select name='m".$r->ndex."'>".$moptuser."".$optm."</select></td>
					<td><select name='a".$r->ndex."'>".$aoptuser."".$opta."</select></td>
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
     <h2>Set Employee Maker/Approver</h2>   
    <div class="clearfix">

	<form name="frmemp" action="tools_setmaker.php?act=sabmet" method="post">
		<table width="120%">
			<tr><td>&nbsp;</td></tr>
			<tr style="color:red;font-size:14px;font-weight:bold;"><td colspan='3'><?php echo $msg;?></td></tr>
			<tr style="color:blue;font-size:12px;font-weight:bold;">
				<td>Name</td>
				<td>Position</td>
				<td>Department</td>
				<td>Maker</td>
				<td>Approver</td>
				<td>New Maker</td>
				<td>New Approver</td>
			</tr>
			<tr><td colspan='7'><hr></td></tr>
			<tr><td colspan="7" align="right"><input type="Submit" value="Update"></td></tr>
			<?php echo $empdata;?>
			<tr><td colspan="7" align="right"><input type="Submit" value="Update"></td></tr>
		</table>
	</form>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>
