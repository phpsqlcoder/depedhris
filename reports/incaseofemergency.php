<?php
ob_start();
session_start();
include("../dbcon.php");
?>
     <h2>Employee Contacts in Case of Emergency</h2>
     <form action="reports/output/incaseofemergency.php" method="post" name="rptfrm" id="rptfrm" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="100%">
	  <tr><td>
	      Select Employee: <select name="id"><option value="all" selected>ALL<?php echo $optionemployee;?></select>  </td>
		  <td><input type="checkbox" name="dep"> Group by Division/Dept </td>
	
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	 
	  </tr>
	  <tr>
	  <td>Dept: <select name="tdept"><option value=''>ALL<?php echo $optiondept;?></select></td>
	  	 <td>Division: <select name="division"><?php echo $optiondivision; ?></select></td>
		 <td><input type="submit" value="Submit"></td>
	  </tr>
     </table>
	<h2>&nbsp;</h2>
     </form>




