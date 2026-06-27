<?php
ob_start();
session_start();
include("../dbcon.php");
?>
     <h2>Employment Background Report</h2>
     <form action="reports/output/employmentbackground.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="100%">
	  <tr><td>
	      Select Employee: <select name="id"><option value="all" selected>ALL EMPLOYEES<?php echo $optionemployee;?></select>
	  </td>
	  
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
	  <tr><td>Dept:&nbsp;&nbsp;<select name="dept"><option value='ALL'>ALL<?php echo $optiondept;?></select></td></tr>
	   <tr><td>Position:&nbsp;&nbsp;<select name="positions"><option value='ALL'>ALL<?php echo $optionposition;?></select></td></tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




