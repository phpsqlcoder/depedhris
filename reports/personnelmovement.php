<?php
ob_start();
session_start();
include("../dbcon.php");
?>
     <h2>Employment Record Report</h2>
     <form action="reports/output/personnelmovement.php" method="post" name="rptfrm" id="rptfrm" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="100%">
	  <tr><td>
	      Select Employee: <select name="id"><?php echo $optionemployee;?></select>
	  </td></tr>
	<tr>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




