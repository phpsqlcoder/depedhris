<?php
ob_start();
session_start();
include("../../employeefunctions.php");
include("../../dbcon.php");

$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));


$cond=" and k.approve2=1";
$qry="SELECT k.*,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.employmentStatus,e.employeeNo 
from kiosk_request k left join employee e on e.ndex=k.empid
 left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";

$qry.=" WHERE (k.date >= '".$cutoffDate['cutoffDateStart']."' and k.date <= '".$cutoffDate['cutoffDateEnd']."') and k.tayp='leave' ".$cond."";
$qry.=" ORDER BY k.ndex desc";
//echo $qry;
$exec=mysql_query($qry);
$var=0;
while($xp=mysql_fetch_array($exec)){

   	$created=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' and action='Create Request' order by ndex desc limit 1"));
   	$approved_hr=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' and action='Approve Request (HR)' order by ndex desc limit 1"));
   	$approved_dept=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' and action='Approve Request' order by ndex desc limit 1"));
   	$dtr=mysql_fetch_array(mysql_query("select * from dailytimesummary where 
   		employeeId='".$xp['empid']."' and date='".$xp['date']."'"));
   	$exp = explode("|",$xp['request']);
   	$ll = mysql_fetch_array(mysql_query("select * from employee_leave where 
   		employeeId='".$xp['empid']."' and startDate>='".$exp['0']."' and startDate<='".$exp['1']."'"));
   	if(!$ll['ndex']){
	     $var++;
	     $ctr1s++;
	     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}

	     
	     $l=mysql_fetch_object(mysql_query("select * from `leave` where ndex='".$exp[2]."'"));

	     $data.='<tr valign="top">
	     			<td>'.$var.'</td>
	     			<td>'.$created['timelog'].'</td>
	     			<td>'.$approved_dept['timelog'].'</td>
	     			<td>'.$approved_hr['timelog'].'</td>
	     			<td>'.$xp['lastName'].', '.$xp['firstName'].' '.$xp['middleName'].'</td>
	     			<td>'.$xp['dept'].'</td>	           
		            <td>'.$exp[0].' to '.$exp[1].'</td>           
		            <td>'.$l->code.'</td> 	   
		            <td>'.$xp['remarks'].'</td>
					
	  			</tr>';
  	}
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="leave_application.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="8" align="center" style="font-size:14px;font-weight:bold;">Leave Application<br> 
	       	<?php echo date('F d, Y',strtotime($_POST['PayrollCutoff']));?>
	       </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="top">
	       <td>Seq</td>
		   <td>Date Filed</td>
		   <td>Approved (Dept)</td>
		   <td>Approved (HR)</td>
	       <td>Name</td>
		   <td>Dept</td>
	       <td>Date of Application</td>
	       <td>Type of Leave</td>     
	       <td width="300">Reasons</td>
	  </tr>
	  <tr><td colspan="8"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




