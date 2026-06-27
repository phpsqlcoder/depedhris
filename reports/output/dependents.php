<?php
ob_start();
session_start();

include("../../dbcon.php");
include("../../employeefunctions.php");
$qry="select d.name as depname, d.relationship,e.firstName,e.employeeNo,e.employmentStatus,e.middleName,d.birthDate,e.lastName,EXTRACT(YEAR FROM (FROM_DAYS(DATEDIFF(NOW(),e.birthDate))))+0 AS employeeAge,
EXTRACT(YEAR FROM (FROM_DAYS(DATEDIFF(NOW(),d.birthDate))))+0 AS dependentAge 
from empdependents d left join employee e on e.ndex=d.employeeId where d.isDependent=1 and e.isActive=1 order by e.lastName,e.firstName";
$exec=mysql_query($qry);
$var=0;
while($r=mysql_fetch_object($exec)){
	if($r->employeeAge>=$_POST['efrom'] && $r->employeeAge<=$_POST['eto'] && $r->dependentAge>=$_POST['dfrom'] && $r->dependentAge<=$_POST['dto']){
	     $var++;
	     $ctr1s++;
	     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	     $data.="<tr bgcolor='".$bgclr1s."'>
		      <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
			 <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
			   <td>".$r->depname."</td>
			   <td>".$r->dependentAge."</td>
		      <td>".$r->birthDate."</td>
			   <td>".$r->relationship."</td>
	     </tr>";
	}
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="dependents.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="4" align="center" style="font-size:14px;font-weight:bold;">Employee Dependents<br> <?php echo $_POST['sex'];?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>ID</td>
 <td>Employee</td>
	       <td>Dependent</td>
		   <td>Age</td>
		  <td>BirthDate</td>
	       <td>Relationship</td>
	  </tr>
	  <tr><td colspan="5"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




