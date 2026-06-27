<?php
ob_start();
session_start();
include("../dbcon.php");
?>    
     <h2>Deleted Logs Report</h2>
     <form name="frmrptdd" id="frmrptdd" action="reports/output/deleted_logs.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	 <tr>
	 	<td><select name="tdept" style="width:100px;"><option value='ALL'>ALL<?php echo $optiondept;?></select></td>
	 <td>Start Date:&nbsp;&nbsp;<input type="Text" name="startdates" id="startdates" size="15"><a href="javascript:show_calendar('frmrptdd.startdates');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
	 <td>End Date:&nbsp;&nbsp;<input type="Text" name="enddates" id="enddates" size="15"><a href="javascript:show_calendar('frmrptdd.enddates');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		</tr>
	  <tr>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




