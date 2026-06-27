<?php
ob_start();
	include("dbcon.php");
	include ("employeefunctions.php");
	$secret_key = "hr15k3y";

	$_GET['start'] = '2019-07-19';
	$_GET['end'] = '2019-07-19';
	$_GET['key'] = 'hr15k3y';

	$key = $_GET['key'];
	$start = $_GET['start'];
	$end = $_GET['end'];
	if($key == $secret_key){

		// $result = mysql_query("select s.employeeId,concat(e.lastName,', ',e.firstName,' ',e.middleName) as fullname,
		// 	s.shiftingId
		// 	from employee_shifting s left join employee e on e.ndex=s.employeeId
		// 	where s.startDate>='".$start."' and s.endDate<='".$end."'");
		// $rows = array();
		// while($r = mysql_fetch_assoc($result)) {
		// 	$rows[] = $r;
		// }

		// print json_encode($rows);

		date_default_timezone_set("Asia/Manila");
		$start = strtotime($start);
		$end = strtotime($end);		
				   
		
		$qry="SELECT e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.ndex as empid,e.employmentStatus,e.employeeNo from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId where e.isActive=1";
		
		$qry.=" ORDER BY d.ndex,e.lastName,e.firstName";
		$exec=mysql_query($qry);
		$var=0;
		while($r=mysql_fetch_object($exec)){

		    date_default_timezone_set("Asia/Manila");
			for ( $a = $start; $a <= $end; $a += 86400 ){				
				$det=date('Y-m-d',$a);
				$rows['fullname'] = $r->lastName." , ".$r->firstName." ".$r->middleName;
				$rows['id'] = getID($r->employmentStatus,$r->employeeNo);
				$rows['dept'] = $r->dept;
				$rows['position'] = $r->position;
				$rows['date'] = $det;


				$shift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$r->empid." and '".$det."' between startDate and endDate and approvedDate<>'0000-00-00 00:00:00'"));
				$lve=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId=".$r->empid." and '".$det."' between startDate and endDate and approvedDate<>'0000-00-00 00:00:00'"));
				$rd=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$r->empid." and '".$det."' between startDate and endDate and approvedDate<>'0000-00-00 00:00:00'"));

				if($shift->shiftingId){
					$rsleave=mysql_fetch_object(mysql_query("select CASE timeIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeIn),'%H:%i %p') END AS tymIn, 
					CASE breakOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakOut),'%H:%i %p') END as brekOut,
					CASE breakIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakIn),'%H:%i %p') END as brekIn,
					CASE timeOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeOut),'%H:%i %p') END as tymOut from shifting where ndex=".$shift->shiftingId.""));
					$stymin=substr($rsleave->tymIn,0,2);
					$sbreakin=substr($rsleave->brekIn,0,2);
					$sbreakout=substr($rsleave->brekOut,0,2);
					$stymout=substr($rsleave->tymOut,0,2);
					if($stymin>=13){$ltymin=($stymin-12).substr($rsleave->tymIn,2);}else{$ltymin=$rsleave->tymIn;}
					if($sbreakout>=13){$lbreakout=($sbreakout-12).substr($rsleave->brekOut,2);}else{$lbreakout=$rsleave->brekOut;}
					if($sbreakin>=13){$lbreakin=($sbreakin-12).substr($rsleave->brekIn,2);}else{$lbreakin=$rsleave->brekIn;}
					if($stymout>=13){$ltymout=($stymout-12).substr($rsleave->tymOut,2);}else{$ltymout=$rsleave->tymOut;}
					if($lbreakout!=''){$brk="&nbsp;".$lbreakout."&nbsp;".$lbreakin;}
					else{$brk="";}
					//$optionsh="<td style='font-size:11px;font-family:Agency FB;".$remark."'>".$ltymin."".$brk."&nbsp;".$ltymout."</td>";
					$shift_name = mysql_fetch_object(mysql_query("select * from shifting where ndex='".$shift->shiftingId."'"));
					$rows['shift timein'] = $ltymin;
					$rows['shift timeout'] = $ltymout;
					$rows['shift break'] = $brk;
					$rows['shift name'] = $shift_name->name;
					$rows['shift type'] = 'SHIFT';
					//$optionsh=$sc->name;
					
				}
				elseif($lve->leaveId){
					$lv=mysql_fetch_object(mysql_query("select * from `leave` where ndex=".$lve->leaveId.""));
					
					//$optionsh="<td style='".$remark2."'>".$lv->code."</td>";
					$rows['shift timein'] = '';
					$rows['shift timeout'] = '';
					$rows['shift break'] = '';
					$rows['shift name'] = $lv->code;
					$rows['shift type'] = 'LEAVE';

				}
				elseif($rd->ndex){
					//$optionsh="OFF";
					$rows['shift timein'] = '';
					$rows['shift timeout'] = '';
					$rows['shift break'] = '';
					$rows['shift name'] = 'OFF';
					$rows['shift type'] = 'OFF';
				}
				else{
					$rows['shift timein'] = '';
					$rows['shift timeout'] = '';
					$rows['shift break'] = '';
					$rows['shift name'] = 'NOT SET';
					$rows['shift type'] = 'NOT SET';
				}
				$result[] = $rows;
				
			}
			
		}

			
	}
	else{
		echo 'Invalid access';
	}
	print json_encode($result);


?>



