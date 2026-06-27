<?php
ob_start();
session_start();
include("../dbcon.php");
include("../myfunctions.php");
?>
     <h2>Length of Service(LOS)</h2>
     <form action="reports/output/los.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
   <table width="100%">
	  <tr><td>
	      Select Employee: <select name="id"><option value="all" selected>ALL<?php echo $optionemployee;?></select><br>
		  Type: <br>&nbsp;&nbsp;<input type="Radio" name="tayp" value="year" checked>Year<br>&nbsp;&nbsp;<input type="Radio" name="tayp" value="month">Month<br>
		  &nbsp;&nbsp;<input type="Radio" name="tayp" value="day">Day<br><br>
		  <td><input type="checkbox" name="dep"> Group by Division/Dept </td>
	  </td>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
     </table>
	<h2>&nbsp;</h2>
     </form>




