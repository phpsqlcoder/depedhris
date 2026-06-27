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
$remrem = mysql_real_escape_string($_POST['aremarks']);
  if(strlen($remrem)<=0){
    die('Please Input Remarks. Click BACK button');
  }

  $ins=mysql_query("insert into kiosk_request
(`tayp`, `empid`, `ref`, `date`, `status`,`request`,`remarks`,createdDate) VALUES 
('drd','".$_GET['emp']."', '0', '".$_POST['dated']."','Save','".$_POST['hrs']."|".$_POST['ot']."|".$_POST['np']."',
'".mysql_real_escape_string($_POST['aremarks'])."','".date('Y-m-d H:i:s')."')");
  $logs = mysql_query("insert into kiosk_request_logs (action,timelog,request_id,user) VALUES ('Create Request','".date('Y-m-d H:i:s')."','".mysql_insert_id()."','".$_SESSION['eid']."')");
  $msg='<div class="alert alert-success">
                <strong>Success!</strong> The request has been sent.
              </div>';
  header("location:applydrd.php");
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
            <h3>Apply Duty Restday</h3>
      <form name="frmitem" id="frmitem">
            <table cellpadding="5" cellspacing="0" border="0" width="100%">
                <tr>
    <td> <input type="hidden" name="emp" value="<?php echo $_GET['emp'];?>">Select Date:<input type="Text" name="startDate" id="startDate" size="15" value="<?php echo $_GET['startDate'];?>"><a href="javascript:show_calendar('frmitem.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a>
    <input type="submit" value="Submit"><br><br></td>
    
  </tr>
  </form>
  <?php 
  if ($_GET['startDate']){ 
 
    $inlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$_SESSION['biometricNo']."' and datelog='".$_GET['startDate']."' and in_out=0 ORDER BY log LIMIT 0,1"));
    $outlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$_SESSION['biometricNo']."' and datelog='".$_GET['startDate']."' and in_out=1 ORDER BY log LIMIT 0,1"));
    ?>
    <?php 
    $xy=0;
    $rrdd = date('D', strtotime($_GET['startDate']));
      if(!$inlog->log){
        echo "<h3 style='color:red;'>No timelogs found! You cant apply DRD for this date.</h3>";
      }
      else{
          $lrestdayq=mysql_query("select * from employee_restday where employeeId=".$_GET['emp']." 
            and startDate<='".$_GET['startDate']."' ORDER BY startDate DESC LIMIT 2 ");
         
        while($lr=mysql_fetch_object($lrestdayq)){

          $lrd=date('D', strtotime($lr->startDate));
          if($lrd==$rrdd){
            $xy=1;
          }
        }
        if($xy==0){
          echo "<h3 style='color:red;'>You selected a date that's not tagged as your restday! You cant apply DRD for this date.</h3>";
        }
        else{
            $chk = mysql_fetch_array(mysql_query("select * from kiosk_request where date='".$_GET['startDate']."' and empid='".$_GET['emp']."' and tayp='drd'"));
            if($chk['ndex']>0){
              echo "<h3 style='color:red;padding-left:300px;'>You already applied DRD for this date.</h3>";
            }
            else{


    ?>
            <form method="post" id="frm_leave" action="applydrd.php?act=send&emp=<?php echo $_GET['emp'];?>">
              <tr>
                <td>&nbsp;</td>
                <td align="center">Time Logs</td>
                <td align="center">Duty Hours<br><font style="color:blue;">(Regular Working 8 hrs)</font></td>
                <td align="center">Overtime<br><font style="color:blue;">(Excess of 8 hrs)</font></td>
                <td align="center">Night Premium<br><font style="color:blue;">(From 6pm to 6am)</font></td>
              </tr>
              <tr><td><br></td></tr>
            <tr valign="top">
              <td><br><br>
                <input type="hidden" name="dated" value="<?php echo $_GET['startDate'];?>">
              </td>
             
              <td style="color:blue;font-size:13px;" width="300" align="center">
                <?php echo $inlog->log."<br>to<br>".$outlog->log; ?>
              </td>
              <td>
                <input type="number" name="hrs" step="0.01" min="0" max="24.00" required="required"> Hrs
              </td>
              <td><input type="number" name="ot" step="0.01" min="0" max="24.00"> Hrs</td>
              <td><input type="number" name="np" step="0.01" min="0" max="24.00"> Hrs</td>
              
              
              <td valign="top"><textarea rows="5" cols="30" name="aremarks" placeholder="Reason"></textarea></td>
              <td>
                <a href="#"  onclick='var result = confirm("Are you sure you want to continue?"); if(result){$("#frm_leave").submit();}'  class="btn green">Send Request</a>
               </td>
            </tr>
          </form>
<?php } }} ?>
  <?php } ?>
              
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
                    <td>Cancel</td>
                </tr>
    </thead>
    <tbody>
               <?php 

$xr=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_GET['emp'].""));
$xpq=mysql_query("Select * from kiosk_request where tayp='drd' and empid='".$_SESSION['ndex']."' order by ndex desc");
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
  $xp['request'] =
          "Hours = ".$req[0]." Hr/s<br>
          Overtime = ".$req[1]." Hr/s<br>
          Night Premium = ".$req[2]." Hr/s<br>
          
          ";
  $log = mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' and action='Create Request'"));
  if($log['ndex']){
    $date_created=$log['timelog'];
  }
  else{
    $date_created='';
  }
   $hist=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' order by ndex desc limit 1"));
   $link="drd_edit.php";
 $cancel = ($xp['approve2'] == '1' ? '' : '<a href="#" onclick="deleter('.$xp['ndex'].');" class="btn btn-xs red">Cancel</a>');
  echo '<tr>
            <td>'.$xp['tayp'].'</td>
            <td>'.$date_created.'</td> 
            <td>'.$xp['date'].'</td>           
            <td>'.$xp['request'].' </td>           
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
      window.location.href="applydrd.php?delete="+x;
    }
    else{
      return false;
    }
  }
</script>