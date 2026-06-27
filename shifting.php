<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
function contime($hour,$minutes,$ampm){
	if($ampm=='PM'){
		$hour=$hour+12;
	}
	$tym=$hour.":".$minutes.":00";
	return $tym;
}

$qry="SELECT 
		CASE timeIn WHEN '00:00:00' THEN '-' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeIn),'%H:%i %p') END AS tymIn, 
		CASE breakOut WHEN '00:00:00' THEN '-' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakOut),'%H:%i %p') END as brekOut,
		CASE breakIn WHEN '00:00:00' THEN '-' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakIn),'%H:%i %p') END as brekIn,
		CASE timeOut WHEN '00:00:00' THEN '-' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeOut),'%H:%i %p') END as tymOut,breakMinutes,ndex,isNightPremium
		FROM shifting";
if($_GET['act']=='editdept'){
	$qr=mysql_fetch_object(mysql_query("SELECT d.*,dd.name as division FROM dept d left join division dd on dd.ndex=d.divisionId where d.ndex=".$_GET['id'].""));
}
elseif($_GET['act']=='add2'){
	//echo $_POST['2isNP']."222";
	if($_POST['2isNP']=='on'){$np2=1;}else{$np2=0;}
	$tyma=contime($_POST['2houra'],$_POST['2mina'],$_POST['2typea']);
	$tymb=contime($_POST['2hourb'],$_POST['2minb'],$_POST['2typeb']);
	if($tyma=='00:00:00'){$tyma='00:00:01';}
	if($tymb=='00:00:00'){$tymb='00:00:01';}
	$qr=mysql_query("insert into shifting (`timeIn`, `timeOut`,`breakMinutes`,isNightPremium)values
			('".$tyma."',
			'".$tymb."',
			'".$_POST['2break']."','".$np2."')");
	header("Location:shifting.php");
}
elseif($_GET['act']=='add4'){
	if($_POST['4isNP']=='on'){$np4=1;}else{$np4=0;}
	$tyma=contime($_POST['4houra'],$_POST['4mina'],$_POST['4typea']);
	$tymb=contime($_POST['4hourb'],$_POST['4minb'],$_POST['4typeb']);
	$tymc=contime($_POST['4hourc'],$_POST['4minc'],$_POST['4typec']);
	$tymd=contime($_POST['4hourd'],$_POST['4mind'],$_POST['4typed']);
	if($tyma=='00:00:00'){$tyma='00:00:01';}
	if($tymb=='00:00:00'){$tymb='00:00:01';}
	if($tymc=='00:00:00'){$tymc='00:00:01';}
	if($tymd=='00:00:00'){$tymd='00:00:01';}

	$qr=mysql_query("insert into shifting (`timeIn`,`breakOut`, `breakIn`, `timeOut`,`breakMinutes`,isNightPremium)values
			('".$tyma."',
			'".$tymb."',
			'".$tymc."',
			'".$tymd."',
			'".$_POST['4break']."','".$np4."')");
	header("Location:shifting.php");
}
elseif($_GET['act']=='saveedit'){
	$qrys=mysql_query("update dept set name='".mysql_real_escape_string($_POST['description'])."',head='".mysql_real_escape_string($_POST['head'])."',divisionId='".$_POST['division']."' WHERE ndex='".$_POST['ndex']."'");
	header("Location:shifting.php");
}
$cat=mysql_query($qry);
while($r=mysql_fetch_object($cat)){
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#fdecf2';}
	if($r->isNightPremium==1){$np='Yes';}else{$np='No';}
	$data.="<tr style='font-size:12px;background-color:".$bgclr1s.";color:black;'>
				<td>".$r->tymIn."</td>
				<td>".$r->brekOut."</td>
				<td>".$r->brekIn."</td>
				<td>".$r->tymOut."</td>
				<td>".$r->breakMinutes."</td>
				<td>".$np."</td>
				<td>
					<a href='#' onclick=\"window.location.href='shifting.php';\"><img src='images/edit.png' title='Edit supplier' height='20px;' width='20px;'></a>
					<img src='images/delete.png' title='Deactivate shifting' height='20px;' width='20px;'>
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
  <h2><strong>Shifting Management</strong></h2>
<form name="frmadd2" method="post" action="shifting.php?act=add2">
<table>
	<tr><td>&nbsp;</td></tr>
	<tr><td colspan="2" style="font-weight:bold;color:maroon;">2 Value Shifting Schedule</td></tr>
	<tr><td>Time In:</td><td style="font-weight:bold;"><select name="2houra"><?php echo $optionhour;?></select>:<select name="2mina"><?php echo $optionminute;?></select><select name="2typea"><?php echo $optionampm;?></select></td></tr>
	<tr><td>Time Out:</td><td style="font-weight:bold;"><select name="2hourb"><?php echo $optionhour;?></select>:<select name="2minb"><?php echo $optionminute;?></select><select name="2typeb"><?php echo $optionampm;?></select></td></tr>
	<tr><td>Break:</td><td><input type="Text" size="2" name="2break"> (minutes)</td></tr>
	<tr><td></td><td><input type="checkbox" name="2isNP">Is Night Premium</td></tr>
	<tr><td colspan="2" align="center"><input type="Submit" value="SAVE"></td></tr>
	<tr><td>&nbsp;</td></tr>
</table>
</form>
<form name="frmadd4" method="post" action="shifting.php?act=add4">
<table>
	<tr><td>&nbsp;</td></tr>
	<tr><td colspan="2" style="font-weight:bold;color:maroon;">4 Value Shifting Schedule</td></tr>
	<tr><td>Time In:</td><td style="font-weight:bold;"><select name="4houra"><?php echo $optionhour;?></select>:<select name="4mina"><?php echo $optionminute;?></select><select name="4typea"><?php echo $optionampm;?></select></td></tr>
	<tr><td>Break Out:</td><td style="font-weight:bold;"><select name="4hourb"><?php echo $optionhour;?></select>:<select name="4minb"><?php echo $optionminute;?></select><select name="4typeb"><?php echo $optionampm;?></select></td></tr>
	<tr><td>Break In:</td><td style="font-weight:bold;"><select name="4hourc"><?php echo $optionhour;?></select>:<select name="4minc"><?php echo $optionminute;?></select><select name="4typec"><?php echo $optionampm;?></select></td></tr>
	<tr><td>Time In:</td><td style="font-weight:bold;"><select name="4hourd"><?php echo $optionhour;?></select>:<select name="4mind"><?php echo $optionminute;?></select><select name="4typed"><?php echo $optionampm;?></select></td></tr>
	<tr><td>Break:</td><td><input type="Text" size="2" name="4break"> (minutes)</td></tr>
	<tr><td></td><td><input type="checkbox" name="4isNP">Is Night Premium</td></tr>
	<tr><td colspan="2" align="center"><input type="Submit" value="SAVE"></td></tr>
	<tr><td>&nbsp;</td></tr>
</table>
</form>
 <table width="100%">
 	<tr style="font-weight:bold;color:blue;">
		<td>Time In</td>
		<td>Break Out</td>
		<td>Break In</td>
		<td>Time Out</td>
		<td>Break (min)</td>
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


