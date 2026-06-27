<?php
ob_start();
session_start();
include("../dbcon.php");
$rs = mysql_query("SELECT * FROM cutoffdates ORDER BY payrollDate DESC",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}
?>
     <h2>Leave (Late) -  Online Application</h2>
     <form name="frmrptdd" action="reports/output/app_leavel.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  <tr>
		<td>Payroll Period <select name="PayrollCutoff"><?php echo $optionSelectPayrollCutoffDate;?></select> &nbsp;
		</td>
	

	  <td><input type="checkbox" name="sex" value="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




