<?php
ob_start();
session_start();
include("../../employeefunctions.php");
include("../../dbcon.php");

$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));

$cond="";
if($_POST['status']=='approved'){
	$cond=" and k.approve2=1";
}
if($_POST['status']=='unapproved'){
	$cond=" and k.approve2=0";
}

$qry="SELECT k.*,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.employmentStatus,e.employeeNo 
from kiosk_request k left join employee e on e.ndex=k.empid
 left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";

$qry.=" WHERE (k.date >= '".$cutoffDate['cutoffDateStart']."' and k.date <= '".$cutoffDate['cutoffDateEnd']."') and k.tayp='Overtime' ".$cond."";
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
     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
     $exp = explode("|",$xp['request']);
     $ot=0;
     $excess=0;
     if($exp[0]>8){
     	$ot = 8;
     	$excess = $exp[0] - 8;
     }
     else{
     	$ot = $exp[0];
     }
     $data.='<tr valign="top">
     			<td>'.$var.'</td>
     			<td>'.$created['timelog'].'</td>
     			<td>'.$approved_dept['timelog'].'</td>
     			<td>'.$approved_hr['timelog'].'</td>
     			<td>'.$xp['lastName'].', '.$xp['firstName'].' '.$xp['middleName'].'</td>
     			<td>'.$xp['dept'].'</td>	           
	            <td>'.$xp['date'].'</td>           
	            <td align="right">'.$ot.'</td>           
	            <td align="right">'.$excess.'</td>           
	            <td align="right">'.$exp[1].'</td>
	            <td align="right">'.$dtr['otLHoliday'].'</td>
	            <td align="right">'.$dtr['otSHoliday'].'</td>
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
	       <td colspan="11" align="center" style="font-size:14px;font-weight:bold;">Overtime Application<br> 
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
	       <td>OT Hours</td>
	       <td>OT Excess</td>
	       <td>OT Night Premium</td>
	       <td>OT on LH</td>
	       <td>OT on SH</td>
	       <td width="300">Reasons</td>
	  </tr>
	  <tr><td colspan="11"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




