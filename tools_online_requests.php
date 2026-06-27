<?php
ob_start();
session_start();
echo '<link href="kiosk/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">';
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include("scripts/scripts.php");
include ("employeefunctions.php");
include ("myfunctions.php");
$qry="SELECT * FROM online_requests";


$cat=mysql_query($qry);
while($c=mysql_fetch_array($cat)){
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#fdecf2';}

	$action = '';
	if($c['status'] <> 'Approved'){
		$next_approver = mysql_fetch_array(mysql_query("select * from online_request_approvers where request_id='".$c['ndex']."' and is_current=1"));
		$approver = mysql_fetch_array(mysql_query("select * from users where ndex='".$next_approver['approver_id']."'"));
		$nap = 'Ongoing Approval ('.$approver['fullName'].')';
	}
	else{
		$nap = 'Approval Completed';

		$action = '<a href="online_apps/'.$c['request_type'].'.docx" target="_blank"><i class="fa fa-paperclip"></i></a>';
	}

	
	$requestor = mysql_fetch_array(mysql_query("select * from employee where ndex='".$c['requestor']."'"));


	$s_app='';
  $s_apps = mysql_query("select * from online_request_approvers where request_id=".$c['ndex']." order by seq");
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


		$type = '';
          if($c['request_type'] == 1){
            $type = 'E-Clearance';
          }
          if($c['request_type'] == 2){
            $type = 'Certificate of Employment';
          }
          if($c['request_type'] == 3){
            $type = 'Resignation / Retirement';
          }

	$data.="<tr style='font-size:12px;color:black; height:30px'>
				<td>".$requestor['firstName']." ".$requestor['middleName']." ".$requestor['lastName']."</td>
				<td>".$type."</td>
				<td>".$c['requested_at']."</td>
				<td>".$s_app."</td>
				<td>".$c['details']."</td>
			
				<td>
					".$action."
					<a href='#' 
						 onclick=\"window.open('kiosk/online_request_logs.php?id=".$c["ndex"]."','displayWindow','toolbar=no,scrollbars=yes,width=1110,height=500')\";>
						<i class='fa fa-list'></i>
					</a>
				</td>
	</tr>";
	date_default_timezone_set("Asia/Manila");
	
}





	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Online Requests</h2>   
    <table width="100%">
 	<tr style="font-weight:bold;color:blue;">
		<td>Employee</td>
		<td>Type</td>
		<td>Requested At</td>
		<td style="width:250px;">Status</td>
		<td>Details</td>
		<td>Action</td>
	</tr>
	<tr><td colspan="10"><hr></td></tr>
	<?php echo $data;?>
 </table>
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
