<?php


	include("dbcon.php");
$x = mysql_query("SELECT * from employee WHERE isActive = 1 and employmentStatus in ('Temporary','Probationary')");
while($r = mysql_fetch_array($x)){
	 $l = mysql_fetch_array(mysql_query("select sum(leaveLimit) as lm from employee_leave_limit  where employeeId='".$r['ndex']."' and year='2019'"));

/*
	$reset = mysql_query("update employee_leave_limit set leaveLimit=0 where employeeId='".$r['ndex']."' and year='2019'");

	$ml=0;
	$ol=30;
	$mcl=0;	
	$pl=0;	
	$aal=60;

	if($r['sex']=='FEMALE'){
		$ml=78;
		$mcl=60;
	}
	if($r['sex']=='MALE'){
		$pl=8;
		
	}


	//ml = 4
	$upd=mysql_query("update employee_leave_limit set leaveLimit=".$ml." where leaveId=4 and year='2019' and employeeId='".$r['ndex']."'");
	//aal = 5
	$upd=mysql_query("update employee_leave_limit set leaveLimit=".$aal." where leaveId=5 and year='2019' and employeeId='".$r['ndex']."'");
	//pl=8
	$upd=mysql_query("update employee_leave_limit set leaveLimit=".$pl." where leaveId=8 and year='2019' and employeeId='".$r['ndex']."'");
	//mcl=16
	$upd=mysql_query("update employee_leave_limit set leaveLimit=".$mcl." where leaveId=16 and year='2019' and employeeId='".$r['ndex']."'");
	//ol=12
	$upd=mysql_query("update employee_leave_limit set leaveLimit=".$ol." where leaveId=12 and year='2019' and employeeId='".$r['ndex']."'");
*/


	echo $r['sex']." ".$r['ndex']." - ".$r['lastName']." ".$r['firstName']." ".$r['sex']." ".$l['lm']."<br>";
}
?>
