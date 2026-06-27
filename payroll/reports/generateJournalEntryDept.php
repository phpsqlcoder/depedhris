<?php
ob_start();
session_start();
include("../../dbcon.php");

//$rs = mysql_query("SELECT * FROM cutoffdates WHERE payrollDate >= '".date('Y-m-d', strtotime('- 15 days',strtotime(date('Y-m-d'))))."' ORDER BY payrollDate DESC",$conn);
$rs = mysql_query("SELECT * FROM cutoffdates ORDER BY payrollDate DESC",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}

?>
     <h2>Generate Payroll Journal Entry </h2>
     <form name="frmrpt" action="reports/output/generateJournalEntrydept.php" method="post" target="foo1" onsubmit="window.open('', 'foo1', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  <tr>
		  <td>Payroll Period <select name="PayrollCutoff"><?php echo $optionSelectPayrollCutoffDate;?></select> &nbsp;
			 <!-- <td><select name='mbtcCompany'><?php echo $optionMBTCCompany2;?></select></td>
	  <td><input type="checkbox" name="eksel" value="on"> Result to Excel </td> -->
	  <td><input type="submit" value="Generate"></td>
	  </tr>
      </table>	
	<h2>&nbsp;</h2>
     </form>




