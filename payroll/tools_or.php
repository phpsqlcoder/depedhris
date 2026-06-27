<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");

$qry="SELECT * FROM payroll_or ORDER BY cutoff";

if($_GET['act']=='editdept'){
	$qr=mysql_fetch_object(mysql_query("SELECT d.*,dd.payrollDate as payroll,dd.ndex as pid FROM payroll_or d left join cutoffDates dd on dd.ndex=d.cutoff where d.ndex=".$_GET['id'].""));
}
elseif($_GET['act']=='adnew'){
	$qr=mysql_query("insert into payroll_or (`cutoff`,`sss_cont`, `sss_salary`, `w_tax`, `philhealth`, `hdmf_cont`, `hdmf_calamity`, `hdmf_salary`, `hdmf_housing`
	,`d_sss_cont`, `d_sss_salary`, `d_w_tax`, `d_philhealth`, `d_hdmf_cont`, `d_hdmf_calamity`, `d_hdmf_salary`, `d_hdmf_housing`)
	values('".$_POST['cutoff']."','".$_POST['sss_cont']."','".$_POST['sss_salary']."','".$_POST['w_tax']."','".$_POST['philhealth']."','".$_POST['hdmf_cont']."','".$_POST['hdmf_calamity']."','".$_POST['hdmf_salary']."','".$_POST['hdmf_housing']."'
	,'".$_POST['dsss_cont']."','".$_POST['dsss_salary']."','".$_POST['dw_tax']."','".$_POST['dphilhealth']."','".$_POST['dhdmf_cont']."','".$_POST['dhdmf_calamity']."','".$_POST['dhdmf_salary']."','".$_POST['dhdmf_housing']."')");
	//echo 
	//header("Location:tools_or.php");
	//echo $_POST['division'];
}
elseif($_GET['act']=='saveedit'){
	$qrys=mysql_query("update payroll_or set 
	cutoff='".$_POST['cutoff']."',
	sss_cont='".$_POST['sss_cont']."',
	sss_salary='".$_POST['sss_salary']."',
	w_tax='".$_POST['w_tax']."',
	philhealth='".$_POST['philhealth']."',
	hdmf_cont='".$_POST['hdmf_cont']."',
	hdmf_calamity='".$_POST['hdmf_calamity']."',
	hdmf_salary='".$_POST['hdmf_salary']."',
	hdmf_housing='".$_POST['hdmf_housing']."',
	
	d_sss_cont='".$_POST['dsss_cont']."',
	d_sss_salary='".$_POST['dsss_salary']."',
	d_w_tax='".$_POST['dw_tax']."',
	d_philhealth='".$_POST['dphilhealth']."',
	d_hdmf_cont='".$_POST['dhdmf_cont']."',
	d_hdmf_calamity='".$_POST['dhdmf_calamity']."',
	d_hdmf_salary='".$_POST['dhdmf_salary']."',
	d_hdmf_housing='".$_POST['dhdmf_housing']."' 
	WHERE ndex='".$_POST['pid']."'");
	header("Location:tools_or.php");
}
$cat=mysql_query($qry);
while($c=mysql_fetch_object($cat)){
	$div=mysql_fetch_object(mysql_query("select * from cutoffdates where ndex=".$c->cutoff.""));
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#fdecf2';}
	$data.="<tr style='font-size:12px;background-color:".$bgclr1s.";color:black;'>
				<td>".$div->payrollDate."</td>
				<td>".$c->d_sss_cont."<br><strong>".$c->sss_cont."</strong></td>
				<td>".$c->d_sss_salary."<br><strong>".$c->sss_salary."</strong></td>
				<td>".$c->d_w_tax."<br><strong>".$c->w_tax."</strong></td>
				<td>".$c->d_philhealth."<br><strong>".$c->philhealth."</strong></td>
				<td>".$c->d_hdmf_cont."<br><strong>".$c->hdmf_cont."</strong></td>
				<td>".$c->d_hdmf_calamity."<br><strong>".$c->hdmf_calamity."</strong></td>
				<td>".$c->d_hdmf_salary."<br><strong>".$c->hdmf_salary."</strong></td>
				<td>".$c->d_hdmf_housing."<br><strong>".$c->hdmf_housing."</strong></td>
				<td>
					<a href='#' onclick=\"window.location.href='tools_or.php?act=editdept&id=".$c->ndex."';\"><img src='../images/edit.png' title='Edit dept' height='20px;' width='20px;'></a>
					<img src='../images/delete.png' title='Deactivate dept' height='20px;' width='20px;'>
		</td>
	</tr>";
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="../css/styles.css" rel="stylesheet" type="text/css" />
    <link href="../css/facebox.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">

<div id="rcont">
  <h2><strong>Payroll - Official Receipt</strong></h2>

<br>
<?php if($_GET['act']=='editdept'){?>
<form name="frmedit" method="post" action="tools_or.php?act=saveedit">
<table>
	<tr><td>&nbsp;</td></tr>
	<input type="Hidden" value="<?php echo $_GET['id'];?>" name="pid">
	<tr><td><h1>Add new</h1></td></tr>
	<tr>
		<td>Payroll:</td>
		<td><select name="cutoff"><?php echo $optioncutoff;?><option selected="selected" value="<?php echo $qr->pid;?>"><?php echo $qr->payroll;?></td>
		<td>OR Date</td>
	</tr>
	<tr>
		<td>SSS Contribution:</td>
		<td><input type="Text" name="sss_cont" size="30" value="<?php echo $qr->sss_cont;?>"></td>
		<td><input type="Text" name="dsss_cont" size="30" value="<?php echo $qr->d_sss_cont;?>"></td>
	</tr>
	<tr>
		<td>SSS Salary:</td>
		<td><input type="Text" name="sss_salary" size=30 value="<?php echo $qr->sss_salary;?>"></td>
		<td><input type="Text" name="dsss_salary" size=30 value="<?php echo $qr->d_sss_salary;?>"></td>
	</tr>
	<tr>
		<td>Withholding Tax:</td>
		<td><input type="Text" name="w_tax" size=30 value="<?php echo $qr->w_tax;?>"></td>
		<td><input type="Text" name="dw_tax" size=30 value="<?php echo $qr->d_w_tax;?>"></td>
	</tr>
	<tr>
		<td>Philhealth:</td>
		<td><input type="Text" name="philhealth" size=30 value="<?php echo $qr->philhealth;?>"></td>
		<td><input type="Text" name="dphilhealth" size=30 value="<?php echo $qr->d_philhealth;?>"></td>
	</tr>
	<tr>
		<td>HDMF Contribution:</td>
		<td><input type="Text" name="hdmf_cont" size=30 value="<?php echo $qr->hdmf_cont;?>"></td>
		<td><input type="Text" name="dhdmf_cont" size=30 value="<?php echo $qr->d_hdmf_cont;?>"></td>
	</tr>
	<tr>
		<td>HDMF Calamity:</td>
		<td><input type="Text" name="hdmf_calamity" size=30 value="<?php echo $qr->hdmf_calamity;?>"></td>
		<td><input type="Text" name="dhdmf_calamity" size=30 value="<?php echo $qr->d_hdmf_calamity;?>"></td>
	</tr>
	<tr>
		<td>HDMF Salary:</td>
		<td><input type="Text" name="hdmf_salary" size=30 value="<?php echo $qr->hdmf_salary;?>"></td>
		<td><input type="Text" name="dhdmf_salary" size=30 value="<?php echo $qr->d_hdmf_salary;?>"></td>
	</tr>
	<tr>
		<td>HDMF Housing:</td>
		<td><input type="Text" name="hdmf_housing" size=30 value="<?php echo $qr->hdmf_housing;?>"></td>
		<td><input type="Text" name="dhdmf_housing" size=30 value="<?php echo $qr->d_hdmf_housing;?>"></td>
	</tr>	
	<tr><td colspan="2" align="center"><input type="Submit" value="SAVE"></td></tr>
	<tr><td>&nbsp;</td></tr>
</table>
</form>
<?php }else{?>
<form name="frmadds" method="post" action="tools_or.php?act=adnew">
<table>
	<tr><td>&nbsp;</td></tr>
	
	<tr><td><h1>Add new</h1></td></tr>
	<tr>
		<td>Payroll:</td>
		<td><select name="cutoff"><?php echo $optioncutoff;?></td>
		<td>OR Date</td>
	</tr>
	<tr>
		<td>SSS Contribution:</td>
		<td><input type="Text" name="sss_cont" size=30></td>
		<td><input type="Text" name="dsss_cont" size=30></td>
	</tr>
	<tr>
		<td>SSS Salary:</td>
		<td><input type="Text" name="sss_salary" size=30></td>
		<td><input type="Text" name="dsss_salary" size=30></td>
	</tr>
	<tr>
		<td>Withholding Tax:</td>
		<td><input type="Text" name="w_tax" size=30></td>
		<td><input type="Text" name="dw_tax" size=30></td>
	</tr>
	<tr>
		<td>Philhealth:</td>
		<td><input type="Text" name="philhealth" size=30></td>
		<td><input type="Text" name="dphilhealth" size=30></td>
	</tr>
	<tr>
		<td>HDMF Contribution:</td>
		<td><input type="Text" name="hdmf_cont" size=30></td>
		<td><input type="Text" name="dhdmf_cont" size=30></td>
	</tr>
	<tr>
		<td>HDMF Calamity:</td>
		<td><input type="Text" name="hdmf_calamity" size=30></td>
		<td><input type="Text" name="dhdmf_calamity" size=30></td>
	</tr>
	<tr>
		<td>HDMF Salary:</td>
		<td><input type="Text" name="hdmf_salary" size=30></td>
		<td><input type="Text" name="dhdmf_salary" size=30></td>
	</tr>
	<tr>
		<td>HDMF Housing:</td>
		<td><input type="Text" name="hdmf_housing" size=30></td>
		<td><input type="Text" name="dhdmf_housing" size=30></td>
	</tr>	
	<tr><td colspan="2" align="center"><input type="Submit" value="SAVE"></td></tr>
	<tr><td>&nbsp;</td></tr>
</table>
</form>
<?php }?>
 <table width="100%">
 	<tr style="font-weight:bold;color:blue;" align="center">
		<td>Payroll</td>
		<td>SSS<br>Contribution</td>
		<td>SSS<br>Salary</td>
		<td>Withholding<br>Tax</td>
		<td>Philhealth</td>
		<td>HDMF<br>Contribution</td>
		<td>HDMF<br>Calamity</td>
		<td>HDMF<br>Salary</td>
		<td>HDMF<br>Housing</td>
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


