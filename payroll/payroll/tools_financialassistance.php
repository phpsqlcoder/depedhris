<?php
ob_start();
session_start();
if(!$_SESSION['ndex']){header("location:../login.php");}
include("../dbcon.php");
include('../inc/ps_pagination.php');

$sql = "SELECT e.lastName , e.firstName, m.* FROM financialassistance  m
									LEFT JOIN employee e ON e.ndex=m.employeeId 
											WHERE 1";
if ($_GET['pageact'] == 'add'){
	$result = mysql_query("INSERT INTO financialassistance ( `employeeId` , `amount` , `payrollDate`, `remarks`)
					VALUES ('".$_POST['employeeId']."', '".$_POST['amount']."', '".$_POST['payrollDate']."', '".$_POST['remarks']."')",$conn);
	header ("Location: ".$_SERVER['PHP_SELF']."");
} elseif ($_GET['pageact'] == "edit"){
	$result = mysql_query("UPDATE financialassistance SET employeeId='".$_POST['employeeId']."',
																						 amount='".$_POST['amount']."',
																						 payrollDate='".$_POST['payrollDate']."',
																						 remarks='".$_POST['remarks']."'
																					 WHERE ndex='".$_POST['id']."'",$conn);
	$linkback = explode('?',$_SERVER['PHP_SELF']);
	header ("Location: ".$linkback[0]."");
} elseif ($_GET['pageact'] == "delete"){
	$result = mysql_query("DELETE FROM financialassistance WHERE ndex = '".$_GET['id']."'",$conn);
	header ("Location: ".$_SERVER['PHP_SELF']."");
} elseif ($_GET['pageact'] == "search" && !empty($_POST['search_text'])){
	$sql .= " && dedType LIKE '%".$_POST['search_text']."%'";
}

if (empty($_GET['myact']) && empty($_GET['pageact'])){
	$myact = "add";
	$submitButton = "Save ->";
} elseif ($_GET['myact'] == 'edit'){
	$myact = 'edit';
	$sql1 .= $sql." && m.ndex='".$_POST['id']."'";
	$rs1 = mysql_fetch_assoc(mysql_query($sql1));
	$edit_id = "<input type='hidden' name='id' value='".$_GET['id']."'>";
	$submitButton = "Update ->";
} 

$sql .= " ORDER BY m.payrollDate";
$showPerPage = 15;
$nPageDisplay = 5;
$pager = new PS_Pagination($conn, $sql, $showPerPage, $nPageDisplay, "param1=valu1&param2=value2");
$rs = $pager->paginate();
if(!$rs) $rs = mysql_query($sql,$conn);
$cnt = 0;
//$rs = mysql_query($sql,$conn); 
while ($dt = mysql_fetch_assoc($rs)){
	$cnt++;
	if ($cnt == 1){ $tr_bcg = "row1"; } else { $tr_bcg = "row2"; $cnt = 0;}
		$edit = "<a href='?myact=edit&id=".$dt['ndex']."'>Edit</a> ";
		$delete = "<a href='?pageact=delete&id=".$dt['ndex']."'>Delete</a> ";
		//$post = "<a href='?myact=postloan&id=".$dt['ndex']."'>post</a> ";
		//$post .= " | ".$edit." | ";
	//$viewloans = "<a href='?myact=viewloans&id=".$dt['ndex']."'>view loans</a> ";
	$chargeButton = '';

	$viewSystemDepartmentList .= "<tr class='".$tr_bcg."'>
			<td>".$dt['lastName'].", ".$dt['firstName']."</td>
			<td align='right'>".date('F d, Y',strtotime($dt['payrollDate']))." </td>
			<td align='right'>".$dt['amount']." </td>
			<td align='right'>".$delete."</td>
		</tr>";
}

$rs = mysql_query("SELECT * FROM employee WHERE isActive='1' ORDER BY lastName",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectEmployee .= "<option value='".$dt['ndex']."'>".$dt['lastName'].", ".$dt['firstName']."</option>";
	if ($dt['ndex'] == $rs1['employeeId']){
		$optionEmployee_selected = "<option value='".$dt['ndex']."'>".$dt['lastName'].", ".$dt['firstName'];
	}
}

if ($myact == 'add'){
	for ($i=1;$i<10;$i++){
		$monthNum = ceil($i / 2) - 1;
		if(($i % 2) == 1){
			if(date('Y-m-15',strtotime('+ '.$monthNum.' month',strtotime(date('Y-m-d')))) > date('Y-m-d')){
				$nextPayrollDate =  date('Y-m-15',strtotime('+ '.$monthNum.' month',strtotime(date('Y-m-d'))));
			}	
		} else {
			if(date('Y-m-t',strtotime('+ '.$monthNum.' month',strtotime(date('Y-m-d')))) > date('Y-m-d')){
				$nextPayrollDate = date('Y-m-t',strtotime('+ '.$monthNum.' month',strtotime(date('Y-m-d'))));	
			}
		}
		$rowCount = mysql_num_rows(mysql_query("SELECT * FROM financialassistance WHERE payrollDate >= '".$nextPayrollDate."' ORDER BY payrollDate DESC LIMIT 1",$conn));
		if ($rowCount == 0 && $nextPayrollDate != ''){ 
			break;
		}
	}
	$rs1['payrollDate'] = $nextPayrollDate;
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
   <h2>Tools >> Financial Assistance &nbsp;&nbsp;&nbsp;&nbsp; </h2> 
	 <div class="clearfix">
	 	<table cellpadding="0" cellspacing="0" border="0" width="1020">
			<td  width="415" valign="top">
			 	<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=<?php echo $myact;?>" margin="0px;" onSubmit="return checkReqfield(this)" name="frmSystemUser">
				<?php echo $edit_id;?>
				<table cellpadding="3" cellspacing="0" border="0">
					<tr><td colspan="2" align="right"><br /><b>::: 	</b><i>Add Financial Assistance</i><hr></td></tr>
					<tr><td width="170">Employee Name</td><td><select name="employeeId"><?php echo $optionEmployee_selected.$optionSelectEmployee;?></select></td></tr>
					<tr><td>Amount</td><td><input type="text" name="amount" value="<?php echo $rs1['amount']?>"></td></tr>
					<tr><td>Payroll Date</td><td><input type="text" name="payrollDate" value="<?php echo $rs1['payrollDate'];?>"></select></td></tr>
					<tr	><td>Remarks</td><td><textarea name="remarks"> <?php echo $rs1['remarks']?></textarea></td></tr>
					<tr><td colspan="2" align="right"><button><?php echo $submitButton;?></button></td></tr>
				</table>
				</form>
			</td>
			<td width="20"></td>
			<td valign="top">
				<table cellpadding="0" cellspacing="0" border="0" width="90%">
					<tr><td colspan="2" align="right"><br /><b>:::</b><i> List of Financial Assistance Created</i><hr></td></tr>
					<tr bgcolor="#e6e0df">
						<td colspan="2" align="right">
							<!-- <table cellpadding="0" width="168" height="28" style="background: url(images/searchInput.png) no-repeat;">
								<td> &nbsp 
									<input type="text" name="searchText" value="search name here" class="srchblah" onfocus="if (this.value == 'search name here') {this.value = '';}" onblur="if (this.value == '') {this.value = 'search name here';}"  
									onkeyup="sampleSearch(this,'tools_employeeloansAjax.php','displayeeloanlistList')" />
								</td>
							</table> -->
						</td>
					</tr>
					<tr>
						<td colspan="2" align="left">
							<div id="displayeeloanlistList">
							<table cellpadding="5" cellspacing="0" border="0" width="100%">
								<tr class="columnheader"><td>Employee Name</td><td align="right">Payroll Date</td><td align="right">Amount</td><td align="right">Action</td></tr>
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
