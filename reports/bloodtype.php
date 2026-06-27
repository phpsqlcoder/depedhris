<?php
ob_start();
session_start();
include("../dbcon.php");

?>
     <h2>Report by Bloodtype</h2>
     <form name="frmrpt" action="reports/output/bloodtype.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  <tr>
		  <td>BloodType: <select name="bloodtype"><?php echo $optionbloodtype;?></select></td>
	  <td><input type="checkbox" name="sex" value="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




