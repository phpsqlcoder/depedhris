<?php
ob_start();
session_start();

	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
	include ("myfunctions.php");


	$year=date('Y');	
	############### Set Leave every Start of the Year ###############
	$sync=mysql_query("select * from employee where isActive=1 and employmentStatus in ('PROBATIONARY','TEMPORARY') ORDER BY sex DESC");
	while($s=mysql_fetch_array($sync)){
	$leaveqry=mysql_query("select * from `leave` where ndex not in (5,6,13,14)");
	while($l=mysql_fetch_array($leaveqry)){
		//echo "select * from `leave` where ndex not in (5,6,13,14)";
		$l['defaultLimit']=0;
		if($s['sex']=='MALE' && $l['ndex']=='8'){
			$l['defaultLimit']=7;
		}
		if($s['sex']=='MALE' && $l['ndex']=='12'){
			$l['defaultLimit']=50;
		}
		//echo $s->sex." - "."update employee_leave_limit set leaveLimit=".$l->defaultLimit." where leaveId=".$l->ndex." and employeeId=".$s->ndex." and year='".$year."'<br>";
		$ck=mysql_num_rows(mysql_query("select * from employee_leave_limit where leaveId=".$l['ndex']." and employeeId=".$s['ndex']." and year='".$year."'"));
		//echo "select * from employee_leave_limit where leaveId=".$l['ndex']." and employeeId=".$s['ndex']." and year='".$year."'<br>";
		if($ck>=1){
			$def=mysql_query("update employee_leave_limit set leaveLimit=".$l['defaultLimit']." where leaveId=".$l['ndex']." and employeeId=".$s['ndex']." and year='".$year."'");
			//echo "update employee_leave_limit set leaveLimit=".$l['defaultLimit']." where leaveId=".$l['ndex']." and employeeId=".$s['ndex']." and year='".$year."'";
		}
		else{

			$def=mysql_query("insert into employee_leave_limit (`employeeId`, `leaveId`, `leaveLimit`, `year`) VALUES (".$s['ndex'].",".$l['ndex'].",".$l['defaultLimit'].",'".$year."')");
		}
	}
	}
	echo "Successfully Processed!";
