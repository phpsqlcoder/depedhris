<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include("scripts/scripts.php");
include ("employeefunctions.php");
include ("myfunctions.php");
//echo $cutoffDate;



	
if ($_GET['pageact'] == "fix"){
	$p = mysql_fetch_object(mysql_query("SELECT * FROM cutoffdates where isLock=0 and ndex='".$_POST['cutoffDate']."'"));
	$emp=mysql_query("select * from employee_shifting where shiftingId='105' and startDate>='".$p->cutoffDateStart."' and startDate<='".$p->cutoffDateEnd."'");
	while($r=mysql_fetch_object($emp)){
		//echo $r->employeeId." - ".$r->startDate." - ".$r->shiftingId."<br>";
		reprocess_timelogs($r->employeeId,$r->startDate);
	}
	/*echo "<table><tr><td>Employee</td><td>Date</td><td>Total Hours</td></tr>";
	$emps=mysql_query("select * from employee_shifting where shiftingId='105' and startDate>='".$p->cutoffDateStart."' and startDate<='".$p->cutoffDateEnd."' ORDER BY employeeId");	
	while($x=mysql_fetch_object($emps)){
		$k=mysql_fetch_object(mysql_query("select * from dailytimesummary where `date`='".$x->startDate."' and employeeId='".$x->employeeId."'"));
		$em=mysql_fetch_object(mysql_query("select * from employee where ndex='".$x->employeeId."'"));
		echo "<tr><td>".$em->lastName.",".$em->firstName." ".$em->middleName."</td><td>".$x->startDate."</td><td>".$k->hoursDuty."</td></tr>";
	}
	echo "<table>";*/
	
	$msg="Successfully Fixed NOC2 Dates!";
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
     <h2>Fix Noc2 Time summary</h2>
	 <span style="color:red;font-weight:bold;">Note: This will only fix dates with NOC2 shifting schedule<br>All manually encoded time summary with NOC2 shift will be overwritten.<br><br></span>
    <div class="clearfix">
		<i style="color:red;font-size:14px;"><?php echo $msg2;?></i>
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=fix" margin="0px;" name="myForm">
				<select name="cutoffDate"><?php echo $optionSelectPayrollCutoffDate;?></select>
				<button>GO</button>
			</form>
    </div> 

  
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
