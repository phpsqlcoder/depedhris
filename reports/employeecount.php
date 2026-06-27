<?php
ob_start();
session_start();
include("../dbcon.php");

?>
     <h2>Manning Counter</h2>
     <form name="frmrpt" action="reports/output/employeecount.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">

     <table width="80%">
	  <tr><td><input type="radio" value="d.name" name="feld">Department</td></tr>
	  <tr><td><input type="radio" value="di.name" name="feld" checked>Division</td></tr>
	  <tr><td><input type="radio" value="u.name" name="feld">Unit</td></tr>
	  <tr><td><input type="radio" value="p.name" name="feld">Position</td></tr>
	  <tr><td><input type="radio" value="e.level" name="feld">Level</td></tr>
	  <tr><td><input type="radio" value="e.employmentStatus" name="feld">Employment Status</td></tr>
	  <tr><td><input type="radio" value="e.bloodType" name="feld">Blood Type</td></tr>
	  <tr><td><input type="radio" value="e.sex" name="feld">Sex</td></tr>
	  <tr><td><input type="radio" value="e.civilStatus" name="feld">Civil Status</td></tr>
	  <tr><td><input type="radio" value="e.payType" name="feld">Pay Type</td></tr>
	  <tr><td><input type="radio" value="e.Taxable" name="feld">Taxable</td></tr>
	  <tr><td><input type="radio" value="e.Religion" name="feld">Religion</td></tr>
	  <tr><td><input type="radio" value="e.Nationality" name="feld">Nationality</td></tr>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr><td>Type of Graph</td></tr>
	  <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="Radio" name="grap" value="FCF_Pie2D" checked>PIE</td></tr>
      <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="Radio" name="grap" value="FCF_Column3D">BAR</td></tr>
      </table>
	  <br><br>
          <table><tr>
     	
	  </tr><tr><td><input type="submit" value="Submit"></td>
     </tr></table>
	<h2>&nbsp;</h2>
     </form>




