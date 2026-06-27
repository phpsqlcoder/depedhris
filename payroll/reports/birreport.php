<?php
ob_start();
session_start();
include("../../dbcon.php");

$ggg = explode('/',$_SERVER['PHP_SELF']);
$rs = mysql_query("SELECT * FROM cutoffdates  ORDER BY payrollDate DESC",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}
?>
     <h2>BIR Report </h2>
     <form name="frmrpt" action="reports/output/<?php echo $ggg[4];?>" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="80%">
	  <tr>
		  <td>Select Month<select name="monthyear"><?php echo $optionmonthyear;?></select> &nbsp;
		 <!--  <input type="checkbox" name="generateFile" value="yeGenerate"> Generate File </td> -->
		  <td><input type="checkbox" name="eksel" value="on"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




