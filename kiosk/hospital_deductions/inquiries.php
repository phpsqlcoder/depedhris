<?php 
ob_start();
include("dbcon.php");
$kiosk="kiosk";
//session_start();
?>
<!DOCTYPE html>
<html lang="en">
<!--<![endif]-->

<!-- Head BEGIN -->
<head>
  <meta charset="utf-8">
  <title>Kiosk - Inquiry portal</title>

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
  <link href="<?php echo $kiosk;?>/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php echo $kiosk;?>/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Global styles END --> 
   
  <!-- Page level plugin styles START -->
  <link href="<?php echo $kiosk;?>/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet">
  <!-- Page level plugin styles END -->

  <!-- Theme styles START -->
  <link href="<?php echo $kiosk;?>/assets/global/css/components.css" rel="stylesheet">
  <link href="<?php echo $kiosk;?>/assets/frontend/layout/css/style.css" rel="stylesheet">
  <link href="<?php echo $kiosk;?>/assets/frontend/layout/css/style-responsive.css" rel="stylesheet">
  <link href="<?php echo $kiosk;?>/assets/frontend/layout/css/themes/red.css" rel="stylesheet" id="style-color">
  <link href="<?php echo $kiosk;?>/assets/frontend/layout/css/custom.css" rel="stylesheet">
  <!-- Theme styles END -->
</head>
<!-- Head END -->

