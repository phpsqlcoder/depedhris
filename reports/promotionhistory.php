<?php
ob_start();
session_start();
include("../dbcon.php");
?>
     <h2>Promotion History</h2>
     <form action="reports/output/promotionhistory2.php" method="post" id="frms" name="frms" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="100%">
	 <tr>
	 <td><select name="tdiv"><?php echo $optiondivision;?></select></td>
	 <td><select name="tdept"><?php echo $optiondept;?></select></td>
	 

	 </tr>
	 <tr>
	 <td>Start Date:&nbsp;&nbsp;<input type="Text" name="startdates" id="startdates" size="15"><a href="javascript:show_calendar('frms.startdates');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
	 <td>End Date:&nbsp;&nbsp;<input type="Text" name="enddates" id="enddates" size="15"><a href="javascript:show_calendar('frms.enddates');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
	 </tr>
	  <tr>
	  	 
	  <td>
	    <select name="id"><?php echo $optionemployee;?></select>
	  </td>
	  
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




