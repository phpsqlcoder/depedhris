<?php
ob_start();
session_start();
include("../dbcon.php");

$rs = mysql_query("SELECT * FROM cutoffdates ORDER BY payrollDate DESC limit 12",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}

?>
     <h2>Used Leave Report </h2>
     <form name="frmrpta" id="frmrpta" action="reports/output/usedleave.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	 <tr>
	 <td>Start Date:&nbsp;&nbsp;<input type="Text" name="startdate" id="startdate" size="15"><a href="javascript:show_calendar('frmrpta.startdate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
	 <td>End Date:&nbsp;&nbsp;<input type="Text" name="enddate" id="enddate" size="15"><a href="javascript:show_calendar('frmrpta.enddate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>			
	 <td>Leave: <select name='lev'><?php echo $optionleave;?></select></td>
	 </tr>
	  <tr>
		  <td> &nbsp;
				<select name="division"><?php echo $optiondivision; ?></select></td>
				<td><select name='mbtcCompany'><?php echo $optionMBTCCompany;?></select></td>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




