<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
if($_SESSION['deptId']!=0){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.";die();}
	include("../dbcon.php");
	include("scripts/scripts.php");
	include ("../employeefunctions.php");
	//include ("../options.php");
	include('scripts/my_pagina_class.php');
	$checkIfExistInCompen = mysql_num_rows(mysql_query($sql = "SELECT * FROM employee_compensation WHERE employeeId='".$_GET['id']."'",$conn));
	if (!$checkIfExistInCompen || $checkIfExistInCompen == 0){
		$insertNew = mysql_query("INSERT INTO employee_compensation(employeeId) VALUE('".$_GET['id']."') ",$conn);
	}
	$sql = "SELECT e.*, ec.basicPay, ec.allowance, ec.payTypeNdex, ec.honorarium, ec.cola, ec.withoutPremium, ec.taxType, ec.pagibigSavings, ec.incentive, ec.hazardPay  FROM employee e 
						LEFT JOIN employee_compensation ec ON ec.employeeId=e.ndex WHERE e.ndex='".$_GET['id']."'";
	$qryCompen = mysql_fetch_assoc(mysql_query($sql,$conn));
	//echo $sql.$qryCompen['basicPay'];
	if ($_GET['pageAction'] == 'updateCompen'){
			$q = mysql_fetch_assoc(mysql_query($sql,$conn));
			
			$updateTable = mysql_query("UPDATE employee_compensation SET basicPay='".$_POST['basicPay']."', allowance='".$_POST['allowance']."', payTypeNdex='".$_POST['payTypeNdex']."', honorarium='".$_POST['honorarium']."', cola='".$_POST['cola']."', withoutPremium='".$_POST['withoutPremium']."',taxType='".$_POST['taxType']."',pagibigSavings='".$_POST['pagibigSavings']."', incentive='".$_POST['incentive']."', hazardPay='".$_POST['hazardPay']."' WHERE employeeId='".$_POST['id']."'",$conn);

			foreach($_POST as $Field => $v){
				if ($qryCompen[$Field] != $v && $Field != 'id'){
					echo $Field."=".$v." --->".$q[$Field];
					$insertEditLogs=mysql_query("insert into employee_edit_logs (`fieldName`, `newValue`, `oldValue`, `updatedBy`, `updatedDate`,`effectivityDate`,`employeeId`) VALUES ('".$Field."','".$_POST[$Field]."','".$q[$Field]."','".$_SESSION['fullName']."','".date('Y-m-d H:i:s')."','".$effectivityDate."',".$_GET['id'].")");

					echo "insert into employee_edit_logs (`fieldName`, `newValue`, `oldValue`, `updatedBy`, `updatedDate`,`effectivityDate`,`employeeId`) VALUES ('".$Field."','".$_POST[$Field]."','".$q[$Field]."','".$_SESSION['fullName']."','".date('Y-m-d H:i:s')."','".$effectivityDate."',".$_GET['id'];
				}
			}
			
			header("Location: ?id=". $_POST['id'] ."");
	}
	
//OPTION PAYTYPE
$payType = array("1" => "Monthly","2" => "Daily");
foreach($payType as $payTypeNdex => $payTypeName){
	$option_select_payType .= "<option value=".$payTypeNdex.">".$payTypeName."</option>";
	if ($payTypeNdex == $qryCompen['payTypeNdex']){
		$option_selected_payType .= "<option value=".$payTypeNdex.">".$payTypeName."</option>";
	}
}

//OPTION CCOMPUTE TAX BASE ON
$taxType = array("1" => "Taxtable (Normal)","2" => "10% on Gross");
foreach($taxType as $taxTypeNdex => $taxTypeName){
	$option_select_taxType .= "<option value=".$taxTypeNdex.">".$taxTypeName."</option>";
	if ($taxTypeNdex == $qryCompen['taxType']){
		$option_selected_taxType .= "<option value=".$taxTypeNdex.">".$taxTypeName."</option>";
	}
}


if ($qryCompen['withoutPremium'] == 'on') $checkWithoutPremium = "checked='checked'";
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="../css/styles.css" rel="stylesheet" type="text/css" />
    <link href="../css/facebox.css" rel="stylesheet" type="text/css" />
	 <link href="../css/payroll.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
        <h2>Employees >> Compensation &nbsp;&nbsp;&nbsp;&nbsp;<!--  <button onclick="window.open('employee_add.php','_self');">Add Employee</button> --></h2>
		  <div>
		<form method="POST" action="?pageAction=updateCompen&id=<?php echo $_GET['id'];?>" name="frmCompen">
		<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">			
			
			<table border="1" cellpadding="10" cellspacing="10">
				<tr class="tableForm"><td width="100">Name</td><td><?php echo $qryCompen['lastName'].", ".$qryCompen['firstName']?></td></tr>
				<tr class="tableForm"><td>Pay Type</td><td><select name="payTypeNdex"><?php echo $option_selected_payType.$option_select_payType;?></select></td></tr>
				
				<tr class="tableForm"><td>Basicpay</td><td><input type="text" name="basicPay" value="<?php echo $qryCompen['basicPay'];?>"></td></tr>
				<tr class="tableForm"><td>COLA</td><td><input type="text" name="cola" value="<?php echo $qryCompen['cola'];?>"></td></tr>
				<tr class="tableForm"><td>Allowance</td><td><input type="text" name="allowance" value="<?php echo $qryCompen['allowance'];?>"></td></tr>
				<tr class="tableForm"><td>Pagibig Savings</td><td><input type="text" name="pagibigSavings" value="<?php echo $qryCompen['pagibigSavings'];?>"></td></tr>
				<tr class="tableForm"><td>Incentive</td><td><input type="text" name="incentive" value="<?php echo $qryCompen['incentive'];?>"></td></tr>
				<tr class="tableForm"><td>Honorarium</td><td><input type="text" name="honorarium" value="<?php echo $qryCompen['honorarium'];?>"></td></tr>
				<tr class="tableForm"><td>Hazard Pay</td><td><input type="text" name="hazardPay" value="<?php echo $qryCompen['hazardPay'];?>"></td></tr>
				<tr class="tableForm"><td>Tax Base on</td><td><select name="taxType"><?php echo $option_selected_taxType.$option_select_taxType;?></select></td></tr>
				
				
				<tr class="tableForm"><td colspan="2" align="right"><input type="checkbox" name="withoutPremium" <?php echo $checkWithoutPremium;?>> Without Premium Computation</td></tr>
				<tr><td colspan="2" align="right" class="tableForm"><input type="Submit" value="UPDATE" class="tableForm"></td></tr>
			</table>
<!-- 			<input name="txtsearch" value="Search here.." style="color:gray;" onfocus="if(this.value=='Search here..'){this.value='';}this.style.color='black';"> -->
		</form>
		</div>
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
</div>
</body>
</html>


