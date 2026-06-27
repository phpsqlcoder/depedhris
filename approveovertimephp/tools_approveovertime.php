<?php
ob_start();
session_start();
if(!$_SESSION['ndex']){header("location:login.php");}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
	include ("myfunctions.php");

if($_GET['act']=='sabmet'){
	$e=mysql_query("SELECT e.ndex,e.employmentStatus,e.employeeNo,e.firstName,e.lastName,e.middleName,p.name as position,d.name as c from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId where e.deptId=".$_POST['deptselect']." order by e.lastName,e.firstName");
	while($rs=mysql_fetch_object($e)){
		$upd=mysql_query("update dailytimesummary set approvedOvertime='".$_POST[$rs->ndex]."'
				 where employeeId=".$rs->ndex." and date='".$_POST['dyt']."'");
		$msg="Successfully Approved Overtime!";
	}
}
if($_POST['deptselect']){
	$d=mysql_fetch_object(mysql_query("select * from dept where ndex=".$_POST['deptselect'].""));
	$emp=mysql_query("SELECT e.ndex,e.employmentStatus,e.biometricNo as bio,e.employeeNo,e.isTaxable,e.firstName,e.divisionId as divs,e.deptId as deptId,e.payType,e.lastName,e.middleName,p.name as position,d.name as c from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId where e.deptId=".$_POST['deptselect']." order by e.lastName,e.firstName");
//	echo "SELECT e.ndex,e.employmentStatus,e.biometricNo as bio,e.employeeNo,e.isTaxable,e.firstName,e.divisionId as divs,e.deptId as deptId,e.payType,e.lastName,e.middleName,p.name as position,d.name as c from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId where e.deptId=".$_POST['selectdept']." order by e.lastName,e.firstName";
	while($r=mysql_fetch_object($emp)){
		$sh=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$r->ndex." and '".$_POST['dyt']."' between startDate and endDate"));
		$t=mysql_query("select * from hrinterface where dtrid='".$r->bio."' and datelog='".$_POST['dyt']."'");
		$aot=mysql_fetch_object(mysql_query("select * from dailytimesummary where employeeId=".$r->ndex." and date='".$_POST['dyt']."'"));
		//echo "select * from hrinterface where dtrid='".$r->bio."' and datelog='".$_POST['dyt']."'";
		$ar=0;
		$check_duplicate_record=0;
		while($s=mysql_fetch_object($t)){
				$ar++;
				$inout[$ar]=$s->in_out;
				if($inout[$ar]==$inout[$ar - 1]){$check_duplicate_record=1;} // If nagduplicate and IN or OUT
				else{
					if($s->in_out==0){ // Select IN records only
						if($r->ndex){
							$shift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$r->ndex." and '".$s->datelog."' between startDate and endDate"));
						}
						if($shift->shiftingId){
							$out=mysql_fetch_object(mysql_query("SELECT * FROM `hrinterface` where in_out=1 and hrint_id> ".$s->hrint_id." and dtrid=".$s->dtrid." limit 0,1")); // Get OUT record
							if($out->log){
								$overtime=overtimeTotalinMinutes($out->log,$shift->shiftingId); // Overtime
								$check_duplicate_record=4;
							}
							else{
								$check_duplicate_record=2; // No Out
							}
						}
						else{
							$check_duplicate_record=3; // No Shifting 
						}
					}
				}
			}
		$ctr1s++;
		if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		$data.="<tr style='font-size:12px;background-color:".$bgclr1s."'>
					<td>".$r->lastName.", ".$r->firstName."&nbsp;".$r->middleName."</td>
					<td>".number_format($overtime/60,2)."</td>
					<td><input type='text' name='".$r->ndex."' value='".$aot->approvedOvertime."' style='text-align:right;' onFocus='this.select()' onClick='this.select()'></td>
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
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Approve Overtime</h2>   
    <div class="clearfix">
	<form name="frmempq" action="tools_approveovertime.php?act=search" method="post">
		<table>
		<tr>
			<td><select name="deptselect"><?php echo $optiondept;?></select></td>
			<td><strong>Date:</strong>&nbsp;&nbsp;</td><td><input type="Text" name="dyt" id="dyt" size="15"><a href="javascript:show_calendar('frmempq.dyt');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
			<td><input type="Submit" value="GO"></td>
		</tr>
		</table>
	</form>
	<?php if($_POST['deptselect']){?>
	<form name="frmemp" action="tools_approveovertime.php?act=sabmet" method="post">
		<table width="70%">
			<tr><td>&nbsp;</td></tr>
			<tr style='color:maroon;font-weight:bold;'>
				<td>Dept: <?php echo $d->name;?><input type="Hidden" name="deptselect" value="<?php echo $d->ndex;?>"></td>
				<td>Date: <?php echo $_POST['dyt'];?><input type="Hidden" name="dyt" value="<?php echo $_POST['dyt'];?>"><br><br></td>
			</tr>
			<tr style="color:red;font-size:14px;font-weight:bold;"><td colspan='3'><?php echo $msg;?></td></tr>
			<tr style="color:blue;font-size:12px;font-weight:bold;">
				<td>Name</td>
				<td>Unapprove<br>Overtime (hr)</td>
				<td>Overtime</td>
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
