<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../employeefunctions.php");
include ("../myfunctions.php");
//echo $cutoffDate;



	
if ($_GET['pageact'] == "runCutoff"){
	$getInfoCutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE ndex='".$_POST['cutoffDate']."'",$conn));
	if($_GET['act'] == "submitdata"){
		$empqry2=mysql_query("select * from employee where isActive=1 ORDER BY lastName,firstName");
		while($r2=mysql_fetch_object($empqry2)){
			$checkIfOnFreeze2=mysql_fetch_object(mysql_query("select * from loanpayments_freeze where employeeId='".$r2->ndex."' && cutoffDate = '".$getInfoCutoffDate['payrollDate']."'"));
			if($checkIfOnFreeze2->ndex){
				if($_POST[$r2->ndex]!='on'){
					$insert=mysql_query("DELETE from loanpayments_freeze where ndex=".$checkIfOnFreeze2->ndex."");
				}
			}
			else{
				if($_POST[$r2->ndex]=='on'){
					
					$insert=mysql_query("INSERT INTO `loanpayments_freeze`(`cutoffDate`, `employeeId`, `addedBy`, `addedDate`) VALUES ('".$getInfoCutoffDate['payrollDate']."',".$r2->ndex.",'".$_SESSION['nym']."','".date('Y-m-d H:i:s')."')");
					//echo "INSERT INTO `loanpayments_freeze`(`cutoffDate`, `employeeId`, `addedBy`, `addedDate`) VALUES ('".$getInfoCutoffDate['payrollDate']."',".$r2->ndex.",".$_SESSION['nym'].",'".date('Y-m-d H:i:s')."')<br>";
				}
			}
		}
	}
	$empqry=mysql_query("select * from employee where isActive=1 ORDER BY lastName,firstName");
		$d="<tr>
				<td>Select</td>
				<td>ID</td>
				<td>Name</td>
			</tr>
			<tr><td colspan='3'><hr></td></tr>";
	while($r=mysql_fetch_object($empqry)){
		$checkIfOnFreeze=mysql_num_rows(mysql_query("select * from loanpayments_freeze where employeeId='".$r->ndex."' && cutoffDate = '".$getInfoCutoffDate['payrollDate']."'"));
		if($checkIfOnFreeze>0){$freeze='checked';}else{$freeze='';}
		$ctr1s++;
		if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		$d.="<tr style='background-color:".$bgclr1s.";font-size:12px;'>
				<td><input ".$freeze." type='Checkbox' name='".$r->ndex."'></td>
				<td>".getID($r->employmentStatus,$r->employeeNo)."</td>
				<td>".$r->lastName." ".$r->firstName." ".$r->middleName."</td>
		</tr>";
	}
}

$rs = mysql_query("SELECT * FROM cutoffdates where isLock=0 ORDER BY payrollDate DESC limit 2",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['ndex']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}
	
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
     <h2>Freeze Deduction</h2>   
    <div class="clearfix">
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=runCutoff" margin="0px;" name="myForm">
				<select name="cutoffDate"><?php echo $optionSelectPayrollCutoffDate;?></select>
				<button>Select</button>
			</form>
    </div>
	<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>?pageact=runCutoff&act=submitdata" margin="0px;" name="myForm2">
		<input type="Hidden" name="cutoffDate" value="<?php echo $_POST['cutoffDate'];?>">
		<table width="70%">
			<tr><td colspan="3"><h2>Cutoff: <?php echo $getInfoCutoffDate['payrollDate'];?></h2></td></tr>
			<?php echo $d;?>
			<tr><td colspan="3" align="center"><input type="Submit" value="SAVE"></td></tr>
		</table>
	</form>
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
