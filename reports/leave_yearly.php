<?php
ob_start();
session_start();
include("../dbcon.php");

?>
     <h2>Sick Leave Report</h2>
     <form name="leav" action="reports/output/leave_yearly.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  <tr>
	  	<td>
		  <select name="yr" id="yr" required="required">
		  	<option value="">Select Year</option>
		  	<?php 
			  	for($x=2010; $x<=2030; $x++){
			  		echo '<option value="'.$x.'">'.$x.'</option>';
			  	}
		  	?>		  	
		  </select>
		 </td>
	 </tr><tr>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




