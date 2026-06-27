<?php
ob_start();
session_start();
include("../../employeefunctions.php");
include("../../dbcon.php");
$getInfoCutoffDates = mysql_fetch_array(mysql_query("SELECT * FROM cutoffdates WHERE ndex='".$_POST['PayrollCutoff']."'"));
$qry="SELECT k.*,e.biometricNo,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.ndex as empid,e.employmentStatus,e.employeeNo from kiosk_request k left join employee e on e.ndex=k.empid left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
		
		$qry.=" where k.approve2='1'
			and k.ndex in (select request_id from kiosk_request_logs where action='Approve Request' 
			and timelog>='".$getInfoCutoffDates['cutoffDateStart']."' and timelog<='".$getInfoCutoffDates['cutoffDateEnd']."')
		";
		//$qry.=" ORDER BY k.approve2,d.ndex,e.lastName,e.firstName";
		$qry.=" ORDER BY k.ndex DESC";
//die($qry);
$exec=mysql_query($qry);
$var=0;
while($r=mysql_fetch_array($exec)){
     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}

     // Overtime
     $ot=0;
     $np=0;
     $otq = mysql_query("select * from kiosk_request where tayp='Overtime' and empid='".$r['empid']."' and ndex in (select request_id from kiosk_request_logs where action='Approve Request' and timelog>='".$getInfoCutoffDates['cutoffDateStart']."' and timelog<='".$getInfoCutoffDates['cutoffDateEnd']."')");
     while($rot = mysql_fetch_array($otq)){
     	$exp = explode("|",$ar['request']);
     	$ot+=$exp[0];
     	$np+=$exp[1];
     }
     $otd = "<td align='right'>".$ot."</td>";
     $npd = "<td align='right'>".$np."</td>";


     // DRD
     $drd=0;
     $otq = mysql_query("select * from kiosk_request where tayp='drd' and empid='".$r['empid']."' and ndex in (select request_id from kiosk_request_logs where action='Approve Request' and timelog>='".$getInfoCutoffDates['cutoffDateStart']."' and timelog<='".$getInfoCutoffDates['cutoffDateEnd']."')");
     while($rot = mysql_fetch_array($otq)){
     	$drd++;
     }
     $drdd = "<td align='right'>".$drd."</td>";

     // Leave
     $leave=0;
     $otq = mysql_query("select * from kiosk_request where tayp='leave' and empid='".$r['empid']."' and ndex in (select request_id from kiosk_request_logs where action='Approve Request' and timelog>='".$getInfoCutoffDates['cutoffDateStart']."' and timelog<='".$getInfoCutoffDates['cutoffDateEnd']."')");
     while($rot = mysql_fetch_array($otq)){
     	$exp = explode("|",$ar['request']);
     	$date1 = date_create($exp[0]);
     	$date2 = date_create($exp[1]);
     	$diff = date_diff($date1,$date2);
     	$leave+=$diff;
     }
     $leaved = "<td align='right'>".$leave."</td>";

     // Log
     $log=0;
     $otq = mysql_query("select * from kiosk_request where tayp='log' and empid='".$r['empid']."' and ndex in (select request_id from kiosk_request_logs where action='Approve Request' and timelog>='".$getInfoCutoffDates['cutoffDateStart']."' and timelog<='".$getInfoCutoffDates['cutoffDateEnd']."')");
     while($rot = mysql_fetch_array($otq)){
     	$log++;
     }
     $logd = "<td align='right'>".$log."</td>";

     // Schedule
     $schedule=0;
     $otq = mysql_query("select * from kiosk_request where tayp='Schedule' and empid='".$r['empid']."' and ndex in (select request_id from kiosk_request_logs where action='Approve Request' and timelog>='".$getInfoCutoffDates['cutoffDateStart']."' and timelog<='".$getInfoCutoffDates['cutoffDateEnd']."')");
     while($rot = mysql_fetch_array($otq)){
     	$schedule++;
     }
     $scheduled = "<td align='right'>".$schedule."</td>";
   


     $data.="<tr bgcolor='".$bgclr1s."'>
	       <td>".$var."</td>
		   <td>".$r['lastName'].", ".$r['firstName']." ".$r['middleName']."</td>
		   ".$otd."
		   ".$npd."
		   ".$drdd."
		  ".$leaved."
		   ".$logd."
		   ".$scheduled."
     </tr>";
}
?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="15" align="center" style="font-size:14px;font-weight:bold;">Online Application Summary</td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr align="center">
	       <td>Seq</td>
		   <td>Name</td>
	       <td>OT hrs</td>
	       <td>OT NP</td>
	       <td>DRD</td>
	       <td>Leave</td>	       
	       <td>Forgot to Log</td>
	       <td>Change Restday</td>
		   
	  </tr>
	  <tr><td colspan="15"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




