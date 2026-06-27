<?php 
ob_start();
session_start();
if(!$_SESSION['ndex']){
  header("location:login.php");
}
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
                    <h1 class="margin-top-20" style="color:black;font-weight:bold;font-size:40px;"><br>Welcome to E-VOICE</h1>
                </div>
                <!-- END TOP BAR MENU -->
            </div>
        </div>        
    </div>
    <!-- END TOP BAR -->
    <!-- BEGIN HEADER -->
    <div class="header" style="background-color:#eaf2dd;">
      <div class="container">
        
        <!-- BEGIN NAVIGATION -->
        <div class="header-navigation font-transform-inherit" style="width:100%">
          <ul class=" col-md-12 col-sm-12">
            <li style="background-color:#9bbb58;margin-right:10px;width:17%;text-align:center;"><a href="index.php"><strong>Home</strong></a></li>
            <li style="background-color:#9bbb58;margin-right:10px;width:20%;text-align:center;"><a href="menu.php"><strong>Payroll</strong></a></li>           
            <li class="dropdown" style="background-color:#9bbb58;margin-right:10px;width:20%;text-align:center;">
              <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
                <strong>Queries and Complaints</strong>                
              </a>
                
              <ul class="dropdown-menu">
                <li><a href="#">Compensation and Benefits</a></li>
                <li><a href="#">Recruitment</a></li>
                <li><a href="#">Training and Development</a></li>
                <li><a href="#">Employee Welfare</a></li>
                <li><a href="#">Industrial Relation</a></li>
                <li><a href="#">Others</a></li>
              </ul>
            </li>
            <li style="background-color:#9bbb58;margin-right:10px;width:19%;text-align:center;"><a href="main_forum.php"><strong>iExpress</strong></a></li>
            <li style="background-color:#9bbb58;margin-right:10px;width:19%;text-align:center;"><a href="login.php?act=logout"><strong>Logout</strong></a></li>
            
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