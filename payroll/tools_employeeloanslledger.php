<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include('../inc/ps_pagination.php');
include("payrollfunctions.php");

$employeeInfo = mysql_fetch_assoc(mysql_query("SELECT * FROM employee WHERE ndex='".$_GET['id']."'",$conn));
include("scripts/scripts.php");
?>

<div id="main_content_wrap" class="container_12">
   <h2>Loan Ledger <br />
   
   	NAME: <?php echo $employeeInfo['lastName'].", ".$employeeInfo['firstName'];?></h2>
	<!--  <div class="clearfix"> -->
	<table width="100%">
		<tr>
			<td>
				<?php
				
				 echo getDeductionData($_GET['loan'],'ledger');?>
			</td>
			<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			<td valign="top">
				<div style="font-style:'Times New Roman';font-size:16px;margin-top:10px;">
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td>Current Deduction per Payroll:</td><td> <?php echo number_format(getDeductionData($_GET['loan'],'Currect Deduction Amount'),2);?><br></td></tr>
					<tr><td>Total Loan Amount:</td><td> <?php echo number_format(getDeductionData($_GET['loan'],'total loan amount'),2);?><br></td></tr>
					<tr><td>Total Paid Amount:</td><td> <?php echo number_format(getDeductionData($_GET['loan'],'total payments made'),2);?>	<br>	</td></tr>
					<tr><td>Current Balance: </td><td><?php echo number_format(getDeductionData($_GET['loan'],'current balance'),2);?><br></td></tr>
					<tr><td>Total No of Deductions:</td><td> <?php echo getDeductionData($_GET['loan'],'total number of deduction');?><br></td></tr>
					<tr><td>Total No of payments:</td><td> <?php echo getDeductionData($_GET['loan'],'total number of payments made');?><br></td></tr>
					<tr><td>Remaining Number of Deduction: </td><td><?php echo getDeductionData($_GET['loan'],'remaining number of deduction');?></td></tr>
                    <tr><td>Deduction As of: </td><td><?php echo estimatedDateOfCompletion($_GET['loan']);?></td></tr>
				</table>
				</table>
				</div>
			</td>
		</tr>	
	<h2>&nbsp;</h2>
	
</div>
