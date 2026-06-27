<?php
ob_start();
session_start();
include("../dbcon.php");

?>
     <h2>Report by Civil Status</h2>
     <form name="frmrpt" action="reports/output/civilstatus.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  <tr>
		  <td>Civil Status: <select name="civilstatus"><?php echo $optioncivilstatus;?></select></td>
	  <td><input type="checkbox" name="sex" value="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




