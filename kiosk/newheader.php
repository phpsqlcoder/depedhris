<?php 
error_reporting(0);
ob_start();
$last_url = substr($_SERVER['REQUEST_URI'],-9);
session_start();

if(!$_SESSION['kiosk_hris']){
	if($last_url!='index.php'){
  header("location:login.php");
}
}

// $last_visit = mysql_fetch_array(mysql_query("select last_login from employee where ndex='".$_SESSION['ndex']."'"));
// $last_login = $last_visit['last_login'];

// $new_msg = mysql_fetch_array(mysql_query("select count(*) from inquiries_reply r left join inquiries i on i.ndex=r.inquiry_id where "))

//echo $_SESSION['lastName'].",".$_SESSION['firstName']." ".$_SESSION['middleName'];
  ?>
<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.2.0
Version: 3.1.2
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest (the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<!-- Head BEGIN -->
<head>
  <meta charset="utf-8">
  <title>E-Voice</title>

  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <meta content="Metronic Shop UI description" name="description">
  <meta content="Metronic Shop UI keywords" name="keywords">
  <meta content="keenthemes" name="author">

  <meta property="og:site_name" content="-CUSTOMER VALUE-">
  <meta property="og:title" content="-CUSTOMER VALUE-">
  <meta property="og:description" content="-CUSTOMER VALUE-">
  <meta property="og:type" content="website">
  <meta property="og:image" content="-CUSTOMER VALUE-"><!-- link to image for socio -->
  <meta property="og:url" content="-CUSTOMER VALUE-">

  <link rel="shortcut icon" href="favicon.ico">

  <!-- Fonts START -->
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|PT+Sans+Narrow|Source+Sans+Pro:200,300,400,600,700,900&amp;subset=all" rel="stylesheet" type="text/css">
  <!-- Fonts END -->

  <!-- Global styles START -->          
  <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Global styles END --> 
   
  <!-- Page level plugin styles START -->
  <link href="assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet">
  <!-- Page level plugin styles END -->

  <!-- Theme styles START -->
  <link href="assets/global/css/components.css" rel="stylesheet">
  <link href="assets/frontend/layout/css/style.css" rel="stylesheet">
  <link href="assets/frontend/layout/css/style-responsive.css" rel="stylesheet">
  <link href="assets/frontend/layout/css/themes/green.css" rel="stylesheet" id="style-color">
  <link href="assets/frontend/layout/css/custom.css" rel="stylesheet">
  <!-- Theme styles END -->
  <style>
    #menu-bar {
  width: 100%;
  margin: 0px 0px 0px 0px;
  padding: 6px 6px 4px 6px;
  height: 44px;
  line-height: 100%;
  border-radius: 5px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  box-shadow: 2px 2px 3px #666666;
  -webkit-box-shadow: 2px 2px 3px #666666;
  -moz-box-shadow: 2px 2px 3px #666666;
  background: #DBECCA; 
  z-index:999;
}
#menu-bar li {
  margin: 0px 0px 2px 0px;
  padding: 2px 15px 0px 6px;
  float: left;
  position: relative;
  list-style: none;
}
#menu-bar a {
  font-weight: normal;
  font-family: 'trebuchet ms';
  font-style: normal;
  font-size: 20px;
  color: #080808;
  text-decoration: none;
  display: block;
  padding: 6px 20px 6px 20px;
  margin: 0;
  margin-bottom: 2px;
  border-radius: 0px;
  -webkit-border-radius: 0px;
  -moz-border-radius: 0px;
 
}
#menu-bar li ul li a {
  margin: 0;
}
#menu-bar .active a, #menu-bar li:hover > a {
  background: #87C055;
  color: #ffffff;
 
  
}
#menu-bar ul li:hover a, #menu-bar li:hover li a {
  background: none;
  border: none;
  color: #ffffff;
  -box-shadow: none;
  -webkit-box-shadow: none;
  -moz-box-shadow: none;
}
#menu-bar ul a:hover {
  background: #87C055 !important;
  color: #FFFFFF !important;
  border-radius: 0;
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
 
}
#menu-bar li:hover > ul {
  display: block;
}
#menu-bar ul {
  background: #DDDDDD;
  background: linear-gradient(top,  #FFFFFF,  #CFCFCF);
  background: -ms-linear-gradient(top,  #FFFFFF,  #CFCFCF);
  background: -webkit-gradient(linear, left top, left bottom, from(#FFFFFF), to(#CFCFCF));
  background: -moz-linear-gradient(top,  #FFFFFF,  #CFCFCF);
  display: none;
  margin: 0;
  padding: 0;
  width: 185px;
  position: absolute;
  top: 32px;
  left: 0;
  border: solid 1px #B4B4B4;
  border-radius: 10px;
  -webkit-border-radius: 10px;
  -moz-border-radius: 10px;
  -webkit-box-shadow: 2px 2px 3px #222222;
  -moz-box-shadow: 2px 2px 3px #222222;
  box-shadow: 2px 2px 3px #222222;
}
#menu-bar ul li {
  float: none;
  margin: 0;
  padding: 0;
}
#menu-bar ul a {
  padding:10px 0px 10px 15px;
  color:#424242 !important;
  font-size:12px;
  font-style:normal;
  font-family:arial;
  font-weight: normal;
  text-shadow: 2px 2px 3px #FFFFFF;
}
#menu-bar ul li:first-child > a {
  border-top-left-radius: 10px;
  -webkit-border-top-left-radius: 10px;
  -moz-border-radius-topleft: 10px;
  border-top-right-radius: 10px;
  -webkit-border-top-right-radius: 10px;
  -moz-border-radius-topright: 10px;
}
#menu-bar ul li:last-child > a {
  border-bottom-left-radius: 10px;
  -webkit-border-bottom-left-radius: 10px;
  -moz-border-radius-bottomleft: 10px;
  border-bottom-right-radius: 10px;
  -webkit-border-bottom-right-radius: 10px;
  -moz-border-radius-bottomright: 10px;
}
#menu-bar:after {
  content: ".";
  display: block;
  clear: both;
  visibility: hidden;
  line-height: 0;
  height: 0;
}
#menu-bar {
  display: inline-block;
}
  html[xmlns] #menu-bar {
  display: block;
}
* html #menu-bar {
  height: 1%;
}
  </style>
