<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");

$qry="SELECT * FROM `leave`";
if($_GET['act']=='editdept'){
	$qr=mysql_fetch_object(mysql_query("SELECT * FROM `leave` where ndex=".$_GET['id'].""));
}
elseif($_GET['act']=='adnew'){
	if($_POST['withpay']=='on'){$wp=1;}else{$wp=0;}
	$qr=mysql_query("insert into `leave` (name,isWithPay,code)values('".$_POST['description']."',".$wp.",'".$_POST['code']."')");
	header("Location:leave.php");
}
elseif($_GET['act']=='saveedit'){
	if($_POST['withpay']=='on'){$wp=1;}else{$wp=0;}
	$qrys=mysql_query("update `leave` set name='".mysql_real_escape_string($_POST['description'])."',code='".$_POST['code']."',isWithPay=".$wp." WHERE ndex='".$_POST['ndex']."'");
	header("Location:leave.php");
}
//echo $qry;
$cat=mysql_query($qry);
while($c=mysql_fetch_object($cat)){
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#fdecf2';}
	if($c->isWithPay==1){$ref='Yes';}else{$ref='No';}
	$data.="<tr style='font-size:12px;background-color:".$bgclr1s.";color:black;'>
				<td>".$c->code."</td>
				<td>".$c->name."</td>
				<td>".$ref."</td>
				<td>
					<a href='#' onclick=\"window.location.href='leave.php?act=editdept&id=".$c->ndex."';\"><img src='images/edit.png' title='Edit supplier' height='20px;' width='20px;'></a>
					<img src='images/delete.png' title='Deactivate division' height='20px;' width='20px;'>
		</td>
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
</head>

<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">

<div id="rcont">
  <h2><strong>Leave Management</strong></h2>
<?php if($_GET['act']=='editdept'){?>
<form name="frmedit" method="post" action="leave.php?act=saveedit">
<input type="Hidden" name="ndex" value="<?php echo $_GET['id'];?>">
<table>
	<tr>
		<td>Code:</td>
		<td><input type="Text" name="code" value="<?php echo $qr->code;?>"></td>
	</tr>
	<tr>
		<td>Description:</td>
		<td><input type="Text" name="description" value="<?php echo $qr->name;?>"></td>
	</tr>
	<tr>
		<td>With Pay:</td>
		<td><input type="checkbox" name="withpay" <?php if($qr->isWithPay==1){echo 'checked="checked"';}?>></td>
		
	</tr>
	<tr><td colspan="2" align="center"><input type="Submit" value="UPDATE"></td></tr>
</table>
</form>
<?php }else{?>
<form name="frmadds" method="post" action="leave.php?act=adnew">
<table>
	<tr><td>&nbsp;</td></tr>
	
	<tr><td><h1>Add new leave</h1></td></tr>
	<tr>
		<td>Code:</td>
		<td><input type="Text" name="code" size=60></td>
	</tr>
	<tr>
		<td>Description:</td>
		<td><input type="Text" name="description" size=60></td>
	</tr>
	<tr>
		<td>With Pay:</td>
		<td><input type="checkbox" name="withpay"></td>
		
	</tr>
	<tr><td colspan="2" align="center"><input type="Submit" value="SAVE"></td></tr>
	<tr><td>&nbsp;</td></tr>
</table>
</form>
<?php }?>
 <table width="100%">
 	<tr style="font-weight:bold;color:blue;">
		<td>Code</td>
		<td>Description</td>
		<td>With Pay</td>
		<td>Action</td>
	</tr>
	<tr><td colspan="10"><hr></td></tr>
	<?php echo $data;?>
 </table>
		
        <div class="container_12"><!--  PLACEHOLDER FOR FLOT - REMOVE IF NOT REQUIRED --></div>
        
        <div class="clearfix">&nbsp;</div>

        <!-- NOTIFICATION - USES CLASS OF "WARNING" and the CANHIDE ENABLES CICK TO FADE AWAY-->
        <div class="container_12">
           
<!--START NOTIFICATIONS  --><!-- INFORMATION - USES CLASS OF "IN2FORMATION" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- WARNING - USES CLASS OF "WARNING" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- SUCCESS - USES CLASS OF "SUCCESS" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- FAILURE - USES CLASS OF "FAILURE" and the CANHIDE ENABLES CICK TO FADE AWAY--></div>   
  	<!--END NOTIFICATIONS  -->
        
        
	<div class="clearfix">&nbsp;</div>
    
    
    
    
    </div>

<!-- START TABULAR DATA EXAMPLE -->
  <div class="container_12">
  
	<h2>&nbsp;</h2>

    <!-- END TABULAR DATA EXAMPLE -->

    <div class="clearfix">&nbsp;</div>
           
           
              
          
</div>

<div class="clearfix">&nbsp;</div>
<div class="container_12">
     


   <?php include "footer.php";?>    
  </div><!-- end content wrap -->


</body>
</html>


