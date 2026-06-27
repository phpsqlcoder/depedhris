<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");
$rs = mysql_query("SELECT * FROM cutoffdates ORDER BY payrollDate DESC limit 1",$conn);
$cutOffDates = mysql_fetch_assoc($rs);

if (empty($_POST['cutoffDate'])){
	$_POST['cutoffDate'] = $cutOffDates['payrollDate'];
}
$sql = "SELECT p.*, e.lastName, e.firstName FROM payroll p 
										  LEFT JOIN employee e ON e.ndex=p.empid WHERE e.isActive='1' && p.pay_period='".$_POST['cutoffDate']."' ORDER BY e.lastName";
$rs = mysql_query($sql,$conn);
$countRes = mysql_num_rows($rs);
$cnt = 	0;
while ($dt = mysql_fetch_assoc($rs)){
	$cnt++;
	if (($cnt % 2) == 1){ $tr_bcg = '#FFFFFF'; } else { $tr_bcg = '#F8F8AC';}
	$rowRes .= "<tr  style='background-color:".$tr_bcg.";margin:8px 8px;'>
								<td style='padding:5px;'>".$dt['lastName'].", ".$dt['firstName']."</td>
								<td><input type='hidden' name='rowNum".$cnt."' value='".$dt['ndex']."'><input type='text' name='adj_other".$cnt."' value='".$dt['adj_other']."'></td>
								<td><input type='hidden' name='rowNum".$cnt."' value='".$dt['ndex']."'><input type='text' name='onCallOvertime".$cnt."' value='".$dt['onCallOvertime']."'></td>
								<td><input type='hidden' name='rowNum".$cnt."' value='".$dt['ndex']."'><input type='text' name='adj_13th_mon_pay".$cnt."' value='".$dt['adj_13th_mon_pay']."'></td>
							</tr>";
		//<td><input type='hidden' name='rowNum".$cnt."' value='".$dt['ndex']."'><input type='text' name='hazardPay".$cnt."' value='".$dt['hazardPay']."'></td>
}
$rowRes .= "<input type='hidden' name='rowNum' value='$cnt'>";
if ($_GET['pageact'] == "updatePayroll"){
	for($i=1;$i <= $_POST['rowNum']; $i++){
	//echo $_POST['oth_income'.$i]."ssssssss";
		$updateCoopPayroll = mysql_query("UPDATE payroll SET adj_other='".$_POST['adj_other'.$i]."', onCallOvertime='".$_POST['onCallOvertime'.$i]."', adj_13th_mon_pay ='".$_POST['adj_13th_mon_pay'.$i]."'  WHERE ndex='".$_POST['rowNum'.$i]."'",$conn);
		//echo "UPDATE payroll SET oth_income='".$_POST['oth_income'.$i]."', hazardPay='".$_POST['hazardPay'.$i]."' WHERE ndex='".$_POST['rowNum'.$i]."' <br>";
	}
	header ("Location: ./tools_adjustment.php");
} // end
//-------------------------------------------------
//-------------------------------------------------
$rs = mysql_query("SELECT * FROM cutoffdates WHERE isLock='0' ORDER BY payrollDate DESC limit 12",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
	if ($dt['payrollDate'] == $_POST['cutoffDate']){
		$optionSelectPayrollCutoffDate_selected = "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- <title>HRIS - Davao Doctors Hospital</title> -->
    <link href="../css/styles.css" rel="stylesheet" type="text/css" />
    <link href="../css/facebox.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include "header.php";?>
	<div id="main_content_wrap" class="container_12">
     <h2>Tools >> Adjustments</h2>   
    <div class="clearfix"> 
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=selectDate" margin="0px;" name="myForm">
				<select name="cutoffDate"><?php echo $optionSelectPayrollCutoffDate_selected.$optionSelectPayrollCutoffDate;?></select>
				<button>Go</button>
			</form>
			<?if ($countRes != 0){?>
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=updatePayroll" margin="0px;" name="myForm">
				<table cellapdding="10" cellspacing="10" ">
					<tr class="columnheader" bgcolor="000000"><td style="padding:10px;">NAME</td><td>ADJ/OTH INCOME</td><td>ON CALL DUTY</td><td>13th MONTH PAY</td></tr>
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
