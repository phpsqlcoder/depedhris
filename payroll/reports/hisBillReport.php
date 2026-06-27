<?php
ob_start();
session_start();
include("../../dbcon.php");

$rs = mysql_query("SELECT * FROM cutoffdates ORDER BY payrollDate DESC",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}
?>
     <h2>Hospital Bill Balance Report</h2>
     <form name="frmrpt" action="reports/output/hisBillReport.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  <tr>
		  <td>
				<select name="division"><?php echo $optiondivision; ?></select></td>
				<td><select name='mbtcCompany'><?php echo $optionMBTCCompany;?></select></td>
				<td>
					<select name="patType">
						<option>In/Out Patient</option>
						<option value='IP'>Inpatient</option>

						<option value='OP'>Outpatient</option>
					</select>
				</td>
				<td>
					<select name="tayp">
						<option>Hospital/Doctor</option>
						<option value='Hospital'>Hospital</option>

						<option value='Doctor'>Doctor</option>
					</select>
				</td>
				</tr><tr>
					<td colspan="2"> Start: <input type="date" name="startdate">&nbsp;
			End: <input type="date" name="enddate"> &nbsp;
			</td>
	  <td><input type="checkbox" name="eksel" value="on"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>	
	<h2>&nbsp;</h2>
     </form>




