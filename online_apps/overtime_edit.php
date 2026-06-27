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
	  $ins=mysql_query("update kiosk_request set request='".$_POST['ot']."|".$_POST['np']."',remarks='".$_POST['rem']."',isPending=0
	  	where ndex='".$id."'");
    date_default_timezone_set("Asia/Manila");
	  $logs = mysql_query("insert into kiosk_request_logs (action,timelog,request_id,user,remarks) VALUES ('Update Request','".date('Y-m-d H:i:s')."','".$id."','".$_SESSION['nym']."','".$_POST['app']."')");
	  header("location:overtime_edit.php?success=1&id=".$id);

}

$p = mysql_fetch_array(mysql_query("select * from kiosk_request where ndex='".$id."'"));
$ex = explode("|", $p['request']);
$e = mysql_fetch_array(mysql_query("select * from employee where ndex='".$p['empid']."'"));
//echo "select * from employee where ndex='".$p['empid']."'";
$r->ndex=$p['empid'];
$det=$p['date'];

$aot=mysql_fetch_object(mysql_query("select * from dailytimesummary where employeeId=".$r->ndex." and date='".$det."'"));
    $ctr1s++;
    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
    $daylog=date('w',strtotime($det));
    $restday=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$r->ndex." and '".$det."' between startDate and endDate"));
    $tymlogs="";
    $tymlogs2="";
    $advot=0;
    if($restday->restday!=$daylog){
      $shifts=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$r->ndex." and approvedDate<>'0000-00-00 00:00:00' and '".$det."' between startDate and endDate"));
      if($shifts->shiftingId){
        $getshift=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shifts->shiftingId.""));
        //echo "select * from hrinterface where dtrid='".$e['biometricNo']."' and datelog='".$det."' and in_out=0 ORDER BY log LIMIT 0,1";
        $inlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$e['biometricNo']."' and datelog='".$det."' and in_out=0 ORDER BY log LIMIT 0,1"));
        $nxtday_timeIn=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$e['biometricNo']."' and datelog>'".$det."' and in_out=0 ORDER BY log LIMIT 0,1"));
        $out=mysql_fetch_object(mysql_query("SELECT * FROM `hrinterface` where in_out=1 and datelog >= '".$det."' and log<'".$nxtday_timeIn->log."' and dtrid='".$e['biometricNo']."' ORDER BY log DESC limit 0,1")); // Get OUT record
        $tymlogs=$getshift->timeOut." &nbsp;&nbsp;to &nbsp;&nbsp;".$out->log;
        $tymlogs2=$inlog->log." &nbsp;&nbsp;to &nbsp;&nbsp;".$getshift->timeIn;
        $datein=date('Y-m-d',strtotime($inlog->log));
        $shiftdatein=$datein." ".$getshift->timeIn;
        //die($inlog->log." xx ".$shiftdatein);
        $advot=timeDiffinSeconds($inlog->log,$shiftdatein)/3600;
        //die($advot);
        if($advot<0){$advot=0;}
        //$advot=$inlog->log." - ".$shiftdatein;
      }
    }
    $totalot+=$aot->approvedOvertime;
    $totalnp+=$aot->approvedOvertimeNightPremium;
    $unapprove_ot = number_format($aot->overtime/60,2);
    //$advot=timeDiffinSeconds($inlog->log,$shiftdatein)/3600;
    $total_overtime = number_format((($aot->overtime * 60) + ($advot * 3600))/3600,2);
    $data="<tr><td>Date</td><td>".$det."</td></tr>
        <tr><td>Time In - ShiftIn</td><td>".$tymlogs2."</td></tr>
        <tr><td>Advance OT</td><td>".number_format($advot,2)."</td></tr>
        <tr><td>ShiftOut - Time Out</td><td>".$tymlogs."</td></tr>
        <tr><td>Unapprove<br>Overtime (hr)</td><td>".number_format($total_overtime/60,2)."</td></tr>
        <tr><td>Overtime</td><td><input type='number' value='".number_format($ex[0],2)."' size='5' step='0.01' name='ot' onkeyup=\"if(parseInt(this.value)>15){alert('Exceed Overtime Limit!'); this.value='".$aot->approvedOvertime."';} if(parseInt(this.value)>0){ $('#rem".$det."').prop('required', 'required');} else { $('#rem".$det."').prop('required', false);}\" max='".$total_overtime."' style='text-align:right;' onFocus='this.select()' onClick='this.select()'></td></tr>
        <tr><td>OT Night Premium</td><td><input type='number' value='".$ex[1]."' step='0.01' max='".$total_overtime."' size='5' onkeyup=\"if(parseInt(this.value)>15){alert('Exceed Overtime Limit!'); this.value='".$aot->approvedOvertimeNightPremium."';} if(parseInt(this.value)>0){ $('#rem".$det."').prop('required', 'required');} else { $('#rem".$det."').prop('required', false);}\" name='np'  style='text-align:right;' onFocus='this.select()' onClick='this.select()'></td> </tr>
        <tr><td>Remarks</td><td><input type='text' name='rem' id='rem' required value='".$p['remarks']."'></td></tr>";
    /*
    $data.="<tr style='font-size:12px;'>
          <td>".$det."</td>
          <td>".$tymlogs2."</td>
          <td>".number_format($advot,2)."</td>
          <td>".$tymlogs."</td>
          <td>".number_format($aot->overtime/60,2)."</td>
          <td><input type='number' size='5' step='0.01' name='".$det."' onkeyup=\"if(parseInt(this.value)>15){alert('Exceed Overtime Limit!'); this.value='".$aot->approvedOvertime."';} if(parseInt(this.value)>0){ $('#rem".$det."').prop('required', 'required');} else { $('#rem".$det."').prop('required', false);}\" max='".$unapprove_ot."' value='".$aot->approvedOvertime."' style='text-align:right;' onFocus='this.select()' onClick='this.select()'></td> 
          <td align='right'><input type='number' step='0.01' max='".$unapprove_ot."' size='5' onkeyup=\"if(parseInt(this.value)>15){alert('Exceed Overtime Limit!'); this.value='".$aot->approvedOvertimeNightPremium."';} if(parseInt(this.value)>0){ $('#rem".$det."').prop('required', 'required');} else { $('#rem".$det."').prop('required', false);}\" name='np".$det."' value='".$aot->approvedOvertimeNightPremium."' style='text-align:right;' onFocus='this.select()' onClick='this.select()'></td>
          <td><input type='text' name='rem".$det."' id='rem".$det."' value='".$aot->overtimeRemarks."'></td>
    </tr>";
	*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>Overtime</title>
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

<form name="frmemp" id="frm_leave" method="post" action="overtime_edit.php?act=submit&id=<?php echo $_GET['id']?>">

	<?php echo $msg;?>
	<h3>Name: <?php echo getID($e['employmentStatus'],$e['employeeNo'])." - ".$e['lastName'].", ".$e['firstName'];?></h3>
   <div class="row">
    <div class="col-md-6">
    <table class="table">
      
      
   
      <?php echo $data;?>
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