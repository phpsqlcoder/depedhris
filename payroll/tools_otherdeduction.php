<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");
include ("../employeefunctions.php");
$rs = mysql_query("SELECT * FROM cutoffdates ORDER BY payrollDate DESC limit 1",$conn);
$cutOffDates = mysql_fetch_assoc($rs);

if (empty($_POST['cutoffDate'])){
	$_POST['cutoffDate'] = $cutOffDates['payrollDate'];
}

if ($_GET['pageact'] == "updatePayroll"){
	
	for($i=1;$i <= $_POST['numrows']; $i++){

		//echo $_POST['empId'.$i];
		$sqlOtherLoan = "SELECT SUM(amountPaid) amountPaid FROM loanpayments lp 
						LEFT JOIN loansetup ls on ls.ndex=lp.loanSetupId
						WHERE ls.loanType='OTHERS' && lp.datePaid='".$_POST['cutoffDate']."' && lp.EmployeeId='".$_POST['empId'.$i]."'";
		$otherLoan = mysql_fetch_assoc(mysql_query($sqlOtherLoan,$conn));
		
		
		$totalhospitaldeduction=0;
		$totalOtherIncome=0;
		$totalOtherIncome += $otherLoan['amountPaid'];
		//Update hospital deduction
			$updateoutpatienthospital=mysql_query("update payroll_hospital_deduction_data set dAmount='".$_POST['outd_hospital'.$i]."' where payroll_Id=".$_POST['rowNum'.$i]." and hospitalType='OutPatient'");
			$updateinpatienthospital=mysql_query("update payroll_hospital_deduction_data set dAmount='".$_POST['ind_hospital'.$i]."' where payroll_Id=".$_POST['rowNum'.$i]." and hospitalType='InPatient'");
		$totalhospitaldeduction=$_POST['outd_hospital'.$i] + $_POST['ind_hospital'.$i];
		//end hospital
		//update other deduction
			$odqryu=mysql_query("select * from payroll_other_deduction where isActive=1 ORDER by ndex");
			while($odu=mysql_fetch_object($odqryu)){
				$updateod=mysql_query("update payroll_other_deduction_data set dAmount='".$_POST[$odu->ndex.'_'.$i]."' where payroll_Id=".$_POST['rowNum'.$i]." and otherDeductionId=".$odu->ndex."");
				$totalOtherIncome+=$_POST[$odu->ndex.'_'.$i];
			}
		// add other deduction to otherIncome
		//$_POST['cutoffDate']

		//end other deduction
		//$updateCoopPayroll = mysql_query("UPDATE payroll SET d_parkingFee='".$_POST['d_parkingFee'.$i]."',d_other='".$_POST['d_other'.$i]."',d_pnb='".$_POST['d_pnb'.$i]."', d_hospital='".$_POST['d_hospital'.$i]."' WHERE ndex='".$_POST['rowNum'.$i]."' && pay_period='".$_POST['cutoffDate']."'",$conn);
		$updateCoopPayroll = mysql_query("UPDATE payroll SET d_parkingFee='".$_POST['d_parkingFee'.$i]."',d_other='".$totalOtherIncome."',d_pnb='".$_POST['d_pnb'.$i]."', d_hospital='".$totalhospitaldeduction."' WHERE ndex='".$_POST['rowNum'.$i]."' && pay_period='".$_POST['cutoffDate']."'",$conn);
	}
} // end

$sql = "SELECT p.*, e.lastName, e.firstName,e.employeeNo,e.employmentStatus FROM payroll p 
										  LEFT JOIN employee e ON e.ndex=p.empid WHERE e.isActive='1' && p.pay_period='".$_POST['cutoffDate']."' ORDER BY e.lastName";
