<?php
ob_start();
session_start();
include("../../dbcon.php");

$employeewa=mysql_query("SELECT * FROM employee order by lastName,firstName,middleName");
$optionemployeeWithInactive="<option value=''> - Select Employee -";
while($rsemployee=mysql_fetch_object($employeewa)){
	$optionemployeeWithInactive.="<option value='".$rsemployee->ndex."'>".$rsemployee->lastName." ".$rsemployee->firstName." ".$rsemployee->middleName." ";
}

$rs = mysql_query("SELECT * FROM cutoffdates ORDER BY payrollDate DESC ",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}

?>
     <h2>Employee Compensation History</h2>
     <form name="frmrpt" action="reports/output/eecompensationHistory.php" method="post" target="foo1" onsubmit="window.open('', 'foo1', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');"> 
		 <!-- <form name="frmrpt" action="reports/output/payslip.php" method="post" target="new"> -->
     <table width="80%">
	  <tr>
		  <td><select name='employeeId'><?php echo $optionemployeeWithInactive;?></select></td>
				 
	  	<td><input type="checkbox" name="sex" value="eksel"> Result to Excel </td>
		
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>	
	<h2>&nbsp;</h2>
     </form>




