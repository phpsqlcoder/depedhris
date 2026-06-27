<?php
ob_start();
session_start();
include("../dbcon.php");
?>
     <h2>Medical Record</h2>
     <form action="reports/output/medicalrecord.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="100%">
	  <tr><td>
	      Select Employee: <select name="id"><option value="ALL">ALL<?php echo $optionemployee;?></select>
	  </td>
	  <td>
	      Division: <select name="division"><option value="ALL">ALL<?php echo $optiondivision;?></select>
	  </td>
	  
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




