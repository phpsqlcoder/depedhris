<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");

$divqry=mysql_query("SELECT * FROM category WHERE status<>1 order by name");
	$optioncategory="<option value=''> - Select Category -";
while($rsdiv=mysql_fetch_object($divqry)){
	$optioncategory.="<option value='".$rsdiv->ndex."'>".$rsdiv->name."";
}

$qry="SELECT * FROM category_sub ORDER BY name";
if($_GET['act']=='search'){
	if($_POST['ser']==1){
		$qry.=" WHERE name like '%".$_POST['serts']."%'";
	}
	
	
}
elseif($_GET['act']=='editdept'){
	$qr=mysql_fetch_object(mysql_query("SELECT d.*,dd.name as category FROM category_sub d left join category dd on dd.ndex=d.categoryId where d.ndex=".$_GET['id'].""));
}
elseif($_GET['act']=='adnew'){
	$qr=mysql_query("insert into category_sub (name,categoryId)values('".$_POST['description']."','".$_POST['category']."')");
	header("Location:category_sub.php");
	//echo $_POST['category'];
}
elseif($_GET['act']=='saveedit'){
	$qrys=mysql_query("update category_sub set name='".mysql_real_escape_string($_POST['description'])."',categoryId='".$_POST['category']."' WHERE ndex='".$_POST['ndex']."'");
	header("Location:category_sub.php");
}
$cat=mysql_query($qry);
while($c=mysql_fetch_object($cat)){
	$div=mysql_fetch_object(mysql_query("select * from category where ndex=".$c->categoryId.""));
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#fdecf2';}
	$data.="<tr style='font-size:12px;background-color:".$bgclr1s.";color:black;'>
				<td>".$c->name."</td>
				<td>".$div->name."</td>
				<td>
					<a href='#' onclick=\"window.location.href='category_sub.php?act=editdept&id=".$c->ndex."';\"><img src='images/edit.png' title='Edit category_sub' height='20px;' width='20px;'></a>
					<img src='images/delete.png' title='Deactivate category_sub' height='20px;' width='20px;'>
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
  <h2><strong>Sub Category Management</strong></h2>
  <table width="831" border="0">
	
    <tr>
	  <td>&nbsp;</td>
      <td>Search Here:</td>
      <td><form id="form1" name="form1" method="post" action="category_sub.php?act=search">
              <select name="ser">
                <option value="1">Description</option>
                <option value="2">Head</option>
              </select>
			  <input type="Text" name="serts">
			  <input type="Submit" value="SEARCH">
      	</form>
	  </td>
    </tr>
  </table>
<br>
<?php if($_GET['act']=='editdept'){?>
<form name="frmedit" method="post" action="category_sub.php?act=saveedit">
	<br><br>
<table>
	<tr><td><h4>Update sub-category</h4></td></tr>
	<tr>
		<td>Description:</td>
		<td><input type="hidden" name="ndex" value="<?php echo $qr->ndex;?>"><input type="Text" name="description" value="<?php echo $qr->name;?>"></td>
	</tr>
	<tr>
		<td>Category:</td>
		<td><select name="category"><?php echo $optioncategory;?><option selected="selected" value="<?php echo $qr->categoryId;?>"><?php echo $qr->category;?></td>
	</tr>
	
	<tr><td colspan="2" align="center"><br><input type="Submit" value="UPDATE"></td></tr>
</table>
</form>
<?php }else{?>
<form name="frmadds" method="post" action="category_sub.php?act=adnew">
<table>
	<tr><td>&nbsp;</td></tr>
	
	<tr><td><h4>Add new sub-category</h4></td></tr>
	<tr>
		<td>Description:</td>
		<td><input type="Text" name="description" size=60></td>
	</tr>
	<tr>
		<td>Category:</td>
		<td><select name="category"><?php echo $optioncategory;?></td>
	</tr>
	
	<tr><td colspan="2" align="center"><br><input type="Submit" value="SAVE"></td></tr>
	<tr><td>&nbsp;</td></tr>
</table>
</form>
<?php }?>
 <table width="100%">
 	<tr style="font-weight:bold;color:blue;">
		<td>Description</td>
		<td>Category</td>
		
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


