<?php
ob_start();

	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
    include ("myfunctions.php");
	
	function getAge($then) {
    $then = date('Ymd', strtotime($then));
    $diff = date('Ymd') - $then;
    return substr($diff, 0, -4);
	}
	$de=mysql_query("delete from employee_leave_limit where year='".date('Y')."'");
	$a=mysql_query("SELECT * from employee where isActive='1'");
	while($b=mysql_fetch_object($a)){
		$age=getAge($b->dateHired);
		
		$vl=0;
		$ml=0;
		$aa=0;
		$el=0;
		$fl=0;
		$pl=0;
		$bl=0;
		$sl=0;
		$abs=0;
		$ol=30;
		$ul=0;
		$mcl=0;
		$spl=7;	
		
		if($b->employmentStatus=='Regular'){
			if($age>=1 && $age<=19){				
				$vl=19;
				$sl=19;
			}
			if($age>=20 && $age<=24){				
				$vl=21;
				$sl=21;
			}
			if($age>=25){				
				$vl=22;
				$sl=21;
			}
			
			if($b->sex=='MALE'){
				$pl=7;
			}
			
			if($b->level=='1' || $b->level=='2'){
				$ul=3;	
			}
			
			$fl=3;
			$bl=1;
			$el=7;
		}
		
		if($b->sex=='FEMALE'){
			$ml=78;	
			$mcl=60;			
		}
	
		
		
		$x=mysql_query("insert into employee_leave_limit (employeeId,leaveId,leaveLimit,year,yer)VALUES('".$b->ndex."',3,".$vl.",'".date('Y')."','".date('Y')."')");
		$x=mysql_query("insert into employee_leave_limit (employeeId,leaveId,leaveLimit,year,yer)VALUES('".$b->ndex."',4,".$ml.",'".date('Y')."','".date('Y')."')");
		$x=mysql_query("insert into employee_leave_limit (employeeId,leaveId,leaveLimit,year,yer)VALUES('".$b->ndex."',5,".$aa.",'".date('Y')."','".date('Y')."')");
		$x=mysql_query("insert into employee_leave_limit (employeeId,leaveId,leaveLimit,year,yer)VALUES('".$b->ndex."',6,".$el.",'".date('Y')."','".date('Y')."')");
		$x=mysql_query("insert into employee_leave_limit (employeeId,leaveId,leaveLimit,year,yer)VALUES('".$b->ndex."',7,".$fl.",'".date('Y')."','".date('Y')."')");
		$x=mysql_query("insert into employee_leave_limit (employeeId,leaveId,leaveLimit,year,yer)VALUES('".$b->ndex."',8,".$pl.",'".date('Y')."','".date('Y')."')");
		$x=mysql_query("insert into employee_leave_limit (employeeId,leaveId,leaveLimit,year,yer)VALUES('".$b->ndex."',9,".$bl.",'".date('Y')."','".date('Y')."')");
		$x=mysql_query("insert into employee_leave_limit (employeeId,leaveId,leaveLimit,year,yer)VALUES('".$b->ndex."',10,".$sl.",'".date('Y')."','".date('Y')."')");
		$x=mysql_query("insert into employee_leave_limit (employeeId,leaveId,leaveLimit,year,yer)VALUES('".$b->ndex."',13,".$abs.",'".date('Y')."','".date('Y')."')");
		$x=mysql_query("insert into employee_leave_limit (employeeId,leaveId,leaveLimit,year,yer)VALUES('".$b->ndex."',12,".$ol.",'".date('Y')."','".date('Y')."')");
		$x=mysql_query("insert into employee_leave_limit (employeeId,leaveId,leaveLimit,year,yer)VALUES('".$b->ndex."',15,".$ul.",'".date('Y')."','".date('Y')."')");
		$x=mysql_query("insert into employee_leave_limit (employeeId,leaveId,leaveLimit,year,yer)VALUES('".$b->ndex."',16,".$mcl.",'".date('Y')."','".date('Y')."')");
		$x=mysql_query("insert into employee_leave_limit (employeeId,leaveId,leaveLimit,year,yer)VALUES('".$b->ndex."',17,".$spl.",'".date('Y')."','".date('Y')."')");
	}
?>
