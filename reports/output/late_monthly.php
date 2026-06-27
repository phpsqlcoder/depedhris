<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
$qry="SELECT e.ndex as emp,e.biometricNo as bio,e.employmentStatus,e.employeeNo,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId
left join division di on di.ndex=d.divisionId
 where e.isActive=1 and e.biometricNo>0";
if($_POST['dept']!='ALL'){
	$qry.=" and d.ndex=".$_POST['dept']."";
}
if($_POST['divi']!='ALL'){
	$qry.=" and di.ndex=".$_POST['divi']."";
}
if($_POST['leve']!='ALL'){
	$qry.=" and e.level=".$_POST['leve']."";
}
if($_POST['emptxt']){
	$qry.=" and (e.lastName like '%".$_POST['emptxt']."%' OR e.firstName like '%".$_POST['emptxt']."%')";
}
//echo $qry;
$qry.=" ORDER BY d.name,e.lastName,e.firstName";
$exec=mysql_query($qry);
$var=0;
while($r=mysql_fetch_object($exec)){
	 $start = strtotime($_POST['startdate']);
	 $end = strtotime($_POST['enddate']);
	 $latedata="";
		for ( $i = $start; $i <= $end; $i += 86400 ){
			$datelog=date('Y-m-d',$i);
			$daily=mysql_fetch_object(mysql_query("select * from dailytimesummary where employeeId=".$r->emp." and date='".$datelog."' and isDayOff=0 and holiday=''"));
			if($daily->undertime>0 || $daily->minutesLate>0){
				$shiftsked=mysql_fetch_object(mysql_query("select e.*,s.name as shft from employee_shifting e left join shifting s on s.ndex=e.shiftingId where e.employeeId=".$r->emp." and e.approvedDate<>'0000-00-00 00:00:00' and '".$datelog."' between e.startDate and e.endDate"));
				$nxtday_timeIn=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->bio."' and datelog>'".$datelog."' and in_out=0 ORDER BY log LIMIT 0,1"));
				if(!$nxtday_timeIn->hrint_id){
					$tlqryqry="select * from hrinterface where dtrid='".$r->bio."' and datelog>='".$datelog."' ORDER BY log";
				}
				else{
					$tlqryqry="select * from hrinterface where dtrid='".$r->bio."' and datelog>='".$datelog."' and log<'".$nxtday_timeIn->log."' ORDER BY log";
				}
				$tlqry=mysql_query($tlqryqry);				
				$timeLogs="";
				$val=0;
				while($tl=mysql_fetch_object($tlqry)){
					$val++;
					if($tl->in_out==1){$clr='blue;font-weight:bold;';}else{$clr='black:font-weight:normal;';}
					if($val==1 && $tl->in_out==1){
						$timeLogs.="";
					}
					else{
						$timeLogs.="<font  style='color:".$clr."'>".substr($tl->log,11)."</font>&nbsp;&nbsp;&nbsp;&nbsp;";
						
					}
				}
				$ctr1s++;
  			    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
				$latedata.="<tr bgcolor='".$bgclr1s."'>
								<td>&nbsp;</td>
								<td>".$datelog."</td>
								<td>".$shiftsked->shft."&nbsp;&nbsp;".$timeLogs."</td>
								<td>".$daily->minutesLate."</td>
								<td>".$daily->undertime."</td>
				</tr>";
			}
		}
	if($latedata!=''){
	     $var++;
	     $data.="<tr style='color:black;font-weight:bold;'>
		       <td>".$var."</td>
			   <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
		       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
			   <td>".$r->dept."</td>
		       <td colspan='2'>".$r->position."</td>
	     </tr>
		 <tr style='color:maroon;font-size:11px;font-weight:bold;'>
		 	<td>&nbsp;</td>			
			<td>Date</td>
			<td>Logs</td>
			<td>Late</td>
			<td>Undertime</td>
		 </tr>
		 ".$latedata."<tr><td>&nbsp;</td></tr>";
	 }
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="latereport.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="6" align="center" style="font-size:14px;font-weight:bold;">Late Report<br><?php echo $_POST['startdate']." to ".$_POST['enddate'];?> </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
		   <td>ID</td>
	       <td>Name</td>
		   <td>Dept</td>
	       <td>Position</td>
	  </tr>
	  <tr><td colspan="6"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




