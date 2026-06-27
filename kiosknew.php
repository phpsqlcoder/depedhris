<?php

ob_start();
include("dbcon.php");
include("scripts/scripts.php");

$ctr1s++;
if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}

if($_GET['act']=='adnew'){
  $target_file = 'excel/' . basename($_FILES["b"]["name"]);
  move_uploaded_file($_FILES["b"]["tmp_name"], $target_file);
$hr=0;
if($_POST['d']=='on'){
$hr=1;
}
  $u=mysql_query("insert into kiosk_form (`name`, `document`,`hr`)values('".$_POST['a']."','".basename($_FILES["b"]["name"])."','".$hr."')");
  header("location:kiosknew.php");
}
if($_GET['acts']=='delete'){
	$del=mysql_query("delete from kiosk_form where ndex='".$_GET['id']."'");
	header("location:kiosknew.php");
}

$dpqry=mysql_query("select * from kiosk_form");
while($d=mysql_fetch_object($dpqry)){
	$data.="<tr>
		<td>".$d->name."</td>
		<td>".$d->document."</td>		
		
<td>".$d->hr."</td>
<td><a href='kiosknew.php?acts=delete&id=".$d->ndex."'>Remove</a></td>
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
				  <td>Description</td>
			      <td>Document</td>
			    <td>For HR</td>
				  <td>Action</td>
			    </tr>
			    <tr><td colspan=7><hr></td></tr>
				  <?php echo $data;?>
			  </table>
	</td>
	</tr>
 </table>
		
			
   
    <form action="kiosknew.php?act=adnew" method="post" enctype="multipart/form-data">
      <table width="60%"  style="color:maroon;" border="1">
	<tr>
	  <td><h1>Add Form</h1></td>
	</tr>
	<tr>
	  <td>Description:</td>
	  <td><input type="text" name="a"></td>
	</tr>
	<tr>
	  <td>Document:</td>
	  <td><input type="file" name="b" id="b"></td>
	</tr>	
<tr>
	  <td>HR Only:</td>
	  <td><input type="checkbox" name="d" id="d"></td>
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


