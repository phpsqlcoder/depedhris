<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include("scripts/scripts.php");
include ("employeefunctions.php");
include ("myfunctions.php");
//echo $cutoffDate;



	
if ($_GET['pageact'] == "lockCutoff"){
	$emp=mysql_query("select * from employee");
	while($r=mysql_fetch_object($emp)){
		//employeeDataCutoff($r->ndex,$_POST['cutoffDate']);
	}
	$lockcutoff=mysql_query("update cutoffdates set isLock=1,lockPayrollBy='".$_SESSION['nym']."',lockPayrollOn='".date('Y-m-d H:i:s')."' where ndex=".$_POST['cutoffDate']."");
	$msg="Successfully Locked Payroll!";
}
if ($_GET['pageact'] == "lockDTR"){
	$lockcutoff=mysql_query("update cutoffdates set isLockDtr=1,lockDtrBy='".$_SESSION['nym']."',lockDtrOn='".date('Y-m-d H:i:s')."' where ndex=".$_POST['cutoffDate']."");
	$msg2="Successfully Locked DTR!";
}
if ($_GET['pageact'] == "lockPTSR"){
	$lockcutoff=mysql_query("update cutoffdates set isLockPtsr=1,lockPtsrBy='".$_SESSION['nym']."',lockPtsrOn='".date('Y-m-d H:i:s')."' where ndex=".$_POST['cutoffDate']."");
	$msg3="Successfully Locked PTSR!";
}
$rs = mysql_query("SELECT * FROM cutoffdates where isLock=0 ORDER BY payrollDate DESC limit 12",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['ndex']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
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
     <h2>Lock DTR</h2>   
    <div class="clearfix">
		<i style="color:red;font-size:14px;"><?php echo $msg2;?></i>
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=lockDTR" margin="0px;" name="myForm">
				<select name="cutoffDate"><?php echo $optionSelectPayrollCutoffDate;?></select>
				<button>Lock DTR</button>
			</form>
    </div> 
	
  </div>
  <div id="main_content_wrap" class="container_12">
     <h2>Lock PTSR</h2>   
    <div class="clearfix">
		<i style="color:red;font-size:14px;"><?php echo $msg3;?></i>
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=lockPTSR" margin="0px;" name="myForm">
				<select name="cutoffDate"><?php echo $optionSelectPayrollCutoffDate;?></select>
				<button>Lock PTSR</button>
			</form>
    </div> 
	
  </div>
<div id="main_content_wrap" class="container_12">
     <h2>Lock Payroll</h2>   
    <div class="clearfix">
		<i style="color:red;font-size:14px;"><?php echo $msg;?></i>
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=lockCutoff" margin="0px;" name="myForm">
				<select name="cutoffDate"><?php echo $optionSelectPayrollCutoffDate;?></select>
				<button>Lock Payroll</button>
			</form>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
