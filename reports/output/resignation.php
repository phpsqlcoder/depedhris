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


$qry="SELECT c.effectivityDate,c.remarks,e.ndex as ndx,e.birthDate,e.firstName,e.lastName,e.employeeNo,e.middleName,p.name as position,d.name as dept,e.employmentStatus,e.dateHired 
from employeechangestatus c left join employee e on e.ndex=c.employeeId
left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
$qry.=" WHERE e.isActive=0 and c.effectivityDate>='2015-01-01' and c.effectivityDate<='2015-12-31' and c.changeType in ('Termination','Retirement','Resignation') ".$stqry." ".$stqry2."";
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
		   <td>".$r->birthDate."</td>
		   <td>".$r->dateHired."</td>
		   <td>".$r->effectivityDate."</td>
		   <td>".$r->remarks."</td>
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
	       <td colspan="50" align="center" style="font-size:14px;font-weight:bold;">Resignation Report<br> </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
<Td>ID</td>
	       <td>Name</td>
		   <td>Status</td>
		    <td>Birthdate</td>
		   <td>Date Hired</td>
		 	<td>Date Resigned</td>
		 	<td>Remarks</td>
	  </tr>
	  <tr><td colspan="50"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




