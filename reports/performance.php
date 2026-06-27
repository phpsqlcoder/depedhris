<?php
ob_start();
session_start();
include("../dbcon.php");
?>    
     <h2>Performance History</h2>
     <form name="frmrptdd" id="frmrptdd" action="reports/output/performance.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	 <tr>
	 	<td><select name="yer">
			<option value='2014'>2014
			<option value='2015'>2015
			<option value='2016'>2016
			<option value='2017'>2017
			<option value='2018'>2018
			<option value='2019'>2019
		</select></td>	
		<td>Dept: <select name="tdept"><option value='ALL'>ALL<?php echo $optiondept;?></select></td>	
		 <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
		</tr>
	 
      </table>
	<h2>&nbsp;</h2>
     </form>




