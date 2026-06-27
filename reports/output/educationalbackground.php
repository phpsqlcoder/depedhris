<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
$qry="SELECT e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.employmentStatus,e.employeeNo from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
$qry.=" WHERE e.ndex=".$_POST['id']." and e.isActive=1";
$r=mysql_fetch_object(mysql_query($qry));
$exec=mysql_query("SELECT * FROM `empeducationalbg` where employeeId=".$_POST['id']." order by fromDate");
$var=0;
while($s=mysql_fetch_object($exec)){
     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
     $data.="<tr bgcolor='".$bgclr1s."'>
	 	
	       <td>".$s->fromDate."</td>
		   <td>".$s->toDate."</td>
		   <td>".$s->degree."</td>
		   <td>".$s->level."</td>
	       <td>".$s->school."</td>
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
	 <tr><td>ID:</td><td colspan="4"><?php echo getID($r->employmentStatus,$r->employeeNo); ?></td></tr>
	 <tr><td>Name:</td><td colspan="4"><?php echo $r->lastName." , ".$r->firstName." ".$r->middleName;?></td></tr>
	 <tr><td>Position:</td><td colspan="4"><?php echo $r->position;?></td></tr>
	 <tr><td>Dept:</td><td colspan="4"><?php echo $r->dept;?></td></tr>
	 <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td colspan="7" align="center" style="font-size:14px;font-weight:bold;">Educational Background Report</td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr style="font-weight:bold;">
	       <td>From</td>
	       <td>To</td>
		   <td>Degree</td>
	       <td>Level</td>
		   <td>School</td>
	  </tr>
	  <tr><td colspan="7"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




