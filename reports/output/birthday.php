<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
if($_POST['stat']=='all'){
	$stqry="";
}
else{
	$stqry=" and e.employmentStatus='".$_POST['stat']."'";
}

$qry="SELECT e.employmentStatus,e.employeeNo,di.name as divi,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.birthDate,DATE_FORMAT(e.birthDate,'%b') as mant,DATE_FORMAT(e.birthDate,'%d') as de from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId
left join division di on di.ndex=d.divisionId";
//if($_POST['sex']=='on'){$cond.="'MALE'";}
//elseif($_POST['sex']=='on'){$cond.="'FEMALE'";}
     //$cond=rtrim($cond,",");
$qry.=" WHERE DATE_FORMAT(e.birthDate,'%M') = '".$_POST['months']."' and e.isActive=1 ".$stqry."";
$qry.=" ORDER BY DATE_FORMAT(e.birthDate,'%d')";
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
		   <td>".$r->mant."</td>
		   <td>".$r->de."</td>
		   <td>".$r->divi."</td>
		    <td>".$r->dept."</td>
	       <td>".$r->position."</td>
     </tr>";
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="birthdayforthemonth.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="6" align="center" style="font-size:14px;font-weight:bold;">Birthday for the Month<br> <?php echo $_POST['months'];?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
		   <td>ID</td>
	       <td>Name</td>
		   <td>Month</td>
		   <td>Day</td>
		   <td>Division</td>
		   <td>Dept</td>
	       <td>Position</td>
	  </tr>
	  <tr><td colspan="8"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




