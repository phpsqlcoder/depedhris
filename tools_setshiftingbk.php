<?php

ob_start();
session_start();

if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
if($_GET['ser']){
	$o=mysql_query("SELECT * from employee where isActive=1 and (lastName like '%".$_GET['stxt']."%' or firstName like '%".$_GET['stxt']."%')");
	while($pp=mysql_fetch_object($o)){
		$d=mysql_fetch_object(mysql_query("select * from dept where ndex=".$pp->deptId.""));
		$da.="<a href='#' onclick=\"window.location.href='tools_setshiftingbk.php?emp=".$pp->ndex."&startDate=".$_GET['startDate']."&endDate=".$_GET['endDate']."'\"><font color='blue'>".$pp->lastName.", ".$pp->firstName." - ".$d->name."<br></font></a>";
	}
	echo $da;
}
else{
$leave=mysql_query("SELECT * FROM `shifting` where status<>1 order by name");
while($rsleave=mysql_fetch_object($leave)){
	$optionsh.="<option value='s".$rsleave->ndex."'>".$rsleave->name."";
}
$leave2=mysql_query("SELECT * FROM `leave` order by code");
while($rsleave2=mysql_fetch_object($leave2)){
	$optionsh.="<option value='l".$rsleave2->ndex."'>".$rsleave2->code."";
}
$optionsh.="<option value='OFF' selected>OFF";

if($_GET['act']=='sabmet'){
	//echo "we";
	/*$st=date('d',strtotime($_GET['startDates']));
	$en=date('d',strtotime($_GET['endDates']));
	$yr=date('Y-m',strtotime($_GET['endDates']));
	for($a=$st;$a<=$en;$a++){
		$det=$yr."-".$a;*/
	$start = strtotime($_GET['startDates']);
	$end = strtotime($_GET['endDates']);
	for ( $a = $start; $a <= $end; $a += 86400 ){
		$det=date('Y-m-d',$a);
		$var=$_GET['dyt'.$det];
		$ndx=substr($var,1);
		if($ndx>=1 || $ndx=='FF'){
			$val=substr($var,1);
			if(substr($var,0,1)=='s'){
				$ckleave=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				if($ckleave->ndex){
					$del=mysql_query("delete from employee_leave where ndex=".$ckleave->ndex."");
				}
				$ckrd=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				if($ckrd->ndex){
					$del=mysql_query("delete from employee_restday where ndex=".$ckrd->ndex."");
				}
				$ckshift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				if($ckshift->ndex){
					$del=mysql_query("delete from employee_shifting where ndex=".$ckshift->ndex."");
				}
				if($_SESSION['deptId']=='0' || $_SESSION['deptId']==''){
					$ins=mysql_query("insert into employee_shifting (`employeeId`, `shiftingId`, `startDate`, `endDate`,approvedBy,approvedDate)
						 VALUES (".$_GET['emp'].",'".$val."','".$det."','".$det."','".$_SESSION['fullName']."','".date('Y-m-d H:i:s')."')");
				}
				else{
					$ins=mysql_query("insert into employee_shifting (`employeeId`, `shiftingId`, `startDate`, `endDate`)
						 VALUES (".$_GET['emp'].",'".$val."','".$det."','".$det."')");
				}
			}
			elseif(substr($var,0,1)=='l'){
				//echo "ll";
				$ckrd=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				if($ckrd->ndex){
					$del=mysql_query("delete from employee_restday where ndex=".$ckrd->ndex."");
				}
				$ckshift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				if($ckshift->ndex){
					$del=mysql_query("delete from employee_shifting where ndex=".$ckshift->ndex."");
				}
				$ckleave=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				
				if($ckleave->ndex){
					$del=mysql_query("delete from employee_leave where ndex=".$ckleave->ndex."");
				}
				$ins=mysql_query("insert into employee_leave (`employeeId`, `leaveId`, `startDate`, `endDate`)
						 VALUES (".$_GET['emp'].",'".$val."','".$det."','".$det."')");
			}
			elseif(substr($var,0,1)=='O'){
				//echo date('w',strtotime($det))."weee";
				$ckshift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				if($ckshift->ndex){
					$del=mysql_query("delete from employee_shifting where ndex=".$ckshift->ndex."");
				}
				$ckleave=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				
				if($ckleave->ndex){
					$del=mysql_query("delete from employee_leave where ndex=".$ckleave->ndex."");
				}
				$ckrd=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$_GET['emp']." and '".$_GET[$det]."' between startDate and endDate"));
				if($ckrd->ndex){
					$del=mysql_query("delete from employee_restday where ndex=".$ckrd->ndex."");
				}
				$ins=mysql_query("insert into employee_restday (`employeeId`, `restday`, `startDate`, `endDate`)
						 VALUES (".$_GET['emp'].",'".date('w',strtotime($det))."','".$det."','".$det."')");
			}
		}
	}
}
if($_GET['emp']){
	$emp=mysql_fetch_object(mysql_query("SELECT e.ndex,e.employmentStatus,e.employeeNo,e.isTaxable,e.firstName,e.divisionId as divs,e.deptId as deptId,e.payType,e.lastName,e.middleName,p.name as position,d.name as c from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId where e.ndex='".$_GET['emp']."' order by e.lastName,e.firstName"));
	/*$st=date('d',strtotime($_GET['startDate']));



$en=date('d',strtotime($_GET['endDate']));
	$yr=date('Y-m',strtotime($_GET['endDate']));
	for($a=$st;$a<=$en;$a++){
		$det=$yr."-".$a;*/
	
	date_default_timezone_set("Asia/Manila");
	$start = strtotime($_GET['startDate']);
	$end = strtotime($_GET['endDate']);
	for ( $a = $start; $a <= $end; $a += 86400 ){
		$det=date('Y-m-d',$a);
		echo $det."<br>";
		
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
    <script type="text/javascript">
	function searchitems(){
	//alert();
		mynodes=frmitem.serialize();	
		new Ajax.Updater('listitem','tools_setshiftingbk.php?ser=searching',{
			method: 'get',
			parameters: mynodes
		});	
	}
    </script>

    <div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Update Employee Number</h2>   
    <div class="clearfix">
	<form name="frmitem" id="frmitem">
<table>
	<tr>
		<td>Start Date:<input type="Text" name="startDate" id="startDate" size="15" value="<?php echo $_GET['startDate'];?>"><a href="javascript:show_calendar('frmitem.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		<td>End Date:<input type="Text" name="endDate" id="endDate" size="15" value="<?php echo $_GET['endDate'];?>"><a href="javascript:show_calendar('frmitem.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		<td><button onclick="window.open('shifting_list.php','displayWindow','toolbar=no,scrollbars=yes,width=410,height=600');">View Shiftings</button></td>
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
<form method="post" action="tools_setshifting.php?act=sabmet">
<table>
	<tr>
		<td colspan="2" style="color:maroon;"><?php echo $emp->lastName.", ".$emp->firstName;?><br><br></td>
	</tr>
	<tr>
		<td>Date</td>
		<td>Shift</td>
	</tr>
	<tr><td colspan="2"><hr></td></tr>
	<?php echo $d;?>
	<tr><td><input type="hidden" name="act" value="sabmet"> <input type="submit" value="SAVE"></td></tr>
</table>
</form>
    </div> 
	
	<?php // include "footer.php";?>
  </div>
</body>
</html>
<?php } ?>