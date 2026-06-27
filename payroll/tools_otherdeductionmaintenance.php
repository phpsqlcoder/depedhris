<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");

$qry="SELECT * FROM payroll_other_deduction ORDER BY name";

if($_GET['act']=='editdept'){
	$qr=mysql_query("update payroll_other_deduction set isActive=0 where ndex=".$_GET['id']."");
}
elseif($_GET['act']=='adnew'){
	$qr=mysql_query("insert into payroll_other_deduction (name,isActive,addedBy,addedDate)values('".$_POST['description']."','1','".$_SESSION['ndex']."','".date('Y-m-d')."')");

	header("Location:tools_otherdeductionmaintenance.php");
	//echo $_POST['division'];
}
elseif($_GET['act']=='saveedit'){
	$qrys=mysql_query("update dept set name='".mysql_real_escape_string($_POST['description'])."',head='".mysql_real_escape_string($_POST['head'])."',divisionId='".$_POST['division']."',sapId='".$_POST['sap']."' WHERE ndex='".$_POST['ndex']."'");
	header("Location:tools_otherdeductionmaintenance.php");
}
$cat=mysql_query($qry);
while($c=mysql_fetch_object($cat)){

	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#fdecf2';}
	if($c->isActive==1){
		$s='Yes';
		$t="<td><a href='#' onclick=\"window.location.href='tools_otherdeductionmaintenance.php?act=editdept&id=".$c->ndex."';\"><img src='../images/delete.png' title='Deactivate' height='20px;' width='20px;'></a></td>";
	}
	else{
		$s='No';
		$t='';
	}
	$data.="<tr style='font-size:12px;background-color:".$bgclr1s.";color:black;'>
	
				<td>".$c->name."</td>
				<td>".$s."</td>
				<td>".$t."</td>
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
  <h2><strong>Other Deduction Maintenance</strong></h2>
 
<form name="frmadds" method="post" action="tools_otherdeductionmaintenance.php?act=adnew">
<table>
	<tr><td>&nbsp;</td></tr>
	
	<tr><td><h1>Add new Deduction</h1></td></tr>
	<tr>
		<td>Description:</td>
		<td><input type="Text" name="description" size=60></td>
	</tr>
	
	<tr><td colspan="2" align="center"><input type="Submit" value="SAVE"></td></tr>
	<tr><td>&nbsp;</td></tr>
</table>
</form>

 <table width="100%">
 	<tr style="font-weight:bold;color:blue;">
		<td>Description</td>		
		<td>Status</td>
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


