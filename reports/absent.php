<?php
ob_start();
session_start();
include("../dbcon.php");

?>

     <h2>Absent Report</h2>
     <form name="frmrpts" id="frmrpts" action="reports/output/absent.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">		
	 	<tr>
			<td>Start Date:&nbsp;&nbsp;<input type="Text" name="startdate" id="startdate" size="15"><a href="javascript:show_calendar('frmrpts.startdate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
			<td>End Date:&nbsp;&nbsp;<input type="Text" name="enddate" id="enddate" size="15"><a href="javascript:show_calendar('frmrpts.enddate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>							
		</tr>
		<tr>			
			<td>Employee:<input type="Text" name="emptxt"><font color="red" style="font-style:italic;">Enter First or Last name..</font></td>
			<td>Absent Type:&nbsp;&nbsp;<select name="absent_type">
				<option value='1'>Authorized Absent
				<option value='2'>Unauthorized Absent	
				<option value='3'>No Logs			
			</select></td>		
		</tr>
		<tr>
		 <td><input type="checkbox" name="eksel"> Result to Excel </td>
		 <td>Level:&nbsp;&nbsp;<select name="lvl"><option value='ALL'>ALL<option value='rf'>RANK and FILE<option value='hc'>HEADS and CONFI</select></td>			
		</tr>

	<tr>	
		  <td><input type="submit" value="Submit"></td>
	</tr>
    </table>
	<h2>&nbsp;</h2>
     </form>




