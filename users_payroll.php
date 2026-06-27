<?php
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
if($_SESSION['deptId']!=0){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.";die();}
if($_SESSION['nym']!='jhang'){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.";die();}
ob_start();
include("dbcon.php");
include("scripts/scripts.php");

if($_GET['act']=='save'){
  $sql=mysql_query("select u.* from users u where u.deptId=0 and nym<>''");
  while($r=mysql_fetch_object($sql)){
  $ctr1s++;
  if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}

  if($_POST['cb'.$r->ndex]=='on'){$ok='1';}else{$ok='0';}
	$upd=mysql_query("update users set isAllowedOnPayroll=".$ok." where ndex=".$r->ndex."");
  }
}
$sql=mysql_query("select u.* from users u where u.deptId=0 and nym<>''");
while($r=mysql_fetch_object($sql)){
$ctr1s++;
if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
if($r->isAllowedOnPayroll=='0'){$dok='';}else{$dok='checked';}
  $data.="<tr style='background-color:".$bgclr1s."'>
	    <td>".$r->nym."</td>
	    <td>".$r->fullName."</td>
		<td><input type='checkbox' name='cb".$r->ndex."' ".$dok."></td>
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
  <h2><strong style="color:blue;">Payroll Access</strong></h2>
	<form action="users_payroll.php?act=save" method="post">
  <table width="120%">
  	<tr>
		<td width='60%'>
			  <table width="60%" align="center">
			    <tr style="color:blue;font-weight:bold;">
			      <td>Username</td>
			      <td>Full Name</td>
				  <td>Payroll Access</td>
			      
			    </tr>
			    <tr><td colspan=4><hr></td></tr>
				  <?php echo $data;?>
			  </table>
		</td>
	</tr>
    <tr><td align="center" colspan="3"><input type="submit" value="SAVE" /></td></tr>
  </table>
  </form>
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>


