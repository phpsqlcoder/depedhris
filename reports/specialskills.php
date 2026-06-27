<?php
ob_start();
session_start();
include("../dbcon.php");
?>
     <h2>Special Skills</h2>
     <form action="reports/output/specialskills.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="100%">
	  <tr><td>
	      Select Employee: <select name="id"><option value="all" selected>ALL<?php echo $optionemployee;?></select><br>
		  Enter Skill:<input type="Text" name="skill">
	  </td>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




