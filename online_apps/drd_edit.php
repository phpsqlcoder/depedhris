<?php
ob_start();
session_start();

if(!$_SESSION['ndex']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include ("../employeefunctions.php");
include('../payroll/payrollfunctions.php');
include("../myfunctions.php");
$id=$_GET['id'];
if(isset($_GET['success'])){
   $msg='<div class="alert alert-success">
                  <strong>Success!</strong> The request has been updated.
                </div>';
}
if(isset($_GET['act'])){
  date_default_timezone_set("Asia/Manila");
	  $ins=mysql_query("update kiosk_request set 
      request='".$_POST['hrs']."|".$_POST['ot']."|".$_POST['np']."',
      remarks='".$_POST['rem']."',isPending=0
	  	where ndex='".$id."'");
    
    
	  $logs = mysql_query("insert into kiosk_request_logs (action,timelog,request_id,user,remarks) VALUES ('Update Request','".date('Y-m-d H:i:s')."','".$id."','".$_SESSION['nym']."','".$_POST['app']."')");
    header("location:drd_edit.php?success=1&id=".$id);

}

$p = mysql_fetch_array(mysql_query("select * from kiosk_request where ndex='".$id."'"));
$ex = explode("|", $p['request']);
$e = mysql_fetch_array(mysql_query("select * from employee where ndex='".$p['empid']."'"));



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>Duty Rest Day</title>
	<link href="../css/styles.css" rel="stylesheet" type="text/css" />
	<link href="../css/facebox.css" rel="stylesheet" type="text/css" />
	<link href="../kiosk1/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Global styles END --> 
   
  <!-- Page level plugin styles START -->
  <link href="../kiosk1/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet">
 

  <!-- Theme styles START -->
  <link href="../kiosk1/assets/global/css/components.css" rel="stylesheet">
  <link href="../kiosk1/assets/frontend/layout/css/style.css" rel="stylesheet">
  <link href="../kiosk1/assets/frontend/layout/css/style-responsive.css" rel="stylesheet">
  <link href="../kiosk1/assets/frontend/layout/css/themes/red.css" rel="stylesheet" id="style-color">
</head>

<body>

<form name="frmemp" id="frm_leave" method="post" action="drd_edit.php?act=submit&id=<?php echo $_GET['id']?>">

	<?php echo $msg;?>
	<h3>Name: <?php echo getID($e['employmentStatus'],$e['employeeNo'])." - ".$e['lastName'].", ".$e['firstName'];?></h3>
  <div class="row">
    <div class="col-md-6">
        <table class="table">
          <tr>
            <td>Date</td>
            <td><?php echo $p['date']; ?></td>
          </tr> 
          <tr>
            <td>Duty</td>
            <td> <input type="number" name="hrs" step="0.01" min="0" max="24.00" value="<?php echo $ex[0]?>"> Hrs</td>
          </tr>
          <tr>
            <td>Overtime</td>
            <td> <input type="number" name="ot" step="0.01" min="0" max="24.00" value="<?php echo $ex[1]?>"> Hrs</td>
          </tr>
          <tr>
            <td>Night Differential</td>
            <td> <input type="number" name="np" step="0.01" min="0" max="24.00" value="<?php echo $ex[2]?>"> Hrs</td>
          </tr>
          <tr>
            <td>Reason</td>
            <td valign="top"><textarea rows="3" cols="30" name="rem" placeholder="Reason" required><?php echo $p['remarks']?></textarea></td>

          </tr>
          <tr>
            <td>Approver Remarks</td>
            <td valign="top"><textarea rows="3" cols="30" name="app" placeholder="Remarks" required></textarea></td>

          </tr>
          <tr><td colspan="12" align="right">
            <input type="submit" value="Update" class="btn green">

          </td></tr>
        </table>
      </form>
      
    </div> 

    <div class="col-md-6">
      <div class="portlet yellow box">
        <div class="portlet-title">
            History
        </div>
        <div class="portlet-body">
          <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th>User</th>
              <th>Date</th>
              <th>Action</th>
              <th>Remarks</th>
            </tr>
          </thead>
          <tbody>
        <?php
          $hist = mysql_query("select * from kiosk_request_logs where request_id='".$id."' order by ndex desc");
          while($h=mysql_fetch_array($hist)){
            echo '<tr>
                    <td>'.$h['user'].'</td>
                    <td>'.date('Y-m-d H:i A',strtotime($h['timelog'])).'</td>
                    <td>'.$h['action'].'</td>
                    <td>'.$h['remarks'].'</td>
            </tr>';
          }
        ?>
        </tbody>
      </table>
        </div>
      </div>
        
    </div>   
  </div>
  


<script src="../kiosk1/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>

    <script src="../kiosk1/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>      
  
    <script type="text/javascript">
    jQuery.noConflict();
        jQuery(document).ready(function() {
              //Metronic.init(); // init metronic core components

        });
    </script>