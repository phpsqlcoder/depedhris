<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
if($_SESSION['deptId']!=0){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.";die();}
	include("../dbcon.php");
	include("scripts/scripts.php");
	//echo date('F');
	$monthToGenerateMidYear13thMonth = array('February','March','April');
	$monthToGenerateYearEnd13thMonth = array("September","October","November","December");
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
<?php if(isset($_GET['sync'])) { ?><table><tr><td style="color: green; font-size: 18px;font-style:italic;">Successfully Sync Hospital Records</td></tr></table> <?php } ?>
     <h2>Tools</h2>   
    <div class="clearfix">
	<table width="100%">

  <tr>
  	<td style="width:50px;">&nbsp;</td>
    <td>
      <table style="color:maroon;font-weight:bold;font-size:12px;">
      	<tr style="height:30px;font-size:14px;"><td align="center"><u>Payroll Tools</u></td></tr>
	  		<tr style="height:30px"><td><a href="tools_holdemployeepay.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Hold Employee Pay</a></td></tr>
			<tr style="height:30px"><td><a href="tools_govpremium.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Compute Government Premiums</a></td></tr>
			<tr style="height:30px"><td><a href="tools_employeeloans.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Employee Deduction Setup</a></td></tr>
			<tr style="height:30px"><td><a href="tools_manualentryloandeduction.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Manual Entry SSS and PAGIBIG loan Deduction</a></td></tr>
			<tr style="height:30px"><td><a href="tools_coopdeduction.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Coop Deduction</a></td></tr>
			<tr style="height:30px"><td><a href="tools_otherdeduction.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Other Deduction</a></td></tr>
			<!-- <tr style="height:30px"><td><a href="tools_uniondues.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Deduction Setup (every payroll)</a></td></tr> -->
			<tr style="height:30px"><td><a href="tools_financialassistance.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Add Financial Assistance</a></td></tr>
			<tr style="height:30px"><td><a href="tools_mortuary.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Add Mortuary</a></td></tr>
			<tr style="height:30px"><td><a href="tools_adjustment.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Adjustment/Other Income</a></td></tr>
			<tr style="height:30px"><td><a href="tools_deductionmaintenance.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Deduction Maintenace</a></td></tr>
			<tr style="height:30px"><td><a href="tools_loandeductionmaintenance.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Loan/Deduction Maintenance</a></td></tr>
			<tr style="height:30px"><td><a href="tools_otherdeductionmaintenance.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Other Deduction Maintenace</a></td></tr>
			<tr style="height:30px"><td><a href="tools_freezededuction.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Freeze Deduction</a></td></tr>
			<tr style="height:30px"><td><a href="tools_coop.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Generate/Load Coop Data</a></td></tr>
			<tr style="height:30px"><td><a href="tools_13thmonth.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;13th Month Deduction</a></td></tr>
<tr style="height:30px"><td><a href="tools_ded_1.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Generate/Load 1 time Deduction</a></td></tr>
			<?php if( in_array(date('F'),$monthToGenerateMidYear13thMonth) ){?>
			<tr style="height:30px"><td><a href="tools_generate13thmonthMidYear.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Generate 13th Month (April)</a></td></tr>
			<?php }
				if(in_array(date('F'),$monthToGenerateYearEnd13thMonth) ){
			?>
			<tr style="height:30px"><td><a href="tools_generate13thmonthYearEnd.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Generate 13th Month (December)</a></td></tr>
			<?php }?>
			<tr style="height:30px"><td><a href="tools_generateSLConversion.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Generate SL Conversion </a></td></tr>
			<tr style="height:30px"><td><a href="tools_or.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Gov't Contribution Official Receipt</a></td></tr>

	<!-- <tr style="height:30px"><td><a href="tools_updateshifting.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Update Shifting Name</a></td></tr> -->
 </table> 
    </td>
    <td>
    	<table style="color:maroon;font-weight:bold;font-size:12px;">
<tr style="height:30px;font-size:14px;"><td align="center"><u>Hospital Deductions</u></td></tr>
    		<tr style="height:30px"><td><a href="tools_sync.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Sync Hospital Deductions</a></td></tr>
<tr style="height:30px"><td><a href="tools_withHospitalLoan.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Hospital Deductions</a></td></tr>
<tr style="height:30px"><td><a href="tools_withHospitalLoan_amort.php" style="text-decoration:none;"><img src="../images/usercontract.png" height="15" width="15">&nbsp;Update Hospital Deductions</a></td></tr>

    	</table>
    </td>
  </tr>
</table> 
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>


