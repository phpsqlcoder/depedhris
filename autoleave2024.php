<?php
ob_start();
session_start();

	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
	include ("myfunctions.php");


	$year=date('Y');	
	############### Set Leave every Start of the Year ###############
	$sync=mysql_query("select * from employee where isActive=1");
	while($s=mysql_fetch_array($sync)){
	$leaveqry=mysql_query("select * from `leave` where ndex not in (5,6,13,14)");
	while($l=mysql_fetch_array($leaveqry)){
		
		$l['defaultLimit']=0;
		if($s['sex']=='MALE' && $l['ndex']=='8'){
			$l['defaultLimit']=7;
		}
		if($s['sex']=='MALE' && $l['ndex']=='12'){
			$l['defaultLimit']=50;
		}
		
		$ck=mysql_num_rows(mysql_query("select * from employee_leave_limit where leaveId=".$l['ndex']." and employeeId=".$s['ndex']." and year='".$year."'"));
		
		if($ck>=1){
			$def=mysql_query("update employee_leave_limit set leaveLimit=".$l['defaultLimit']." where leaveId=".$l['ndex']." and employeeId=".$s['ndex']." and year='".$year."'");
			
		}
		else{

			$def=mysql_query("insert into employee_leave_limit (`employeeId`, `leaveId`, `leaveLimit`, `year`, `yer`) VALUES (".$s['ndex'].",".$l['ndex'].",".$l['defaultLimit'].",'".$year."','".$year."')");
		}
	}
	}

	$sync=mysql_query("select `level` as lvl,employmentStatus,sex,dateHired,ndex,lastName,DATEDIFF('2024-01-01', t.dateHired) / 365.25  AS age, civilStatus from employee t where isActive=1");
	while($x=mysql_fetch_array($sync)){
		//echo $x['lastName'].' - '.$x['dateHired'].' - '.$x['age'].'<br>';
		$age=$x['age'];
		$vl=0;
		$sl=0;
		$ml=0;
		$ol=30;
		$mcl=0;
		$spl=7;
		$pl=0;
		$fl=0;
		$bl=0;
		$el=0;
		$ul=0;
		if($age>=1 && $age<10){
			$vl=14;
			$sl=19;
			if($x['lvl']>=3){
				$vl=15;
			}
		}
		if($age>=10 && $age<20){
			$vl=15;
			$sl=20;
			if($x['lvl']>=3){
				$vl=15;
			}
		}
		if($age>=20 && $age<25){
			$vl=16;
			$sl=21;
			if($x['lvl']>=3){
				$vl=17;
			}
		}
		if($age>=25){
			$vl=17;
			$sl=21;
			if($x['lvl']>=3){
				$vl=18;
			}
		}
		if($x['sex']=='FEMALE'){
			$ml=105;
			$mcl=60;
		}
		if($x['sex']=='MALE' && $x['employmentStatus']=='Regular' && $x['civilStatus']=='SINGLE'){
			$pl=7;
		}
		if($x['employmentStatus']=='Regular'){			
			$fl=3;
			$bl=1;
			$el=7;
		}
		if(($x['lvl']==1 || $x['lvl']==2) && $x['employmentStatus']=='Regular'){
			$ul=3;
		}
		//if($x['lvl']>=3){
			//$vl=15;
		//}
		//vl = 3
		$upd=mysql_query("update employee_leave_limit set leaveLimit=".$vl." where leaveId=3 and year='2024' and employeeId='".$x['ndex']."'");
		//ml = 4
		$upd=mysql_query("update employee_leave_limit set leaveLimit=".$ml." where leaveId=4 and year='2024' and employeeId='".$x['ndex']."'");
		//el= 6
		$upd=mysql_query("update employee_leave_limit set leaveLimit=".$el." where leaveId=6 and year='2024' and employeeId='".$x['ndex']."'");
		//fl=7
		$upd=mysql_query("update employee_leave_limit set leaveLimit=".$fl." where leaveId=7 and year='2024' and employeeId='".$x['ndex']."'");
		//pl=8
		$upd=mysql_query("update employee_leave_limit set leaveLimit=".$pl." where leaveId=8 and year='2024' and employeeId='".$x['ndex']."'");
		//bl=9
		$upd=mysql_query("update employee_leave_limit set leaveLimit=".$bl." where leaveId=9 and year='2024' and employeeId='".$x['ndex']."'");
		//sl=10
		$upd=mysql_query("update employee_leave_limit set leaveLimit=".$sl." where leaveId=10 and year='2024' and employeeId='".$x['ndex']."'");
		//ol=12
		$upd=mysql_query("update employee_leave_limit set leaveLimit=".$ol." where leaveId=12 and year='2024' and employeeId='".$x['ndex']."'");
		//ul=15
		$upd=mysql_query("update employee_leave_limit set leaveLimit=".$ul." where leaveId=15 and year='2024' and employeeId='".$x['ndex']."'");
		//mcl=16
		$upd=mysql_query("update employee_leave_limit set leaveLimit=".$mcl." where leaveId=16 and year='2024' and employeeId='".$x['ndex']."'");
		//spl=17
		$upd=mysql_query("update employee_leave_limit set leaveLimit=".$spl." where leaveId=17 and year='2024' and employeeId='".$x['ndex']."'");


	}

	echo "Successfully Processed!";


