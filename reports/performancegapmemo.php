<?php
ob_start();
session_start();
include("../dbcon.php");
?>
     <h2>Performance and Behavioral Gap Memo</h2>
     <form action="reports/output/performancegapmemo.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
   <table width="100%">
	  <tr><td>
	      Select Employee: <select name="id"><option value='all' selected>ALL<?php echo $optionemployee;?></select>
	  </td></tr>
	  <tr><td><tr><td>Start Date:&nbsp;&nbsp;<input type="Text" name="startdate" id="startdate" size="15"><a href="javascript:show_calendar('rptfrm.startdate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr></td>
	  <td><tr><td>End Date:&nbsp;&nbsp;<input type="Text" name="enddate" id="enddate" size="15"><a href="javascript:show_calendar('rptfrm.enddate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr></td></tr>
	  <tr>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




