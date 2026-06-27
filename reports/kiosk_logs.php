<?php
ob_start();
session_start();
include("../dbcon.php");

?>
     <h2>Kiosk Logs</h2>
     <form name="frmrpt" action="reports/output/kiosklogs.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




