<?php
ob_start();
session_start();
include("../dbcon.php");
?>
     <h2>Report by Division/Dept</h2>
     <form name="frmrpt" action="reports/output/divdept.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="100%">
	  <tr>
	  	<td>Select Division:</td>
		<td><select name="div"><option value="all"> - ALL DIVISION -<?php echo $optiondivision;?></select></td>
	</tr>
	<tr>
	  	<td>Select Department:</td>
		<td><select name="dep"><option value="all"> - ALL DEPARTMENT -<?php echo $optiondept;?></select></td>
	</tr>
	<tr>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