<!-- Body BEGIN -->
<body class="corporate">
    <!-- BEGIN STYLE CUSTOMIZER -->
    <div class="color-panel hidden-sm">
      <div class="color-mode-icons icon-color"></div>
      <div class="color-mode-icons icon-color-close"></div>
      <div class="color-mode">
        <p>THEME COLOR</p>
        <ul class="inline">
          <li class="color-red current color-default" data-style="red"></li>
          <li class="color-blue" data-style="blue"></li>
          <li class="color-green" data-style="green"></li>
          <li class="color-orange" data-style="orange"></li>
          <li class="color-gray" data-style="gray"></li>
          <li class="color-turquoise" data-style="turquoise"></li>
        </ul>
      </div>
    </div>
    <!-- END BEGIN STYLE CUSTOMIZER --> 
    
    <!-- BEGIN TOP BAR -->
    <div class="pre-header">
        <div class="container">
            <div class="row">
                <!-- BEGIN TOP BAR LEFT PART -->
                <div class="col-md-6 col-sm-6 additional-shop-info">
                    <ul class="list-unstyled list-inline">
                        <li><i class="fa fa-phone"></i><span><?php echo $_SESSION['fullName'];?></span></li>
                        <li><i class="fa fa-envelope-o"></i><span><?php echo $_SESSION['email'];?></span></li>
                    </ul>
                </div>
                <!-- END TOP BAR LEFT PART -->
                <!-- BEGIN TOP BAR MENU -->
                <div class="col-md-6 col-sm-6 additional-nav">
                    <ul class="list-unstyled list-inline pull-right">
                        <li><a href="page-login.html">HRIS</a></li>
                        <li><a href="login.php?act=logout">Logout</a></li>
                    </ul>
                </div>
                <!-- END TOP BAR MENU -->
            </div>
        </div>        
    </div>
    <!-- END TOP BAR -->
   

    <div class="main">
      <div class="container">
        <br>
        <div class="row margin-bottom-40">
          <!-- BEGIN CONTENT -->
          <div class="col-md-12 col-sm-12">
            <h1>KIOSK - INQUIRY PORTAL</h1>
            <div class="content-page">
              <div class="row">
                <div class="col-md-3 col-sm-3">
                  <ul class="tabbable faq-tabbable">
                    <li class="active"><a href="#tab_1" data-toggle="tab">Compensation and Benefits</a></li>
                    <li><a href="#tab_2" data-toggle="tab">Talent Management</a></li>
                    <li><a href="#tab_3" data-toggle="tab">Organizational Development</a></li>
                    <li><a href="#tab_4" data-toggle="tab">Employee Welfare</a></li>
                    <li><a href="#tab_5" data-toggle="tab">Industrial Relation</a></li>
                    <li><a href="#tab_6" data-toggle="tab">Others</a></li>
                  </ul>
                </div>
                <div class="col-md-9 col-sm-9">
                    <div class="tab-content" style="padding:0; background: #fff;">
                      <!-- START TAB 1 -->
                      <div class="tab-pane active" id="tab_1">
                         <div class="panel-group" id="accordion1">
						    <?php
							$q=mysql_query("select * from inquiries where category='Compensation and Benefits' ORDER BY sent DESC");
							while($r=mysql_fetch_object($q)){
							?>   
                            <div class="panel panel-success">
                               <div class="panel-heading">
                                  <h4 class="panel-title">
                                     <a href="#accordion<?php echo $r->ndex;?>" data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle collapsed">
                                     <?php $s="Anonymous sender"; if($r->sender){$s=$r->sender;}?>
									 <?php echo $s."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$r->sent."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$r->sender_ip;?>
                                     </a>
                                  </h4>
                               </div>
                               <div class="panel-collapse collapse" id="accordion<?php echo $r->ndex;?>">
                                  <div class="panel-body">
                                     <?php echo $r->msg;?>
                                  </div>
                               </div>
                            </div>
							<?php } ?>                          
                         </div>
                      </div>
                      <!-- END TAB 1 -->
                      <!-- START TAB 2 -->
                      <div class="tab-pane" id="tab_2">
                         <div class="panel-group" id="accordion2">
                            <?php
							$q=mysql_query("select * from inquiries where category='Talent Management' ORDER BY sent DESC");
							while($r=mysql_fetch_object($q)){
							?>   
                            <div class="panel panel-success">
                               <div class="panel-heading">
                                  <h4 class="panel-title">
                                     <a href="#accordion<?php echo $r->ndex;?>" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle collapsed">
                                     <?php $s="Anonymous sender"; if($r->sender){$s=$r->sender;}?>
									 <?php echo $s."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$r->sent."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$r->sender_ip;?>
                                     </a>
                                  </h4>
                               </div>
                               <div class="panel-collapse collapse" id="accordion<?php echo $r->ndex;?>">
                                  <div class="panel-body">
                                     <?php echo $r->msg;?>
                                  </div>
                               </div>
                            </div>
							<?php } ?>    
                         </div>
                      </div>
                      <!-- END TAB 3 -->
                      <!-- START TAB 3 -->
                      <div class="tab-pane" id="tab_3">
                         <div class="panel-group" id="accordion3">
                             <?php
							$q=mysql_query("select * from inquiries where category='Organizational Development' ORDER BY sent DESC");
							while($r=mysql_fetch_object($q)){
							?>   
                            <div class="panel panel-success">
                               <div class="panel-heading">
                                  <h4 class="panel-title">
                                     <a href="#accordion<?php echo $r->ndex;?>" data-parent="#accordion3" data-toggle="collapse" class="accordion-toggle collapsed">
                                     <?php $s="Anonymous sender"; if($r->sender){$s=$r->sender;}?>
									 <?php echo $s."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$r->sent."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$r->sender_ip;?>
                                     </a>
                                  </h4>
                               </div>
                               <div class="panel-collapse collapse" id="accordion<?php echo $r->ndex;?>">
                                  <div class="panel-body">
                                     <?php echo $r->msg;?>
                                  </div>
                               </div>
                            </div>
							<?php } ?>    
                         </div>
                      </div>					  
                      <!-- END TAB 3 -->
					  <!-- START TAB 3 -->
                      <div class="tab-pane" id="tab_4">
                         <div class="panel-group" id="accordion4">
                             <?php
							$q=mysql_query("select * from inquiries where category='Employee Welfare' ORDER BY sent DESC");
							while($r=mysql_fetch_object($q)){
							?>   
                            <div class="panel panel-success">
                               <div class="panel-heading">
                                  <h4 class="panel-title">
                                     <a href="#accordion<?php echo $r->ndex;?>" data-parent="#accordion4" data-toggle="collapse" class="accordion-toggle collapsed">
                                     <?php $s="Anonymous sender"; if($r->sender){$s=$r->sender;}?>
									 <?php echo $s."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$r->sent."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$r->sender_ip;?>
                                     </a>
                                  </h4>
                               </div>
                               <div class="panel-collapse collapse" id="accordion<?php echo $r->ndex;?>">
                                  <div class="panel-body">
                                     <?php echo $r->msg;?>
                                  </div>
                               </div>
                            </div>
							<?php } ?>    
                         </div>
                      </div>					  
                      <!-- END TAB 3 -->
					  <!-- START TAB 3 -->
                      <div class="tab-pane" id="tab_5">
                         <div class="panel-group" id="accordion5">
                             <?php
							$q=mysql_query("select * from inquiries where category='Industrial Relation' ORDER BY sent DESC");
							while($r=mysql_fetch_object($q)){
							?>   
                            <div class="panel panel-success">
                               <div class="panel-heading">
                                  <h4 class="panel-title">
                                     <a href="#accordion<?php echo $r->ndex;?>" data-parent="#accordion5" data-toggle="collapse" class="accordion-toggle collapsed">
                                     <?php $s="Anonymous sender"; if($r->sender){$s=$r->sender;}?>
									 <?php echo $s."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$r->sent."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$r->sender_ip;?>
                                     </a>
                                  </h4>
                               </div>
                               <div class="panel-collapse collapse" id="accordion<?php echo $r->ndex;?>">
                                  <div class="panel-body">
                                     <?php echo $r->msg;?>
                                  </div>
                               </div>
                            </div>
							<?php } ?>    
                         </div>
                      </div>					  
                      <!-- END TAB 3 -->
					  <!-- START TAB 3 -->
                      <div class="tab-pane" id="tab_6">
                         <div class="panel-group" id="accordion6">
                             <?php
							$q=mysql_query("select * from inquiries where category='Others' ORDER BY sent DESC");
							while($r=mysql_fetch_object($q)){
							?>   
                            <div class="panel panel-success">
                               <div class="panel-heading">
                                  <h4 class="panel-title">
                                     <a href="#accordion<?php echo $r->ndex;?>" data-parent="#accordion6" data-toggle="collapse" class="accordion-toggle collapsed">
                                     <?php $s="Anonymous sender"; if($r->sender){$s=$r->sender;}?>
									 <?php echo $s."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$r->sent."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$r->sender_ip;?>
                                     </a>
                                  </h4>
                               </div>
                               <div class="panel-collapse collapse" id="accordion<?php echo $r->ndex;?>">
                                  <div class="panel-body">
                                     <?php echo $r->msg;?>
                                  </div>
                               </div>
                            </div>
							<?php } ?>    
                         </div>
                      </div>					  
                      <!-- END TAB 3 -->
                    </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
      </div>
    </div>

    <!-- BEGIN PRE-FOOTER -->
    <div class="pre-footer">
      <div class="container">
        <div class="row">
          <!-- BEGIN BOTTOM ABOUT BLOCK -->
          <div class="col-md-4 col-sm-6 pre-footer-col">
            <h2>About us</h2>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam sit nonummy nibh euismod tincidunt ut laoreet dolore magna aliquarm erat sit volutpat.</p>

            <div class="photo-stream">
              <h2>Photos Stream</h2>
              <ul class="list-unstyled">
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/people/img5-small.jpg"></a></li>
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/works/img1.jpg"></a></li>
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/people/img4-large.jpg"></a></li>
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/works/img6.jpg"></a></li>
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/works/img3.jpg"></a></li>
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/people/img2-large.jpg"></a></li>
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/works/img2.jpg"></a></li>
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/works/img5.jpg"></a></li>
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/people/img5-small.jpg"></a></li>
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/works/img1.jpg"></a></li>
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/people/img4-large.jpg"></a></li>
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/works/img6.jpg"></a></li>
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/works/img3.jpg"></a></li>
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/people/img2-large.jpg"></a></li>
                <li><a href="#"><img alt="" src="<?php echo $kiosk;?>/assets/frontend/pages/img/works/img2.jpg"></a></li>
              </ul>                    
            </div>
          </div>
          <!-- END BOTTOM ABOUT BLOCK -->

          <!-- BEGIN BOTTOM CONTACTS -->
          <div class="col-md-4 col-sm-6 pre-footer-col">
            <h2>Our Contacts</h2>
            <address class="margin-bottom-40">
              35, Lorem Lis Street, Park Ave<br>
              California, US<br>
              Phone: 300 323 3456<br>
              Fax: 300 323 1456<br>
              Email: <a href="mailto:info@metronic.com">info@metronic.com</a><br>
              Skype: <a href="skype:metronic">metronic</a>
            </address>

            <div class="pre-footer-subscribe-box pre-footer-subscribe-box-vertical">
              <h2>Newsletter</h2>
              <p>Subscribe to our newsletter and stay up to date with the latest news and deals!</p>
              <form action="#">
                <div class="input-group">
                  <input type="text" placeholder="youremail@mail.com" class="form-control">
                  <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit">Subscribe</button>
                  </span>
                </div>
              </form>
            </div>
          </div>
          <!-- END BOTTOM CONTACTS -->

          <!-- BEGIN TWITTER BLOCK --> 
          <div class="col-md-4 col-sm-6 pre-footer-col">
            <h2 class="margin-bottom-0">Latest Tweets</h2>
            <a class="twitter-timeline" href="https://twitter.com/twitterapi" data-tweet-limit="2" data-theme="dark" data-link-color="#57C8EB" data-widget-id="455411516829736961" data-chrome="noheader nofooter noscrollbar noborders transparent">Loading tweets by @keenthemes...</a>
          </div>
          <!-- END TWITTER BLOCK -->
        </div>
      </div>
    </div>
    <!-- END PRE-FOOTER -->

    <!-- BEGIN FOOTER -->
    <div class="footer">
      <div class="container">
        <div class="row">
          <!-- BEGIN COPYRIGHT -->
          <div class="col-md-6 col-sm-6 padding-top-10">
            2014 © Metronic Shop UI. ALL Rights Reserved. <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
          </div>
          <!-- END COPYRIGHT -->
          <!-- BEGIN PAYMENTS -->
          <div class="col-md-6 col-sm-6">
            <ul class="social-footer list-unstyled list-inline pull-right">
              <li><a href="#"><i class="fa fa-facebook"></i></a></li>
              <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
              <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
              <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
              <li><a href="#"><i class="fa fa-twitter"></i></a></li>
              <li><a href="#"><i class="fa fa-skype"></i></a></li>
              <li><a href="#"><i class="fa fa-github"></i></a></li>
              <li><a href="#"><i class="fa fa-youtube"></i></a></li>
              <li><a href="#"><i class="fa fa-dropbox"></i></a></li>
            </ul>  
          </div>
          <!-- END PAYMENTS -->
        </div>
      </div>
    </div>
    <!-- END FOOTER -->

    <!-- Load javascripts at bottom, this will reduce page load time -->
    <!-- BEGIN CORE PLUGINS (REQUIRED FOR ALL PAGES) -->
    <!--[if lt IE 9]>
    <script src="<?php echo $kiosk;?>/assets/global/plugins/respond.min.js"></script>
    <![endif]--> 
    <script src="<?php echo $kiosk;?>/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="<?php echo $kiosk;?>/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
    <script src="<?php echo $kiosk;?>/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>      
    <script src="<?php echo $kiosk;?>/assets/frontend/layout/scripts/back-to-top.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->

    <!-- BEGIN PAGE LEVEL JAVASCRIPTS (REQUIRED ONLY FOR CURRENT PAGE) -->
    <script src="<?php echo $kiosk;?>/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script><!-- pop up -->

    <script src="<?php echo $kiosk;?>/assets/frontend/layout/scripts/layout.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            Layout.init();    
            Layout.initTwitter();
        });
    </script>
    <!-- END PAGE LEVEL JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>