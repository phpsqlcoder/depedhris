<?php
ob_start();
session_start();
include("../../dbcon.php");
if($_POST['id']!='all'){

$qry="SELECT e.ndex as emp,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
$qry.=" WHERE e.ndex=".$_POST['id']." and e.isActive=1";

$qryex=mysql_query($qry);
$r=mysql_fetch_object($qryex);
	$exec=mysql_query("SELECT * FROM `empemploymentbg` where employeeId=".$r->emp." and companyName<>'' order by fromDate");
	$var=0;
	while($s=mysql_fetch_object($exec)){
	     $var++;
	     $ctr1s++;
	     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	     $data.="<tr bgcolor='".$bgclr1s."'>
		       <td>".$s->fromDate."</td>
			   <td>".$s->toDate."</td>
			   <td>".$s->companyName."</td>
		       <td>".$s->position."</td>
			   <td>".$s->monthlySalary."</td>
			   <td>".$s->dutiesResponsibilities."</td>
			   <td>".$s->reasonForLeaving."</td>
	     </tr>";
	}

?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="reportbysex.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">
	 <tr><td>&nbsp;</td></tr>
	 <tr><td>Name:</td><td colspan="4"><?php echo $r->lastName." , ".$r->firstName." ".$r->middleName;?></td></tr>
	 <tr><td>Position:</td><td colspan="4"><?php echo $r->position;?></td></tr>
	 <tr><td>Dept:</td><td colspan="4"><?php echo $r->dept;?></td></tr>
	 <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td colspan="7" align="center" style="font-size:14px;font-weight:bold;">Employment History Report</td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr style="font-weight:bold;">
	       <td>From</td>
	       <td>To</td>
		   <td>Company</td>
	       <td>Position</td>
		   <td>Salary/mo</td>
		   <td>Duties/<br>Responsibilities</td>
		   <td>Reason for<br>Leaving</td>
	  </tr>
	  <tr><td colspan="7"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>

<?php } else {?>
<?php

$qry="SELECT e.ndex as emp,di.name as division,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId left join division di on di.ndex=e.divisionId";
$qry.=" WHERE  e.isActive=1";
if($_POST['positions']!='ALL'){
	$qry.=" and e.position=".$_POST['positions']."";
}
if($_POST['dept']!='ALL'){
	$qry.=" and e.deptId=".$_POST['dept']."";
}

$qryex=mysql_query($qry);
while($r=mysql_fetch_object($qryex)){
$exec=mysql_query("SELECT * FROM `empemploymentbg` where employeeId=".$r->emp." and companyName<>'' order by fromDate");
$sc=mysql_fetch_object(mysql_query("select * from empeducationalbg where level not in ('High School','Elementary','')"));
$var=0;
while($s=mysql_fetch_object($exec)){
     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
     $data2.="<tr bgcolor='".$bgclr1s."'>
	       <td>".$r->lastName.",".$r->firstName." ".$r->middleName."</td>
		   <td>".$r->position."</td>
		   <td>".$r->dept."</td>
	       <td>".$r->division."</td>
		   <td>".$sc->school."</td>
		   <td>Company:".$s->companyName." <br>Position: ".$s->position." <br>Date: ".$s->fromDate." - ".$s->endDate."</td>
		   <td>".$s->dutiesResponsibilities."</td>
		   
     </tr>";
}
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="reportbysex.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">

	  <tr>
	       <td colspan="7" align="center" style="font-size:14px;font-weight:bold;">Employment Background</td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr style="font-weight:bold;">
	       <td>Name</td>
	       <td>Position</td>
		   <td>Dept</td>
	       <td>Division</td>
		   <td>School</td>
		   <td>Employment Background</td>
		   <td>Main Duties & Responsibilities</td>
	  </tr>
	  <tr><td colspan="7"><hr></td></tr>
	  <?php echo $data2;?>
      </table>
	  <?php include("../rptfooter.php");?>

<?php } ?>