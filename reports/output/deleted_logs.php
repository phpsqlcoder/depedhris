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

$aqry="SELECT * from hrinterface_deleted where datelog>='".$_POST['startdates']."' and datelog<='".$_POST['enddates']."'";
$aqry.=" ORDER BY log";
//echo $qry;
$exec=mysql_query($aqry);
$var=0;
while($h=mysql_fetch_object($exec)){
	$r=mysql_fetch_object(mysql_query("SELECT e.employmentStatus,e.employeeNo,di.name as divi,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.birthDate,DATE_FORMAT(e.birthDate,'%b') as mant,DATE_FORMAT(e.birthDate,'%d') as de from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId
left join division di on di.ndex=d.divisionId where e.biometricNo='".$h->dtrid."'"));

     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
     $data.="<tr bgcolor='".$bgclr1s."'>
	       <td>".$var."</td>
		   <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
	       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
		   <td>".$h->log."</td>
		   <td>".($h->in_out=='0' ? 'IN':'OUT')."</td>
		   <td>".$h->user."</td>
		   <td>".$h->dateDeleted."</td>
		
     </tr>";
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="DeletedLogs.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="6" align="center" style="font-size:14px;font-weight:bold;">Deleted Logs<br> <?php echo $_POST['startdates']." to ".$_POST['enddates'];?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
		   <td>ID</td>
	       <td>Name</td>
		   <td>Log</td>
		   <td>Type</td>
		   <td>User</td>
		   <td>Date Deleted</td>
		
	  </tr>
	  <tr><td colspan="8"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




