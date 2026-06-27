<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");

$qry="SELECT * FROM holiday";
if($_GET['act']=='search'){
	if($_GET['act']=='search'){
		$qry.=" WHERE DATE_FORMAT(date,'%Y') = '".$_POST['yer']."'";
	}
	else {
		$qry.=" WHERE DATE_FORMAT(date,'%Y') = '".date('Y')."'";
	}
	
}


elseif($_GET['act']=='editholiday'){
	$qr=mysql_fetch_object(mysql_query("SELECT d.*,dd.name as division FROM dept d left join division dd on dd.ndex=d.divisionId where d.ndex=".$_GET['id'].""));
}
elseif($_GET['act']=='adnew'){
	$qr=mysql_query("insert into holiday (name,date,isSpecial,encodedBy,encodedDate)values
			('".mysql_real_escape_string($_POST['description'])."','".$_POST['dyt']."','".$_POST['tayp']."','".$_SESSION['ndex']."','".date('Y-m-d H:i:s')."')");
	header("Location:holiday.php");
	//echo $_POST['division'];
}
elseif($_GET['act']=='saveedit'){
	$qrys=mysql_query("update dept set name='".mysql_real_escape_string($_POST['description'])."',head='".mysql_real_escape_string($_POST['head'])."',divisionId='".$_POST['division']."' WHERE ndex='".$_POST['ndex']."'");
	header("Location:dept.php");
}
$qry.=" ORDER BY date DESC";
$cat=mysql_query($qry);
while($c=mysql_fetch_object($cat)){
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#fdecf2';}
	if($c->isSpecial==1){$tayp='SPECIAL';}else{$tayp='LEGAL';}
	$data.="<tr style='font-size:12px;background-color:".$bgclr1s.";color:black;'>
				<td>".$c->date."</td>
				<td>".$c->name."</td>
				<td>".$tayp."</td>
				<td>
					<a href='#' onclick=\"window.location.href='holiday.php;\"><img src='images/edit.png' title='Edit supplier' height='20px;' width='20px;'></a>
		</td>
	</tr>";
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">

<div id="rcont">
  <h2><strong>List of Holidays</strong></h2>
  <table width="831" border="0">
	
    <tr>
	  <td>&nbsp;</td>
      <td align="right">Select Year:&nbsp;&nbsp;</td>
      <td><form id="form1" name="form1" method="post" action="holiday.php?act=search">
              <select name="yer">
		<?php
		$yr=date('Y');
			for($x=$yr-5;$x<=$yr;$x++){
				if($x==$yr){$f="selected='selected'";}else{$f="";}
				echo "<option value='".$x."' ".$f.">".$x."";
			}
		?>
              </select>
			  <input type="Submit" value="SEARCH">
      	</form>
	</td>
    </tr>
  </table>
<br>
<?php if($_GET['act']=='editholiday'){?>
<form name="frmedit" method="post" action="holiday.php?act=saveedit">
<table>
	<tr>
		<td>Description:</td>
		<td><input type="hidden" name="ndex" value="<?php echo $qr->ndex;?>"><input type="Text" name="description" value="<?php echo $qr->name;?>"></td>
	</tr>
	<tr>
		<td>Division:</td>
		<td><select name="division"><?php echo $optiondivision;?><option selected="selected" value="<?php echo $qr->divisionId;?>"><?php echo $qr->division;?></td>
	</tr>
	<tr>
		<td>Head:</td>
		<td><input type="Text" name="head" size=60 value="<?php echo $qr->head;?>"></td>
	</tr>
	<tr><td colspan="2" align="center"><input type="Submit" value="UPDATE"></td></tr>
</table>
</form>
<?php }else{?>
<form name="frmadds" method="post" action="holiday.php?act=adnew">
<table>
	<tr><td>&nbsp;</td></tr>
	
	<tr><td><h1>Add new Holiday</h1></td></tr>
	<tr>
		<td>Description:</td>
		<td><input type="Text" name="description" size=60></td>
	</tr>
	<tr>
		<td>Date:</td>
		<td><input type="Text" name="dyt" id="dyt" size="15"><a href="javascript:show_calendar('frmadds.dyt');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
	</tr>
	<tr>
		<td>Type:</td>
		<td><input type="Radio" name="tayp" checked="checked" value="0"> Legal <input type="Radio" name="tayp" value="1"> Special </td>
	</tr>
	<tr><td colspan="2" align="center"><input type="Submit" value="SAVE"></td></tr>
	<tr><td>&nbsp;</td></tr>
</table>
</form>
<?php }?>
 <table width="100%">
 	<tr style="font-weight:bold;color:blue;">
		<td>Date</td>
		<td>Description</td>
		<td>Type</td>
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


