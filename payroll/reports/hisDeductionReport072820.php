<?php
ob_start();
session_start();
include("../../dbcon.php");

$rs = mysql_query("SELECT * FROM cutoffdates ORDER BY payrollDate DESC",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}
?>
     <h2>Hospital Bills Deduction Report</h2>
     <form name="frmrpt" action="reports/output/hisDeductionReport.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
     	<tr>
		  <td>Start Date
			</td>
			<td>End Date 
			</td>
			<td>Division</td>
			<td>Type</td>
	  
	  </tr>
	  <tr>
		  <td> <input type="date" name="startdate">&nbsp;
			</td>
			<td><input type="date" name="enddate"> &nbsp;
			</td>
			<td><select name="division"><?php echo $optiondivision; ?></select></td>
			<td><select name='mbtcCompany'><?php echo $optionMBTCCompany;?></select></td>
	  
	  </tr>
	  <tr>
	  	<td><input type="checkbox" name="payroll">Include Payroll</td>
	  	<td><input type="checkbox" name="manual">Include Manual</td>
	  	<td><input type="checkbox" name="eksel" value="on"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>	
	<h2>&nbsp;</h2>
     </form>