</head>
<!-- Head END -->

<!-- Body BEGIN -->
<body class="corporate" style="background-color:#eaf2dd;">
   

    <!-- BEGIN TOP BAR -->
    <div class="pre-header" style="background-color:#eaf2dd;">
        <div class="container">
            <div class="row">
                <!-- BEGIN TOP BAR LEFT PART -->
                <div class="col-md-5 col-sm-5 additional-shop-info">
                  <a class="site-logo" href="index.php"><img src="images/nlogo.png" alt="DDH" height="90"></a>
                </div>
                <div class="col-md-2 col-sm-2 additional-nav">
                  <a class="site-logo text-center" href="index.php"><img src="images/hwhl.png" alt="DDH" height="90"></a>
                </div>
                <!-- END TOP BAR LEFT PART -->
                <!-- BEGIN TOP BAR MENU -->
                <div class="col-md-5 col-sm-5 additional-nav pull-right" style="display: inline-block;vertical-align: middle;float: none;">
                    <h1 class="margin-top-20" style="color:black;font-weight:bold;font-size:40px;">Welcome to E-VOICE</h1><h3 style="text-align:center;"><?php echo $_SESSION['firstName']." ".$_SESSION['lastName'];?></h3>
                </div>
                <!-- END TOP BAR MENU -->
            </div>
        </div>        
    </div>
    <!-- END TOP BAR -->
    <!-- BEGIN HEADER -->
    <div class="header" style="background-color:#eaf2dd;background-image: url('images/hdbg1.png'); background-repeat: repeat-x; height:75px;">
      <div class="container">
        
        <!-- BEGIN NAVIGATION -->
        <div class="header-navigation font-transform-inherit" style="width:100%;position:relative;top:15px;">
          <ul id="menu-bar" class=" col-md-12 col-sm-12">
            <li><a href="index.php"><strong>Home</strong></a></li>
            <li><a href="#"><strong>Payroll</strong></a>
                 <ul class="dropdown-menu">
                  <li><a href="dtr_n.php">Daily Time Records</a></li>
                  <li><a href="payslip_n.php">Payslips</a></li>
                  <?php //if($_SESSION['ndex'] == 88 || $_SESSION['ndex'] == 4258 || $_SESSION['ndex'] == 704) { ?>
                    <li><a href="payslip_annual.php">Payslips (Annual)</a></li>
                  <?php //} ?>
                  <li><a href="ledger.php">Deduction Ledger</a></li>      
                  <li><a href="hospital.php">Hospital Deductions</a></li>            
                </ul>
            </li>
             <li><a href="#"><strong>Online Application</strong></a>
                 <ul class="dropdown-menu">
                  <li><a href="applications.php">View All Applications</a></li> 
                  <li><a href="leavefile.php">Apply Overtime</a></li>
                 <li><a href="applylog.php">Failure to Log</a></li>
                 <li><a href="changesched.php">Change Schedule</a></li> 
                  <li><a href="applyleave.php">Leave</a></li>
                  <li><a href="applydrd.php">Duty Restday</a></li>           
                </ul>
            </li>         
            <!-- <li>
              <a  href="inquiries.php" title="INQUIRIES is a venue where you can ask questions, give feedback and recommend quality improvements. FAQs is a list of questions and answers relating to a particular subject.">
                <strong>Inquiries & FAQs</strong>                
              </a>
            </li>   -->
             <li>
              <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="inquiries.php" title="INQUIRIES is a venue where you can ask questions, give feedback and recommend quality improvements. FAQs is a list of questions and answers relating to a particular subject.">
                <strong>Inquiries & FAQs</strong>                
              </a>
              <ul class="dropdown-menu">
                <li><a href="inquiries.php">View All</a></li>
                <li><a data-toggle="modal" href="#basica">Compensation and Benefits</a></li>
                <li><a data-toggle="modal" href="#basicb">Talent Management</a></li>
                <li><a data-toggle="modal" href="#basicc">Organizational Development</a></li>
                <li><a data-toggle="modal" href="#basicd">Employee Welfare</a></li>
                <li><a data-toggle="modal" href="#basice">Industrial Relation</a></li>
                <li><a data-toggle="modal" href="#basicf">Others</a></li>
              </ul>
              
            </li> 
		<li>
              <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#" title="">
                <strong>Forms</strong>                
              </a>
                
              <ul class="dropdown-menu">
		<li><a data-toggle="modal" target="_blank" href="../HRFORMS/HRD-SF-013 Leave Notification Form.pdf" href="#basicd">Leave Notification</a></li>
		<li><a data-toggle="modal" target="_blank" href="../HRFORMS/HRD-SF-015  Request for Overtime, Duty Rest Day (DRD).pdf" href="#basicd">Overtime</a></li>
