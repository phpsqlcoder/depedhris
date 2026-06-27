<?php
ob_start();
session_start();
include("../../dbcon.php");

$rs = mysql_query("SELECT * FROM cutoffdates ORDER BY payrollDate DESC",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}
$deductionqry=mysql_query("select * from loandeductionmaintenance order by name");
while($d=mysql_fetch_object($deductionqry)){
	$ddata.="&nbsp;&nbsp;&nbsp;<input type='checkbox' name='d".$d->ndex."'>".$d->name."</br>";	
}
?>
     <h2>Payroll Deduction </h2>
     <form name="frmrpt" action="reports/output/payrollDeductionReportnpreview.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  <tr>
		  <td>Payroll Period <select name="PayrollCutoff"><?php echo $optionSelectPayrollCutoffDate;?></select> &nbsp;
				<select name="division"><?php echo $optiondivision; ?></select></td>
				<td><select name='mbtcCompany'><?php echo $optionMBTCCompany;?></select></td>
	  <td><input type="checkbox" name="eksel" value="on"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      <tr><td><strong><br />Choose deductions:</strong><br /><?php echo $ddata;?></td></tr>
      </table>	
	<h2>&nbsp;</h2>
     </form>




