<?php
ob_start();
session_start();
include("../dbcon.php");

?>
     <h2>Leave Ledger</h2>
     <form name="frmrpt" action="reports/output/resignation.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  <tr>
	  		<td><select name="emp"><option value="all"> - Select Employee - <?php echo $optionemployee;?></select></td>
		  <td>Level: <select name="lvl"><option value="all" selected>ALL
		  <option value="0">Temporary
		  <option value="1,2">Rank and File
		  <option value="3,4,5,6,7,8,9">Heads and Confi
		  </select></td>
		  
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




