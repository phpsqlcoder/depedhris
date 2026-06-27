<?php
ob_start();
session_start();
include("../../dbcon.php");

$rs = mysql_query("SELECT * FROM cutoffdates ORDER BY payrollDate DESC",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}
?>
     <h2>Employee Loan Deduction Report</h2>
     <form name="frmrpt" action="reports/output/loan_employee.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
<table width="80%">
    <tr>
        <td>Start Date</td>
        <td>End Date </td>
        <td>Division</td>
        <td>Type</td>
        <td>Employee</td>
        <td>Loan Type</td>
    </tr>
    <tr>
        <td> <input type="date" name="startdate" required>&nbsp;</td>
        <td><input type="date" name="enddate" required> &nbsp;</td>
        <td><select name="division" style="width:200px;"><?php echo $optiondivision; ?></select></td>
        <td><select name='mbtcCompany'><?php echo $optionMBTCCompany;?></select></td>
        <td><select name='employeeId' style="width:200px;"><?php echo $optionemployee_all;?></select></td>
        <td><select name='tayp'>
        	<?php
        	//leave
		$leave=mysql_query("SELECT * FROM loandeductionmaintenance order by name");
			echo "<option value=''> - ALL -";
		while($rsleave=mysql_fetch_object($leave)){
			echo "<option value='".$rsleave->ndex."'>".$rsleave->name."";
		}
		?>
        </select></td>
    </tr>
    <tr>
        
        <td colspan="2"> <input type="checkbox" name="eksel" value="on"> Result to Excel</td>
        <td><input type="submit" value="Submit"></td>
    </tr>
</table>
	<h2>&nbsp;</h2>
     </form>




