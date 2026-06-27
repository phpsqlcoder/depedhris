<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
	include ("myfunctions.php");
if($_GET['act']=='process'){
	//$test=mysql_query("update hrinterface set isProcessed='' where isProcessed=1");
	//$jk=mysql_query("delete from dailytimesummary where employeeId=690");
	/*$e=mysql_query("select dtrid,datelog,count(*) as cnt from hrinterface where datelog>='".$_POST['startDate']."' and datelog<='".$_POST['endDate']."'  group by dtrid,datelog");
		while($rs=mysql_fetch_object($e)){
			if($rs->cnt>=1){
				$qr=mysql_query("select * from hrinterface where datelog='".$rs->datelog."' and dtrid='".$rs->dtrid."' order by hrint_id");
				$ee=mysql_fetch_object(mysql_query("select * from employee where biometricNo=".$rs->dtrid.""));
				$ar=0;
				$check_duplicate_record=0;
				$hours_duty=0;
				$breaklogs=array();
				while($s=mysql_fetch_object($qr)){
					array_push($breaklogs,$s->log);
					$ar++;
					$inout[$ar]=$s->in_out;
					//if($inout[$ar]==$inout[$ar - 1]){$check_duplicate_record=1;} // If nagduplicate and IN or OUT
					//else{
						if($s->in_out==0){ // Select IN records only
							if($ee->ndex){
								$shift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$ee->ndex." and '".$s->datelog."' between startDate and endDate"));
							}
							if($shift->shiftingId){
								$out=mysql_fetch_object(mysql_query("SELECT * FROM `hrinterface` where in_out=1 and hrint_id> ".$s->hrint_id." and dtrid=".$s->dtrid." limit 0,1")); // Get OUT record
								if($out->log){
									unset($breaklogs[0]); $last_element_breaklogs=count($breaklogs) - 1; unset($breaklogs[$last_element_breaklogs]);
									$total_break_time=getBreakTimeTotal($breaklogs,$shift->shiftingId);
									$hours_duty+=dailyTimeTotal($s->log,$out->log,$shift->shiftingId);
									$late=lateTotalinMinutes($s->log,$shift->shiftingId); // Late
									$undertime=undertimeTotalinMinutes($out->log,$shift->shiftingId); // Undertime
									$overtime=overtimeTotalinMinutes($out->log,$shift->shiftingId); // Overtime
									$nytdifferential=nightDifferential($s->log,$out->log,$shift->shiftingId);
									if($nytdifferential<0){
										$check_duplicate_record=4;// error nytdiff
									}
								}
								else{
									$check_duplicate_record=2; // No Out
								}
							}
							else{
								$check_duplicate_record=3; // No Shifting 
							}
						}
					//}
				}
				$chkifexist=mysql_num_rows(mysql_query("select * from dailytimesummary where employeeId='".$ee->ndex."' and date='".$rs->datelog."'"));
				if($chkifexist>=1){
					$updatelog=mysql_query("update dailytimesummary set hoursDuty=".$hours_duty.",isError=".$check_duplicate_record.",undertime=".$undertime.",overtime=".$overtime.",minutesLate=".$late.",night_prem=".$nytdifferential." where employeeId='".$ee->ndex."' and date='".$rs->datelog."'");
				}
				else{
				$insert=mysql_query("insert into dailytimesummary
						    (`employeeId`, `date`, `days_absent`, `undertime`, `ot_reg`, `ot_exc`, `days_work`, `spholiday`, `lholiday`, `duty_rd`, `vac_lve`, `man_lve`, `sick_lve`, `night_prem`, `isError`, `hoursDuty`,`minutesLate`,`overtime`)
						 VALUES
						    ('".$ee->ndex."','".$rs->datelog."',0,".$undertime.",0,0,0,0,0,0,0,0,0,".$nytdifferential.",".$check_duplicate_record.",".$hours_duty.",".$late.",".$overtime.")");
				}
				$changeisProcess=mysql_query("update hrinterface set isProcessed=1 where dtrid='".$rs->dtrid."' and datelog='".$rs->datelog."'");
			}
		}*/
		$dated=strtotime($_POST['startDate']);
		echo $_POST['startDate'];
		while ($dated <= strtotime($_POST['endDate'])) {
			echo $dated."<br>";
			$dated = date ("Y-m-d", strtotime("+1 day", strtotime($dated)));
		}
		//$insert_absent=
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
	<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
</head>

<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Process Time Logs</h2>
    
    <div class="clearfix">
<form action="tools_processtimelogs.php?act=process" name="tlfrm" method="post">
	<table>
		<tr>
			<td>Start Date:</td><td><input type="Text" name="startDate" id="startDate" size="15"><a href="javascript:show_calendar('tlfrm.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
			<td>End Date:&nbsp;&nbsp;</td><td><input type="Text" name="endDate" id="endDate" size="15"><a href="javascript:show_calendar('tlfrm.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;<input type="Submit" value="Process"></td>
		</tr>
	</table>
</form>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>
