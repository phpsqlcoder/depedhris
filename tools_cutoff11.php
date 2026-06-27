<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include("scripts/scripts.php");
include ("employeefunctions.php");
include ("myfunctions.php");
//echo $cutoffDate;



	
if ($_GET['pageact'] == "runCutoff"){
	$getInfoCutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE ndex='".$_POST['cutoffDate']."'",$conn));
	// update daily timesummary leaves
	$sqlUpd = "SELECT * FROM dailytimesummary WHERE date BETWEEN '".$getInfoCutoffDate['cutoffDateStart']."' AND '".$getInfoCutoffDate['cutoffDateEnd']."'";
	$rsUpd =  mysql_query($sqlUpd, $conn);
	while ($dtupd =  mysql_fetch_assoc($rsUpd)){
		$overtimeEccess = 0;
		if ($dtupd['leaveId'] == '3'){
			$update = mysql_query("UPDATE dailytimesummary SET vac_lve='1', days_work='1' WHERE ndex='".$dtupd['ndex']."'",$conn);
		} elseif ($dtupd['leaveId'] == '10'){
			$update = mysql_query("UPDATE dailytimesummary SET sick_lve='1', days_work='1' WHERE ndex='".$dtupd['ndex']."'",$conn);
		} elseif ($dtupd['leaveId'] == '15'){
			$update = mysql_query("UPDATE dailytimesummary SET unionLeave='1', days_work='1' WHERE ndex='".$dtupd['ndex']."'",$conn);
		} elseif ($dtupd['leaveId'] == '6'){
			$update = mysql_query("UPDATE dailytimesummary SET wpLeave='1', days_work='1' WHERE ndex='".$dtupd['ndex']."'",$conn);
		} elseif ($dtupd['leaveId'] == '12'){
			$update = mysql_query("UPDATE dailytimesummary SET officialLeave='1', days_work='1' WHERE ndex='".$dtupd['ndex']."'",$conn);
		} elseif ($dtupd['leaveId'] == '8'){
			$update = mysql_query("UPDATE dailytimesummary SET paternityLeave='1', days_work='1' WHERE ndex='".$dtupd['ndex']."'",$conn);
		} elseif ($dtupd['leaveId'] == '7'){
			$update = mysql_query("UPDATE dailytimesummary SET funeralLeave='1', days_work='1' WHERE ndex='".$dtupd['ndex']."'",$conn);
		} else {
			$update = mysql_query("UPDATE dailytimesummary SET funeralLeave='',paternityLeave='',officialLeave='',unionLeave='',wpLeave='',sick_lve='',vac_lve='',man_lve='' WHERE ndex='".$dtupd['ndex']."'",$conn);
		}
		
		if ($dtupd['approvedOvertime'] > 8){
			$overtimeEccess = $dtupd['approvedOvertime'] - 8;
			$dtupd['approvedOvertime'] = 8;
		}
		
		//for($r=1;$r<=10;$r++ ){
		//	echo date('Y-m-d',strtotime('-'.$r.' days',strtotime(date($dtupd['date']))))."<br>";
		//	$prevDate = date('Y-m-d',strtotime('-'.$r.' days',strtotime(date($dtupd['date']))));
		//	$checkPrevDays = mysql_fetch_assoc(mysql_query("SELECT * FROM dailytimesummary WHERE date ='".$prevDate."' && employeeId='".$dtupd['employeeId']."' && days_work>0 ",$conn));
		//}
		
//		die();
		if($dtupd['holiday'] == 'S'){
			$update = mysql_query("UPDATE dailytimesummary SET  undertime='0',minutesLate='0',days_work='1',spholiday='".$dtupd['hoursDuty']."', otSHoliday='".$dtupd['approvedOvertime']."', ot_exc ='".$overtimeEccess."' WHERE ndex='".$dtupd['ndex']."'",$conn);
		} elseif($dtupd['holiday'] == 'L') {
			$update = mysql_query("UPDATE dailytimesummary SET  undertime='0',minutesLate='0',days_work='1',lholiday='".$dtupd['hoursDuty']."', otLHoliday='".$dtupd['approvedOvertime']."', ot_exc ='".$overtimeEccess."' WHERE ndex='".$dtupd['ndex']."'",$conn);
		} 
		
		if($dtupd['isDayOff'] == '1'){
			$update = mysql_query("UPDATE dailytimesummary SET undertime='0',minutesLate='0',duty_rd='".$dtupd['hoursDuty']."', otRestDay='".$dtupd['approvedOvertime']."', ot_exc ='".$overtimeEccess."' WHERE ndex='".$dtupd['ndex']."'",$conn);
		}
		
		if($dtupd['isDayOff']!== '1' && $dtupd['holiday'] != 'S' && $dtupd['holiday'] != 'L'){
			$update = mysql_query("UPDATE dailytimesummary SET ot_reg='".$dtupd['approvedOvertime']."', ot_exc ='".$overtimeEccess."' WHERE ndex='".$dtupd['ndex']."'",$conn);
		}
		
		if ($dtupd['isBirthday'] == 1){
			$update = mysql_query("UPDATE dailytimesummary SET days_work='1' WHERE ndex='".$dtupd['ndex']."'",$conn);
		} 
	
	}
	
	//die();
	//echo $getInfoCutoffDate['payrollDate'];
	$checkIfExistInPayroll = mysql_num_rows(mysql_query("SELECT * FROM payroll WHERE pay_period='".$getInfoCutoffDate['payrollDate']."'",$conn));
	
	//echo $checkIfExistInPayroll." / ".$getInfoCutoffDate['payrollDate'];
	//if($checkIfExistInPayroll == 0){
		$sql= "SELECT ndex FROM employee WHERE isActive='1'";
		$rs = mysql_query($sql,$conn);
		while ($dt = mysql_fetch_assoc($rs)){
			$checkIfExistInPayroll = mysql_num_rows(mysql_query("SELECT * FROM payroll WHERE empid='".$dt['ndex']."' && pay_period='".$getInfoCutoffDate['payrollDate']."'",$conn));
			if ($checkIfExistInPayroll == 0){
				$insertIntoPayroll = mysql_query("INSERT INTO payroll (empid, pay_period) VALUES ('".$dt['ndex']."','".$getInfoCutoffDate['payrollDate']."')",$conn);
			}
		}
	//}
	
	/*if (isErrorTimeSummary($getInfoCutoffDate['cutoffDateStart'],$getInfoCutoffDate['cutoffDateEnd']) == 1){
		echo "<script>alert('There are DTR errors. kindly check it on Tools>>DTR Error'); window.location.href='./tools_dtrerror.php';</script>"; halt;
	} else {*/
		$sql = "SELECT e.ndex as employeeId, SUM(dts.days_absent) daysAbsent, SUM(dts.minutesLate + dts.undertime) undertime, SUM(dts.ot_reg) otReg, SUM(dts.ot_exc) otExc, SUM(dts.days_work) daysWork
											, SUM(dts.spholiday) SpHoliday
											, SUM(otSHoliday) otSHoliday
											, SUM(dts.lholiday) LHoliday 
											, SUM(dts.otLHoliday) otLHoliday
											, SUM(dts.duty_rd) dutyRd 
											, SUM(dts.otRestDay) otRestDay
											, SUM(dts.vac_lve) vacLeave 
											, SUM(dts.unionLeave) unionLeave 
											, SUM(dts.wpLeave) wpLeave 
											, SUM(dts.officialLeave) officialLeave 
											, SUM(dts.sick_lve) sickLeave 
											, SUM(dts.paternityLeave) paternityLeave
											, SUM(dts.funeralLeave) funeralLeave
											, SUM(dts.night_prem + dts.approvedOvertimeNightPremium) nightPremium
											, SUM(CASE WHEN dts.holiday='L' THEN dts.hoursDuty else 0 end) legalHolidayPrem
											, SUM(CASE WHEN dts.holiday='S' THEN dts.hoursDuty else 0 end) specialHolidayPrem 
											, residencyTrainingProgram, employmentStatus, level
											FROM employee e
											LEFT JOIN dailytimesummary dts ON dts.employeeId=e.ndex 
											WHERE e.isActive='1' && dts.date BETWEEN '".$getInfoCutoffDate['cutoffDateStart']."' AND '".$getInfoCutoffDate['cutoffDateEnd']."' 
												GROUP BY dts.employeeId, residencyTrainingProgram, employmentStatus, level";
												
		//echo $sql."<br>";
		//	die();
		$rs = mysql_query($sql,$conn);
		while ($dt = mysql_fetch_assoc($rs)){
			$payrollRem ='';
			$vacationLeave ='';
			$sickLeave ='';
			$unionLeave ='';
			$wpLeave ='';
			$officialLeave ='';
			$paternityLeave ='';
			$funeralLeave ='';
			$funeralLeave ='';
			$bdayLeave ='';
			
			$rs1 = mysql_query("SELECT * FROM dailytimesummary dts WHERE employeeId='".$dt['employeeId']."' && dts.date BETWEEN '".$getInfoCutoffDate['cutoffDateStart']."' AND '".$getInfoCutoffDate['cutoffDateEnd']."'",$conn);
			while ($dt1 = mysql_fetch_assoc($rs1)){
				if ($dt1['leaveId'] == '3'){
					//vac_lve
					$vacationLeave .= date('m/d',strtotime($dt1['date'])).';';
				} elseif ($dt1['leaveId'] == '10'){
					//SICK_lve
					$sickLeave .= date('m/d',strtotime($dt1['date'])).';';
				} elseif ($dt1['leaveId'] == '15'){
					//UNION LEAVE
					$unionLeave .= date('m/d',strtotime($dt1['date'])).';';
				} elseif ($dt1['leaveId'] == '6'){
					//WITH PAY LEAVE
					$wpLeave .= date('m/d',strtotime($dt1['date'])).';';
				} elseif ($dt1['leaveId'] == '12'){
					//OFFICIAL LEAVE
					$officialLeave .= date('m/d',strtotime($dt1['date'])).';';
				} elseif ($dt1['leaveId'] == '8'){
					//PATERNITY LEAVE
					$paternityLeave .= date('m/d',strtotime($dt1['date'])).';';
				} elseif ($dt1['leaveId'] == '7'){
					//FUNERAL LEAVE
					$funeralLeave .= date('m/d',strtotime($dt1['date'])).';';
				} elseif($dt1['isBirthday'] == '1'){
					//Bday leave
					$bdayLeave .= $dt1['date'];
				} elseif($dt1['leaveId'] == '5'){
					//WO
					$withoutPay .= date('m/d',strtotime($dt1['date'])).';';
				} elseif($dt1['leaveId'] == '6'){
					//WP
					$withPay .= date('m/d',strtotime($dt1['date'])).';';
				} elseif($dt1['leaveId'] == '4'){
					//ML
					$maternityLeave .= date('m/d',strtotime($dt1['date'])).';';
				}
			}
			
			if ($vacationLeave){$payrollRem = "VL (".$vacationLeave.")";}
			if ($sickLeave){$payrollRem .= " SL (".$sickLeave.")";}
			if ($unionLeave){$payrollRem .= " UL (".$unionLeave.")";}
			if ($wpLeave){$payrollRem .= " WP (".$wpLeave.")";}
			if ($officialLeave){$payrollRem .= " OL (".$officialLeave.")";}
			if ($paternityLeave){$payrollRem .= " PL (".$paternityLeave.")";}
			if ($funeralLeave){$payrollRem .= " FL (".$funeralLeave.")";}
			if ($bdayLeave){$payrollRem .= " BL (".$bdayLeave.")";}
			if ($withoutPay){$payrollRem .= " WO (".$withoutPay.")";}
			if ($withPay){$payrollRem .= " WP (".$withPay.")";}
			if ($maternityLeave){$payrollRem .= " ML (".$maternityLeave.")";}
			
			$updatePayroll = mysql_query("UPDATE payroll SET 
																										 days_absent = '".$dt['daysAbsent']."'
																										 , undertime = '".($dt['undertime']/60)."'
																										 , ot_reg = '".$dt['otReg']."'
																										 , ot_exc = '".$dt['otExc']."'
																										 , days_work = '".$dt['daysWork']."'
																										 , spholiday = '".$dt['specialHolidayPrem']."'
																										 , otLHoliday = '".$dt['otLHoliday']."' 
																										 , lholiday = '".$dt['legalHolidayPrem']."'
																										 , otSHoliday = '".$dt['otSHoliday']."'
																										 , duty_rd = '".$dt['dutyRd']."'
																										 , otRestDay = '".$dt['otRestDay']."'
																										 , vac_lve = '".$dt['vacLeave']."'
																										 , unionLeave = '".$dt['unionLeave']."'
																										 , wpLeave = '".$dt['wpLeave']."'
																										 , official_lve = '".$dt['officialLeave']."'
																										 , sick_lve = '".$dt['sickLeave']."'
																										 , paternityLeave = '".$dt['paternityLeave']."'
																										 , funeralLeave = '".$dt['funeralLeave']."'
																										 , night_prem = '".$dt['nightPremium']."'
																										 ,residencyTrainingProgram='".$dt['residencyTrainingProgram']."'
																										 ,employmentStatus='".$dt['employmentStatus']."'
																										 ,level='".$dt['level']."'
																										 , payrollRemarks = '".$payrollRem."'
																												WHERE empid='".$dt['employeeId']."' && pay_period='".$getInfoCutoffDate['payrollDate']."'",$conn);
			
			
		//}	
	}
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
     <h2>Payroll Cut-Off</h2>   
    <div class="clearfix">
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=runCutoff" margin="0px;" name="myForm">
				<select name="cutoffDate"><?php echo $optionSelectPayrollCutoffDate;?></select>
				<button>Re/Cut-off</button>
			</form>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
