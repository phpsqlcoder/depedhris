<?php
ob_start();
session_start();
include("../dbcon.php");

?>
     <h2>Leave Report</h2>
     <form name="leav" action="reports/output/leave_monthly.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  <tr>
		  <td>Level: <select name="lvl"><option value="all" selected>ALL
		  <option value="0" selected>Temporary
		  <option value="1,2" selected>Rank and File
		  <option value="3,4,5,6,7,8,9" selected>Heads and Confi
		  </select></td>
		  <td>Start:&nbsp;&nbsp;<input type="Text" name="startdate" id="startdate" size="15"><a href="javascript:show_calendar('leav.startdate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		  <td>End:&nbsp;&nbsp;<input type="Text" name="enddate" id="enddate" size="15"><a href="javascript:show_calendar('leav.enddate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
	 </tr><tr>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