<li><a data-toggle="modal" target="_blank" href="../HRFORMS/HRD-SF-060  On-call duty form.pdf" href="#basicd">On call duty</a></li>
		<li><a data-toggle="modal" target="_blank" href="../HRFORMS/HRD-SF-008  Change day off  work schedule  off set.pdf" href="#basicc">Change Schedule</a></li>
                <li><a data-toggle="modal" target="_blank" href="../HRFORMS/HRD-SF-022  Failure to punch-in out form.pdf" href="#basice">Failure to Login/Logout</a></li>                
<li><a data-toggle="modal" target="_blank" href="../HRFORMS/MEAL REQUEST.pdf" href="#basicd">Meal Request</a></li>
                <li><a data-toggle="modal" target="_blank" href="../HRFORMS/HRD-SF-056 Acknowledgment of Verbal Reprimand.pdf" href="#basicf">AVR</a></li>
                                
<li><a data-toggle="modal" target="_blank" href="../HRFORMS/Authority To Deduct New 2016.pdf">Authority to Deduct</a></li>
                <li><a data-toggle="modal" target="_blank" href="../HRFORMS/Cessation of Loan.pdf" href="#basicb">Cessation of Loan</a></li>
           
                <li><a data-toggle="modal" target="_blank" href="../HRFORMS/prescription.pdf" href="#basicd">Prescription</a></li>  
     <li><a data-toggle="modal" target="_blank" href="../HRFORMS/TRAINING REQUEST FORM.pdf" href="#basicd">Training Request</a></li>         
