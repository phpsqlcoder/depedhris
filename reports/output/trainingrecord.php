<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
$qry="SELECT t.* from emptrainings t left join employee e on e.ndex=t.employeeId left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";

$qry.=" WHERE t.employeeId = '".$_POST['id']."' and t.name<>''";
$qry.=" ORDER BY t.fromDate";
$exec=mysql_query($qry);
$var=0;
$total=0;
while($r=mysql_fetch_object($exec)){
     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	 if($r->endDate=='0000-00-00'){$r->endDate=$r->fromDate;}
	 $ts1 = strtotime($r->fromDate);
	 $ts2 = strtotime($r->endDate);
	 $seconds_diff = $ts2 - $ts1;
	 $noofdays=floor($seconds_diff/3600/24) + 1;
	 $total+=$noofdays;
     $data.="<tr bgcolor='".$bgclr1s."'>
	       <td>".$var."</td>
	       <td>".$r->name."</td>
		    <td>".$r->fromDate."</td>
	       <td>".$r->endDate."</td>
		   <td align='center'>".$noofdays."</td>
		   <td>".$r->venue."</td>
     </tr>";
}
$r=mysql_fetch_object(mysql_query("SELECT d.name as dept,p.name as positionn,e.* from 
 employee e 
 left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId WHERE e.ndex = '".$_POST['id']."'"));
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="trainings.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr><td>ID No:</td><td><?php echo getID($r->employmentStatus,$r->employeeNo);?></td></tr>
	 <tr><td>Name:</td><td colspan="4"><?php echo $r->lastName." , ".$r->firstName." ".$r->middleName;?></td></tr>
	 <tr><td>Position:</td><td colspan="4"><?php echo $r->positionn;?></td></tr>
	 <tr><td>Dept:</td><td colspan="4"><?php echo $r->dept;?></td></tr>
	  <tr>
	       <td colspan="6" align="center" style="font-size:14px;font-weight:bold;">Training Record<br></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
	       <td>Training Attended</td>
		   <td>From</td>
	       <td>To</td>
		   <td align="right">No. of days</td>
		   <td>Type</td>
	  </tr>
	  <tr><td colspan="6"><hr></td></tr>
	  <?php echo $data;?>
	  <tr><td colspan="6"><hr></td></tr>
	  <tr><td colspan="4">&nbsp;</td><td align="center"><?php echo $total;?></td></tr>
      </table>
	  <?php include("../rptfooter.php");?>




