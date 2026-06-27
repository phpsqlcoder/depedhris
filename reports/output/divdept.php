<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
if($_POST['div']!='all'){
	$divqry=" and d.divisionId=".$_POST['div']."";
}
else{
	$divqry="";
}
if($_POST['dep']!='all'){
	$depqry=" and e.deptId=".$_POST['dep']."";
}
else{
	$depqry="";
}
$qry="SELECT di.name as division,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.employmentStatus,e.employeeNo from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId left join division di on di.ndex=d.divisionId";
$qry.=" WHERE e.isActive=1 ".$divqry."".$depqry."";
$qry.=" ORDER BY di.name,d.name,e.lastName,e.firstName";
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
		     <td>".$r->division."</td>
			<td>".$r->dept."</td>
	      
     </tr>";
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="reportbydivdept.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="6" align="center" style="font-size:14px;font-weight:bold;">Employee List by Division/Dept<br></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
		   <td>ID</td>
	       <td>Name</td>
		   <td>Division</td>
		   <td>Dept</td>
	       
	  </tr>
	  <tr><td colspan="6"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




