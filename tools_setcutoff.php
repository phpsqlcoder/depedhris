<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");

$qry="SELECT * FROM cutoffdates ORDER BY payrollDate DESC";
if($_GET['act']=='editdept'){
	$qr=mysql_fetch_object(mysql_query("SELECT * FROM cutoffdates where ndex=".$_GET['id'].""));
}
elseif($_GET['act']=='adnew'){
	$qr=mysql_query("insert into cutoffdates (`isLock`,`payrollDate`, `cutoffDateStart`, `cutoffDateEnd`)values('0','".$_POST['cutoff']."','".$_POST['startdate']."','".$_POST['enddate']."')");
	header("Location:tools_setcutoff.php");
}
elseif($_GET['act']=='saveedit'){
	$qrys=mysql_query("update cutoffdates set payrollDate='".mysql_real_escape_string($_POST['cutoffe'])."',cutoffDateStart='".mysql_real_escape_string($_POST['startdatee'])."',cutoffDateEnd='".$_POST['enddatee']."' WHERE ndex='".$_POST['ndex']."'");
	//echo "update cutoffdates set payrollDate='".mysql_real_escape_string($_POST['cutoffe'])."',cutoffDateStart='".mysql_real_escape_string($_POST['startdatee'])."',cutoffDateEnd='".$_POST['enddatee']."' WHERE ndex='".$_POST['ndex']."'";
	header("Location:tools_setcutoff.php");
}
$cat=mysql_query($qry);
while($c=mysql_fetch_object($cat)){
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#fdecf2';}
	if($c->isLock==0){
		$btn="<a href='#' onclick=\"window.location.href='tools_setcutoff.php?act=editdept&id=".$c->ndex."';\"><img src='images/edit.png' title='Edit Cutoff' height='20px;' width='20px;'></a>";
	}
	else{
		$btn="<a href='#' onclick=\"alert('CutOff Date is already locked!');\"><img src='images/logkiosk.png' title='Cutoff is Locked' height='20px;' width='20px;'></a>";
	}
	$data.="<tr style='font-size:12px;background-color:".$bgclr1s.";color:black;'>
				<td>".$c->payrollDate."</td>
				<td>".$c->cutoffDateStart."</td>
				<td>".$c->cutoffDateEnd."</td>
				<td>".$btn."
					
					
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
	<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
</head>

<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">

<div id="rcont">
  <h2><strong>Set Cut Off</strong></h2>

<?php if($_GET['act']=='editdept'){?>
<form name="frmedit" method="post" action="tools_setcutoff.php?act=saveedit">
<table>
	<tr>
		<td>Payroll Date:</td>
		<td><input type="hidden" name="ndex" value="<?php echo $qr->ndex;?>"><input type="Text" name="cutoffe" value="<?php echo $qr->payrollDate;?>" id="cutoffe" size="15"><a href="javascript:show_calendar('frmadds.cutoffe');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
	</tr>
	<tr>
		<td>Start:</td>
		<td><input type="Text" name="startdatee" value="<?php echo $qr->cutoffDateStart;?>" id="startdatee" size="15"><a href="javascript:show_calendar('frmadds.startdatee');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
	</tr>
	<tr>
		<td>End:</td>
		<td><input type="Text" name="enddatee" value="<?php echo $qr->cutoffDateEnd;?>" id="enddatee" size="15"><a href="javascript:show_calendar('frmadds.enddatee');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
	</tr>
	<tr><td colspan="2" align="center"><input type="Submit" value="UPDATE"></td></tr>
</table>
</form>
<?php }else{?>
<form name="frmadds" method="post" action="tools_setcutoff.php?act=adnew">
<table>
	<tr><td>&nbsp;</td></tr>
	
	<tr><td><h1>Add new CutOff</h1></td></tr>
	<tr>
		<td>Payroll Date:</td>
		<td><input type="Text" name="cutoff" id="cutoff" size="15"><a href="javascript:show_calendar('frmadds.cutoff');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
	</tr>
	<tr>
		<td>Start:</td>
		<td><input type="Text" name="startdate" id="startdate" size="15"><a href="javascript:show_calendar('frmadds.startdate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
	</tr>
	<tr>
		<td>End:</td>
		<td><input type="Text" name="enddate" id="enddate" size="15"><a href="javascript:show_calendar('frmadds.enddate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
	</tr>

	<tr><td colspan="2" align="center"><input type="Submit" value="SAVE"></td></tr>
	<tr><td>&nbsp;</td></tr>
</table>
</form>
<?php }?>
 <table width="100%">
 	<tr style="font-weight:bold;color:blue;">
		<td>Payroll</td>
		<td>Start</td>
		<td>End</td>
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


