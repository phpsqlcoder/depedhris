<?php

ob_start();
include("dbcon.php");
include("scripts/scripts.php");

$ctr1s++;
if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}

if($_GET['act']=='adnew'){
$nn="0";
  if($_POST['cb']=='on'){
  $nn="1";
}
  $target_file = 'excel/' . basename($_FILES["b"]["name"]);
  move_uploaded_file($_FILES["b"]["tmp_name"], $target_file);
  $u=mysql_query("insert into kiosk_slide (`seq`, `image`,registration,descriptioned)values('".$_POST['a']."','".basename($_FILES["b"]["name"])."','".$nn."','".$_POST['description']."')");
  header("location:kiosknewslide.php");
}
if($_GET['acts']=='delete'){
  $del=mysql_query("update kiosk_slide set active=0 where id='".$_GET['id']."'");
  header("location:kiosknewslide.php");
}

if($_GET['acts']=='activate'){
  $del=mysql_query("update kiosk_slide set active=1 where id='".$_GET['id']."'");
  header("location:kiosknewslide.php");
}

$dpqry=mysql_query("select * from kiosk_slide ORDER by id desc limit 100");
while($d=mysql_fetch_object($dpqry)){
if($d->active==0){
  $xx = "<a href='kiosknewslide.php?acts=activate&id=".$d->id."'>Activate</a>";
}
else{
  $xx = "<a href='kiosknewslide.php?acts=delete&id=".$d->id."'>Deactivate</a>";
}
  $data.="<tr>
    <td>".$d->seq."</td>
<td>".$d->Descriptioned."</td>
    <td><a href='excel/".$d->image."' target='_blank'><img src='excel/".$d->image."' height='50'></a></td>    
    <td>".$xx."</td>
<td><a href='#' onclick=\"window.open('kiosknewslide_p.php?id=".$d->id."','displayWindow','toolbar=no,scrollbars=yes,width=1110,height=500')\";>Participants</a></td>
  </tr>";

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
 
<div id="rcont">
  
  <table width="100%">
    <tr>
    <td>
        <table align="center" width="100%">
          <tr style="color:blue;font-weight:bold;">
          <td>Sequence</td>
<td>Description</td>
            <td>Image</td>          
          <td>Action</td>
          </tr>
          <tr><td colspan=7><hr></td></tr>
          <?php echo $data;?>
        </table>
  </td>
  </tr>
 </table>
    
      
   
    <form action="kiosknewslide.php?act=adnew" method="post" enctype="multipart/form-data">
      <table width="60%"  style="color:maroon;" border="1">
  <tr>
    <td><h1>Add Slide</h1></td>
  </tr>
  <tr>
    <td>Seq #:</td>
    <td><input type="text" name="a"></td>
  </tr>
<tr>
    <td>With Registration:</td>
    <td><input type="checkbox" name="cb" id="cb"></td>
  </tr>
<tr>
    <td>Description:</td>
    <td><input type="text" name="description" id="description"></td>
  </tr>
  <tr>
    <td>Image:</td>
    <td><input type="file" name="b" id="b"></td>
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


