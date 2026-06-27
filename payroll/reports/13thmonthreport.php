<?php
ob_start();
session_start();
include("../../dbcon.php");

$ggg = explode('/',$_SERVER['PHP_SELF']);

$rs = mysql_query("SELECT * FROM payroll13thmonth GROUP BY cutOffDate ORDER BY cutOffDate DESC",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['cutOffDate']."'>".date('F Y',strtotime($dt['cutOffDate']))."</option>";
}
?>
     <h2>13th Month Report </h2>
     <form name="frmrpt" action="reports/output/<?php echo $ggg[4];?>" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	 	<!-- <tr>
			<select name="division"><?php echo $optiondivision; ?></select></td> -->
			<td><select name='a13MonthCutoff'><?php echo $optionSelectPayrollCutoffDate;?></select></td>
			<td><select name='mbtcCompany'><?php echo $optionMBTCCompany;?></select></td>
	  		<td><input type="checkbox" name="eksel" value="eksel"> Result to Excel </td>
		  	<td><input type="submit" value="generate"></td>
		</tr>
      </table>	
	<h2>&nbsp;</h2>
     </form>




