<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
	include ("myfunctions.php");

	//$year=date('Y');
if($_GET['yer']){

	$year=$_GET['yer'];	
	############### Set Leave every Start of the Year ###############
/*$sync=mysql_query("select * from employee where isActive=1 and employmentStatus in ('PROBATIONARY','TEMPORARY') ORDER BY sex DESC");
while($s=mysql_fetch_object($sync)){
	$leaveqry=mysql_query("select * from `leave` where ndex not in (5,6,13,14)");
	while($l=mysql_fetch_object($leaveqry)){
		$l->defaultLimit=0;
		if($s->sex=='MALE' && $l->ndex=='8'){
			$l->defaultLimit=7;
		}
		if($s->sex=='MALE' && $l->ndex=='12'){
			$l->defaultLimit=50;
		}
		//echo $s->sex." - "."update employee_leave_limit set leaveLimit=".$l->defaultLimit." where leaveId=".$l->ndex." and employeeId=".$s->ndex." and year='".$year."'<br>";
		$ck=mysql_num_rows(mysql_query("select * from employee_leave_limit where leaveId=".$l->ndex." and employeeId=".$s->ndex." and year='".$year."'"));
		if($ck>=1){
			$def=mysql_query("update employee_leave_limit set leaveLimit=".$l->defaultLimit." where leaveId=".$l->ndex." and employeeId=".$s->ndex." and year='".$year."'");
		}
		else{
			$def=mysql_query("insert into employee_leave_limit (`employeeId`, `leaveId`, `leaveLimit`, `year`) VALUES (".$s->ndex.",".$l->ndex.",".$l->defaultLimit.",'".$year."')");
		}
	}
}*/

if($_GET['act']=='sabmet'){
	$q=mysql_query("select * from employee_leave_limit where year='".$year."' order by ndex");
	while($a=mysql_fetch_object($q)){
		$save=mysql_query("update employee_leave_limit set leaveLimit=".$_POST[$a->ndex]." where ndex=".$a->ndex."");
	}
}
//insert eleave limit for those who have no limit yet

$le_arr='3,4,7,8,9,10,12,15';
$l_arr=explode(",",$le_arr);
$es=mysql_query("select * from employee where isActive=1");
while($s=mysql_fetch_object($es)){
	$ce=mysql_num_rows(mysql_query("select * from employee_leave_limit where employeeId=".$s->ndex." and yer='".date('Y')."'"));

	if($ce==0){
		foreach($l_arr as $la){
			$ins_l=mysql_query("insert into employee_leave_limit (`employeeId`, `leaveId`, `leaveLimit`, `year`, `yer`)
			VALUES('".$s->ndex."','".$la."','0','".$year."','".$year."')");
		}
		//echo $s->lastName." - ".$s->ndex."<br>";
	}
}

$sql=mysql_query("select e.*,d.name as dep,p.name as position from employee e left join dept d on d.ndex=e.deptId left join position p on p.ndex=e.position where e.isActive=1 order by e.dateHired");
while($r=mysql_fetch_object($sql)){
	$empdata.="<tr style='font-size:12px;font-family:Arial;color:maroon;font-weight:bold;'>
					<td>".getID($r->employmentStatus,$r->employeeNo)." - ".$r->lastName.", ".$r->firstName." ".$r->middleName."</td>
					<td>".$r->position." - ".$r->dateHired."</td>
					<td>".$r->dep."</td>
				
	</tr>";
	$lea=mysql_query("select * from employee_leave_limit where employeeId=".$r->ndex." and year='".$year."' order by ndex");
	while($le=mysql_fetch_object($lea)){
		$leav=mysql_fetch_object(mysql_query("select * from `leave` where ndex=".$le->leaveId.""));
		$empdata.="<tr style='font-size:11px;font-family:Arial;'>
					<td>&nbsp;</td>
					<td>".$leav->name."</td>
					<td><input type='text' name='".$le->ndex."' value='".$le->leaveLimit."' style='text-align:right;' size='3'></td>
		</tr>";
	}
	$empdata.="<tr><td>&nbsp;</td></tr>";
	
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
     <h2>Set Leave</h2>   
    <div class="clearfix">

	<form name="frmemp" action="tools_setleave.php?act=sabmet&yer=<?php echo $_GET['yer'];?>" method="post">
		<table width="100%">
			<tr><td>&nbsp;</td></tr>
			<tr style="color:red;font-size:14px;font-weight:bold;"><td colspan='3'><?php echo $msg;?></td></tr>
			<tr style="color:blue;font-size:12px;font-weight:bold;">
				<td>Name</td>
				<td>Position</td>
				<td>Department</td>
			</tr>
			<tr><td colspan='6'><hr></td></tr>
			<tr><td colspan="6" align="right"><input type="Submit" value="Update"></td></tr>
			<?php echo $empdata;?>
			<tr><td colspan="6" align="right"><input type="Submit" value="Update"></td></tr>
		</table>
	</form>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>
<?php } else { ?>
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
     <h2>Set Leave</h2>   
    <div class="clearfix">

	<form action="tools_setleave.php" method="get">
		Select Year: <select name='yer'>
			<option value="2013">2013
			<option value="2014">2014
			<option value="2015">2015
			<option value="2016">2016
			<option value="2017">2017
			<option value="2018">2018
			<option value="2019">2019
			<option value="2020" selected="selected">2020
			<option value="2021">2021
			<option value="2022">2022
			<option value="2023">2023
			<option value="2024">2024
			<option value="2025">2025
			<option value="2026">2026
			<option value="2027">2027
			<option value="2028">2028
			<option value="2029">2029
			
		</select>
		<input type='submit' value="GO">
	</form>
	  </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>

<?php } ?>