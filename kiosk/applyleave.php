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
$optionleave = "(7,8,10,17,15,3,5)";
$eee = mysql_fetch_array(mysql_query("select  DATE_ADD( dateHired, INTERVAL 1 YEAR ) as anniv from employee where ndex='".$_SESSION['ndex']."'"));
date_default_timezone_set("Asia/Manila");
$datenow = date('Y-m-d');
$anniversary = date('Y-m-d',strtotime($eee['anniv']));
//echo $datenow." xx ".$anniversary;
if($datenow < $anniversary){
  $optionleave = "(7,8,17,15,5)";
}

$_GET['emp']=$_SESSION['ndex'];

###############################
if ($_GET['startDate']){ 
  $start =strtotime($_GET['startDate']);
        $end =strtotime($_GET['endDate']);
        $msg='';
        $xx=0;
        $total_apply = 0;
        $yy='';
        $yy_dates='';
        $le = '';
        for ( $a = $start; $a <= $end; $a += 86400 ){
          $det=date('Y-m-d',$a);        
          $total_apply++;
/*      
          $leave_rec = mysql_fetch_array(mysql_query("select * from kiosk_request where empid='".$_GET['emp']."' and `date` = '".$det."' and tayp='leave'"));
*/
          $ck_rd = mysql_fetch_array(mysql_query("select * from employee_restday where employeeId='".$_GET['emp']."' and `startDate` = '".$det."'"));
          if($ck_rd['ndex']>0){
            $xx++;            
            $le.='<br>'.$det;
          }
          
        }
        if($xx>0){
          $msg.='<div class="alert alert-danger">Note: Date range selected includes OFF days '.$le.'</div>';
        }
       
    $total_net = $total_apply - $xx;
}



$levqry=mysql_query("select * from `leave` where name not like '%birth%' and ndex<>12  order by name");
  while($lev=mysql_fetch_object($levqry)){
     $ctr1s++;
       if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F9ECCF';}

    $levtotalqry=mysql_query("SELECT * from employee_leave where employeeId='".$_GET['emp']."' and leaveId='".$lev->ndex."' and startDate>='".date('Y')."'-01-01'' and endDate<='".date('Y').'-12-31'."' order by startDate");
    $levlimit=mysql_fetch_object(mysql_query("select * from employee_leave_limit where employeeId='".$_GET['emp']."' and leaveId='".$lev->ndex."' and yer='".date('Y')."'"));

    //    $levtotalqry=mysql_query("SELECT * from employee_leave where employeeId='".$_GET['emp']."' and leaveId='".$lev->ndex."' and startDate>='2019-01-01' and endDate<='2019-12-31' order by startDate");
    // $levlimit=mysql_fetch_object(mysql_query("select * from employee_leave_limit where employeeId='".$_GET['emp']."' and leaveId='".$lev->ndex."' and yer='2019'"));


    $levconsume=0;
    $leavedates="";
    while($l=mysql_fetch_object($levtotalqry)){
    $lstart = strtotime($l->startDate);
    $lend = strtotime($l->endDate);
      for ( $la = $lstart; $la <= $lend; $la += 86400 ){
        $lrd="(";
        $lrestdayq=mysql_query("select * from employee_restday where employeeId=".$_GET['emp']." and startDate<'".date('Y-m-d',$la)."' ORDER BY startDate DESC LIMIT 2 ");
        while($lr=mysql_fetch_object($lrestdayq)){$lrd.=date('D', strtotime($lr->startDate))."&nbsp;";}
        $lrd.=")";
        $lday = date('D', strtotime(date('Y-m-d',$la)));
        $levconsume++;
      }
    }
    $levremaining=$levlimit->leaveLimit - $levconsume;

    $disable_option = "";
if($lev->ndex<>5){
    $zl[$lev->ndex] = $levremaining;
    if($total_net > $levremaining){      
      $disable_option = " disabled";
    }
    $levremainingx = "(".$levremaining.")";
}
else{
  $levremainingx = '';
}


    $optionleave.="<option value='".$lev->ndex."' ".$disable_option.">".$lev->name." ".$levremainingx."";
    // - $levconsume
    /*
    $levdata.="<tr onclick=\"Effect.toggle('".$lev->ndex."', 'blind', { duration: 1.0 });\" style='background-color:".$bgclr1s.";font-size:12px;'>
            <td>".$lev->name."</td>
            <td align='right'>".$levremaining."</td>
          </tr>
          <tr><td colspan='2' align='center'>
            <div id='".$lev->ndex."' style='display:none;'><table style='font-size:11px;color:maroon;'>".$leavedates."</table></div>
          </td></tr>
          ";
    */
  }

