<?php
ob_start();
session_start();
include("../dbcon.php");

?>
     <h2>Dependents (Health Benefits)</h2>
     <form name="frmrpt" action="reports/output/dependents_health.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
   <table width="50%">
			<tr><td><input type="checkbox" name="eksel"> Result to Excel </td></tr>
			<tr><td><input type="Submit" value="Generate"></td></tr>
		</table>
	<h2>&nbsp;</h2>
     </form>




