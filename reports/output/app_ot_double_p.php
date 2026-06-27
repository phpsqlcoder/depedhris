<?php
ob_start();
session_start();
include("../../employeefunctions.php");
include("../../dbcon.php");

$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));


$cond=" and k.approve2=1";
$qry="SELECT e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.employmentStatus,e.employeeNo, k.`tayp`, k.`empid`, k.`date`, count(k.ndex) as cnt
from kiosk_request k left join employee e on e.ndex=k.empid
 left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";

$qry.=" WHERE (k.date >= '".$cutoffDate['cutoffDateStart']."' and k.date <= '".$cutoffDate['cutoffDateEnd']."') and e.lastName<>''";
$qry.=" 
GROUP BY e.firstName,e.lastName,e.middleName,p.name,d.name,e.employmentStatus,e.employeeNo, k.`tayp`, k.`empid`, k.`date` 
HAVING count(k.ndex) > 1
ORDER BY k.ndex desc";
//echo $qry;
$exec=mysql_query($qry);
$var=0;
while($xp=mysql_fetch_array($exec)){
$var++;
   	
     $data.='<tr valign="top">
     			<td>'.$var.'</td>
     			
     			<td>'.$xp['lastName'].', '.$xp['firstName'].' '.$xp['middleName'].'</td>
     			<td>'.$xp['dept'].'</td>	           
	            <td>'.$xp['date'].'</td>           
	            <td align="right">'.$xp['tayp'].'</td>          
	           
	            <td>'.$xp['remarks'].'</td>
				
  			</tr>';
  	
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="overtime_application.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="11" align="center" style="font-size:14px;font-weight:bold;">Double Application<br> 
	       	<?php echo date('F d, Y',strtotime($_POST['PayrollCutoff']));?>
	       </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="top">
	       <td>Seq</td>
		   <td>Name</td>
		   <td>Dept</td>
		   <td>Date</td>
	       <td>Type</td>
		   
	       <td width="300">Reasons</td>
	  </tr>
	  <tr><td colspan="11"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




