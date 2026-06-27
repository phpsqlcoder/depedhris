<?php
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}

ob_start();
include("dbcon.php");
include("scripts/scripts.php");

$ctr1s++;
if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}

if($_GET['act']=='adnew'){
$target_file = 'kiosk/assets/slides/' . basename($_FILES["b"]["name"]);

  move_uploaded_file($_FILES["b"]["tmp_name"], $target_file);
  $u=mysql_query("insert into slides (`seq`, `fyl`, `txt1`, `txt2`, `txt3`, `txt4`)values('".$_POST['a']."','".basename($_FILES["b"]["name"])."','".$_POST['c']."','".$_POST['d']."','".$_POST['e']."','".$_POST['f']."')");
  header("location:kiosk.php");
}
if($_GET['acts']=='delete'){
	$del=mysql_query("delete from slides where ndex='".$_GET['id']."'");
	header("location:kiosk.php");
}

$dpqry=mysql_query("select * from slides order by seq");
while($d=mysql_fetch_object($dpqry)){
	$data.="<tr>
		<td>".$d->seq."</td>
		<td>".$d->fyl."</td>
		<td>".$d->txt1."</td>
		<td>".$d->txt2."</td>
		<td>".$d->txt3."</td>
		<td>".$d->txt4."</td>
		<td><a href='kiosk.php?acts=delete&id=".$d->ndex."'>Remove</a></td>
	</tr>";
	$chkbox.="<input type='checkBox' name='".$d->ndex."' ".$chkdefault.">".$d->name."<br>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
	
	<!-- Global styles START -->          
  <link href="kiosk/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="kiosk/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Global styles END --> 
   
  <!-- Page level plugin styles START -->
  <link href="kiosk/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet">
  <link href="kiosk/assets/global/plugins/carousel-owl-carousel/owl-carousel/owl.carousel.css" rel="stylesheet">
  <link href="kiosk/assets/global/plugins/slider-revolution-slider/rs-plugin/css/settings.css" rel="stylesheet">
  <!-- Page level plugin styles END -->

  <!-- Theme styles START -->
  <link href="kiosk/assets/global/css/components.css" rel="stylesheet">
  <link href="kiosk/assets/frontend/layout/css/style.css" rel="stylesheet">
  <link href="kiosk/assets/frontend/pages/css/style-revolution-slider.css" rel="stylesheet"><!-- metronic revo slider styles -->
  <link href="kiosk/assets/frontend/layout/css/style-responsive.css" rel="stylesheet">
  <link href="kiosk/assets/frontend/layout/css/themes/red.css" rel="stylesheet" id="style-color">
  <link href="kiosk/assets/frontend/layout/css/custom.css" rel="stylesheet">
</head>

<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
 <!-- BEGIN SLIDER -->
    <div class="page-slider margin-bottom-40" >
      <div class="fullwidthbanner-container revolution-slider">
        <div class="fullwidthabnner">
          <ul id="revolutionul"> 
		  
			            <li data-transition="fade" data-slotamount="8" data-masterspeed="700" data-delay="9400" data-thumb="http://hriskiosk/hris/kiosk/assets/slides/Parol Winners final.jpg">
              <!-- THE MAIN IMAGE IN THE FIRST SLIDE -->
              <img src="../../assets/frontend/pages/img/revolutionslider/bg1.jpg" alt="">
                            
              <div class="caption lft slide_title slide_item_left"
                data-x="30"
                data-y="105"
                data-speed="400"
                data-start="1500"
                data-easing="easeOutExpo">
                Text 1 Goes Here 
              </div>
              <div class="caption lft slide_subtitle slide_item_left"
                data-x="30"
                data-y="180"
                data-speed="400"
                data-start="2000"
                data-easing="easeOutExpo">
                Text 2 Goes Here
              </div>
              <div class="caption lft slide_desc slide_item_left"
                data-x="30"
                data-y="220"
                data-speed="400"
                data-start="2500"
                data-easing="easeOutExpo">
                Text 3 Goes Here
              </div>
              <a target="_blank" class="caption lft btn green slide_btn slide_item_left" href="http://192.168.7.21/hris/kiosk/assets/slides/"
                data-x="30"
                data-y="290"
                data-speed="400"
                data-start="3000"
                data-easing="easeOutExpo">
                Text 4 Goes Here 
              </a>                        
              <div class="caption lfb"
                data-x="640" 
                data-y="55" 
                data-speed="700" 
                data-start="1000" 
                data-easing="easeOutExpo">
                <img src="http://192.168.7.21/hris/kiosk/assets/slides/Parol Winners final.jpg" alt="Image 1">
              </div>
            </li>
			
						
            </ul>
            <div class="tp-bannertimer tp-bottom"></div>
        </div>
    </div>
</div>
    <!-- END SLIDER -->
<div id="rcont">
  <h2><strong style="color:blue;">Users</strong></h2><?php if($_SESSION['nym']=='jhang'){?> <a href="users_payroll.php">Payroll Users</a><?php } ?>
  <table width="100%">
  	<tr>
		<td>
			  <table align="center" width="100%">
			    <tr style="color:blue;font-weight:bold;">
				  <td>Sequence #</td>
			      <td>Image</td>
			      <td>Text 1</td>
				  <td>Text 2</td>
			      <td>Text 3</td>
				  <td>Text 4</td>
				  <td>Action</td>
			    </tr>
			    <tr><td colspan=7><hr></td></tr>
				  <?php echo $data;?>
			  </table>
	</td>
	</tr>
 </table>
		
			
   
    <form action="kiosk.php?act=adnew" method="post" enctype="multipart/form-data">
      <table width="100%"  style="color:maroon;" border="1">
	<tr>
	  <td><h1>Add Slide</h1></td>
	</tr>
	<tr>
	  <td>Sequence #:</td>
	  <td><input type="text" name="a"></td>
	</tr>
	<tr>
	  <td>Image:</td>
	  <td><input type="file" name="b" id="b"> Dimension (600x989)</td>
	</tr>
	<tr>
	  <td>Text 1:</td>
	  <td><input type="text" name="c" size="50"></td>
	</tr>
	<tr>
	  <td>Text 2:</td>
	  <td><input type="text" name="d" size="50"></td>
	</tr>
	<tr>
	  <td>Text 3:</td>
	  <td><input type="text" name="e" size="50"></td>
	</tr>
	<tr>
	  <td>Text 4:</td>
	  <td><input type="text" name="f" size="50" value="More info"></td>
	</tr>
	
	<tr><td colspan=2 align="center"><input type="submit" value="SAVE"></td></tr>
	<tr><td>&nbsp;</td></tr>
      </table>
    </form> 
 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
	 <script src="kiosk/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
    <script src="kiosk/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
    <script src="kiosk/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>      
    <script src="kiosk/assets/frontend/layout/scripts/back-to-top.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->

    <!-- BEGIN PAGE LEVEL JAVASCRIPTS (REQUIRED ONLY FOR CURRENT PAGE) -->
    <script src="kiosk/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script><!-- pop up -->
    <script src="kiosk/assets/global/plugins/carousel-owl-carousel/owl-carousel/owl.carousel.min.js" type="text/javascript"></script><!-- slider for products -->

    <!-- BEGIN RevolutionSlider -->
  
    <script src="kiosk/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.plugins.min.js" type="text/javascript"></script>
    <script src="kiosk/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js" type="text/javascript"></script> 
    <script src="kiosk/assets/frontend/pages/scripts/revo-slider-init.js" type="text/javascript"></script>
    <!-- END RevolutionSlider -->

    <script src="kiosk/assets/frontend/layout/scripts/layout.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            Layout.init();    
            Layout.initOWL();
            RevosliderInit.initRevoSlider();
         
        });
    </script>
  </div>
</body>
</html>


