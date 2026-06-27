<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include('../inc/ps_pagination.php');


$employeeInfo = mysql_fetch_assoc(mysql_query("SELECT * FROM employee WHERE ndex='".$_GET['id']."'",$conn));
$sql = "SELECT * FROM loansetup WHERE employeeId='".$_GET['id']."' && posted='1' && isDeleted='0'";// && posted='1'";
//echo $sql;
$rs = mysql_query($sql);
while ($dt = mysql_fetch_assoc($rs)){
	$loanAmount = $dt['loanAmount'];
	$rs1 = mysql_query("SELECT * FROM loanpayments WHERE loanSetupId='".$dt['ndex']."' ORDER BY datePaid");
	$viewLedger = "";
	while ($dt1 = mysql_fetch_assoc($rs1)){
		$loanAmount = $loanAmount - $dt1['amountPaid'];
		
		$viewLedger .= "<tr align='right'>
							<td align='left'>Payment</td>
							<td align='left'>".date('F d, Y',strtotime($dt1['datePaid']))."</td>
							<td>&nbsp;</td>
							<td>".number_format($dt1['amountPaid'],2)."</td>
							<td>".number_format($loanAmount,2)."</td></tr>";
	}

	$viewResult .="<tr onclick=\"Effect.toggle('".$dt['ndex']."', 'blind', { duration: 1.0 });\" style='background-color:#ffff99;font-size:12px;'><td>".$dt['loanType']." / ".$dt['ndex']."</td><td>".number_format($dt['loanAmount'],2)."</td><td>".$dt['nOfDeduction']."</td><td>".$dt['dedDateStart']."</td><td>".number_format($dt['dedAmount'],2)."</td><td>".number_format($dt['loanBalance'],2)."</td></tr>
					<tr>
						<td colspan='5'>
							<div id='".$dt['ndex']."' style='display:none;'><br>
								<table cellpadding='5' style='background-color:#ffffcc;' >
									<tr align='center'><td width='250'>&nbsp;</td><td width='150'>Payroll Date</td><td width='100'>Debit</td><td width='100'>Credit</td><td width='100'>Balance</td></tr>
									<tr align='right'><td align='left'>Loan</td><td width='250' align='left'>&nbsp;</td><td width='100'>".number_format($dt['loanAmount'],2)."</td><td width='100'>&nbsp;</td><td width='100'>".number_format($dt['loanAmount'],2)."</td></tr>
									".$viewLedger."
								</table><br>
							</div>
						</td>
					</tr>";
}


include("scripts/scripts.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- <title>HRIS - Davao Doctors Hospital</title> -->
	<link rel="stylesheet" type="text/css" href="mycsss.css" />
	<link href="../css/styles.css" rel="stylesheet" type="text/css" />
	<link href="../css/facebox.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="scripts/datepickercontrol/datepickercontrol.css">  
	<script type="text/javascript" src="scripts/datepickercontrol/datepickercontrol.js"></script>
	<script language="JavaScript">
	<!-- Begin
			function checkReqfield(form) {
				uname = form.attendant_name.value;
				msg = "\nPlease fill-in. The following are requierd fields... ";
				if (uname == "" ) {
					if (uname == ""){ msg += "\n   - Full Name"}
					alert (msg)
					return false;
				} else return true;
			}
			
			// function ni nga gi create nako  para tawagon sa imong input box inig onkeyup
			function sampleSearch(f,url,plchldr){
				var pars = '';
				if (f != 'displayFirst'){ 
					pars = $(f).serialize();} else { pars ='';	
				}
				pars = pars + '&ajaxAct=' + plchldr;
				url = url;
				var myAjax = new Ajax.Updater( plchldr,url, { method: 'get',parameters: pars,});
			}
	// End -->
	</script>
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
   <h2>Tools >> Loan Setup >> Ledger &nbsp;&nbsp;&nbsp;&nbsp;  <br />
   	NAME: <?php echo $employeeInfo['lastName'].", ".$employeeInfo['firstName'];?></h2>
	<!--  <div class="clearfix"> -->
	 	<table cellpadding="5" cellspacing="0" width="100%">
			<tr><td>LOAN TYPE</td><td>LOAN AMOUNT</td><td>NUMBER OF <br> DEDUCTION</td><td>DEDUCTION<br>DATE START</td><td>AMORTIZATION</td><td>LOAN BALANCE</td></tr>
			<?php echo $viewResult;?>
		</table>
	<!-- <div> -->
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
</div>
</body>
</html>
