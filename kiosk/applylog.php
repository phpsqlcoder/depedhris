<?php 
ob_start();
include("../dbcon.php");
include("../employeefunctions.php");
include('../payroll/payrollfunctions.php');
include("newheader.php");
session_start();
if(!$_SESSION['ndex']){header("location:login.php");}
$msg='';

if(isset($_GET['delete'])){
  $transfer = mysql_query("insert into kiosk_request_deleted (`ndex`, `tayp`, `empid`, `ref`, `date`, `status`, `request`, `approve1`, `approve2`, `remarks`, `isPending`)
    select `ndex`, `tayp`, `empid`, `ref`, `date`, `status`, `request`, `approve1`, `approve2`, `remarks`, `isPending` from kiosk_request where ndex='".$_GET['delete']."'
    ");
  $delete = mysql_query("delete from kiosk_request where ndex='".$_GET['delete']."'");
   $msg='<div class="alert alert-success">
                <strong>Success!</strong> The request has been deleted.
              </div>';
}

$_GET['emp']=$_SESSION['ndex'];

if(isset($_GET['act'])){
date_default_timezone_set("Asia/Manila");
if($_POST['dated']){
  $remrem = mysql_real_escape_string($_POST['aremarks']);
  if(strlen($remrem)<=0){
    die('Please Input Remarks. Click BACK button');
  }

  $ins=mysql_query("insert into kiosk_request
(`tayp`, `empid`, `ref`, `date`, `status`,`request`,`remarks`,createdDate) VALUES 
('log','".$_GET['emp']."', '0', '".$_POST['dated']."','Save','".$_POST['inout']."|".$_POST['hours']."|".$_POST['minutes']."|".$_POST['ampm']."','".mysql_real_escape_string($_POST['aremarks'])."','".date('Y-m-d H:i:s')."')");
 
      $logs = mysql_query("insert into kiosk_request_logs (action,timelog,request_id,user) VALUES ('Create Request','".date('Y-m-d H:i:s')."','".mysql_insert_id()."','".$_SESSION['eid']."')");
  header("location: applylog.php");
  $msg='<div class="alert alert-success">
                <strong>Success!</strong> The request has been sent.
              </div>';
}
}

$r=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_GET['emp'].""));
  
?>
        <div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
    <div class="main">
      <div class="container">
        <?php echo $msg; ?>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN CONTENT -->
          <div class="col-md-12 col-sm-12">
            <h3>Forgot to Log</h3>
      <form name="frmitem" id="frmitem">
            <table cellpadding="5" cellspacing="0" border="0" width="100%">
                <tr>
    <td> <input type="hidden" name="emp" value="<?php echo $_GET['emp'];?>">Select Date:<input type="Text" name="startDate" id="startDate" size="15" value="<?php echo $_GET['startDate'];?>"><a href="javascript:show_calendar('frmitem.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a>
    <input type="submit" value="Submit"><br><br></td>
    
  </tr>
  </form>
  <?php if ($_GET['startDate']){ 
    $lags='<b style="color:blue;">Actual Timelogs:</b><br><br>';
    //echo "select * from hr_interface where dtrid='".$r->biometricNo."' and datelog='".$_GET['startDate']."'";
    $chk = mysql_fetch_array(mysql_query("select * from kiosk_request where date='".$_GET['startDate']."' and empid='".$_GET['emp']."' and tayp='log'"));
    $n=mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$_GET['startDate']."'");
    while($m=mysql_fetch_array($n)){
      $in_out = ($m['in_out'] == '1' ? 'Timeout' : 'Timein');
      $lags.=$in_out.': '.date('Y-m-d h:i A',strtotime($m['log'])).'<br>';
    }

    $nd=mysql_query("select * from hrinterface_deleted where dtrid='".$r->biometricNo."' and datelog='".$_GET['startDate']."'");
    while($md=mysql_fetch_array($nd)){
      $in_outd = ($md['in_out'] == '1' ? 'Timeout' : 'Timein');
      $lags.=$in_outd.': '.date('Y-m-d h:i A',strtotime($md['log'])).' (existing)<br>';
    }

    if($chk['ndex']>0){ // return this to zero after the update 2022-07-13
      echo "<h4 style='color:red;padding-left:300px;'>Failure to Log request already exist on this date.</h4>";
    }
    else{
    ?>
  <form method="post" id="frm_leave" action="applylog.php?act=send&emp=<?php echo $_GET['emp'];?>">
  <tr>
    <td>
      <?php echo $lags; ?>
      <input type="hidden" name="dated" value="<?php echo $_GET['startDate'];?>">
    </td>
    <td><br><br><strong style="color:blue;font-size:15px;"><?php echo $_GET['startDate'];?></strong>&nbsp;&nbsp;&nbsp;<select name="inout"><option value="in">Timein<option value="out">Timeout</select></td>
    <td><br><br><select name="hours"><?php echo $optionhour;?></select></td>
    <td><br><br><select name="minutes"><?php echo $optionminute;?></select></td>
    <td><br><br><select name="ampm"><?php echo $optionampm;?></select></td>
    <td valign="top"><br><br><textarea rows="5" cols="30" name="aremarks" placeholder="Reason" required></textarea></td>
    <td><br><br>
      <a href="#"  onclick='var result = confirm("Are you sure you want to continue?"); if(result){$("#frm_leave").submit();}'  class="btn green">Send Request</a>
    </td>
  </tr>
</form>
  <?php }} ?>
              
              </table>
            




