<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
if($_SESSION['ndex']!=12 && $_SESSION['ndex']!=15 && $_SESSION['ndex']!=16 && $_SESSION['ndex']!=17 && $_SESSION['ndex']!=14 && $_SESSION['ndex']!=21 && $_SESSION['ndex']!=22 && $_SESSION['ndex']!=336 && $_SESSION['ndex']!=273) {  echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
	include ("myfunctions.php");
if($_GET['ser']){
	
	$o=mysql_query("SELECT * from employee where isActive=1 and (lastName like '%".$_GET['stxt']."%' or firstName like '%".$_GET['stxt']."%')");
	while($pp=mysql_fetch_object($o)){
		$d=mysql_fetch_object(mysql_query("select * from dept where ndex=".$pp->deptId.""));
		$da.="<a href='#' onclick=\"window.location.href='tools_contract.php?emp=".$pp->ndex."'\"><font color='blue'>".getID($pp->employmentStatus,$pp->employeeNo)." - ".$pp->lastName.", ".$pp->firstName." - ".$d->name."<br></font></a>";
	}
	echo $da;
}
else{

if($_GET['act']=='sabmet'){
	
	header("Location:tools_contract.php?emp=".$_GET['emp']."");
	$msg="Successfully Updated Time Logs!";
}
if($_GET['emp']){
	$r=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_GET['emp'].""));
	
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

<script type="text/javascript">
	function searchitems(){
	//alert();
		mynodes=frmitem.serialize();	
		new Ajax.Updater('listitem','tools_contract.php?ser=searching',{
			method: 'get',
			parameters: mynodes
		});	
	}
    </script>
    
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Print Contract</h2>   
    <div class="clearfix">
		<form name="frmitem" id="frmitem">
<table>

	<tr>
		
		<td colspan="2">Search: <input type="text" name="stxt" id="stxt" onkeyup="searchitems();">&nbsp;&nbsp;<font color="#ff0000"><i>Enter any part of last or first name.</i></font></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
				<tr>
					<td colspan=5><div id="listitem"></div></td>
				</tr>
	<tr valign="top"><td>&nbsp;</td></tr>
</table>
		</form>
	<?php if($_GET['emp']){?>
	<form name="frmemp" action="tools_contract_print.php?act=sabmet&emp=<?php echo $_GET['emp'];?>" method="get" target="_blank">
		<input type="hidden" name="emp" value="<?php echo $_GET['emp'];?>">
		<table width="70%">
			<tr><td>&nbsp;</td></tr>
			<tr style='color:maroon;font-weight:bold;'>
				<td>Name: <?php echo $r->lastName.", ".$r->firstName;?><input type="Hidden" name="deptselect" value="<?php echo $d->ndex;?>"></td>
				<td><br><br></td>
			</tr>
			<tr style="color:red;font-size:14px;font-weight:bold;"><td colspan='3'><?php echo $msg;?></td></tr>
			<tr>
				<td>
					<select name="tayp" id="tayp" onchange="tayp_change();">
						<option value="">- Select Template - </option>
						<option value="Fixed Term">Fixed Term</option>
						<option value="Probationary Contract">Probationary Contract</option>
						<option value="Probationary Compensation and Benefits">Probationary Compensation and Benefits</option>
						<option value="Regularization Contract">Regularization Contract</option>
						<option value="Regularization Compensation and Benefits">Regularization Compensation and Benefits</option>
					</select>

				</td>			
				
			</tr>
			<tr class="ft all" style="display:none;">
				<td><br>Contract Creation Date: <input type="text" name="ft_date"></td>
			</tr>
			<tr class="ft all" style="display:none;">
				<td><br>Contract Start: <input type="text" name="ft_date_st"></td>
			</tr>
			<tr class="ft all" style="display:none;">
				<td><br>Contract End: <input type="text" name="ft_date_end"></td>
			</tr>
			<tr class="ft all" style="display:none;">
				<td><br>Compensation: <input type="text" name="ft_compensation"></td>
			</tr>
			<tr class="ft all" style="display:none;">
				<td><br>Payable In: <input type="text" name="ft_payable"></td>
			</tr>
			<!-- <tr>
				<td><br><input type="checkbox">With Job Description</td>
			</tr>
			<tr>
				<td><input type="checkbox">With Compensation</td>
			</tr>
			<tr>
				<td><input type="checkbox">With Length of Service</td>
			</tr>
			 -->
			<tr><td><br><br><input type="Submit" value="Print"></td></tr>

			
		</table>
	</form>
	<?php }?>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>

<script>
	function tayp_change(){
		var all = document.getElementsByClassName('all');

		for (var i = 0; i < all.length; i ++) {
		    all[i].style.display = 'none';
		}
		var vv = document.getElementById("tayp").value;
		if(vv == 'Fixed Term'){
			var ft = document.getElementsByClassName('ft');

			for (var i = 0; i < ft.length; i ++) {
			    ft[i].style.display = 'block';
			}
    
		}
	}
</script>
</body>
</html>
<?php }?>