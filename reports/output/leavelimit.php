<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
if($_POST['lvl']=='all'){
	$stqry2="";
}
else{
	$stqry2="and e.level in (".$_POST['lvl'].")";
}
if($_POST['emp']=='all'){
	$stqry="";
}
else{
	$stqry="and e.ndex in (".$_POST['emp'].")";
}


$qry="SELECT e.ndex as ndx,e.firstName,e.lastName,e.employeeNo,e.middleName,p.name as position,d.name as dept,e.employmentStatus,e.dateHired from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
$qry.=" WHERE e.isActive=1 ".$stqry." ".$stqry2."";
$qry.=" ORDER BY e.lastName,e.firstName,e.middleName";
//echo $qry;
$exec=mysql_query($qry);
$var=0;
while($r=mysql_fetch_object($exec)){
     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
     $data.="<tr bgcolor='".$bgclr1s."'>
	       <td>".$var."</td>
<td>".getID($r->employmentStatus,$r->employeeNo)."</td>
	       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
		   <td>".$r->employmentStatus."</td>
		   <td>".$r->dateHired."</td>";
		   
	$leaveqry=mysql_query("Select * from `leave` where ndex in (7,12,8,10,3,15) order by code");
	while($l=mysql_fetch_object($leaveqry)){
		$startyear=date('Y-m-d',strtotime('01-01-'.$_POST['yer']));
		$endyear=$_POST['yer'].'-12-31';
		$endyear=date('Y-m-d',strtotime($endyear));
		$li=mysql_fetch_object(mysql_query("select * from employee_leave_limit where employeeId=".$r->ndx." and leaveId=".$l->ndex." and yer='".$_POST['yer']."' "));
		$usedLeave=mysql_num_rows(mysql_query("select * from employee_leave where employeeId=".$r->ndx." and leaveId=".$l->ndex." and startDate between '".$startyear."' and '".$endyear."'"));
		//echo "select * from employee_leave where employeeId=".$r->ndx." and leaveId=".$l->ndex." and startDate between '".$startyear."' and '".$endyear."' and approvedDate<>'0000-00-00 00:00:00'";
		if(!$li->ndex){$limit=0;}else{$limit=$li->leaveLimit;}
		$unused=$limit - $usedLeave;
		$data.="<td>".$limit."</td>
				<td>".$usedLeave."</td>
				<td>".$unused."</td>
				";
	}
      $data.="</tr>";
}
$leaveqry=mysql_query("Select * from `leave` where ndex in (7,12,8,10,3,15) order by code");
	while($l=mysql_fetch_object($leaveqry)){
		$hdr.="<td>".$l->code."</td>
				<td>Used ".$l->code."</td>
				<td>Unused ".$l->code."</td>";
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
	       <td colspan="50" align="center" style="font-size:14px;font-weight:bold;">Leave Ledger<br> <?php echo $_POST['yer'];?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
<Td>ID</td>
	       <td>Name</td>
		   <td>Status</td>
		   <td>Date Hired</td>
		 	<?php echo $hdr;?>
	  </tr>
	  <tr><td colspan="50"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




