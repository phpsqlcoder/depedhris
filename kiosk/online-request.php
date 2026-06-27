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


$_GET['emp']=$_SESSION['ndex'];

if($_GET['act']){
  date_default_timezone_set("Asia/Manila");
  $coe_f = '';
  $coe_p = '';
  if(isset($_POST['coe_purpose'])){
    $coe_p = $_POST['coe_purpose'];
  }
  if(isset($_POST['coe_freq'])){
    $coe_f = $_POST['coe_freq'];
  }
  $insert = mysql_query("insert into online_requests (`requestor`, `requested_at`, `request_type`, `details`, email, coe_purpose, coe_freq) values 
    ('".$_GET['emp']."','".date('Y-m-d H:i:s')."','".$_GET['id']."','".$_POST['details']."','".$_POST['email']."','".$coe_p."','".$coe_f."')");
  $ins=mysql_insert_id();

  $dd = mysql_query("select * from templates_approvers where template_id='".$_GET['id']."' order by seq");

  while($d=mysql_fetch_array($dd)){
    $cur = 0;
    if($d['seq'] == 1){
      $cur = 1;
    }

    $app = $d['user_id'];
 
    if($d['type']=='dept'){

      $depts = mysql_query("select * from users where deptId like '%".$_SESSION['deptId']."%'");
      while($de=mysql_fetch_array($depts)){
        $ex = explode(",", $de['deptId']);
        if (in_array($_SESSION['deptId'], $ex)){
          $app = $de['ndex'];
        }
      }
    }

    $insert_app = mysql_query("insert into online_request_approvers (`request_id`, `approver_id`, `status`,  `seq`, `is_current`, can_override, dept) values 
    ('".$ins."','".$app."','Pending','".$d['seq']."','".$cur."', '".$d['can_override']."', '".$d['dept']."')");
  }

  //logs
  $insert_app = mysql_query("insert into online_request_logs (`request_id`, `action`, `user_id`, `created_at`) values 
    ('".$ins."','Created the Request','".$_SESSION['firstName']." ".$_SESSION['middleName']." ".$_SESSION['lastName']."','".date('Y-m-d H:i:s')."')");
  //die();
  //header("location: online-request.php?id=".$_GET['id']);
}

$title = '';
if($_GET['id'] == 1){
  $title = 'E-Clearance';
}
if($_GET['id'] == 2){
  $title = 'Certificate of Employment';
}
if($_GET['id'] == 3){
  $title = 'Resignation / Retirement';
}


 
  
?>
     <div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
    <div class="main">
      <div class="container">
        <h3>Online Request (<?php echo $title; ?>)<br></h3>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN CONTENT -->
          <div class="col-md-12 col-sm-12">
         <form name="frmitem" id="frmitem" method="post" action="online-request.php?act=submit&id=<?php echo $_GET['id'];?>">
            <table cellpadding="15" cellspacing="20" border="0" width="80%">
                
                <tr>
                  <td>Details: <input type="text" name="details" id="details" class="form-control margin-bottom-40">
                    </td>
                    <td>Email Address: <input required type="text" name="email" id="email" class="form-control margin-bottom-40" value="<?php echo $_SESSION['emailAddress']; ?>">
                    </td>
                </tr>
                <tr>
                  <td>
                    Purpose: <input type="text" name="coe_purpose" id="coe_purpose" class="form-control margin-bottom-40">
                  </td>
                  <td>
                    Is this your first time to request COE: <select name="coe_freq" id="coe_freq" class="form-control margin-bottom-40">
                      <option value="No" selected>No</option>
                      <option value="Yes">Yes</option>
                    </select>
                  </td>
                </tr>
               
             
                <?php
                  if($_GET['id'] == 2){
                ?>
                  <tr>
        <td><br><input type="checkbox">With Job Description</td>
      </tr>
      <tr>
        <td><input type="checkbox">With Compensation</td>
      </tr>
      <tr>
        <td><input type="checkbox">With Number of Hours<br><br></td>
      </tr>
              <?php } ?>
            </table>
            <input type="submit" value="Submit Request" class="btn btn-sm btn-success">
          </form>

           


<br><br><br>

<div class="portlet box green">
  <div class="portlet-title">
    <div class="caption">
      <i class="fa fa-picture"></i>Online Requests
    </div>           
  </div>
  <div class="portlet-body">
    <div class="table-scrollable">
      <table class="table">
      <?php
        $rs=mysql_query("select * from online_requests where requestor=".$_GET['emp']." order by ndex desc");
        while($r = mysql_fetch_array($rs)){
          $type = '';
          $docdoc = '';
          if($r['request_type'] == 1){
            $type = 'E-Clearance';
            $docdoc = 'eclearance';
          }
          if($r['request_type'] == 2){
            $type = 'Certificate of Employment';
            $docdoc = 'ecoe';
          }
          if($r['request_type'] == 3){
            $type = 'Resignation / Retirement';
            $docdoc = 'eresignation';
          }


          $s_app='';
          $s_apps = mysql_query("select * from online_request_approvers where request_id=".$r['ndex']." order by seq");
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
            <a><i class="fa fa-user" title="'.$us['fullName'].'" style="font-size:26px;'.$color.'"></i></a>&nbsp;
            ';
          }

          $doc = '<a href="'.$docdoc.'.php?id='.$r['ndex'].'" target="_blank"><i class="fa fa-paperclip"></i></a>';

          // if($r['status'] == 'Approved'){
          //   $doc = '<a href="eclearance.php?id='.$r['ndex'].'" target="_blank"><i class="fa fa-paperclip"></i></a>';
          // }

          echo '
            <tr>
              <td>'.$type.'</td>
              <td>'.$r['requested_at'].'</td>
              <td>
                '.$s_app.'
                '.$doc.'
              </td>
              <td>
                <a href="#" title="Logs" onclick=\'window.open("online_request_logs.php?id='.$r['ndex'].'","displayWindow","toolbar=no,scrollbars=yes,width=1110,height=500")\';><img src=\'images/view.png\' height="15" width="15">Logs</a>

              </td>
            </tr>
          ';
        }
      ?>
      </table>
    </div>
  </div>
</div>



</div>

</div>

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