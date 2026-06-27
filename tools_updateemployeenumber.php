<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");

if($_GET['act']=='sabmet'){
	$e=mysql_query("SELECT e.ndex,e.employmentStatus,e.employeeNo,e.firstName,e.lastName,e.middleName,p.name as position,d.name as c from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId order by e.lastName,e.firstName");
	while($rs=mysql_fetch_object($e)){
		$upd=mysql_query("update employee set employeeNo='".$_POST['empnum'.$rs->ndex]."',
				 divisionId='".$_POST['division'.$rs->ndex]."',
				 deptId='".$_POST['dept'.$rs->ndex]."',
				 employmentStatus='".$_POST['employmentstatus'.$rs->ndex]."',
				 payType='".$_POST['paytype'.$rs->ndex]."',
				 isTaxable='".$_POST['taxable'.$rs->ndex]."'
				 where ndex=".$rs->ndex."");
		$msg="Successfully Update Employee Number!";
	}
}
$emp=mysql_query("SELECT e.ndex,e.employmentStatus,e.employeeNo,e.isTaxable,e.firstName,e.divisionId as divs,e.deptId as deptId,e.payType,e.lastName,e.middleName,p.name as position,d.name as c from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId order by e.lastName,e.firstName");
while($r=mysql_fetch_object($emp)){
	$division=mysql_fetch_object(mysql_query("select * from division where ndex=".$r->divs.""));
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	$d.="<tr style='font-size:12px;background-color:".$bgclr1s."'>
			<td>".$r->lastName.", ".$r->firstName."&nbsp;".$r->middleName."</td>
			<td><select name='division".$r->ndex."'><option value='".$r->divs."' selected='selected'>".$division->name."".$optiondivision."</td>
			<td><select name='dept".$r->ndex."'><option value='".$r->deptId."' selected='selected'>".$r->c."".$optiondept."</td>
			<td><select name='employmentstatus".$r->ndex."'><option value='".$r->employmentStatus."' selected='selected'>".$r->employmentStatus."".$optionemploymentstatus."</td>
			<td><select name='paytype".$r->ndex."'><option value='".$r->payType."' selected='selected'>".$r->payType."".$optionpaytype."</td>
			<td><select name='taxable".$r->ndex."'><option value='".$r->isTaxable."' selected='selected'>-Yes or No-<option value='1'>YES<option value='0'>NO</td>
			<td><input type='text' value='".$r->employeeNo."' size='5' name='empnum".$r->ndex."'></td>
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
     <h2>Update Employee Number</h2>   
    <div class="clearfix">
	<form name="frmemp" action="tools_updateemployeenumber.php?act=sabmet" method="post">
		<table>
			<tr style="color:red;font-size:14px;font-weight:bold;"><td colspan='3'><?php echo $msg;?></td></tr>
			<tr style="color:blue;font-size:12px;font-weight:bold;">
				<td>Name</td>
				<td>Division</td>
				<td>Dept</td>
				<td>Employment Status</td>
				<td>Pay Type</td>
				<td>Taxable</td>
				<td>Employee#</td>
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
