<?php
ob_start();
session_start();
include("../../dbcon.php");

$qry="select d.name as depname, d.relationship,e.firstName,e.middleName,e.lastName,e.level,e.dateHired,d.birthDate
from empdependents d left join employee e on e.ndex=d.employeeId where d.isMedicalDependent=1 and e.isActive=1 order by e.lastName,e.firstName";
$exec=mysql_query($qry);
$var=0;
while($r=mysql_fetch_object($exec)){
	     $var++;
	     $ctr1s++;
	     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	     $date = new DateTime($r->birthDate);
	     $now = new DateTime();
	     $interval = $now->diff($date);

	     $data.="<tr bgcolor='".$bgclr1s."'>
		       <td>".$var."</td>
			 <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
			 <td>".$r->level."</td> 
			 <td>".$r->dateHired."</td> 
			   <td>".$r->depname."</td> 
			   
			   <td>".$r->relationship."</td>
			   <td>".$r->birthDate."</td>
			   <td>".$interval->y."</td>
	     </tr>";
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="dependents_health_benefits.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="4" align="center" style="font-size:14px;font-weight:bold;">Employee Dependents (Hospital Benefits)<br> <?php echo $_POST['sex'];?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
 <td>Employee</td>
 <td>Level</td>
 <td>Date Hired</td>
	       <td>Dependent</td>
		  
	       <td>Relationship</td>
	       <td>Birth Date</td>
	       <td>Age</td>
	  </tr>
	  <tr><td colspan="5"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




