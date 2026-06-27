<?php
ob_start();
session_start();
include("../../dbcon.php");
?>    
     <h2>Monthly SSS Emergency Loan Report</h2>
     <form name="frmrptdd" id="frmrptdd" action="reports/output/monthly_sssemergency.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	 <tr>
	 	<td><select name="tdept"><option value='ALL'>ALL<?php echo $optiondept;?></select></td>
	<td>Select Month<select name="monthyear"><?php echo $optionmonthyear;?></select></td>	</tr>
	  <tr>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




