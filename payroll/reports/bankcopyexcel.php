<?php
ob_start();
session_start();
include("../../dbcon.php");

$rs = mysql_query("SELECT * FROM cutoffdates ORDER BY payrollDate DESC ",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}

?>
     <h2>Generate Bank Copy (Excel)</h2>
     <form name="frmrpt" action="reports/output/bankcopyexcel.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  <tr>
		  <td>Payroll Period <select name="PayrollCutoff"><?php echo $optionSelectPayrollCutoffDate;?></select> &nbsp;
				<select name="division">
				<option value=""> - Select Division -
				<option value="contractual">Contractual
				<option value="rankandfile">Rank and File
				<option value="resident">Resident
				<option value="sectionheads">Section Heads
				</select></td>

	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




