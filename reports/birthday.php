<?php
ob_start();
session_start();
include("../dbcon.php");

?>
     <h2>Birthday for the Month</h2>
     <form name="frmrpt" action="reports/output/birthday.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  <tr>
		  <td>Month: <select name="months"><?php echo $optionmonths;?></select></td>
		  <td>Status: <select name="stat"><option value='all'>ALL<?php echo $optionemploymentstatus;?></select></td>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




