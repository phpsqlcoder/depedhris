<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
$qry="SELECT e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.employmentStatus,e.employeeNo,e.specialSkills from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
//if($_POST['sex']=='on'){$cond.="'MALE'";}
//elseif($_POST['sex']=='on'){$cond.="'FEMALE'";}
     //$cond=rtrim($cond,",");

if($_POST['id']=='all'){
	$qry.=" WHERE e.specialSkills like '%".$_POST['skill']."%' and e.isActive=1";
}
else{
	$qry.=" WHERE e.specialSkills like '%".$_POST['skill']."%' and e.ndex='".$_POST['id']."' and e.isActive=1";
}
$qry.=" ORDER BY e.lastName,e.firstName";
//echo $qry;
$exec=mysql_query($qry);
$var=0;
while($r=mysql_fetch_object($exec)){
     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
     $data.="<tr bgcolor='".$bgclr1s."'>
	       <td>".$var."</td>
	       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
		   <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
		    <td>".$r->dept."</td>
	       <td>".$r->position."</td>
	       <td>".$r->specialSkills."</td>
     </tr>";
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="specialskills.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="6" align="center" style="font-size:14px;font-weight:bold;">Special Skills and Talents<br> <?php echo $_POST['skill'];?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
	       <td>Name</td>
	       <td>ID</td>
		   <td>Dept</td>
	       <td>Position</td>
	       <td>Skills</td>
	  </tr>
	  <tr><td colspan="6"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




