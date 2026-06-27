<?php 
ob_start();
include("../dbcon.php");
include("../employeefunctions.php");

session_start();
if(!$_SESSION['ndex']){header("location:login.php");}
$rs=mysql_fetch_array(mysql_query("select * from online_requests where ndex=".$_GET['id']." order by ndex desc"));
$e=mysql_fetch_array(mysql_query("select * from employee where ndex=".$rs['requestor'].""));
  $d=mysql_fetch_array(mysql_query("select * from dept where ndex=".$e['deptId'].""));
  $p=mysql_fetch_array(mysql_query("select * from position where ndex=".$e['position'].""));

$dd = '';
  $s_apps = mysql_query("select * from online_request_approvers where request_id=".$rs['ndex']." order by seq");
    while($sa = mysql_fetch_array($s_apps)){
  
      $us = mysql_fetch_array(mysql_query("select * from users where ndex='".$sa['approver_id']."'"));
      $sig = '';
      $dyt= '';
      if($sa['status'] == 'Approved'){
          $sig = '<img src="../bir/sig.png" style="height:50px;">';
          $dyt = $sa['approved_at'];
      }
      $dd.='<tr>
        <td>'.$sa['dept'].'</td>
        <td>'.$us['fullName'].'</td>
        <td align="center">'.$sig.'</td>
        <td>'.$dyt.'</td>
      </tr>';
      
    }


    $his = '';
    $cnt = 0;
  $sta = mysql_query("select * from employeechangestatus where employeeId=".$rs['requestor']." and effectivityDate<>'0000-00-00' and changeType in ('deptId','position') order by effectivityDate");
    while($st = mysql_fetch_array($sta)){
      $cnt++;
      if($st['changeType'] == 'position'){
        $de = mysql_fetch_array(mysql_query("select * from position where ndex='".$st['new_value']."'"));
        $nym = $de['name'];
      }
      if($st['changeType'] == 'deptId'){
        $de = mysql_fetch_array(mysql_query("select * from dept where ndex='".$st['new_value']."'"));
        $nym = $de['name'];
      }
      $new_date = $st['effectivityDate'];
      
      if($cnt >= 2){

        $his.='<tr>
          <td>'.$nym.'</td>
          <td>'.date('F d, Y',strtotime($old_date)).' to '.date('F d, Y',strtotime($old_date)).'</td>
          
          <td>'.round((strtotime($new_date) - strtotime($old_date))/3600, 1).'</td>
        </tr>';
      }
      $old_date = $st['effectivityDate'];
      
    }

?>


<table width="100%">
      <tr>
        <td align="center"><h3 style="text-decoration:underline;">CERTIFICATION</h3>
  </td>
      </tr>
  </table>


  <table width="100%" style="font-family:Arial;font-size:14px;">
    <tr><td><br><br>To Whom It May Concern:</td></tr>
    <tr><td><br><br>This is to certify that <?php echo $e['lastName'].", ".$e['firstName']." ".$e['middleName']?>, is employed with Davao Doctors Hospital, a 250-bed capacity hospital, assigned to the following departments for the periods and positions indicated below.<br><br><br></td></tr>
    
  </table>

  <table width="100%" style="font-family:Arial;font-size:14px;" border="1" cellpadding="10" cellspacing="0">
    <tr style="color:blue;font-weight:bold;">
      <td align="center">Department</td>
      <td align="center">Period Covered</td>
      <td align="center">No. of Hours</td>
    </tr>
  
    <?php echo $his;?>

  </table>

  <table width="100%" style="font-family:Arial;font-size:14px;">
    <tr><td><br>Issued this <?php echo date('jS',strtotime(date('Y-m-d')))?> day of <?php echo date('F')?>, <?php echo date('Y')?>, at Davao City, Philippines.</td>
    </tr>


    <tr><td align="center"><br><br><br>_____________________________________</td>
    </tr>
    <tr><td align="center">HR Director / HR Authorized Representative</td>
    </tr>


  </table>