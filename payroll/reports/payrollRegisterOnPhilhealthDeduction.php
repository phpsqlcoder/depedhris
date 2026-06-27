<?php
ob_start();
session_start();
include("../../dbcon.php");

$ggg = explode('/',$_SERVER['PHP_SELF']);

$numberOfYearToShow = 5;
for ($x = 0;$x <= $numberOfYearToShow; $x++){
	$year = date('Y') - $x;
	$optionSelectPayrollYear .= "<option value='{$year}'> {$year}</option>";
}

?>
     <h2>Payroll Register On Philhealth Deduction</h2>
     <form name="frmrpt" action="reports/output/<?php echo $ggg[4];?>" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  <tr>
		  <td>Select Year <select name="PayrollYear"><?php echo $optionSelectPayrollYear;?></select> &nbsp;
				<!-- <select name="division"><?php echo $optiondivision; ?></select> --></td>
				 <td><select name='mbtcCompany'><?php echo $optionMBTCCompany;?></select></td>
	  <td><input type="checkbox" name="eksel" value="on"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>	
	<h2>&nbsp;</h2>
     </form>




