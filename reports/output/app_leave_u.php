<?php
ob_start();
session_start();
include("../../employeefunctions.php");
include("../../dbcon.php");

$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));

$cond=" and k.approve2=0";

$qry="SELECT k.*,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.employmentStatus,e.employeeNo,e.dateHired
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
     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}

     $exp = explode("|",$xp['request']);
     $l=mysql_fetch_object(mysql_query("select * from `leave` where ndex='".$exp[2]."'"));

     $data.='<tr valign="top">
     			<td><a href="#" onclick="approve_req('.$xp['ndex'].')">'.$var.'</a></td>
     			<td>'.$created['timelog'].'</td>
     			<td>'.$approved_dept['timelog'].'</td>
     			<td>'.$approved_hr['timelog'].'</td>
     			<td>'.$xp['lastName'].', '.$xp['firstName'].' '.$xp['middleName'].'</td>
          <td>'.$xp['dateHired'].'</td>  
     			<td>'.$xp['dept'].'</td>	           
	            <td>'.$exp[0].' to '.$exp[1].'</td>           
	            <td>'.$l->code.'</td> 	   
	            <td>'.$xp['remarks'].'</td>
				
  			</tr>';
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
	       <td colspan="12" align="center" style="font-size:14px;font-weight:bold;">Leave Application (Unapproved)<br> 
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
         <td>Hired</td>
		   <td>Dept</td>
	       <td>Date of Application</td>
	       <td>Type of Leave</td>     
	       <td width="300">Reasons</td>
	  </tr>
	  <tr><td colspan="12"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>

<script src="../../kiosk1/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>

    <script src="../../kiosk1/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
    <script src="../../kiosk1/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <script src="../../kiosk1/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>   
    <script src="../../kiosk1/assets/global/scripts/metronic.js" type="text/javascript"></script> 

  
    <script type="text/javascript">
    //jQuery.noConflict();
        jQuery(document).ready(function() {
              Metronic.init(); // init metronic core components
              Layout.init();
        });
      </script>
<script>
	function approve_req(x){
    	jQuery.ajax({
    		method: "GET",
    		url: "../../tools_approveonlinehr_ajax.php?act=approve&id="+x
    	})
    	.done(function(data){
    		alert('Successfully Approved!');
    		//jQuery('#cbdiv'+x).html('Approve');
    		//jQuery('#cdiv'+x).hide(1000,'swing');
    		//alert(data);
    	});
    }

</script>

