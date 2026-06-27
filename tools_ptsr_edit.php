<?php
ob_start();
session_start();

$h = "<tr><td colspan='12'><br><br></td></tr>
		</td></tr><tr style=\"font-size:11px;\">
				<td>Seq&nbsp;&nbsp;&nbsp;</td>
				<td>Name</td>
				<td>Days Work</td>
				<td>Absent</td>
				<td>Undertime</td>
				<td>Overtime<br>(Reg)</td>
				<td>Overtime<br>(Exc)</td>
				<td>Overtime<br>(Duty Restday)</td>
				<td>Overtime<br>(LHoliday)</td>
				<td>Overtime<br>(SHoliday)</td>
				<td>Overtime<br>(RDSHoliday)</td>
				<td>Overtime<br>(RDLHoliday)</td>
				<td>Night <br>Premium</td>
				<td>Special<br>Holiday</td>
				<td>Legal<br>Holiday</td>
				<td>Duty<br>Restday</td>
				<td>Vacation<br>Leave</td>
				<td>Union<br>Leave</td>
				<td>Paternity<br>Leave</td>
				<td>Sick<br>Leave</td>
				<td>Official<br>Leave</td>
				<td>Funeral<br>Leave</td>
				<td>WithPay<br>Leave</td>
				<td>Birthday<br>Leave</td>
				<td style=\"color:maroon;\">Adjustment<br>OT (Reg)</td>
				<td style=\"color:maroon;\">Adjustment<br>Undertime</td>
				<td style=\"color:maroon;\">Adjustment<br>Days Work</td>
				<td style=\"color:maroon;\">Adjustment<br>Sick Leave</td>
				<td style=\"color:maroon;\">Adjustment<br>Vacation Leave</td>
				<td style=\"color:maroon;\">Adjustment<br>Night Premium</td>
				<td style=\"color:maroon;\">Adjustment<br>Duty Restday</td>
				<td style=\"color:maroon;\">Adjustment<br>Official Leave</td>
				<td style=\"color:maroon;\">Adjustment<br>Bday Leave</td>
			</tr>";
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
if($_GET['act']=='sabmet'){
		$sql ="select e.lastName,e.firstName,e.middleName,p.*,d.name departmentName
												from payroll p 
													left join employee e on e.ndex=p.empid 
													LEFT JOIN dept d ON d.ndex = e.deptId 
														where p.pay_period='".$_GET['PayrollCutoff']."'";
		
		if ($_GET['division']){$sql .= " && e.divisionId='".$_GET['division']."'";}
		if ($_GET['mbtcCompany'] == 1){
			$sql .= " && e.level IN (0)";
			$reportTitle = 'CONTRACTUAL';
		} elseif ($_GET['mbtcCompany'] == 2) {
			$sql .= " && e.level IN (1,2)";
			$reportTitle = 'RANK & FILE';
		} elseif ($_GET['mbtcCompany'] == 3) {
			$sql .= " && e.level IN (3,4,5)";
			$reportTitle = 'OFFICER';
		} elseif ($_GET['mbtcCompany'] == 0) {
			$reportTitle = 'ALL EMPLOYEE';
		}
		$sql .= "order by d.name,e.lastName,e.firstName,e.middleName";
		$emp=mysql_query($sql);
		$var=0;
		while($r=mysql_fetch_object($emp)){
			$var++;
			$ctr1s++;
			 if($r->departmentName != $prevDepartment){
		 		$d .= "<tr><td colspan='17'><br><br></td></tr><tr></td><td colspan='17'> &nbsp; &nbsp;<b> ".$r->departmentName."</b></td></tr>";
			}
			if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
			$d.="<tr style='font-size:12px;background-color:".$bgclr1s."'>
					<td>".$var."</td>
					<td>".$r->lastName.",".$r->firstName."&nbsp;".$r->middleName."</td>
					<td><input type='Text' style='text-align:right;' size='5' name='days_work".$r->ndex."' value='".$r->days_work."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='days_absent".$r->ndex."' value='".$r->days_absent."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='undertime".$r->ndex."' value='".$r->undertime."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='ot_reg".$r->ndex."' value='".$r->ot_reg."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='ot_exc".$r->ndex."' value='".$r->ot_exc."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='otRestDay".$r->ndex."' value='".$r->otRestDay."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='otLHoliday".$r->ndex."' value='".$r->otLHoliday."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='otSHoliday".$r->ndex."' value='".$r->otSHoliday."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='otRDSHoliday".$r->ndex."' value='".$r->otRDSHoliday."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='otRDLHoliday".$r->ndex."' value='".$r->otRDLHoliday."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='night_prem".$r->ndex."' value='".$r->night_prem."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='spholiday".$r->ndex."' value='".$r->spholiday."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='lholiday".$r->ndex."' value='".$r->lholiday."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='duty_rd".$r->ndex."' value='".$r->duty_rd."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='vac_lve".$r->ndex."' value='".$r->vac_lve."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='unionLeave".$r->ndex."' value='".$r->unionLeave."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='paternityLeave".$r->ndex."' value='".$r->paternityLeave."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='sick_lve".$r->ndex."' value='".$r->sick_lve."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='official_lve".$r->ndex."' value='".$r->official_lve."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='funeralLeave".$r->ndex."' value='".$r->funeralLeave."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='wpLeave".$r->ndex."' value='".$r->wpLeave."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='bday_lve".$r->ndex."' value='".$r->bday_lve."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='adj_ot_reg".$r->ndex."' value='".$r->adj_ot_reg."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='adj_undertime".$r->ndex."' value='".$r->adj_undertime."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='adj_days_work".$r->ndex."' value='".$r->adj_days_work."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='adj_sick_lve".$r->ndex."' value='".$r->adj_sick_lve."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='adj_vac_lve".$r->ndex."' value='".$r->adj_vac_lve."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='adj_night_prem".$r->ndex."' value='".$r->adj_night_prem."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='adj_duty_rd".$r->ndex."' value='".$r->adj_duty_rd."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='adj_official_lve".$r->ndex."' value='".$r->adj_official_lve."'></td>
					<td><input type='Text' style='text-align:right;' size='5' name='adj_bday_lve".$r->ndex."' value='".$r->adj_bday_lve."'></td>
			</tr>
			";
			$prevDepartment = $r->departmentName;
			if (fmod($var,10) == 0){
				$d.= $h;
			}
		}
		
}
elseif($_GET['act']=='update'){
		//$emp=mysql_query("select e.lastName,e.firstName,e.middleName,p.* from payroll p left join employee e on e.ndex=p.empid where p.pay_period='".$_GET['PayrollCutoff']."' and e.divisionId=".$_GET['division']." order by e.lastName,e.firstName,e.middleName");
		$sql ="select e.lastName,e.firstName,e.middleName,p.*,d.name departmentName
												from payroll p 
													left join employee e on e.ndex=p.empid 
													LEFT JOIN dept d ON d.ndex = e.deptId 
														where p.pay_period='".$_GET['PayrollCutoff']."'";
		if ($_GET['division']){$sql .= " && e.divisionId='".$_GET['division']."'";}
		if ($_GET['mbtcCompany'] == 1){
			$sql .= " && e.level IN (0)";
			$reportTitle = 'CONTRACTUAL';
		} elseif ($_GET['mbtcCompany'] == 2) {
			$sql .= " && e.level IN (1,2)";
			$reportTitle = 'RANK & FILE';
		} elseif ($_GET['mbtcCompany'] == 3) {
			$sql .= " && e.level IN (3,4,5)";
			$reportTitle = 'OFFICER';
		} elseif ($_GET['mbtcCompany'] == 0) {
			$reportTitle = 'ALL EMPLOYEE';
		}
		$sql .= "order by d.name,e.lastName,e.firstName,e.middleName";
		$emp=mysql_query($sql);
		$var=0;
		while($r=mysql_fetch_object($emp)){
		$var++;
			$upd=mysql_query("update payroll set 
								days_work='".$_POST['days_work'.$r->ndex]."',
								days_absent='".$_POST['days_absent'.$r->ndex]."',
								undertime='".$_POST['undertime'.$r->ndex]."',
								ot_reg='".$_POST['ot_reg'.$r->ndex]."',
								ot_exc='".$_POST['ot_exc'.$r->ndex]."',
								otRestDay='".$_POST['otRestDay'.$r->ndex]."',
								otLHoliday='".$_POST['otLHoliday'.$r->ndex]."',
								otSHoliday='".$_POST['otSHoliday'.$r->ndex]."',
								otRDSHoliday='".$_POST['otRDSHoliday'.$r->ndex]."',
								otRDLHoliday='".$_POST['otRDLHoliday'.$r->ndex]."',
								night_prem='".$_POST['night_prem'.$r->ndex]."',
								spholiday='".$_POST['spholiday'.$r->ndex]."',
								lholiday='".$_POST['lholiday'.$r->ndex]."',
								duty_rd='".$_POST['duty_rd'.$r->ndex]."',
								vac_lve='".$_POST['vac_lve'.$r->ndex]."',
								unionLeave='".$_POST['unionLeave'.$r->ndex]."',
								paternityLeave='".$_POST['paternityLeave'.$r->ndex]."',
								sick_lve='".$_POST['sick_lve'.$r->ndex]."',
								funeralLeave='".$_POST['funeralLeave'.$r->ndex]."',
								official_lve='".$_POST['official_lve'.$r->ndex]."',
								wpLeave='".$_POST['wpLeave'.$r->ndex]."',
								bday_lve='".$_POST['bday_lve'.$r->ndex]."',
								adj_ot_reg='".$_POST['adj_ot_reg'.$r->ndex]."',
								adj_undertime='".$_POST['adj_undertime'.$r->ndex]."',
								adj_days_work='".$_POST['adj_days_work'.$r->ndex]."',
								adj_sick_lve='".$_POST['adj_sick_lve'.$r->ndex]."',
								adj_vac_lve='".$_POST['adj_vac_lve'.$r->ndex]."',
								adj_night_prem='".$_POST['adj_night_prem'.$r->ndex]."',
								adj_duty_rd='".$_POST['adj_duty_rd'.$r->ndex]."',
								adj_official_lve='".$_POST['adj_official_lve'.$r->ndex]."',
								adj_bday_lve='".$_POST['adj_bday_lve'.$r->ndex]."'
							WHERE ndex=".$r->ndex."");
		}
		header("Location: tools_ptsr_edit.php?act=sabmet&PayrollCutoff=".$_GET['PayrollCutoff']."&division=".$_GET['division']."&mbtcCompany=".$_GET['mbtcCompany']."");
}
//$rs = mysql_query("SELECT * FROM cutoffdates where isLock=0 ORDER BY payrollDate DESC limit 12",$conn);
$rs = mysql_query("SELECT * FROM cutoffdates where isLock=0 ORDER BY payrollDate DESC",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
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
     <h2>Update Payroll Time Summary</h2>   
    <div class="clearfix">
<?php if(!$_GET['act']){?>
	<form name="frmemp" action="tools_ptsr_edit.php" method="get">
	<input type="Hidden" name="act" value="sabmet">
		<table width="800">
			<tr>
				<td>Payroll Period <select name="PayrollCutoff"><?php echo $optionSelectPayrollCutoffDate;?></select></td>
				<td>Division:<select name="division"><?php echo $optiondivision; ?></select></td>
				<td><select name='mbtcCompany'><?php echo $optionMBTCCompany;?></select></td>
				<td><input type="Submit" value="Search"></td></tr>			
		</table>
	</form>
<?php } 
else{?>
	<form name="frmemp" action="tools_ptsr_edit.php?act=update&division=<?php echo $_GET['division'];?>&PayrollCutoff=<?php echo $_GET['PayrollCutoff'];?>&mbtcCompany=<?php echo $_GET['mbtcCompany']; ?>" method="post">
		<table width="800">
			<tr><td colspan="17"></td><td colspan="21" align="center" style="color:maroon;font-weight:bold;"><u>ADJUSTMENTS</u></td></tr>
			<tr style="font-size:11px;">
				<td>Seq&nbsp;&nbsp;&nbsp;</td>
				<td>Name</td>
				<td>Days Work</td>
				<td>Absent</td>
				<td>Undertime</td>
				<td>Overtime<br>(Reg)</td>
				<td>Overtime<br>(Exc)</td>
				<td>Overtime<br>(Duty Restday)</td>
				<td>Overtime<br>(LHoliday)</td>
				<td>Overtime<br>(SHoliday)</td>
				<td>Overtime<br>(RDSHoliday)</td>
				<td>Overtime<br>(RDLHoliday)</td>
				<td>Night<br>Premium</td>
				<td>Special<br>Holiday</td>
				<td>Legal<br>Holiday</td>
				<td>Duty<br>Restday</td>
				<td>Vacation<br>Leave</td>
				<td>Union<br>Leave</td>
				<td>Paternity<br>Leave</td>
				<td>Sick<br>Leave</td>
				<td>Official<br>Leave</td>
				<td>Funeral<br>Leave</td>
				<td>WithPay<br>Leave</td>
				<td>Birthday<br>Leave</td>
				<td style="color:maroon;">Adjustment<br>OT (Reg)</td>
				<td style="color:maroon;">Adjustment<br>undertime</td>
				<td style="color:maroon;">Adjustment<br>Days Work</td>
				<td style="color:maroon;">Adjustment<br>Sick Leave</td>
				<td style="color:maroon;">Adjustment<br>Vacation Leave</td>
				<td style="color:maroon;">Adjustment<br>Night Premium</td>
				<td style="color:maroon;">Adjustment<br>Duty Restday</td>
				<td style="color:maroon;">Adjustment<br>Official Leave</td>
				<td style="color:maroon;">Adjustment<br>Bday Leave</td>
			</tr>
			<tr><td colspan=30"><hr></td></tr>
			<?php echo $d; ?>
			<tr><td colspan="30"><hr></td></tr>
			<tr><td colspan="30" align="right"><input type="Submit" value="UPDATE"></td></td></tr>
		</table>
	</form>
<?php } ?>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
