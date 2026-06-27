<?php
ob_start();
session_start();
include("../dbcon.php");
include("../employeefunctions.php");
include("payrollfunctions.php");

// Get last 13th Month CutOff
$rsCutoff = mysql_fetch_assoc(mysql_query("SELECT DISTINCT cutOffDate FROM payroll13thmonth WHERE DATEDIFF( now(), cutOffDate) <  5 ORDER BY cutOffDate DESC LIMIT 1"));

if (!$rsCutoff){
	echo "Editting of Deduction for 13th Month is not available.";
	die();
}

if ($_GET['act'] == "submit"){
	//echo "asdlkh".count($_POST['empNo']);
	foreach($_POST['empNo'] AS $empNoS){
		//echo $empNoS.$_POST['wtax'.$empNoS]."<br>";
		$update = mysql_query("UPDATE payroll13thmonth SET wtax = '".$_POST['wtax'.$empNoS]."', 
														   hospitalBill = '".$_POST['hospitalBill'.$empNoS]."',
														   otherDeduction = '".$_POST['otherDeduction'.$empNoS]."',
														   cashAdvance = '".$_POST['cashAdvance'.$empNoS]."'
																WHERE empNo='".$empNoS."'", $conn);
	}
	header("Location: ?");
}

$sql = "SELECT * FROM payroll13thmonth WHERE cutOffDate='".$rsCutoff['cutOffDate']."'";
$rs =  mysql_query($sql, $conn);
$ln = 0;
while ($r =  mysql_fetch_assoc($rs)){
	$ln++;
	$ctr1s++;
	$empinfo = mysql_fetch_assoc(mysql_query("SELECT * FROM employee WHERE ndex='".$r['empNo']."'", $conn));
	
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	$data .= "<input type='hidden' name='empNo[]' value='".$r['empNo']."'>
			<tr bgcolor='".$bgclr1s."'><td>{$ln}</td>
	     	<td>".$empinfo['lastName'].", ".$empinfo['firstName']."</td>
			<td>".$r['amount13thMonth']."</td>
	      	<td><input type='Text' value='".$r['wtax']."' name='wtax".$r['empNo']."' style='text-align:right;'></td>
		   	<td><input type='Text' value='".$r['hospitalBill']."' name='hospitalBill".$r['empNo']."' style='text-align:right;'></td>
			<td><input type='Text' value='".$r['hospitalBill']."' name='hospitalBill".$r['empNo']."' style='text-align:right;'></td>
			<td><input type='Text' value='".$r['cashAdvance']."' name='cashAdvance".$r['empNo']."' style='text-align:right;'></td></tr>";
}

	//$payrollDateRange = '2014-01-01 to '.$a13MonthCutoff;
	$payrollDateRange = date('M. 1, Y',strtotime(date('Y').'-01-01'))." to ".date('M. 15, Y',strtotime($a13MonthCutoff));

	echo "13TH MONTH DEDUCTION ".strtoupper(date('F Y',strtotime($rsCutoff['cutOffDate'])));
?>
	
     <?php //include("../rptheader.php");?>
     <form action="tools_13thmonth.php?act=submit" method="post">
	 <table width="70%" style="font-family:Arial;font-size:12px;">
	  <thead>
	
	  <tr><td>&nbsp;</td></tr>
	  <tr valign="bottom" align="center">
			<td></td>
	     	<td>NAME</td>
	      	<td>Gross 13th month Pay</td>
		  	<td>Witholding Tax</td>
		   	<td>Hospital Bill</td>
			<td>Cash Advance</td>
			<td>Other Deduction</td>
	  </tr>
	  <tr><td colspan="6"><hr></td></tr>
	  </thead>
	  <tbody>
	  
	 	<?php echo $data;?>
	  </tbody>
		<tr>
				<td colspan="6"><input type="Submit" value="Submit"></td>
		   	<td align="right"><hr></td>
      </table>
	  </form>
	  <?php //include("../rptfooter.php");?>




