<?php
ob_start();
session_start();
include("../dbcon.php");
?>
     <h2>Total Duty per Employee</h2>
     <form action="reports/output/duty_employee_details.php" method="post" target="foo" id="frmrpts" name="frmrpts" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="100%" cellspacing="10" cellpadding="10">
     	<tr>
			<td>Start Date:&nbsp;&nbsp;<input type="Text" required name="startdate" id="startdate" size="15"><a href="javascript:show_calendar('frmrpts.startdate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a><br><br></td>
									
		</tr>
		<tr><td>End Date:&nbsp;&nbsp;<input type="Text" required name="enddate" id="enddate" size="15"><a href="javascript:show_calendar('frmrpts.enddate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a><br><br></td></tr>
     	<tr><td>Division:&nbsp;&nbsp;<select name="division"><option value='ALL'>ALL<?php echo $optiondivision;?></select><br><br></td></tr>
     	<tr><td>Dept:&nbsp;&nbsp;<select name="dept"><option value='ALL'>ALL<?php echo $optiondept;?></select><br><br></td></tr>
     	<tr><td>Position:&nbsp;&nbsp;<select name="position"><option value='ALL'>ALL<?php echo $optionposition;?></select><br><br></td></tr>
     	<tr>
			<td><input type="checkbox" name="eksel"> Result to Excel <br><br></td>
			
		</tr>
		<tr><td><input type="submit" value="Submit"></td></tr>

      </table>
	<h2>&nbsp;</h2>
     </form>




