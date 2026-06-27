<?php
ob_start();
session_start();
include("../../dbcon.php");

$sql = "SELECT e.lastName, e.firstName, e.middleName, p.undertime, p.ot_reg, p.ot_exc, p.days_work, p.duty_rd, p.vac_lve, p.man_lve, p.sick_lve, p.days_absent FROM employee e LEFT JOIN payroll p ON p.empid = e.ndex WHERE e.isActive='1' && p.pay_period='".$PayrollCutoff."'";
if ($division){$sql .= " && e.divisionId='".$division."'";}
//echo $division." --".$PayrollCutoff."<br>".$sql.mysql_num_rows(mysql_query($sql));
$sql.=" ORDER BY e.lastName,e.firstName";
$exec=mysql_query($sql);
$var=0;
while($r=mysql_fetch_object($exec)){
     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
     $data.="<tr bgcolor='".$bgclr1s."'>
	       <td>".$var."</td>
	       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
		    <td>".$r->undertime."</td>
	      <td>".$r->days_absent."</td>
				<td>".$r->duty_rd."</td>
				<td>".$r->ot_reg."</td>
				<td>".$r->ot_exc."</td>
				<td>".$r->man_lve."</td>
				<td>".$r->sick_lve."</td>
				<td>".$r->vac_lve."</td>
				<td>".$r->night_prem."</td>
				<td>".$r->days_work."</td>
     </tr>";
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="reportbyposition.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="95%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="12" align="center" style="font-size:14px;font-weight:bold;">Payroll Time Summary Report<br> </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	
	  <tr>
	     	<td>Seq</td>
	      <td>Name</td>
		   	<td>Undertime</td>
	      <td>Absent</td>
				<td>Duty Restday</td>
				<td>OT (Reg)</td>
				<td>OT (Exc)</td>
				<td>Mandatory</td>
				<td>Sick</td>
				<td>Vacation</td>
				<td>Night Premium</td>
				<td>Days Work</td>
	  </tr>
	  <tr><td colspan="12"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




