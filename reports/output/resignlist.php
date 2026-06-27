<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");

$qry="SELECT e.employmentStatus,e.employeeNo,e.ndex,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.endDate as ending,e.birthDate,e.dateHired from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
$qry.=" WHERE e.isActive=0";
$qry.=" ORDER BY e.endDate,e.lastName,e.firstName";

$exec=mysql_query($qry);
$var=0;
$addqr = "";
if(isset($_POST['startdate'])){
	$addqr = " and (effectivityDate>='".$_POST['startdate']."' and effectivityDate<='".$_POST['enddate']."') and effectivityDate<>'0000-00-00'";
}
while($r=mysql_fetch_object($exec)){
	$cs=mysql_fetch_object(mysql_query("select * from employeechangestatus where changeType in ('Resignation','Retirement','End of Contract','End of Residency Training','Termination','Separation','AWOL') and employeeId=".$r->ndex." ".$addqr.""));
     
     $ctr1s++;
$col=mysql_fetch_array(mysql_query("select * from empeducationalbg where employeeId='".$r->ndex."' and `level`='College'"));
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	if(!$cs->effectivityDate || !$cs->effectivityDate=='0000-00-00'){$en='Unrecorded';}else{$en=$cs->effectivityDate;}
//	if(!$cs->effectivityDate || !$cs->effectivityDate=='0000-00-00'){$en='Unrecorded';}else{$en=$cs->effectivityDate;}
	if(isset($_POST['startdate'])){
		$var++;
		if($en<>'Unrecorded'){
	     $data.="<tr bgcolor='".$bgclr1s."'>
		       <td>".$var."</td>
		
	<td>".getID($r->employmentStatus,$r->employeeNo)."</td>
		       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
			
			   <td>".$r->position."</td>
			   <td>".$r->dept."</td>
			   
			   <td>".$r->birthDate."</td>
			   <td>".$r->dateHired."</td>
			   <td>".$en."</td>
			<td>".$col['school']."</td>
<td>".$cs->changeType."</td>
			   <td>".$cs->remarks."</td>

	     </tr>";
	    }
	}
	else{
		$var++;
	     $data.="<tr bgcolor='".$bgclr1s."'>
		       <td>".$var."</td>
	<td>".getID($r->employmentStatus,$r->employeeNo)."</td>
		       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
			
			   <td>".$r->position."</td>
			   <td>".$r->dept."</td>
			   
			   <td>".$r->birthDate."</td>
			   <td>".$r->dateHired."</td>
			   <td>".$en."</td>
			<td>".$col['school']."</td>
<td>".$cs->changeType."</td>
			   <td>".$cs->remarks."</td>

	     </tr>";
	}
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="resignlist.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="10" align="center" style="font-size:14px;font-weight:bold;">List of Attrition<br>As of <?php echo date('Y-m-d');?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
<td>ID</td>
	       <td>Name</td>
		   <td>Position</td>
		   <td>Dept</td>
		   
		   <td>Birth Date</td>
		   <td>Hired Date</td>
		   <td>Effectivity Date</td>
<td>College</td>
<td>Type</td>
		   <td>Remarks</td>
	  </tr>
	  <tr><td colspan="10"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




