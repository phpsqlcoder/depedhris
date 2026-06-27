<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
$qry="SELECT e.firstName,e.lastName,e.middleName,e.employmentStatus,e.employeeNo,d.name as dept,SUM(t.undertime + t.minutesLate) as let from 
dailytimesummary t left join employee e on e.ndex=t.employeeId
left join dept d on d.ndex=e.deptId
where e.isActive=1 and t.date>='".$_POST['startdate']."' and t.date<='".$_POST['enddate']."'
group by e.firstName,e.lastName,e.middleName,d.name
having SUM(t.undertime + t.minutesLate)<=0
order by e.lastName,e.firstName
";
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
		    <td>".$r->dept."</td>
     </tr>";
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="nolatereport.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="6" align="center" style="font-size:14px;font-weight:bold;">No Late Report<br><?php echo $_POST['startdate']." to ".$_POST['enddate'];?> </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
		   <td>ID</td>
	       <td>Name</td>
		   <td>Dept</td>
	  </tr>
	  <tr><td colspan="6"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




