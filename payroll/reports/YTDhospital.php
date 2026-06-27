<?php
ob_start();
session_start();
include("../../dbcon.php");

$ggg = explode('/',$_SERVER['PHP_SELF']);

$employeewa=mysql_query("SELECT * FROM employee order by lastName,firstName,middleName");
$optionemployeeWithInactive="<option value=''> - Select Employee -";
while($rsemployee=mysql_fetch_object($employeewa)){
	$optionemployeeWithInactive.="<option value='".$rsemployee->ndex."'>".$rsemployee->lastName." ".$rsemployee->firstName." ".$rsemployee->middleName." ";
}

$numberOfYearToShow = 5;
for ($x = 0;$x <= $numberOfYearToShow; $x++){
	$year = date('Y') - $x;
	$optionSelectPayrollYear .= "<option value='{$year}'> {$year}</option>";
}

?>
     <h2>YTD Hospital Deduction </h2>
     <form name="frmrpt" action="reports/output/<?php echo $ggg[4];?>" method="post" target="foo1" onsubmit="window.open('', 'foo1', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');"> 
		 <!-- <form name="frmrpt" action="reports/output/payslip.php" method="post" target="new"> -->
     <table width="80%">
	  <tr>
		  <td>
				<select name="division"><?php echo $optiondivision; ?></select></td>
				<td><select name='mbtcCompany'><?php echo $optionMBTCCompany;?></select></td>
				<td>
					<select name="patType">
						<option>In/Out Patient</option>
						<option value='IP'>Inpatient</option>

						<option value='OP'>Outpatient</option>
					</select>
				</td>
				<td>
					<select name="tayp">
						<option>Hospital/Doctor</option>
						<option value='Hospital'>Hospital</option>

						<option value='Doctor'>Doctor</option>
					</select>
				</td>
				</tr><tr>
					<td colspan="2"> Start: <input type="date" name="startdate">&nbsp;
			End: <input type="date" name="enddate"> &nbsp;
			</td>
	  <td><input type="checkbox" name="eksel" value="on"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>	
	<h2>&nbsp;</h2>
     </form>