$rs = mysql_query($sql,$conn);
$countRes = mysql_num_rows($rs);
$cnt = 	0;
while ($dt = mysql_fetch_assoc($rs)){
	$cnt++;
	//Hospital Deduction
	$outPatientAmount=mysql_fetch_object(mysql_query("select * from payroll_hospital_deduction_data where payroll_Id='".$dt['ndex']."' and hospitalType='OutPatient'"));
	$inPatientAmount=mysql_fetch_object(mysql_query("select * from payroll_hospital_deduction_data where payroll_Id='".$dt['ndex']."' and hospitalType='InPatient'"));
	if(!$outPatientAmount->ndex){
			$insertdata=mysql_query("insert into payroll_hospital_deduction_data(`payroll_Id`, `hospitalType`, `dAmount`)VALUES(".$dt['ndex'].",'OutPatient','0.00')");
			$ohamt=0.00;
		}
		else{
			$ohamt=$outPatientAmount->dAmount;
	}
	if(!$inPatientAmount->ndex){
			$insertdata=mysql_query("insert into payroll_hospital_deduction_data(`payroll_Id`, `hospitalType`, `dAmount`)VALUES(".$dt['ndex'].",'InPatient','0.00')");
			$ihamt=0.00;
		}
		else{
			$ihamt=$inPatientAmount->dAmount;
	}
	//end hospital
	if (($cnt % 2) == 1){ $tr_bcg = '#FFFFFF'; } else { $tr_bcg = '#F8F8AC';}
	$rowRes .= "<tr  style='background-color:".$tr_bcg.";margin:8px 8px;'>
					<td style='padding:5px;'>".getID($dt['employmentStatus'],$dt['employeeNo'])."</td>
					<td style='padding:5px;'><input size='4' type='hidden' name='empId".$cnt."' value='".$dt['empid']."'>".$dt['lastName'].", ".$dt['firstName']." </td>
					<td><input type='hidden' name='rowNum".$cnt."' value='".$dt['ndex']."'><input size='4' type='text' name='d_pnb".$cnt."' value='".$dt['d_pnb']."'></td>
					<td><input type='hidden' name='rowNum".$cnt."' value='".$dt['ndex']."'><input size='4' type='text' name='d_parkingFee".$cnt."' value='".$dt['d_parkingFee']."'></td>
					<input type='hidden' name='rowNum".$cnt."' value='".$dt['ndex']."'><input type='hidden' name='d_hospital".$cnt."' value='".$dt['d_hospital']."'>					
					<td><input type='hidden' name='rowNum".$cnt."' value='".$dt['ndex']."'><input size='4' type='text' name='outd_hospital".$cnt."' value='".$ohamt."'></td>
					<td><input type='hidden' name='rowNum".$cnt."' value='".$dt['ndex']."'><input size='4' type='text' name='ind_hospital".$cnt."' value='".$ihamt."'></td>
				";

	$odqry=mysql_query("select * from payroll_other_deduction where isActive=1 ORDER by ndex");
	$odcnt=mysql_num_rows($odqry);
	while($od=mysql_fetch_object($odqry)){
		$odhdr.="";
		$getAmount=mysql_fetch_object(mysql_query("select * from payroll_other_deduction_data where payroll_Id='".$dt['ndex']."' and otherDeductionId=".$od->ndex.""));
		if(!$getAmount->ndex){
			$insertdata=mysql_query("insert into payroll_other_deduction_data(`payroll_Id`, `otherDeductionId`, `dAmount`)VALUES(".$dt['ndex'].",".$od->ndex.",'0.00')");
			$oamt=0.00;
		}
		else{
			$oamt=$getAmount->dAmount;
		}
		$rowRes.="<td><input size='4' onchange=\"computeTotal(".$odcnt.",'d_other".$cnt."',".$cnt.");\" type='text' id='".$od->ndex."_".$cnt."' name='".$od->ndex."_".$cnt."' value='".$oamt."'></td>";
	}	
	//$rowRes.="<td><input type='hidden' name='rowNum".$cnt."' value='".$dt['ndex']."'><input readonly='readonly' type='hidden' name='d_other".$cnt."' value='".$dt['d_other']."'></td>
	$rowRes.="</tr>";
}

//-------------------------------------------------
//-------------------------------------------------
$rs = mysql_query("SELECT * FROM cutoffdates WHERE isLock='0' ORDER BY payrollDate DESC limit 12",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
	if ($dt['payrollDate'] == $_POST['cutoffDate']){
		$optionSelectPayrollCutoffDate_selected = "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
	}
}

$hodqry=mysql_query("select * from payroll_other_deduction where isActive=1 ORDER by ndex");	
	while($hod=mysql_fetch_object($hodqry)){
		$ho.="<td>".$hod->name."</td>";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- <title>HRIS - Davao Doctors Hospital</title> -->
    <link href="../css/styles.css" rel="stylesheet" type="text/css" />
    <link href="../css/facebox.css" rel="stylesheet" type="text/css" />
	
	<script>
		/*function computeTotal(a,b,c){
			var xx=0;
			var y=0;
			for(xx=1;xx<=a;xx++){
				z=xx+'_'+c.toString();
				//z=z.toString();
				//var f=z.toString();
				//y=y+parseFloat(document.getElementById(x).value);
				//alert(x+c.toString());
				alert($(z).value);
				//alert($(z).value);
				//alert(String(z));
			}
			alert(y);
			//document.getElementById(b).value
		}*/
	</script>
</head>
<body>
<?php include "header.php";?>
	<div id="main_content_wrap" class="container_12">
     <h2>Tools >> Other Deduction</h2>   
    <div class="clearfix"> 
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=selectDate" margin="0px;" name="myForm">
				<select name="cutoffDate"><?php echo $optionSelectPayrollCutoffDate_selected.$optionSelectPayrollCutoffDate;?></select>
				<button>Go</button>
			</form>
			<?if ($countRes != 0){?>
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=updatePayroll" margin="0px;" name="myForm">
				<input type="hidden" name="numrows" value="<?php echo $cnt;?>">
				<input type="hidden" name="cutoffDate" value="<?php echo $_POST['cutoffDate'];?>">
				<table cellapdding="10" cellspacing="10" ">
					<tr class="columnheader" bgcolor="000000"><td style="padding:10px;">ID</td><td style="padding:10px;">NAME</td><td>PNB <br>DEDUCTION</td><td>PARKING <br>FEE</td><td>OUTPATIENT <br>DEDUCTION</td><td>INPATIENT <br>DEDUCTION</td><?php echo $ho;?></tr>					
					<?php echo $rowRes;?>
				</table>
				<button>Update</button>
			</form>
			<?}?>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
