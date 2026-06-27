<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
$ckqry=mysql_num_rows(mysql_query("SELECT e.firstName,e.lastName,e.middleName,d.name as dept from dailytimesummary dt
left join employee e on e.ndex=dt.employeeId left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId
WHERE e.deptId = '".$_POST['tdept']."' and dt.isError=1 and e.isActive=1
ORDER BY e.lastName,e.firstName
"));
$sqry="select * from dept where ndex<>0";
if($_POST['tdept']!='ALL'){
	$sqry.=" and ndex=".$_POST['tdept']."";
}
$dp=mysql_fetch_object(mysql_query($sqry));
$dqry="select * from dept where ndex<>0";
if($_POST['tdept']!='ALL'){
	$dqry.=" and ndex=".$_POST['tdept']."";
	$hdr=$dp->name;
}
else{
	$hdr="ALL DEPT";
}
$execdqry=mysql_query($dqry." ORDER BY name");
while($d=mysql_fetch_object($execdqry)){
	$qry="SELECT e.ndex as empid,e.firstName,e.lastName,e.middleName,e.employeeNo,e.employmentStatus,d.name as dept,
	sum(dt.hoursDuty) as hoursDuty,
	sum(dt.minutesLate) as late,
	sum(dt.undertime) as undertime,
	sum(dt.days_work) as days_work,
	sum(dt.days_absent) as days_absents,
	sum(dt.undertime) as undertime,
	sum(dt.approvedOvertimeNightPremium) as approvedOvertimeNightPremium,
	sum(dt.night_prem) as night_prem,
	sum(dt.approvedOvertime) as approvedOvertime,
	sum(dt.holiday_off) as holiday_off
	 from dailytimesummary dt
	left join employee e on e.ndex=dt.employeeId left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
	$qry.=" WHERE dt.date>='".$_POST['startdates']."' and dt.date<='".$_POST['enddates']."' and e.deptId = '".$d->ndex."' and e.isActive=1";
	$qry.=" GROUP BY e.firstName,e.lastName,e.middleName,d.name
	ORDER BY e.lastName,e.firstName";
	//echo $qry."<br><br><br>";
	$exec=mysql_query($qry);
	$data.="<tr>
				<td>&nbsp;</td>
				<td colspan='5' style='font-weight:bold;font-size:13px;color:maroon;'>".$d->name."</td>
	</tr>";
	$var=0;
	$t1=0;
	$t2=0;
	$t3=0;
	$t4=0;
	$t5=0;
	$t6=0;
	$t7=0;
	$t8=0;
	$t9=0;
	$t10=0;
	$t11=0;
	$t12=0;
	while($r=mysql_fetch_object($exec)){
		$drd=mysql_fetch_object(mysql_query("select sum(hoursDuty) as rd from dailytimesummary where employeeId='".$r->empid."' and isDayOff=1 and date>='".$_POST['startdates']."' and date<='".$_POST['enddates']."'"));
		$sh=mysql_fetch_object(mysql_query("select count(*) as ash from dailytimesummary where employeeId='".$r->empid."' and holiday='S' and date>='".$_POST['startdates']."' and date<='".$_POST['enddates']."'"));
		$lh=mysql_fetch_object(mysql_query("select count(*) as alh from dailytimesummary where employeeId='".$r->empid."' and holiday='L' and date>='".$_POST['startdates']."' and date<='".$_POST['enddates']."'"));
		$ab=mysql_fetch_object(mysql_query("select sum(days_absent) as abse  from dailytimesummary where employeeId='".$r->empid."' and days_absent='1' and date>='".$_POST['startdates']."' and date<='".$_POST['enddates']."'"));
		$shp=mysql_fetch_object(mysql_query("select sum(hoursDuty) as ashp from dailytimesummary where employeeId='".$r->empid."' and holiday='S' and days_work=1 and date>='".$_POST['startdates']."' and date<='".$_POST['enddates']."'"));
		$lhp=mysql_fetch_object(mysql_query("select sum(hoursDuty) as alhp from dailytimesummary where employeeId='".$r->empid."' and holiday='L' and days_work=1 and date>='".$_POST['startdates']."' and date<='".$_POST['enddates']."'"));
		// leave
		$start = strtotime($_POST['startdates']);
		$end = strtotime($_POST['enddates']);
		$leavecounter=0;
		for ( $i = $start; $i <= $end; $i += 86400 ){
			$lvdate=date('Y-m-d',$i);
			$leaveqry=mysql_num_rows(mysql_query("select * from employee_leave where employeeId=".$r->empid." and leaveId not in (13,5) and '".$lvdate."' between startDate and endDate"));
			
			if($leaveqry==1){
			
				$leavecounter++;
			}
		}
		// end leave
	     $var++;
	     $ctr1s++;
	     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		 $np=$r->night_prem + $r->approvedOvertimeNightPremium;
		    $t1+=$r->days_work;
			$t2+=$ab->abse;
			$t3+=$drd->rd;
			$t4+=$sh->ash;
			$t5+=$lh->alh;
			$t6+=$shp->ashp;
			$t7+=$lhp->alhp;
			$t8+=$r->late;
			$t9+=$r->undertime/60;
			$t10+=$np;
			$t11+=$r->approvedOvertime;
			$t11+=$r->holiday_off;
	     $data.="<tr bgcolor='".$bgclr1s."'>
		       <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
		       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
			
			   <td>".$r->days_work."</td>
			   <td>".$ab->abse."</td>
			   <td>".$drd->rd."</td>
			   <td>".$sh->ash."</td>
			   <td>".$lh->alh."</td>
			   <td>".$shp->ashp."</td>
			   <td>".$lhp->alhp."</td>
			   <td>".$r->late."</td>
			   <td>".$r->undertime."</td>
			   <td>".$np."</td>
			    <td>".$r->approvedOvertime."</td>
				<td>".$leavecounter."</td>
				<td>".$r->holiday_off."</td>
	     </tr>";
	}
	$data.="
	<tr><td colspan='20'><hr></td></tr>
	<tr>
				<td>Total ".$var."</td>
				<td>&nbsp;</td>
				
				<td>".$t1."</td>
				<td>".$t2."</td>
				<td>".$t3."</td>
				<td>".$t4."</td>
				<td>".$t5."</td>
				<td>".$t6."</td>
				<td>".$t7."</td>
				<td>".$t8."</td>
				<td>".$t9."</td>
				<td>".$t10."</td>
				<td>".$t11."</td>
				<td>".$t12."</td>
	</tr><tr><td>&nbsp;</td></tr>";
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="timesummary.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="20" align="center" style="font-size:14px;font-weight:bold;">Time Summary Report<br> <?php echo $_POST['startdates']." to ".$_POST['enddates']."<br>".$hdr;?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
	       <td>Name</td>
		  
		   <td>Days Duty</td>
		   <td>Absent</td>		
		   <td>DRD</td>		   		   
		   <td>Special Holiday</td>
		   <td>Legal Holiday</td>		   
		   <td>Special Holiday<br>(Premium)</td>
		   <td>Legal Holiday<br>(Premium)</td>
		   <td>Late(min)</td>
		   <td>Undertime(min)</td>
		   <td>Night Premium</td>
		   <td>Overtime</td>
		   <td>Leave Used</td>
		   <td>Holiday Off</td>
		   
	  </tr>
	  <tr><td colspan="20"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




