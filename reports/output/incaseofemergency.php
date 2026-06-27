<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../myfunctions.php");
include("../../employeefunctions.php");
$addsql="";
$grdiv="";
$grdep="";
if ($_POST['division']){
	$addsql.=" and e.divisionId='".$_POST['division']."'";
	$grdiv = " && ndex=".$_POST['division']."";
}
if ($_POST['tdept']){
	$addsql.=" && e.deptId='".$_POST['tdept']."'";
	$grdep=" && ndex=".$_POST['tdept']."";
}
if($_POST['dep']!='on'){
	$qry="SELECT 
		e.*,
	p.name as position,d.name as dept,di.name as division,u.name as unit,e.employmentStatus,e.employeeNo
			from employee e 
			left join position p on p.ndex=e.position 
			left join dept d on d.ndex=e.deptId
			left join division di on di.ndex=e.divisionId
			left join unit u on u.ndex=e.unitId 
		WHERE e.ndex<>'' ".$addsql."";
	if($_POST['id']!='all'){$qry.=" and e.ndex=".$_POST['id']."";}
	$qry.=" order by e.lastName,e.firstName";
    //echo $qry;
	$exec=mysql_query($qry);
	$var=0;
	while($r=mysql_fetch_object($exec)){
	     $var++;
	     $ctr1s++;
	     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	     $data.="<tr bgcolor='".$bgclr1s."'>
		      
			   <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
		       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
			   <td>".$r->emergencyContactPerson."</td>
			   <td>".$r->emergencyContactNumber."</td>
			   <td>".$r->emergencyRelationship."</td>
	     </tr>";
	}
}
elseif($_POST['dep']=='on'){
	$div=mysql_query("select * from division where status<>1 ".$grdiv." order by name");
	while($di=mysql_fetch_object($div)){
		$data.="<tr><td style='color:maroon;font-weight:bold;' colspan='3'>".$di->name."</td></tr>";
		$dept=mysql_query("select * from dept where divisionId=".$di->ndex." and status<>1 ".$grdep." order by name");
		while($de=mysql_fetch_object($dept)){
			$data.="<tr><td style='color:black;font-weight:bold;' colspan='3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$de->name."</td></tr>";
			$qry="SELECT 
					e.*,
				p.name as position,d.name as dept,di.name as division,u.name as unit,e.employmentStatus,e.employeeNo
						from employee e 
						left join position p on p.ndex=e.position 
						left join dept d on d.ndex=e.deptId
						left join division di on di.ndex=e.divisionId
						left join unit u on u.ndex=e.unitId 
					WHERE e.ndex<>'' and e.deptId=".$de->ndex."";
				if($_POST['id']!='all'){$qry.=" and e.ndex=".$_POST['id']."";}
				$qry.=" order by e.lastName,e.firstName";
				$exec=mysql_query($qry);
				$var=0;
				while($r=mysql_fetch_object($exec)){
				     $var++;
				     $ctr1s++;
				     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
				     $data.="<tr bgcolor='".$bgclr1s."'>
					       <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
					       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
						   <td>".$r->emergencyContactPerson."</td>
						   <td>".$r->emergencyContactNumber."</td>
						   <td>".$r->emergencyRelationship."</td>
				     </tr>";
				}
		}
	}
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="incaseofemergency.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="5" align="center" style="font-size:14px;font-weight:bold;">Employee Contact in case of Emergency</td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>ID No.</td>
	       <td>Name</td>
		   <td>Contact Person</td>
	       <td>Contact No.</td>
		   <td>Relationship</td>
	  </tr>
	  <tr><td colspan="5"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




