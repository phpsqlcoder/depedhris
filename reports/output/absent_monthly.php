<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../myfunctions.php");
include("../../employeefunctions.php");
$qry="SELECT e.ndex as emp,e.biometricNo as bio,e.employmentStatus,e.employeeNo,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.level from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId where e.isActive=1 ";
if($_POST['dept']!='ALL'){
	$qry.=" and d.ndex=".$_POST['dept']."";
}
if($_POST['lvl']!='ALL'){
	
if($_POST['lvl']=='rf'){
	$qry.=" and e.level in (1,2)";
}
if($_POST['lvl']=='hc'){
	$qry.=" and e.level in (3,4,5,6)";
}	
}
if($_POST['emptxt']){
	$qry.=" and (e.lastName like '%".$_POST['emptxt']."%' OR e.firstName like '%".$_POST['emptxt']."%')";
}
$qry.=" and e.isActive=1";
//echo $qry;
$exec=mysql_query($qry);
$var=0;
while($r=mysql_fetch_object($exec)){
	 $start = strtotime($_POST['startdate']);
	 $end = strtotime($_POST['enddate']);
	 $latedata="";
	 $totalabs=0;
		for ( $i = $start; $i <= $end; $i += 86400 ){
			$datelog=date('Y-m-d',$i);
			//$daily=mysql_fetch_object(mysql_query("select * from dailytimesummary where employeeId=".$r->emp." and date='".$datelog."' and isDayOff=0 and holiday=''"));
			if(checkDayIfAbsent($datelog,$r->emp)==0){
				$totalabs++;
				$ctr1s++;
  			    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
				$latedata.="".$datelog.",";
			}
		}
	if($latedata!=''){
	     $var++;
	     $data.="<tr style='color:black;font-weight:bold;'>
		       <td>".$var."</td>
			   <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
		       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
			   <td>".$r->level."</td>
			   <td>".$r->dept."</td>
		       <td>".$r->position."</td>
			   <td>".$totalabs." days</td>
	     </tr>
		 <tr style='color:maroon;font-size:11px;font-weight:bold;'>
		 	<td>&nbsp;</td>
			<td colspan='5'>Date/s:".$latedata."</td>
		 </tr>";
	 }
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="absentreport.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="6" align="center" style="font-size:14px;font-weight:bold;">Absent Report<br><?php echo $_POST['startdate']." to ".$_POST['enddate'];?> </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
		   <td>ID</td>
	       <td>Name</td>
		   <td>Level</td>
		   <td>Dept</td>
	       <td>Position</td>
		   <td>Total</td>
	  </tr>
	  <tr><td colspan="6"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




