<?php
session_start();
if(!$_SESSION['ndex']){header("location:../");}
//echo $_SESSION['deptId'];
//if($_SESSION['deptId']!=0){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.";die();}
ob_start();
include("../dbcon.php");
include("../myfunctions.php");
include("scripts/scripts.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript">
		function addrow(ndex,ndexadd){
				$(window['dev' + ndex]).style.display='block';
				$(window['cn' + ndexadd]).style.display='none';
				$(cntr).value=parseInt($(cntr).value)+1;
		}
	</script>  
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "../calendar.inc"; ?>
	<link href="../css/styles.css" rel="stylesheet" type="text/css" />
    <link href="../css/facebox.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include "header.php";?>
<form id="frmrpt" name="frmrpt"></form>
<div id="main_content_wrap" class="container_12">
<div id="rcont">
  <h2>Certifications</h2>
<table width="100%">
  <tr>
  	<td style="width:50px;">&nbsp;</td>
    <td>
      <table style="color:maroon;font-weight:bold;font-size:12px;">
				
				<tr style="height:30px"><td><a href="#" onclick="window.open('certifications/cert_emp_cont.php','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=800');" style="text-decoration:none;">
				<img src="../images/report1.png" height="15" width="15">&nbsp;SSS Employee Contribution</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="window.open('certifications/cert_sss_salary_loan.php','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=800');" style="text-decoration:none;">
				<img src="../images/report1.png" height="15" width="15">&nbsp;SSS Salary Loan Payment</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="window.open('certifications/cert_emergency_loan.php','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=800');" style="text-decoration:none;">
				<img src="../images/report1.png" height="15" width="15">&nbsp;SSS Emergency Loan Payment</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="window.open('certifications/cert_withholding_tax.php','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=800');" style="text-decoration:none;">
				<img src="../images/report1.png" height="15" width="15">&nbsp;Withholding Tax</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="window.open('certifications/cert_phic_employee_cont.php','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=800');" style="text-decoration:none;">
				<img src="../images/report1.png" height="15" width="15">&nbsp;PHIC Employee Contribution</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="window.open('certifications/cert_calamity_loan.php','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=800');" style="text-decoration:none;">
				<img src="../images/report1.png" height="15" width="15">&nbsp;Calamity Loan Payment</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="window.open('certifications/cert_hdmf_cont.php','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=800');" style="text-decoration:none;">
				<img src="../images/report1.png" height="15" width="15">&nbsp;HDMF Employee Contribution</a></td></tr><br />
				<tr style="height:30px"><td><a href="#" onclick="window.open('certifications/cert_multipurpose_loan.php','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=800');" style="text-decoration:none;">
				<img src="../images/report1.png" height="15" width="15">&nbsp;HDMF Multi-Purpose Loan Payment</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="window.open('certifications/cert_hdmf_housing_loan.php','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=800');" style="text-decoration:none;">
				<img src="../images/report1.png" height="15" width="15">&nbsp;HDMF Housing Loan Payment</a></td></tr><br />
				
				
      </table>
    </td>
	
  </tr>
</table> 
  <h2>&nbsp;</h2>
	</div>
    <?php include "footer.php";?>
  </div>
</body>
</html>
