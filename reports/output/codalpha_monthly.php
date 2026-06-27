<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
$qry="SELECT c.*,e.firstName,e.lastName,e.middleName,e.employmentStatus,e.employeeNo,p.name as position,d.name as dept,e.birthDate from employeecod c left join employee e on e.ndex=c.employeeId left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId ORDER BY e.lastName,e.firstName";

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
		   <td>".$r->typeOfOffense."</td>
		   <td>".$r->dateOfIncident."</td>
		    <td>".$r->details."</td>
     </tr>";
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="codalphalist.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="6" align="center" style="font-size:14px;font-weight:bold;">COD Alphalist Report<br> <?php echo $_POST['months'];?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
		    <td>ID</td>
	       <td>Name</td>
		   <td>Type</td>
		   <td>Date</td>
		   <td>Details</td>
	  </tr>
	  <tr><td colspan="6"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




