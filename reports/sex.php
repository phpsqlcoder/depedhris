<?php
ob_start();
session_start();
?>
     <h2>Report by Gender</h2>
     <form action="reports/output/sex.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="40%">
	  <tr><td>
	       <input type="Radio" name="sex" value="MALE"> MALE <br>
	       <input type="Radio" name="sex" value="FEMALE"> FEMALE
	  </td>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




