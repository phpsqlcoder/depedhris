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

?>


<table width="100%">
      <tr>
        <td align="center"><h3>Davao Doctors Hospital<br>
  118 E. Quirino Ave., Davao City</h3>
  </td>
      </tr>
  </table>


  <table width="100%" style="font-family:Arial;font-size:14px;">
    <tr>
      <td><strong>Name:</strong> <?php echo $e['lastName'].", ".$e['firstName']." ".$e['middleName']?></td>
      <td><strong>Date Prepared:</strong> <?php echo date('F d, Y')?></td>
      
    </tr>
    <tr>
      <td><strong>Position/Department:</strong>  <?php echo $p['name']; ?>/<?php echo $d['name']; ?></td>
      <td><strong>Date of Separation from Service:</strong> <?php echo date('F d, Y',strtotime($e['endDate']))?></td>
    </tr>
    <tr><td><strong>Employment Status:</strong> <?php echo $e['employmentStatus']; ?></td></tr>
    <tr><td><strong>Contact No:</strong> <?php echo $d['name']; ?></td></tr>
  </table>
  <table width="100%" style="font-family:Arial;font-size:14px;">
    <tr><td><br>The above named employee requests clearance of all his/her accountabilities with the company. Please write employee's accountabilities and affix your signature over printed name to denote that you have cleared all accountabilities of the employee under your department/section.  ANY ACCOUNTABILITIES DISCOVERED FROM THE LAST PAYROLL AND OTHER BENEFITS OF THE ABOVE NAMED EMPLOYEE.<br><br>FINANCE PAYROLL SHALL NOT RELEASE AMOUNT DUE THE EMPLOYEE UNLESS ALL ACCOUNTABILITIES INDICATED IN THIS CLEARANCE HAVE BEEN FULLY PAID OR DEDUCTED FROM THE EMPLOYEE'S REMAINING SALARY AND BENEFITS</td></tr>

    <tr><td><br>THIS CLEARANCE SHALL BE ACCOMPLISHED ONE (1) MONTH FROM THE EFFECTIVITY OF RESIGNATIN, RETIREMENT OR TERMINATION.<br><br></td></tr>
  </table>

  <table width="100%" style="font-family:Arial;font-size:14px;" border="1" cellpadding="10" cellspacing="0">
    <tr style="color:blue;font-weight:bold;">
      <td style="width:30%" align="center">Department</td>
      <td style="width:30%" align="center">Employee Accountability</td>
      <td style="width:20%" align="center">Authorized Signature</td>
      <td style="width:20%" align="center">Date</td>
    </tr>
  
    <?php echo $dd;?>

  </table>

  <table width="100%" style="font-family:Arial;font-size:14px;">
    


    <tr><td align="center"><br><br><br>_____________________________________</td>
    </tr>
    <tr><td align="center">HR Director / HR Authorized Representative</td>
    </tr>


  </table>