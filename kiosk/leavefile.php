<?php 
ob_start();
include("../dbcon.php");
include("../employeefunctions.php");
include('../payroll/payrollfunctions.php');
include("newheader.php");
include("../myfunctions.php");
//error_reporting(E_ALL);
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

if(isset($_GET['startDate'])){

$r=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_GET['emp'].""));
 
    
  $start = strtotime($_GET['startDate']);
  $end = strtotime($_GET['endDate']);
  $totalot=0;
  $totalnp=0;
  for ( $a = $start; $a <= $end; $a += 86400 ){
    $det=date('Y-m-d',$a);
    if($_GET['act']=='submitot' && $_POST[$det]>0 && $_POST['h'.$det]==0){
      $ckd = mysql_fetch_array(mysql_query("select * from kiosk_request where empid='".$_GET['emp']."' and date='".$det."' and tayp='Overtime'"));
      if(!$ckd['empid']){
        date_default_timezone_set("Asia/Manila");
        //$remrem = mysql_real_escape_string($_POST['aremarks']);  
        $remrem = mysql_real_escape_string($_POST['rem'.$det]);
        if(strlen($remrem)<=0){
          die('Please Input Remarks. Click BACK button');
        }
        if($_POST[$det]>16.01){
          die('Overtime maximum time is 16 hours only. Click BACK button');
        }
        if($_POST[$det]<.30){
          die('Overtime minimum time is 30 minutes. Click BACK button');
        }
        $ins=mysql_query("insert into kiosk_request
        (`tayp`, `empid`, `ref`, `date`, `status`,`request`,`remarks`,createdDate) VALUES
         ('Overtime','".$_GET['emp']."', '0', '".$det."','Save','".$_POST[$det]."|".$_POST['np'.$det]."','".mysql_real_escape_string($_POST['rem'.$det])."','".date('Y-m-d H:i:s')."')");
        
        $logs = mysql_query("insert into kiosk_request_logs (action,timelog,request_id,user) VALUES ('Create Request','".date('Y-m-d H:i:s')."','".mysql_insert_id()."','".$_SESSION['eid']."')");
          $msg='<div class="alert alert-success">
                        <strong>Success!</strong> The request has been sent.
                      </div>';
      }
        header("location:leavefile.php?startDate=".$_GET['startDate']."&endDate=".$_GET['endDate']."");
    }
    
    //echo $det."<br>";
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
        $inlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$det."' and in_out=0 ORDER BY log LIMIT 0,1"));
        $nxtday_timeIn=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog>'".$det."' and in_out=0 ORDER BY log LIMIT 0,1"));
        $out=mysql_fetch_object(mysql_query("SELECT * FROM `hrinterface` where in_out=1 and datelog >= '".$det."' and log<'".$nxtday_timeIn->log."' and dtrid='".$r->biometricNo."' ORDER BY log DESC limit 0,1")); // Get OUT record
        $tymlogs=$out->log;
        $shiftOut=$getshift->timeOut;
        $tymlogs2=$inlog->log;
        $shiftIn=$getshift->timeIn;
        $datein=date('Y-m-d',strtotime($inlog->log));
        $shiftdatein=$datein." ".$getshift->timeIn;
        $advot=timeDiffinSeconds($inlog->log,$shiftdatein)/3600;

        if($advot<0){$advot=0;}
        //$advot=$inlog->log." - ".$shiftdatein;
      }
    }
    $totalot+=$aot->approvedOvertime;
    $totalnp+=$aot->approvedOvertimeNightPremium;
    $unapprove_ot = number_format($aot->overtime/60,2);
    $total_overtime = number_format((($aot->overtime * 60) + ($advot * 3600))/3600,2);
    $h='0';
    if($aot->approvedOvertime>0){$h='1';}
    $chk = mysql_fetch_array(mysql_query("select * from kiosk_request where date='".$det."' and empid='".$_GET['emp']."' and tayp='Overtime'"));
    if($total_overtime>0){
      $data.="<tr style='font-size:12px;background-color:".$bgclr1s."'>
            <td>".$det."</td>
            <td>".$tymlogs2."</td>
            <td>".$shiftIn."</td>
            <td>".number_format($advot,2)."</td>
            <td>".$shiftOut."</td>
            <td>".$tymlogs."</td>
            <td>".number_format($aot->overtime/60,2)." hrs<input type='hidden' name='h".$det."' value='".$h."'></td>
      ";
      if($chk['ndex']>0){
          $log = mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$chk['ndex']."' and action='Create Request'"));         
              $date_created=$log['timelog'];

          $data.="<td colspan='3' style='color:gray;' align='center'>FILED (".$date_created.").</td>";
      }
      else{
        if($tymlogs=='' OR $tymlogs2 ==''){          
          $data.="<td colspan='3' style='color:gray;' align='center'>Incomplete Logs.</td>";
        }
        else {
        $data.="
              <td align='right'><input type='number' size='3' step='0.01' name='".$det."' style='width:80px;text-align:right;' onkeyup=\"if(parseInt(this.value)>15){alert('Exceed Overtime Limit!'); this.value='".$aot->approvedOvertime."';} if(parseInt(this.value)>0){ $('#rem".$det."').prop('required', 'true');} else { $('#rem".$det."').prop('required', false);}\" max='".$total_overtime."' onchange=\" if(parseFloat(this.value)<.5){alert('Minimum value should be 0.5 hrs!'); this.value=0.00;}\" value='".$aot->approvedOvertime."' style='text-align:right;' onFocus='this.select()' onClick='this.select()'> hrs</td>

              <td align='right'><input type='number' step='0.01' max='".$total_overtime."' style='width:80px;text-align:right;' onkeyup=\"if(parseInt(this.value)>15){alert('Exceed Overtime Limit!'); this.value='".$aot->approvedOvertimeNightPremium."';} if(parseInt(this.value)>0){ $('#rem".$det."').prop('required', 'true');} else { $('#rem".$det."').prop('required', false);}\" name='np".$det."' value='".$aot->approvedOvertimeNightPremium."' style='text-align:right;' onchange=\" if(parseFloat(this.value)<.5){alert('Minimum value should be 0.5 hrs!'); this.value=0.00;}\" onFocus='this.select()' onClick='this.select()'> hrs &nbsp;&nbsp;&nbsp;</td>
              <td><input type='text' name='rem".$det."' id='rem".$det."' value='".$aot->overtimeRemarks."'></td>
        </tr>";
        }
      }
    }
  }
  $data.="<tr>
        <td>Total</td>
        <td colspan='5' align='right'>".number_format($totalot,2)." hrs</td>
        <td align='right'>".number_format($totalnp,2)." hrs</td>
  </tr>";
}
?>
     <div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
    <div class="main">
      <div class="container">
        <h3>Apply Overtime<br></h3>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN CONTENT -->
          <div class="col-md-12 col-sm-12">
         <form name="frmitem" id="frmitem">
            <table cellpadding="5" cellspacing="0" border="0" width="80%">
                <tr>
    <td>Start Date:<input type="Text" name="startDate" id="startDate" size="15" value="<?php echo $_GET['startDate'];?>"><a href="javascript:show_calendar('frmitem.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
    <td>End Date:<input type="Text" name="endDate" id="endDate" size="15" value="<?php echo $_GET['endDate'];?>"><a href="javascript:show_calendar('frmitem.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a>&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Search"></td>
    
  </tr>
              
              </table>
            </form>

            <?php if($_GET['startDate']){
  $ds=mysql_fetch_object(mysql_query("select * from dept where ndex=".$r->deptId.""));
  ?>
  <form name="frmemp" id="frm_leave" method="post" action="leavefile.php?act=submitot&startDate=<?php echo $_GET['startDate'];?>&endDate=<?php echo $_GET['endDate'];?>">
    <table width="100%">
      <tr><td>&nbsp;</td></tr>
      <tr style='color:maroon;font-weight:bold;'>
        <td colspan="4">Name: <?php echo getID($r->employmentStatus,$r->employeeNo)." - ".$r->lastName.", ".$r->firstName." - ".$ds->name;?><input type="Hidden" name="deptselect" value="<?php echo $d->ndex;?>"></td>
        <td><br><br></td>
        <td colspan="5" style="color:red;">Note: Convert minutes to hours (e.g. 30 mins Overtime divided by 60 mins = 0.5 hours)</td>
      </tr>
      <tr style="color:red;font-size:14px;font-weight:bold;"><td colspan='3'><?php echo $msg;?></td></tr>
      <tr style="color:blue;font-size:12px;font-weight:bold;">
        <td>Date</td>
        <td>Time In</td>
        <td>ShiftIn</td>
        <td>Unapprove<br>Overtime (hr/s)<br>Early In</td>
        <td>ShiftOut</td>
        <td align="right" style="padding-right:20px;">Time Out</td>
        <td>Unapprove<br>Overtime (hr/s)</td>
        <td align="center">Overtime<br>(Excess of Regular <br>Working Hrs)</td>
        <td align="center">OT Night Premium<br>(From 6PM to 6AM)</td>
        <td align="center">Remarks</td>
      </tr>
      <tr><td colspan='10'><hr></td></tr>
   
      <?php echo $data;?>
      <tr><td colspan="10" align="right">
        <input type="submit" value="Send Request" class="btn green">

      </td></tr>
    </table>
  </form>
  <?php }?>


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
$xpq=mysql_query("Select * from kiosk_request where tayp='Overtime' and empid='".$_SESSION['ndex']."' order by ndex desc");
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
  $hist=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' order by ndex desc limit 1"));
   $link="overtime_edit.php";
    $cancel = ($xp['approve2'] == '1' ? '' : '<a href="#" onclick="deleter('.$xp['ndex'].');" class="btn btn-xs red">Cancel</a>');
  echo '<tr>
            <td>'.$xp['tayp'].'</td>
            <td>'.$date_created.'</td>
            <td>'.$xp['date'].'</td>           
            <td>OT='.$req[0].', NP='.$req[1].'</td>           
            <td>'.$xp['remarks'].'</td>
              <td><a href="#" onclick=\'window.open("../online_apps/'.$link.'?id='.$xp['ndex'].'","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=500")\';>'.$hist['remarks'].'</a></td>
             <td align="center">'.$manager.'</td>
             <td align="center">'.$hr.'</td>
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
      window.location.href="leavefile.php?delete="+x;
    }
    else{
      return false;
    }
  }
</script>