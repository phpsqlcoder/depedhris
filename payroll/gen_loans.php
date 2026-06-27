<?php

$conn=mysql_connect("localhost","root","pangitka");
mysql_select_db("hris",$conn);
//include ("../options.php");
include('payrollfunctions.php');
include ("../employeefunctions.php");
//echo getDeductionData(207,'current balance');

	$sql = "SELECT l.*, e.lastName, e.firstName, e.ndex employeeNdex,ld.name as loan FROM loan_employee  l
											LEFT JOIN employee e ON e.ndex=l.employeeId	
											LEFT JOIN loandeductionmaintenance ld on ld.ndex=l.loanId
												WHERE l.employeeId>0 && l.isDeleted<>'1'";
	

	$sql .= " ORDER BY lastName,firstName,dedDateStart desc";
	
	$rs = mysql_query($sql,$conn);
	$cnt = 0;
	//echo $sql;
	while ($dt = mysql_fetch_assoc($rs)){
		$cnt++;
		if ($cnt == 1){ $tr_bcg = "row1"; } else { $tr_bcg = "row2"; $cnt = 0;}
		if ($dt['posted'] != '1'){
			$edit = "<a href='?myact=edit&id=".$dt['ndex']."&emp=".$_GET['emp']."'>Edit</a> ";
			$delete = "<a href='?pageact=delete&id=".$dt['ndex']."&emp=".$_GET['emp']."'>Delete</a> ";
			//$adjustment = "<a href='#' onclick=\"$('adj".$dt['ndex']."').toggle();\">Adjustment</a>";
			$post = "<a href='?myact=postloan&id=".$dt['ndex']."&emp=".$_GET['emp']."'>post</a>";
			$post .= " | ".$edit." | ".$delete." |";
		} else {
			$post= '';
			$edit ='';
		}
		$chargeButton = '';
		$ctr1s++;
		if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#fdecf2';}	
		$ball = number_format(getDeductionData($dt['ndex'],'current balance'),2);
		if($ball>0){
			$viewSystemDepartmentList .= "<tr class='".$tr_bcg."' style='font-size:12px;background-color:".$bgclr1s.";color:black;'>
					<td><a href='#' onclick=\"window.open('tools_employeeloanslledger.php?id=".$dt['employeeNdex']."&loan=".$dt['ndex']."','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=500')\";>".$dt['lastName'].", ".$dt['firstName']." </a></td>
					<td>".$dt['loan']." </td>
					<td align='right'>".$dt['loanAmount']." </td>
					<td align='right'>".$dt['nOfDeduction']." </td>
					<td align='right'>".date('Y-m-d',strtotime($dt['dedDateStart']))." </td>
					<td align='right'>".number_format(getDeductionData($dt['ndex'],'current balance'),2)."</td>
				
			
				</tr>
				
				";
		}
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
				var loanAmount = form.loanAmount.value.trim();
				var nOfDeduction = form.nOfDeduction.value.trim();
				var dedDateStart = form.dedDateStart.value.trim();
				var remarks = form.remarks.value.trim();
				
				msg = "\nPlease fill-in. The following are requierd fields... ";
				if (loanAmount == "" || nOfDeduction == "" || dedDateStart == "" || remarks == "") {
					if (loanAmount == ""){ msg += "\n   - Loan Amount"}
					if (nOfDeduction == ""){ msg += "\n   - No. of Deduction"}
					if (dedDateStart == ""){ msg += "\n   - Deduction Start"}
					if (remarks == ""){ msg += "\n   - Remarks"}
					
					alert (msg)
					return false;
				} else return true;
			}
			
			$(document).ready(function(){
				$("#loanAmount").on(function(e) {
				
				}
			}
			
			function char_allowed(f) {
					var field = document.getElementById(f);
					var valo = new String();
					var numere = "0123456789.";
					var chars = field.value.split("");
					for (i = 0; i < chars.length; i++) {
						if (numere.indexOf(chars[i]) != -1) valo += chars[i];
						else{}
					}
					if (field.value != valo) field.value = valo;
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
   <h2>Tools >> Deduction Setup &nbsp;&nbsp;&nbsp;&nbsp; </h2> 
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
	 
		
	 	<table border="0" width="1020">
			
			<td valign="top">
				<table cellpadding="0" cellspacing="0" border="0" width="90%">
					<tr><td colspan="2" align="right"><br /><b>:::</b><i> List of Employees With Loan</i><hr></td></tr>
					
					<tr>
						<td colspan="2" align="left">
							<div id="displayeeloanlistList">
							<table cellpadding="5" cellspacing="0" border="0" width="100%">
								<tr class="columnheader" style="color:blue;"><td>Name</td><td>Deduction</td><td align="right">LoanAmount</td><td align="right">No. of ded</td><td  align="right">Start of Ded<br />(Date)</td><td  align="center">Balance</td><td align="right">Action</td></tr>
								<tr><td colspan="10"><hr></td></tr>
								<?php echo $viewSystemDepartmentList;?>
							</table>
							</div>
						</td>
					</tr>
				</table>	
			</td>
		</table>
	
	<div>
	
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
</div>
</body>
</html>
