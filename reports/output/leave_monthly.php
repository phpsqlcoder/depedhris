<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
if($_POST['lvl']=='all'){
	$stqry="";
}
else{
	$stqry="and e.level in (".$_POST['lvl'].")";
}

$qry="SELECT distinct e.ndex as ndx,e.firstName,e.lastName,e.middleName,p.name as position,e.employmentStatus,e.employeeNo,d.name as dept,e.employmentStatus,e.dateHired from 
employee_leave l left join employee e on e.ndex=l.employeeId

left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
$qry.=" WHERE e.isActive=1 ".$stqry."  and l.startDate between '".$_POST['startdate']."' and '".$_POST['enddate']."'";
$qry.=" ORDER BY e.lastName,e.firstName,e.middleName";
//echo $qry;
$exec=mysql_query($qry);
$var=0;

while($r=mysql_fetch_object($exec)){

     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	 $lqry="SELECT ll.name as lev,l.startDate from 
		employee_leave l left join employee e on e.ndex=l.employeeId
		left join `leave` ll on ll.ndex=l.leaveId";
		$lqry.=" WHERE e.isActive=1 ".$stqry." and e.ndex=".$r->ndx." and l.startDate between '".$_POST['startdate']."' and '".$_POST['enddate']."' order by l.ndex";
		//echo $lqry;
		//echo $lqry."<br><br>";
		$leqry=mysql_query($lqry);
		$leaves="";
		$tot=0;
		while($le=mysql_fetch_object($leqry)){
			$leaves.=$le->startDate.",";
			$tot++;
		}
		$leaves=rtrim($leaves,",");
		
	 $lqry2="SELECT ll.name as lev,count(l.ndex) as su from 
		employee_leave l left join employee e on e.ndex=l.employeeId
		left join `leave` ll on ll.ndex=l.leaveId";
		$lqry2.=" WHERE e.isActive=1 ".$stqry." and e.ndex=".$r->ndx." and l.startDate between '".$_POST['startdate']."' and '".$_POST['enddate']."' GROUP BY ll.name order by l.ndex";
		//echo $lqry."<br><br>";
		$leqry2=mysql_query($lqry2);
		$leaves2="";
		//$tot2=0;
		while($le2=mysql_fetch_object($leqry2)){
			$leaves2.=$le2->lev."=".$le2->su.",";
			//$tot++;
		}
		$leaves2=rtrim($leaves2,",");
     $data.="<tr bgcolor='".$bgclr1s."'>
	       <td>".$var."</td>
		   <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
	       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
		   <td>".$r->dept."</td>
		   <td>".$tot."</td>
		   <td>".$leaves."</td>
		   <td>".$r->lev."</td>
		   <td>".$leaves2."</td>
	
		   ";
      $data.="</tr>";
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="leavelimit.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="20" align="center" style="font-size:14px;font-weight:bold;">Leave Report<br> <?php echo $_POST['startdate']." to ".$_POST['enddate'];?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
		   <td>ID</td>
	       <td>Name</td>
		
		   <td>Dept</td>
		   <td>Total</td>
		   <td>Date</td>
		   <td>Type</td>
		
		  
	  </tr>
	  <tr><td colspan="20"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




