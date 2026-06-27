<?php
ob_start();
session_start();
include("../dbcon.php");
?>    
     <h2>Overtime Report</h2>
     <form name="frmrptdd" id="frmrptdd" action="reports/output/overtime.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="60%">
	 <tr>
	 	<td><select name="tdept"><?php echo $optiondept;?></select></td>
		
		<td><select name="cutoff"><option value="0"> - Select Cutoff -<?php echo $optioncutoff;?></select></td>
		</tr>
	  <tr>
	  <td>Level:&nbsp;<select name="leve"><option value='ALL'>ALL<?php echo $optionrank;?></select></td>	
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




