<?php
ob_start();
session_start();
include("../../dbcon.php");

$ggg = explode('/',$_SERVER['PHP_SELF']);

$employeewa=mysql_query("SELECT * FROM employee order by lastName,firstName,middleName");
$optionemployeeWithInactive="<option value=''> - Select Employee -";
while($rsemployee=mysql_fetch_object($employeewa)){
	$optionemployeeWithInactive.="<option value='".$rsemployee->ndex."'>".$rsemployee->lastName." ".$rsemployee->firstName." ".$rsemployee->middleName." ";
}

$numberOfYearToShow = 20;
for ($x = 0;$x <= $numberOfYearToShow; $x++){
	$year = date('Y') - $x;
	$optionSelectPayrollYear .= "<option value='{$year}'> {$year}</option>";
}

?>
     <h2>Employee YTD 13th Month Payroll Register  </h2>
     <form name="frmrpt" action="reports/output/<?php echo $ggg[4];?>" method="post" target="foo1" onsubmit="window.open('', 'foo1', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');"> 
		 <!-- <form name="frmrpt" action="reports/output/payslip.php" method="post" target="new"> -->
     <table width="80%">
	  <tr>
		  <td>Cut-Off <select name="PayrollYear"><?php echo $optionSelectPayrollYear;?></select> &nbsp;
			<!--<select name="division"><?php echo $optiondivision; ?></select></td>-->
			<!--<td><select name='mbtcCompany'><?php echo $optionMBTCCompany;?></select></td>-->
		</tr>
		<tr>
			<td><select name='employeeId'><?php echo $optionemployee_all;?></select></td>
	  	<td><input type="checkbox" name="sex" value="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>	
	<h2>&nbsp;</h2>
     </form>




