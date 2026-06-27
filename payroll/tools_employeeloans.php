<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include('../inc/ps_pagination.php');
include('payrollfunctions.php');
include ("../employeefunctions.php");
//echo getDeductionData(207,'current balance');
if($_GET['ser']){
	$o=mysql_query("SELECT * from employee where (lastName like '%".$_GET['stxt']."%' or firstName like '%".$_GET['stxt']."%')");
	while($pp=mysql_fetch_object($o)){
		$d=mysql_fetch_object(mysql_query("select * from dept where ndex='".$pp->deptId."'"));
		if($pp->isActive==0){$color='red';}else{$color='blue';}
		$da.="<a href='#' onclick=\"window.location.href='tools_employeeloans.php?emp=".$pp->ndex."'\"><font color='".$color."'>".getID($pp->employmentStatus,$pp->employeeNo)." - ".$pp->lastName.", ".$pp->firstName." - ".$d->name."<br></font></a>";
	}
	echo $da;
}
else{
	$rs2 = mysql_query("SELECT * FROM cutoffdates where isLock=0 ORDER BY payrollDate limit 12",$conn);
	while ($dt2 = mysql_fetch_assoc($rs2)){
		$optionSelectPayrollCutoffDate.= "<option value='".$dt2['payrollDate']."'>".date('F d, Y',strtotime($dt2['payrollDate']))."</option>";
	}
	$e=mysql_fetch_object(mysql_query("select * from employee where ndex='".$_GET['emp']."'"));
	if($_GET['act']=='adjustment'){
		/*$oldvalue=mysql_fetch_object(mysql_query("select * from loansetup where ndex=".$_GET['loanid'].""));
		$insertToHistory=mysql_query("insert into loansetup_logs (`loansetupId`, `oldValue`, `newValue`, `name`, `actDate`, `actBy`)VALUES(".$_GET['loanid'].",".$oldvalue->loanBalance.",0,'Reset To Zero','".date('Y-m-d H:i:s')."','".$_SESSION['nym']."')");
		$resetToZero=mysql_query("update loansetup set loanBalance='0' where ndex=".$_GET['loanid']."");*/
		$adjustment=mysql_query("insert into loan_employee_payments (`loanSetupId`, `datePaid`, `amountPaid`, `remarks`)values(".$_GET['loan'].",'".$_POST['det'.$_GET['loan']]."','".$_POST['amt'.$_GET['loan']]."','".$_POST['remark'.$_GET['loan']]."')");
	}
	$sql = "SELECT l.*, e.lastName, e.firstName, e.ndex employeeNdex,ld.name as loan FROM loan_employee  l
											LEFT JOIN employee e ON e.ndex=l.employeeId	
											LEFT JOIN loandeductionmaintenance ld on ld.ndex=l.loanId
												WHERE 1 && l.isDeleted<>'1' && l.employeeId='".$_GET['emp']."'";
	if ($_GET['pageact'] == 'add'){
		$checkifRestrict=mysql_fetch_object(mysql_query("select * from loandeductionmaintenance where ndex='".$_POST['loanType']."'"));
		
		$result = mysql_query("INSERT INTO loan_employee ( `employeeId` , `loanId` , `loanAmount` , `nOfDeduction` , `dedDateStart` , `remarks` )
						VALUES ('".$_GET['emp']."', '".$_POST['loanType']."', '".$_POST['loanAmount']."', '".$_POST['nOfDeduction']."', '".$_POST['dedDateStart']."', '".$_POST['remarks']."')",$conn);
		header ("Location: ".$_SERVER['PHP_SELF']."?emp=".$_GET['emp']."");
	} elseif ($_GET['pageact'] == "edit"){
		$result = mysql_query("UPDATE loan_employee SET loanAmount='".$_POST['loanAmount']."',
							    loanId='".$_POST['loanType']."',
							    nOfDeduction='".$_POST['nOfDeduction']."',
							dedDateStart='".$_POST['dedDateStart']."',
							remarks='".$_POST['remarks']."',
							postedDate='".date("Y-m-d H:i:s")."' WHERE ndex='".$_POST['id']."'",$conn);
		$linkback = explode('?',$_SERVER['PHP_SELF']);
		header ("Location: ".$linkback[0]."?emp=".$_GET['emp']."");
	} elseif ($_GET['pageact'] == "delete"){
		$result = mysql_query("UPDATE loan_employee SET `isDeleted`='1' WHERE ndex = '".$_GET['id']."'",$conn);
		$linkback = explode('?',$_SERVER['PHP_SELF']);
		header ("Location: ".$linkback[0]."?emp=".$_GET['emp']."");
	} elseif ($_GET['pageact'] == "search" && !empty($_POST['search_text'])){
		$sql .= " && (lastName LIKE '%".$_POST['search_text']."%' || firstName LIKE '%".$_POST['search_text']."%')";
	}
	
	if (empty($_GET['myact']) && empty($_GET['pageact'])){
		$myact = "add";
		$submitButton = "Save ->";
	} elseif ($_GET['myact'] == 'edit'){
		$loanType = mysql_fetch_object(mysql_query("SELECT ld.* FROM loan_employee l left join `loandeductionmaintenance` ld on ld.ndex=l.loanId where l.ndex='".$_GET['id']."'"));
			$optionSelectloanType_selected = "<option value='".$loanType->ndex."' selected='selected'>".$loanType->name;
		$myact = 'edit';
		$sql1 .= $sql." && l.ndex='".$_GET['id']."'";
		$rs1 = mysql_fetch_assoc(mysql_query($sql1));
		$edit_id = "<input type='hidden' name='id' value='".$_GET['id']."'>";
		$submitButton = "Update ->";
	} elseif ($_GET['myact'] == 'postloan') {
		$postLoan = mysql_query("UPDATE loan_employee SET posted='1', postedDate='".date('Y-m-d H:i:s')."' where ndex = '".$_GET['id']."'",$conn);
		$linkback = explode('?',$_SERVER['PHP_SELF']);
		header ("Location: ".$linkback[0]."?emp=".$_GET['emp']."");
	}
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
		$viewSystemDepartmentList .= "<tr class='".$tr_bcg."' style='font-size:12px;background-color:".$bgclr1s.";color:black;'>
				<td><a href='#' onclick=\"window.open('tools_employeeloanslledger.php?id=".$dt['employeeNdex']."&loan=".$dt['ndex']."','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=500')\";>".$dt['lastName'].", ".$dt['firstName']." </a></td>
				<td>".$dt['loan']." </td>
				<td align='right'>".$dt['loanAmount']." </td>
				<td align='right'>".$dt['nOfDeduction']." </td>
				<td align='right'>".date('Y-m-d',strtotime($dt['dedDateStart']))." </td>
				<td align='right'>".number_format(getDeductionData($dt['ndex'],'current balance'),2)."</td>
				<td align='right'>".$post.$viewloans."</td>
				<td align='center'><a href='#' onclick=\"$('adj".$dt['ndex']."').toggle();\" title=\"Manual Adjustment\"><img src='../images/rr.png' height='20'></a></td>
			</tr>
			<tr>
				<td colspan='10'>
					<form method='post' name='frm".$dt['ndex']."' action=\"tools_employeeloans.php?emp=".$_GET['emp']."&act=adjustment&loan=".$dt['ndex']."\">
					<table width='100%' style='display:none;background-color:#EBF4FA;' id='adj".$dt['ndex']."'>
						<tr><td>
							Date:<select name='det".$dt['ndex']."'>".$optionSelectPayrollCutoffDate."</select>
							Remarks:<input type='Text' name='remark".$dt['ndex']."' value='Adjustment'>&nbsp;Amount:<input type='Text' size='5' style='text-align:right;' readonly='readonly' value='".getDeductionData($dt['ndex'],'current balance')."' name='amt".$dt['ndex']."'><input type='submit' value='GO'><br>
							<i style='color:red;'>Note: The value you will enter will subtract the current remaining balance!</i>
							</td></tr></table>
					</form>
				</td>
			</tr>
			";
	}
	
	$rs = mysql_query("SELECT * FROM employee WHERE isActive='1' ORDER BY lastName",$conn);
	while ($dt = mysql_fetch_assoc($rs)){
		$optionSelectEmployee .= "<option value='".$dt['ndex']."'>".$dt['lastName'].", ".$dt['firstName']."</option>";
		if ($dt['ndex'] == $rs1['employeeId']){
			$optionEmployee_selected = "<option value='".$dt['ndex']."'>".$dt['lastName'].", ".$dt['firstName'];
		}
	}
	$loansqry=mysql_query("SELECT * FROM `loandeductionmaintenance` order by name,type");
	while($lq=mysql_fetch_object($loansqry)){
		if($lq->restrictDouble==1){
			$loanexist=mysql_fetch_object(mysql_query("select * from loan_employee where employeeId='".$_GET['emp']."' and loanId='".$lq->ndex."'"));	
			if(number_format(getDeductionData($loanexist->ndex,'current balance'),2)!=0){
			
			}
			else{
				$optionloans2.="<option value='".$lq->ndex."'>".$lq->name."";
			}
		}
		else{
			$optionloans2.="<option value='".$lq->ndex."'>".$lq->name."";
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
	<script>
		function delete_confirmation(a){
					var r=confirm("Are you sure you want to RESET this to 0?");
					if (r==true)
					  {
					  	window.location.href='tools_employeeloans.php?reset=resetloanbalance&loanid='+a;
					  }
					else
					  {
							return false;
					  }
			}
	
	function searchitems(){
	//alert();
		mynodes=frmitem.serialize();	
		new Ajax.Updater('listitem','tools_employeeloans.php?ser=searching',{
			method: 'get',
			parameters: mynodes
		});	
	}

	</script>
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
	 	<?php if($_GET['emp']){?>
		<table>
			<tr>
			<td  width="415" valign="top">
			 	<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=<?php echo $myact;?>&emp=<?php echo $_GET['emp'];?>" margin="0px;" onSubmit="return checkReqfield(this)" name="frmSystemUser">
				<?php echo $edit_id;?>
				<table cellpadding="3" cellspacing="0" border="0">
					<tr><td colspan="2" align="right"><br /><b>::: 	</b><i>Add Loan</i><hr></td></tr>
					<tr><td width="170">Employee Name</td><td><?php echo $e->lastName.", ".$e->firstName." ".$e->middleName; ?></td></tr>
					<tr><td>Deduction Type</td><td><select name="loanType"><?php echo $optionSelectloanType_selected.$optionloans2;?></select></td></tr>
					<tr><td>Loan Amount</td><td><input type="number" required="required" name="loanAmount" id="loanAmount" step="0.01" min="1" value="<?php echo $rs1['loanAmount']?>"></td></tr>
					<tr><td>No. of Deductions</td><td><input type="number" required="required" name="nOfDeduction" step="0.01" min="1" value="<?php echo $rs1['nOfDeduction']?>"></td></tr>
					<tr><td>Dedution Start</td><td><input type="text" name="dedDateStart" id="DPC_date2" size="14" datepicker="true" datepicker_format="YYYY-MM-DD" value="<?php echo $rs1['dedDateStart']?>"></td></tr>
					<tr	><td>Remarks</td><td><textarea name="remarks" id="remarks"> <?php echo $rs1['remarks']?></textarea></td></tr>
					<tr><td colspan="2" align="right"><button><?php echo $submitButton;?></button></td></tr>
				</table>
				</form>
			</td>
			</tr>
		</table>
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
		<?php } ?>
	<div>
	
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
</div>
</body>
</html>
<?php } ?>