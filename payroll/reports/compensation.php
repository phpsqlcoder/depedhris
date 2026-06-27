<?php
session_start();
include ("../../dbcon.php");
ob_start();

?>
     <h2>Compensation Master File</h2>
     <form action="reports/output/compensation.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
    <table width="40%">
	<tr>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
		 <td><select name='mbtcCompany'><?php echo $optionMBTCCompany;?></select></td>
	  <td><input type="submit" value="Submit"></td>
	 </tr>
    </table>
	<h2>&nbsp;</h2>
    </form>

<?php ob_end_flush();?>

