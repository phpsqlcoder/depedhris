<?php
ob_start();
session_start();
include("../../dbcon.php");

$rs = mysql_query("SELECT * FROM cutoffdates ORDER BY payrollDate DESC limit 300",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}

?>
     <h2>Payroll Time Summary Report </h2>
     <form name="frmrpt" action="reports/output/payrollTimeSummary_inday.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	 <tr>
			Payroll Period <select name="PayrollCutoffstart"><?php echo $optionSelectPayrollCutoffDate;?></select> &nbsp;
			Payroll Period <select name="PayrollCutoffend"><?php echo $optionSelectPayrollCutoffDate;?></select> &nbsp;
		</tr>
	  <tr>
		  <td>
				Select Division:<select name="division"><?php echo $optiondivision; ?></select></td>
				<td><select name='mbtcCompany'><?php echo $optionMBTCCompany;?></select></td>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




