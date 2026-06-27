<?php
ob_start();
session_start();
include("../../dbcon.php");

$rs = mysql_query("SELECT * FROM payroll13thmonth GROUP BY cutOffDate ORDER BY cutOffDate DESC",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['cutOffDate']."'>".date('F Y',strtotime($dt['cutOffDate']))."</option>";
}

?>
     <h2>13th Month Bank Report</h2>
     <form name="frmrpt" action="reports/output/bankReport13thmonth.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  <tr>
		  <td>
		  Cut-Off <select name="PayrollCutoff"><?php echo $optionSelectPayrollCutoffDate;?></select> &nbsp;
				<!-- <select name="division"><?php echo $optiondivision; ?></select> --></td>
				 <td><select name='mbtcCompany'><?php echo $optionMBTCCompanyBankReport;?></select></td>
	  <td><input type="checkbox" name="sex" value="eksel"> Result to Excel </td>
		
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>	
	<h2>&nbsp;</h2>
     </form>




