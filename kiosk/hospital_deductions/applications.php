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

$data='';
$r=mysql_fetch_object(mysql_query("select * from employee where ndex='".$_SESSION['ndex']."'"));
$pq=mysql_query("Select * from kiosk_request where empid='".$_SESSION['ndex']."' order by ndex desc");

while($p=mysql_fetch_array($pq)){
  $log = mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$p['ndex']."' and action='Create Request'"));
  if($log['ndex']){
    $date_created=$log['timelog'];
  }
  else{
    $date_created='';
  }
  
  //$hr = ($p['approve2'] == '1' ? '<a href="#" class="btn btn-xs green"><i class="fa fa-check"></i></a>' : '');
  if($p['approve2'] == '1'){
    $hrapp=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$p['ndex']."' and action='Approve Request (HR)' order by ndex desc limit 1"));
    $hr='<a href="#" class="btn btn-xs green"><i class="fa fa-check"></i></a><br>'.date('M d h:i A',strtotime($hrapp['timelog']));
  }
  else{
    $hr='';
  }
  $type = $p['tayp'];
  if($type=='Overtime'){
      $link="overtime_edit.php";
        $inlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$p['date']."' and in_out=0 ORDER BY log LIMIT 0,1"));
          $outlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$p['date']."' and in_out=1 ORDER BY log LIMIT 0,1"));
          $exp = explode("|",$p['request']);
          $p['request'] = "
            Overtime = ".$exp[0]." hrs<br>
            Night Premium = ".$exp[1]." hrs<br>
            Time In = ".date('H:i:s',strtotime($inlog->log))." <br>
            Time Out = ".date('H:i:s',strtotime($outlog->log))." <br>
          ";
      }
      if($type=='drd'){
        $link="drd_edit.php";
        $inlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$p['date']."' and in_out=0 ORDER BY log LIMIT 0,1"));
          $outlog=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid='".$r->biometricNo."' and datelog='".$p['date']."' and in_out=1 ORDER BY log LIMIT 0,1"));
          $exp = explode("|",$p['request']);
          $p['request'] =
          "Hours = ".$exp[0]." Hr/s<br>
          Overtime = ".$exp[1]." Hr/s<br>
          Night Premium = ".$exp[2]." Hr/s<br>
          Time In = ".date('H:i:s',strtotime($inlog->log))." <br>
          Time Out = ".date('H:i:s',strtotime($outlog->log))." <br>
          ";
      }
      if($type=='log'){
        $link="log_edit.php";
        $exp = explode("|",$p['request']);
          $p['request'] =
          "Type = Time ".strtoupper($exp[0])."<br>
          Time = ".$exp[1].":".$exp[2]." ".$exp[3]." <br>
          
          ";
      }
      if($type=='Schedule'){
        $link="sched_edit.php";
        if($p['request']=='OFF'){
          $n= 'OFF';
        }
        else{
          $s = mysql_fetch_object(mysql_query("select 
            CASE timeIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeIn),'%H:%i %p') END AS tymIn, 
            CASE breakOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakOut),'%H:%i %p') END as brekOut,
            CASE breakIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakIn),'%H:%i %p') END as brekIn,
            CASE timeOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeOut),'%H:%i %p') END as tymOut,breakMinutes,ndex
           from shifting where ndex='".str_replace('s', '', $p['request'])."'"));

          $shift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$r->empid." and '".$r->date."' between startDate and endDate"));
          $n = $s->tymIn." - ".$s->brekOut." - ".$s->brekIn." - ".$s->tymOut;
        }
        
        $o = mysql_fetch_object(mysql_query("select 
          CASE timeIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeIn),'%H:%i %p') END AS tymIn, 
          CASE breakOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakOut),'%H:%i %p') END as brekOut,
          CASE breakIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakIn),'%H:%i %p') END as brekIn,
          CASE timeOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeOut),'%H:%i %p') END as tymOut,breakMinutes,ndex
         from shifting where ndex='".$shift->shiftingId."'"));
          $p['request'] =
          "Old = ".$o->tymIn." - ".$o->brekOut." - ".$o->brekIn." - ".$o->tymOut."<br>
          New = ".$n."    
          ";
      }
      if($type=='leave'){
        $link="leave_edit.php";
        $exp = explode("|",$p['request']);
        $l=mysql_fetch_object(mysql_query("select * from `leave` where ndex='".$exp[2]."'"));
          
          $p['request'] =
          "Type = ".$l->code."<br>
          Start = ".$exp[0]."<br>
          End = ".$exp[1]."<br>         
          ";
      }

  $hist=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$p['ndex']."' order by ndex desc limit 1"));
  $remm='';
  if(strlen($hist['remarks'])>1){
    $remm = '('.$hist['user'].')';
  }

  $cancel = ($p['approve2'] == '1' ? '' : '<a href="#" onclick="deleter('.$p['ndex'].');" class="btn btn-xs red">Cancel</a>');
  $manager='';
  if($p['isDisapproved'] == '1'){
    $manager='<a href="#/" title="'.$hist['remarks'].'">Disapproved</a>';
  }
  else{
    if($p['approve1'] == '1'){
      $mnapp=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$p['ndex']."' and action='Approve Request' order by ndex desc limit 1"));
      $manager = '<a href="#" class="btn btn-xs green"><i class="fa fa-check"></i></a><br>'.date('M d h:i A',strtotime($mnapp['timelog']));
    }
    else{
      $manager='';
    }
  }
  
  $disap = ($p['isDisapproved'] == '1' ? 'Disapproved' : '');
  $data.='<tr>
            <td>'.$p['tayp'].'</td>
            <td>'.$date_created.'</td>  
            <td>'.$p['date'].'</td>           
            <td>'.$p['request'].'</td>
        
            <td width="300">'.$p['remarks'].'</td>
            <td><a href="#" onclick=\'window.open("../online_apps/'.$link.'?id='.$p['ndex'].'","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=500")\';>'.$hist['remarks'].' '.$remm.'</a></td>
             <td>'.$manager.'</td>
             <td>'.$hr.'</td>
             <td>'.$cancel.'</td>
  </tr>';
}
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
            <span class="pull-right"><a href="#" class="btn btn-xs green"><i class="fa fa-check"></i></a> = APPROVED</span>
            <h1>Applications </h1>
            
            <table cellpadding="5" class="table table-condensed table-striped" cellspacing="0" border="0" width="100%">
                <tr>
                    <th>Type</th>
                    <th>Date Filed</th>
                    <th>Date of Application</th>
                    <th>Details</th>
                 
                    <th>Reason</th>
                    <th>Comments</th>
                    <th>Manager</th>
                    <th>HR</th>
                    <th>Cancel</th>
                </tr>
                <?php echo $data;?>
              </table>
            


      
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
      window.location.href="applications.php?delete="+x;
    }
    else{
      return false;
    }
  }
</script>