<br><br><br>

<div class="portlet box green">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-picture"></i>Filed Applications
              </div>           
            </div>
            <div class="portlet-body">
              <div class="table-scrollable">

  <table class="table">
    <thead>
                <tr>
                    <td>Type</td>
                    <td>Date Filed</td>
                    <td>Date of Application</td>
                    <td>Details</td>                    
                    <td>Reason</td>
                    <td>Comments</td>
                    <td>Manager</td>
                    <td>HR</td>
                </tr>
    </thead>
    <tbody>
               <?php 

$xr=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_GET['emp'].""));
$xpq=mysql_query("Select * from kiosk_request where tayp='log' and empid='".$_SESSION['ndex']."' order by ndex desc");
while($xp=mysql_fetch_array($xpq)){  
  //$manager = ($xp['approve1'] == '1' ? '<a href="#" class="btn btn-xs green"><i class="fa fa-check"></i></a>' : '');
  $manager='';
  if($xp['isDisapproved'] == '1'){
    $manager='<a href="#/" title="'.$hist['remarks'].'">Disapproved</a>';
  }
  else{
    if($xp['approve1'] == '1'){
      $mnapp=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' and action='Approve Request' order by ndex desc limit 1"));
      $manager = '<a href="#" class="btn btn-xs green"><i class="fa fa-check"></i></a><br>'.date('M d h:i A',strtotime($mnapp['timelog']));
    }
    else{
      $manager='';
    }
  }
  if($xp['approve2'] == '1'){
    $hrapp=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' and action='Approve Request (HR)' order by ndex desc limit 1"));
    $hr='<a href="#" class="btn btn-xs green"><i class="fa fa-check"></i></a><br>'.date('M d h:i A',strtotime($hrapp['timelog']));
  }
  else{
    $hr='';
  }
  $req=explode("|",$xp['request']);
  $log = mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' and action='Create Request'"));
  if($log['ndex']){
    $date_created=$log['timelog'];
  }
  else{
    $date_created='';
  }
  $cancel = ($xp['approve2'] == '1' ? '' : '<a href="#" onclick="deleter('.$xp['ndex'].');" class="btn btn-xs red">Cancel</a>');
   $hist=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' order by ndex desc limit 1"));
   $link="log_edit.php";
  echo '<tr>
            <td>'.$xp['tayp'].'</td>
            <td>'.$date_created.'</td>   
            <td>'.$xp['date'].'</td>           
            <td>Time'.$req[0].' '.$req[1].':'.$req[2].' '.$req[3].'</td>           
            <td>'.$xp['remarks'].'</td>
             <td><a href="#" onclick=\'window.open("../online_apps/'.$link.'?id='.$xp['ndex'].'","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=500")\';>'.$hist['remarks'].'</a></td>
             <td>'.$manager.'</td>
             <td>'.$hr.'</td>
             <td>'.$cancel.'</td>
  </tr>';
}
               ?>
    </tbody>
              </table>

    </div>
            </div>
          </div>


      
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
      </div>
    </div>
<?php include("newfooter.php");?>
<script type="text/javascript">
  function deleter(x){
    var result = confirm("Are you sure you want to cancel this request?"); 
    if(result){
      window.location.href="applylog.php?delete="+x;
    }
    else{
      return false;
    }
  }
</script>