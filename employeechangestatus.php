<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
if($_SESSION['ndex']=='141'){ echo "Restricted access!";die();}
include("dbcon.php");
include("scripts/scripts.php");
include ("employeefunctions.php");
if($_GET['act']=='adnew'){
	$em=mysql_fetch_object(mysql_query("select * from employee where ndex='".$_GET['id']."'"));
	//if($_POST['employeestatus']=='Lateral Transfer' || $_POST['employeestatus']=='Promotion' || $_POST['employeestatus']=='Demotion'){
		if($_POST['position'] != ''){
			$newval=$_POST['position'];
			$oldval='Position - '.$em->position;
		
			$insposition=mysql_query("insert into employeechangestatus(employeeId,`changeType`, `newValue`, `effectivityDate`,createdBy,createdDate,remarks) VALUES ('".$_GET['id']."','position','".$newval."','".$_POST['effectivityDate']."','".$_SESSION['fullName']."','".date('Y-m-d')."','".$_POST['reasons']."')");
				
		}

		if($_POST['dept'] != ''){
			$newval=$_POST['dept'];
			$oldval='Dept - '.$em->dept;
		
			$insdept=mysql_query("insert into employeechangestatus(employeeId,`changeType`, `newValue`, `effectivityDate`,createdBy,createdDate,remarks) VALUES ('".$_GET['id']."','deptId','".$newval."','".$_POST['effectivityDate']."','".$_SESSION['fullName']."','".date('Y-m-d')."','".$_POST['reasons']."')");
			
		}

		if($_POST['empstatus'] != ''){
			$newval=$_POST['empstatus'];
			$oldval='Status - '.$em->employmentStatus;
		
			$insdept=mysql_query("insert into employeechangestatus(employeeId,`changeType`, `newValue`, `effectivityDate`,createdBy,createdDate,remarks) VALUES ('".$_GET['id']."','employmentStatus','".$newval."','".$_POST['effectivityDate']."','".$_SESSION['fullName']."','".date('Y-m-d')."','".$_POST['reasons']."')");
			
		}
	if($_POST['employeestatus']=='Lateral Transfer' || $_POST['employeestatus']=='Promotion' || $_POST['employeestatus']=='Demotion' || $_POST['employeestatus']=='Employment Status'){
		
	}
	else{
		$newval=$_POST['employeestatus'];
		$ins=mysql_query("insert into employeechangestatus(employeeId,`changeType`, `newValue`, `effectivityDate`,createdBy,createdDate,`remarks`) VALUES ('".$_GET['id']."','".$_POST['employeestatus']."','".$newval."','".$_POST['effectivityDate']."','".$_SESSION['fullName']."','".date('Y-m-d')."','".$_POST['reasons']."')");
	}
	/*}
	elseif($_POST['employeestatus']=='Employment Status'){
		$newval=$_POST['empstatus'];
		$oldval=$em->employmentStatus.' - '.$em->employeeNo;
	}
	else{
		$newval=$_POST['employeestatus'];
		$oldval='Position - '.$em->position;
	}
	
	*/
//echo "insert into employeechangestatus(employeeId,`changeType`, `newValue`, `effectivityDate`,createdBy,createdDate,`remarks`) VALUES ('".$_GET['id']."','".$_POST['employeestatus']."','".$newval."','".$_POST['effectivityDate']."','".$_SESSION['fullName']."','".date('Y-m-d')."','".$_POST['reasons']."')";
	//die();
	header("location:employeechangestatus.php?aa=saved&id=".$_GET['id']."");
}
if($_GET['aa']=='saved'){
	$msg='<font color="red" size="+2">Employee Status has been set!</font>';
}
if($_GET['act']=='clear'){
	$clear_emp=mysql_query("update employee set isCleared='1' where ndex='".$_GET['id']."'");
	$msg='<font color="red" size="+2">Employee has been cleared!</font>';
}
if($_GET['act']=='awol'){
	$clear_emp=mysql_query("update employee set awol='1' where ndex='".$_GET['id']."'");
	$msg='<font color="red" size="+2">Employee has been tag as Awol!</font>';
}
if($_GET['act']=='30day'){
	$clear_emp=mysql_query("update employee set daynotice='1' where ndex='".$_GET['id']."'");
	$msg='<font color="red" size="+2">Employee has been Successfully Tag!</font>';
}
//compensation
$checkIfExistInCompen = mysql_num_rows(mysql_query($sql = "SELECT * FROM employee_compensation WHERE employeeId='".$_GET['id']."'",$conn));
	if (!$checkIfExistInCompen || $checkIfExistInCompen == 0){
		$insertNew = mysql_query("INSERT INTO employee_compensation(employeeId) VALUE('".$_GET['id']."') ",$conn);
	}
	$sql = "SELECT e.*, ec.basicPay, ec.allowance, ec.payTypeNdex, ec.honorarium, ec.cola, ec.withoutPremium, ec.taxType,d.name as deptname, ec.incentive, ec.hazardPay  FROM employee e
						LEFT JOIN dept d on d.ndex=e.deptId
						LEFT JOIN employee_compensation ec ON ec.employeeId=e.ndex WHERE e.ndex='".$_GET['id']."'";
	$qryCompen = mysql_fetch_assoc(mysql_query($sql,$conn));
	
	if ($_GET['pageAction'] == 'updateCompen'){
			$updateTable = mysql_query("UPDATE employee_compensation SET basicPay='".$_POST['basicPay']."', allowance='".$_POST['allowance']."', payTypeNdex='".$_POST['payTypeNdex']."', honorarium='".$_POST['honorarium']."', cola='".$_POST['cola']."', withoutPremium='".$_POST['withoutPremium']."',taxType='".$_POST['taxType']."', incentive='".$_POST['incentive']."', hazardPay='".$_POST['hazardPay']."' WHERE employeeId='".$_POST['id']."'",$conn);
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
	
//end compensation




$emp=mysql_fetch_object(mysql_query("select * from employee where ndex='".$_GET['id']."'"));

$dtab=mysql_query("DROP TABLE IF EXISTS `emphistory`");
	// Create temporary table
	$tmptable=mysql_query("
	
	CREATE TEMPORARY TABLE `emphistory` (
					`employeeId` INT NOT NULL,
					`tayp` VARCHAR(200) NOT NULL, 
					`oldn` VARCHAR(200) NOT NULL, 
					`newn` VARCHAR(200) NOT NULL, 
					`remarks` VARCHAR(200) NOT NULL,
					`fr` VARCHAR(200) NOT NULL,					
					`createdBy` VARCHAR(200) NOT NULL,		
					`dyt` DATE NOT NULL
	)");
	$tesq=mysql_query("select * from employeechangestatus where employeeId=".$_GET['id']." and changeType in ('Resignation','End of Contract','Separation','Lateral Transfer',
'employmentStatus','Demotion','Promotion','End of Residency Training','Retirement','Termination','Employment Status','deptId','position','employmentStatus','AWOL')");
while($tsq=mysql_fetch_object($tesq)){
	$instsq=mysql_query("insert into emphistory(employeeId,tayp,oldn,newn,remarks,dyt,fr,createdBy)VALUES('".$tsq->employeeId."','".$tsq->changeType."','','".$tsq->newValue."','".$tsq->remarks."','".$tsq->effectivityDate."','employeechangestatus','".$tsq->createdBy."')");
}
//echo "select * from employee_edit_logs where employeeId=".$_GET['id']." and fieldName in ('deptId','position')";
$tetq=mysql_query("select * from employee_edit_logs where employeeId=".$_GET['id']." and fieldName in ('deptId','position')");
while($ttq=mysql_fetch_object($tetq)){
	$insttq=mysql_query("insert into emphistory(employeeId,tayp,oldn,newn,remarks,dyt,fr,createdBy)VALUES('".$ttq->employeeId."','".$ttq->fieldName."','".$ttq->oldValue."','".$ttq->newValue."','','".$ttq->effectivityDate."','employee_edit_logs','".$ttq->updatedBy."')");
}
$c=0;
$p=0;
$d=0;
$sqry=mysql_query("select * from emphistory where employeeId=".$_GET['id']." order by dyt,tayp");
while($r=mysql_fetch_object($sqry)){
	$c++;
	$newvalue=$r->newn;
	$oldvalue=$r->oldn;
	
	
	if($c==1){$tayp=$r->tayp;}
	if($tayp!=$r->tayp){$tayp=$r->tayp;}
	//echo $tayp."<br>";
	//if($r->tayp)
//	$oldvalue=
	if($r->tayp=='Lateral Transfer' || $r->tayp=='Promotion' || $r->tayp=='Demotion' || $r->tayp=='position'){
		$p++;
		$val=mysql_fetch_object(mysql_query("select * from position where ndex='".$r->newn."'"));
		$valo=mysql_fetch_object(mysql_query("select * from position where ndex='".$r->oldn."'"));
		if($p==1){
			$poldvalue=$val->name;
			$oldvalue="";
		}
		else{
			$oldvalue=$poldvalue;
		}
		$newvalue=$val->name;
		
		if($poldvalue!=$val->name){$poldvalue=$val->name;}
	}
	if($r->tayp=='deptId'){
		$d++;			
		$val=mysql_fetch_object(mysql_query("select * from dept where ndex='".$r->newn."'"));
		$valo=mysql_fetch_object(mysql_query("select * from dept where ndex='".$r->oldn."'"));
		if($d==1){
			$doldvalue=$val->name;
			$oldvalue="";
		}
		else{
			$oldvalue=$doldvalue;
		}
		$newvalue=$val->name;
		
		if($doldvalue!=$val->name){$doldvalue=$val->name;}
	}
	$type=$r->tayp;
	if($r->tayp=='deptId'){
		$type='DEPT';
	}
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	$data.="<tr style='background-color:".$bgclr1s.";'>
				<td>".$r->dyt."</td>
				<td>".$type."</td>
				<td>".$oldvalue."</td>
				<td>".$newvalue."</td>
				<td>".$r->remarks."</td>			
				<td>".$r->createdBy."</td>
	</tr>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Employee status</title>
<script type="text/javascript">
	function statchange(a){
		if(a=='Lateral Transfer' || a=='Promotion' || a=='Demotion'){
			$('position').style.display='inline';
			$('dept').style.display='inline';
			$('empstatus').style.display='none';
		}
		else if(a=='Employment Status'){
			$('empstatus').style.display='inline';
			$('position').style.display='none';
			$('dept').style.display='none';
		}
		else{
			$('dept').style.display='none';
			$('position').style.display='none';
			$('empstatus').style.display='none';
		}
	}
</script>
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
</head>
<body>


<table style="font-family:Arial;font-size:12px;" width="100%">
	<tr><td><?php echo $msg;?></td></tr>
	<tr><td align="center"><u><h1>Change Employee Status</h1></u></td></tr>	
</table>
<?php if( $_SESSION['ndex']=='17' || $_SESSION['ndex']=='21' || $_SESSION['ndex']=='22' || $_SESSION['ndex']=='114'){$hyd='block';}else{$hyd='none';}?>
  <div style="width:350px;webkit-border-radius: 8px; -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;background-image: url(images/leavebg.png);	background-repeat: repeat-x;background-color: yellow;display:<?php echo $hyd;?>;">
		<form method="POST" action="?pageAction=updateCompen" name="frmCompen">
		<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">			
			
			<table border="0" style="font-family:Arial;font-size:12px;" align="center">
				<tr><td colspan="10" align="center"><strong>Compensation</strong></td></tr>
				<tr><td colspan="10"><hr></td></tr>
				<tr class="tableForm"><td width="100">Name</td><td><?php echo $qryCompen['lastName'].", ".$qryCompen['firstName']." (".$qryCompen['deptname'].")";?></td></tr>
				<tr class="tableForm"><td>Pay Type</td><td><select name="payTypeNdex"><?php echo $option_selected_payType.$option_select_payType;?></select></td></tr>
				
				<tr class="tableForm"><td>Basicpay</td><td><input type="text" name="basicPay" value="<?php echo $qryCompen['basicPay'];?>"></td></tr>
				<tr class="tableForm"><td>COLA</td><td><input type="text" name="cola" value="<?php echo $qryCompen['cola'];?>"></td></tr>
				<tr class="tableForm"><td>Allowance</td><td><input type="text" name="allowance" value="<?php echo $qryCompen['allowance'];?>"></td></tr>
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
<form name="frmcompo" action="employeechangestatus.php?act=adnew&id=<?php echo $_GET['id'];?>" method="post">
<table style="font-family:Arial;font-size:12px;">
	<tr><td colspan="2" style="color:maroon;"><strong><?php echo getID($emp->employmentStatus,$emp->employeeNo);?>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo $emp->lastName.",".$emp->firstName." ".$emp->middleName;?></strong></td></tr>
	<tr><td><strong>Status:</strong></td><td>
		<select name="employeestatus" onchange="statchange(this.value);">
			<option value=""> - Select New Status -
			<option value="Resignation">Resignation
			<option value="Retirement">Retirement
			<option value="End of Contract">End of Contract
			<option value="End of Residency Training">End of Residency Training
			<option value="Termination">Termination
			<option value="Separation">Separation
			<option value="Lateral Transfer">Lateral Transfer
			<option value="Promotion">Promotion
			<option value="Demotion">Demotion			
			<option value="AWOL">AWOL
			<option value="Employment Status">Employment Status
		</select>
		<select name="position" id="position" style="display:none;"><?php echo $optionposition;?></select>
		<select name="dept" id="dept" style="display:none;"><option value="">- Select Dept -<?php echo $optiondept;?></select>
		<select name="empstatus" id="empstatus" style="display:none;"><?php echo $optionemploymentstatus;?></select>
	</td></tr>
	<tr><td><strong>Reason:</strong>&nbsp;&nbsp;</td><td><select name="reasons" id="reasons"><option value=""> - Select Reason - <?php echo $optionresignation; ?></select></td></tr>
	<tr><td><strong>Effectivity Date:</strong>&nbsp;&nbsp;</td><td><input type="Text" name="effectivityDate" id="effectivityDate" size="15"><a href="javascript:show_calendar('frmcompo.effectivityDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
	<tr><td align="center" colspan="2"><input type="submit" value="SAVE"></td></tr>
	<tr><td colspan="5"><hr></td></tr>
</table>
</form>
<form style="font-family:Arial;font-size:10px;" name="frmcompoclear" action="employeechangestatus.php?act=clear&id=<?php echo $_GET['id'];?>" method="post">
	Cleared: <?php if($emp->isCleared==1){echo "YES";}else{echo "NO <input type='SUBMIT' VALUE='Clear employee'>";}?>
</form>
<form style="font-family:Arial;font-size:10px;" name="frmcompoclears" action="employeechangestatus.php?act=awol&id=<?php echo $_GET['id'];?>" method="post">
	AWOL: <?php if($emp->awol==1){echo "YES";}else{echo "NO <input type='SUBMIT' VALUE='Tag as Awol'>";}?>
</form>
<form style="font-family:Arial;font-size:10px;" name="frmcompocleard" action="employeechangestatus.php?act=30day&id=<?php echo $_GET['id'];?>" method="post">
	Non compliance to 30 day notice: <?php if($emp->daynotice==1){echo "YES";}else{echo "NO <input type='SUBMIT' VALUE='Tag'>";}?>
</form>
<table style="font-family:Arial;font-size:12px;" width="100%">
	<tr style="color:blue;font-weight:bold;">
		<td>Effectivity Date</td>
		<td>Status</td>
		<td>Old Value</td>
		<td>New Value</td>
		<td>Reason</td>		
		<td>Encoded By</td>
	</tr>
	<tr><td colspan="7"><hr></td></tr>

	<?php echo $data;?>
</table>
</body>
</html>
<?php ob_end_flush();?>