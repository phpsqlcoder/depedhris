<?php
ob_start();
session_start();
echo '<link href="kiosk/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">';
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");


if(isset($_GET['act'])){
	date_default_timezone_set("Asia/Manila");

	if($_GET['act'] == 'Approved'){
		$upd = mysql_query("update online_request_approvers set status='".$_GET['act']."',is_current=0,approved_at='".date('Y-m-d H:i:s')."' where ndex='".$_GET['id']."'");
		$cur = mysql_fetch_array(mysql_query("select * from online_request_approvers where ndex='".$_GET['id']."'"));

		$next_seq = $cur['seq'] + 1;
		$get_next = mysql_num_rows(mysql_query("select * from online_request_approvers where request_id='".$cur['request_id']."' and seq='".$next_seq."'"));
		if($get_next > 0 && $_GET['act'] == 'Approved'){
			$next = mysql_query("update online_request_approvers set is_current=1 where request_id='".$cur['request_id']."' and seq='".$next_seq."'");
		}
		else{
			$next = mysql_query("update online_requests set status='Approved' where ndex='".$cur['request_id']."'");
		}
		$insert_app = mysql_query("insert into online_request_logs (`request_id`, `action`, `user_id`, `created_at`) values 
	    ('".$cur['request_id']."','".$_GET['act']." the Request','".$_SESSION['fullName']."','".date('Y-m-d H:i:s')."')");
		header("location: dashboard_dept.php");
	}
	if($_GET['act'] == 'Disapproved'){
		$upd = mysql_query("update online_request_approvers set status='".$_GET['act']."',remarks='".$_GET['remarks']."',disapproved_at='".date('Y-m-d H:i:s')."' where ndex='".$_GET['id']."'");
		$cur = mysql_fetch_array(mysql_query("select * from online_request_approvers where ndex='".$_GET['id']."'"));		
		$insert_app = mysql_query("insert into online_request_logs (`request_id`, `action`, `user_id`, `created_at`) values 
	    ('".$cur['request_id']."','".$_GET['act']." the Request with remarks: ".$_GET['remarks']."','".$_SESSION['fullName']."','".date('Y-m-d H:i:s')."')");
		header("location: dashboard_dept.php");
	}

	
}

$qry="SELECT * FROM online_request_approvers where approver_id='".$_SESSION['ndex']."' order by ndex desc";


$cat=mysql_query($qry);
while($c=mysql_fetch_array($cat)){
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#fdecf2';}
	$request = mysql_fetch_array(mysql_query("select * from online_requests where ndex='".$c['request_id']."'"));

	$requestor = mysql_fetch_array(mysql_query("select * from employee where ndex='".$request['requestor']."'"));

		$type = '';
          if($request['request_type'] == 1){
            $type = 'E-Clearance';
          }
          if($request['request_type'] == 2){
            $type = 'Certificate of Employment';
          }
          if($request['request_type'] == 3){
            $type = 'Resignation / Retirement';
          }

  $action = '';
  if($c['is_current'] == 1 || ($c['can_override'] == 1 && $c['status'] == 'Pending')) {
  	$action = '<a href="dashboard_dept.php?id='.$c['ndex'].'&act=Approved&can_override='.$c['can_override'].'" title="Approve this request"><i class="fa fa-check" style="font-size:18px;"></i> </a>  <a href="#" onclick="disapprove_r('.$c['ndex'].')"> <i class="fa fa-times text-danger" style="font-size:18px;" title="Dispprove this request"></i>  </a>
  	<a href="#" onclick=\'window.open("kiosk/online_request_logs.php?id='.$request['ndex'].'","displayWindow","toolbar=no,scrollbars=yes,width=1110,height=500")\'; title="Show Logs"> <i class="fa fa-bars text-warning" style="font-size:18px;"></i>  </a>';
  }
  else{
  	$action = '
  	<a href="#" onclick=\'window.open("kiosk/online_request_logs.php?id='.$request['ndex'].'","displayWindow","toolbar=no,scrollbars=yes,width=1110,height=500")\'; title="Show Logs"> <i class="fa fa-bars text-warning" style="font-size:18px;"></i>  </a>';
  }


  $s_app='';
    $s_apps = mysql_query("select * from online_request_approvers where request_id=".$request['ndex']." order by seq");
    while($sa = mysql_fetch_array($s_apps)){
  
      $us = mysql_fetch_array(mysql_query("select * from users where ndex='".$sa['approver_id']."'"));
      $col = 'B8B4B3';
      if($sa['is_current'] == 1){
        $col = '1432F2';
      }

      if($sa['status'] == 'Approved'){
        $col = '06BF1F';
      }

      if($sa['status'] == 'Disapproved'){
        $col = 'EB1208';
      }

      $color='color:#'.$col;
      $s_app.='
      <a><i class="fa fa-user" title="'.$us['fullName'].'" style="font-size:15px;'.$color.'"></i></a>&nbsp;
      ';
    }



	$data.="<tr style='font-size:12px;color:black; height:60px'>
				<td>".$requestor['firstName']." ".$requestor['middleName']." ".$requestor['lastName']."</td>
				<td>".$type."</td>
				<td>".$request['requested_at']."</td>
				<td align='center'>".$c['status']."<br>".$s_app."</td>
				<td>".$request['details']."</td>
				<td>
					".$action."
				</td>
	</tr>";
	date_default_timezone_set("Asia/Manila");
	if($c['status'] == 'Pending'){

	 $insert_app = mysql_query("insert into online_request_logs (`request_id`, `action`, `user_id`, `created_at`) values 
    ('".$request['ndex']."','View the Request','".$_SESSION['fullName']."','".date('Y-m-d H:i:s')."')");
	}
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
    <link href="kiosk/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="kiosk/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "headerperdept.php";?>
<div id="main_content_wrap" class="container_12">

<div id="rcont">
  <h2><strong>Online Requests</strong></h2>
 	<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
  	<form action="">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter Remarks</h4>
        <input type="hidden" name="act" value="Disapproved">
        <input type="hidden" name="can_override" value="0">
        <input type="hidden" name="id" id="idd" value="0">
      </div>
      <div class="modal-body">
        <textarea name="remarks" id="remarks" cols="30" rows="10"></textarea>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default">Disapprove</button>
      </div>
    </div>
    </form>
  </div>
</div>
<br>
<script>
	function disapprove_r(i){
		
		$('#idd').val(i);
		$('#myModal').modal('show');
	}
</script>
 <table width="120%" style="margin-left:-100px;">
 	<tr style="font-weight:bold;color:blue;">
		<td>Employee</td>
		<td>Type</td>
		<td>Requested At</td>
		<td>Status</td>
		<td>Details</td>
		<td>Action</td>
	</tr>
	<tr><td colspan="10"><hr></td></tr>
	<?php echo $data;?>
 </table>
		
        <div class="container_12"><!--  PLACEHOLDER FOR FLOT - REMOVE IF NOT REQUIRED --></div>
        
        <div class="clearfix">&nbsp;</div>

        <!-- NOTIFICATION - USES CLASS OF "WARNING" and the CANHIDE ENABLES CICK TO FADE AWAY-->
        <div class="container_12">
           
<!--START NOTIFICATIONS  --><!-- INFORMATION - USES CLASS OF "IN2FORMATION" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- WARNING - USES CLASS OF "WARNING" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- SUCCESS - USES CLASS OF "SUCCESS" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- FAILURE - USES CLASS OF "FAILURE" and the CANHIDE ENABLES CICK TO FADE AWAY--></div>   
  	<!--END NOTIFICATIONS  -->
        
        
	<div class="clearfix">&nbsp;</div>
    </div>
<!-- START TABULAR DATA EXAMPLE -->
  <div class="container_12">
	<h2>&nbsp;</h2>
    <!-- END TABULAR DATA EXAMPLE -->
    <div class="clearfix">&nbsp;</div>
</div>
<div class="clearfix">&nbsp;</div>
<div class="container_12">
<?php include "footer.php";?>     
  </div><!-- end content wrap -->


</body>
</html>

<script src="kiosk/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="kiosk/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
    <script src="kiosk/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>    
