<?php
ob_start();
session_start();
include("../dbcon.php");

?>
     <h2>Dependents</h2>
     <form name="frmrpt" action="reports/output/dependents.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
   <table width="50%">
			<tr><td><em>Employee Age Bracket:</em></td><td>FROM <input type="Text" name="efrom" size="3" value="0" style="text-align:right;" style="color:gray;" onfocus="if(this.value=='0'){this.value='';}this.style.color='black';"> TO <input type="Text" name="eto" size="3" value="150" style="text-align:right;" style="color:gray;" onfocus="if(this.value=='150'){this.value='';}this.style.color='black';"></td><td><input type="checkbox" name="eksel"> Result to Excel </td></tr>
			<tr><td><em>Dependents Age Bracket:</em></td><td>FROM <input type="Text" name="dfrom" size="3" value="0" style="text-align:right;" style="color:gray;" onfocus="if(this.value=='0'){this.value='';}this.style.color='black';"> TO <input type="Text" name="dto" size="3" value="150" style="text-align:right;" style="color:gray;" onfocus="if(this.value=='150'){this.value='';}this.style.color='black';"></td><td><input type="Submit" value="Generate"></td></tr>
		</table>
	<h2>&nbsp;</h2>
     </form>