#########################
/*
$leave=mysql_query("SELECT * FROM `leave` where ndex in ".$optionleave." order by name");
  $optionleave="<option value=''> - Select Leave -";
while($rsleave=mysql_fetch_object($leave)){
  $optionleave.="<option value='".$rsleave->ndex."'>".$rsleave->name."";
}
*/


if(isset($_GET['act'])){
  date_default_timezone_set("Asia/Manila");
  $remrem = mysql_real_escape_string($_POST['aremarks']);
  if(strlen($remrem)<=0){
    die('Please Input Remarks. Click BACK button');
  }
  $zlx = $_POST['leave'];
  if($total_net > $zl[$zlx]){
    die('You dont have enough leave balance. Click BACK button');
  }


  $ins=mysql_query("insert into kiosk_request
(`tayp`, `empid`, `ref`, `date`, `status`,`request`,`remarks`,createdDate) VALUES 
('leave','".$_GET['emp']."', '0', '".$_POST['dated']."','Save','".$_POST['dated']."|".$_POST['dates']."|".$_POST['leave']."','".mysql_real_escape_string($_POST['aremarks'])."','".date('Y-m-d H:i:s')."')");
  $logs = mysql_query("insert into kiosk_request_logs (action,timelog,request_id,user) VALUES ('Create Request','".date('Y-m-d H:i:s')."','".mysql_insert_id()."','".$_SESSION['eid']."')");
  $msg='<div class="alert alert-success">
                <strong>Success!</strong> The request has been sent.
              </div>';
  header("location:applyleave.php");
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
            <h1>Apply Leave</h1>
      <form name="frmitem" id="frmitem">
            <table cellpadding="5" cellspacing="0" border="0" width="100%">
                <tr>
    <td> <input type="hidden" name="emp" value="<?php echo $_GET['emp'];?>">Start Date:<input type="Text" name="startDate" id="startDate" required="required" size="15" value="<?php echo $_GET['startDate'];?>"><a href="javascript:show_calendar('frmitem.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a>
    End Date:<input type="Text" required="required" name="endDate" id="endDate" size="15" value="<?php echo $_GET['endDate'];?>"><a href="javascript:show_calendar('frmitem.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a><input type="submit" value="Apply"><br><br></td>
    
  </tr>
  </form>
  <?php if ($_GET['startDate']){ 

      
    ?>
  <form method="post" id="frm_leave" action="applyleave.php?act=send&emp=<?php echo $_GET['emp'];?>">
    <tr><td colspan="3"><?php //echo $msg; ?></td></tr>
  <tr>
 
      <input type="hidden" name="dated" value="<?php echo $_GET['startDate'];?>">
      <input type="hidden" name="dates" value="<?php echo $_GET['endDate'];?>">
   

    
    <td><br><br><strong style="color:blue;font-size:15px;"><?php echo $_GET['startDate'];?> to <?php echo $_GET['endDate'];?></strong>&nbsp;&nbsp;&nbsp;
      <select name="leave" id="leave_id" required="required"><?php echo $optionleave;?></select></td>
      <td valign="top"><br><br><textarea rows="5" cols="30" name="aremarks" placeholder="Reason" required></textarea></td>
    <td><br><br><a href="#"  onclick='var result = confirm("Are you sure you want to continue?"); if(result){
      if(!$("#leave_id").val()){
        alert("Please select leave type!");
        return false;
      }
      else{
        $("#frm_leave").submit();
      }
    }'  class="btn green">Send Request</a></td>
  </tr>
</form>
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
$xpq=mysql_query("Select * from kiosk_request where tayp='leave' and empid='".$_GET['emp']."' order by ndex desc");
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
  $jk=mysql_fetch_array(mysql_query("select * from `leave` where ndex='".$req[2]."'"));

$log = mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' and action='Create Request'"));
  if($log['ndex']){
    $date_created=$log['timelog'];
  }
  else{
    $date_created='';
  }

   $hist=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' order by ndex desc limit 1"));
   $link="leave_edit.php";
  $cancel = ($xp['approve2'] == '1' ? '' : '<a href="#" onclick="deleter('.$xp['ndex'].');" class="btn btn-xs red">Cancel</a>');
  echo '<tr>
            <td>'.$xp['tayp'].'</td>
            <td>'.$date_created.'</td>           
            <td>From '.$req[0].' to '.$req[1].'<br>'.$jk['name'].'</td>           
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
      window.location.href="applyleave.php?delete="+x;
    }
    else{
      return false;
    }
  }
</script>