<li><a data-toggle="modal" target="_blank" href="../HRFORMS/MultiPurposeLoanApplicationForm_V07.2023.pdf" href="#basicd">Pagibig Multi-Purpose Loan</a></li>

<li><a data-toggle="modal" target="_blank" href="../HRFORMS/HDMF_MembersChangeofInformationForm.pdf" href="#basicd">HDMF Members Change Info</a></li>
<li><a data-toggle="modal" target="_blank" href="../HRFORMS/SSS_Maternity Notification.pdf" href="#basicd">SSS Maternity Notification</a></li>
<li><a data-toggle="modal" target="_blank" href="../HRFORMS/SSS_Sickness Notification.pdf" href="#basicd">SSS Sickness Notification</a></li>
<li><a data-toggle="modal" target="_blank" href="../HRFORMS/SSS_Change Request.pdf" href="#basicd">SSS Data Change Form (E-4)</a></li>
<li><a data-toggle="modal" target="_blank" href="../HRFORMS/SSS MY.SSS Member Form.pdf" href="#basicd">SSS MY.SSS Member Form</a></li>
<li><a data-toggle="modal" target="_blank" href="../HRFORMS/PHIC_Members Registration Form.pdf" href="#basicd">PHIC Membership</a></li>
<li><a data-toggle="modal" target="_blank" href="../HRFORMS/BIRForm2305_Update of ER and EE Info.pdf" href="#basicd">BIR Form 2305</a></li>

          
           

              </ul>
            </li>	
            <li style='display:none;'><a href="main_forum.php" title="iEXPRESS is a venue where you can share your thoughts and to stay in contact with your colleagues.  Users can post message, share articles, quotes and respond or link to the information posted by others."><strong>iExpress</strong></a></li>

            
<?php

if($_SESSION['kiosk_hris']){
	$xx='
<li>
              <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#" title="">
                <strong>Account</strong>                
              </a>
                
              <ul class="dropdown-menu">
		<li><a data-toggle="modal" href="#mydoc"><strong>MyDocNow</strong></a></li>
    <li><a href="emp_meals.php">Meals</a></li>
    <li><a href="editemp.php">Update 201 File</a></li>  
		<li><a href="changepassword.php"><strong>Change Password</strong></a></li>
		<li><a href="login.php?act=logout"><strong>Logout</strong></a></li>




          
           

              </ul>
            </li>	';
}
else{
	$xx='<li><a href="login.php"><strong>Login</strong></a></li>';
}
echo $xx;
?>

            
            <!-- BEGIN TOP SEARCH -->
           
            <!-- END TOP SEARCH -->
          </ul>
        </div>
        <!-- END NAVIGATION -->
      </div>
    </div>
    <!-- Header END -->

    <div class="main">
      <div class="container">

        <div class="modal fade" id="mydoc" tabindex="-1" role="mydoc" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                  <h4 class="modal-title">MyDocNow</h4>
                </div>
                <div class="modal-body">
                  <?php
                    $mnym='';
                    $mid='';
                    $mpword='';
                   $myq=mysql_fetch_array(mysql_query("select * from abc where a='".$_SESSION['eid']."'"));
                   //echo "select * from abc where a='".$_SESSION['eid']."'";
                    $mnym = $myq['b'];
                    $mid = $myq['a'];
                    $mpword = $myq['c'];
                    
                    echo '<p>Name: '.$mnym.'</p>';
                    echo '<p>ID: '.$mid.'</p>';
                    echo '<p>Password: '.$mpword.'</p>';
                  ?>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn blue">Save changes</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>

        <div class="modal fade" id="basica" tabindex="-1" role="basica" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="post" action="concernsubmit.php?act=a">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Compensation and Benefits</h4>
              </div>
              <div class="modal-body">
                 
                 <table width="100%">
                    <tr><td><h2>Enter your inquiry/inquiries here:</h2></td></tr>
                    <tr><td><textarea rows="5" cols="70" name="concern"></textarea></td></tr>
                    <input type="hidden" name="category" value="COMPENSATION AND BENEFITS">
                 </table>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <input class="btn blue" type="Submit" value="Submit">
              </div>
              </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="basicb" tabindex="-1" role="basicb" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="post" action="concernsubmit.php?act=b">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Talent Management</h4>
              </div>
              <div class="modal-body">
                 
                 <table width="100%">
                    <tr><td><h2>Enter your inquiry/inquiries here:</h2></td></tr>
                    <tr><td><textarea rows="5" cols="70" name="concern"></textarea></td></tr>
                    <input type="hidden" name="category" value="Talent Management">
                 </table>
               
              </div>
              <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <input class="btn blue" type="Submit" value="Submit">
              </div>
               </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="basicc" tabindex="-1" role="basicc" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="post" action="concernsubmit.php?act=c">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Organizational Development</h4>
              </div>
              <div class="modal-body">
                 
                 <table width="100%">
                    <tr><td><h2>Enter your inquiry/inquiries here:</h2></td></tr>
                    <tr><td><textarea rows="5" cols="70" name="concern"></textarea></td></tr>
                    <input type="hidden" name="category" value="Organizational Development">
                 </table>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <input class="btn blue" type="Submit" value="Submit">
              </div>
              </form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="basicd" tabindex="-1" role="basicd" aria-hidden="true">
          <div class="modal-dialog">
            <form method="post" action="concernsubmit.php?act=d">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Employee Welfare</h4>
              </div>
              <div class="modal-body">
                 
                 <table width="100%">
                    <tr><td><h2>Enter your inquiry/inquiries here:</h2></td></tr>
                    <tr><td><textarea rows="5" cols="70" name="concern"></textarea></td></tr>
                    <input type="hidden" name="category" value="Employee Welfare">
                 </table>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <input class="btn blue" type="Submit" value="Submit">
              </div>
            </div>
            </form>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="basice" tabindex="-1" role="basice" aria-hidden="true">
          <div class="modal-dialog">
            <form method="post" action="concernsubmit.php?act=e">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Industrial Relation</h4>
              </div>
              <div class="modal-body">
                 
                 <table width="100%">
                    <tr><td><h2>Enter your inquiry/inquiries here:</h2></td></tr>
                    <tr><td><textarea rows="5" cols="70" name="concern"></textarea></td></tr>
                     <input type="hidden" name="category" value="Industrial Relation">
                 </table>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <input class="btn blue" type="Submit" value="Submit">
              </div>
            </div>
            </form>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="basicf" tabindex="-1" role="basicf" aria-hidden="true">
          <div class="modal-dialog">
            <form method="post" action="concernsubmit.php?act=f">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Others</h4>
              </div>
              <div class="modal-body">
                
                 <table width="100%">
                    <tr><td><h2>Enter your inquiry/inquiries here:</h2></td></tr>
                    <tr><td><textarea rows="5" cols="70" name="concern"></textarea></td></tr>
                    <input type="hidden" name="category" value="Others">
                 </table>
                

              </div>
              <div class="modal-footer">
                <button type="button" class="btn default" data-dismiss="modal">Close</button>
                <input class="btn blue" type="Submit" value="Submit">
              </div>
            </div>
            </form>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
