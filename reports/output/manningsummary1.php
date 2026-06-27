<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
$head="";
$esqryh=mysql_query("select distinct employmentStatus from employee where isActive=1 order by employmentStatus");
	
	while($eh=mysql_fetch_object($esqryh)){
		$head.="<td>".$eh->employmentStatus."</td>";
	}
		$head.="<td>Total</td>";
$dqry=mysql_query("select * from dept order by name");
while($d=mysql_fetch_object($dqry)){
	$data.="<tr>
			<td>".$d->name."</td>
	";
	$esqry=mysql_query("select distinct employmentStatus from employee where isActive=1 order by employmentStatus");
	$subtotal=0;
	while($es=mysql_fetch_object($esqry)){
		$cnt=0;

		$cnt=mysql_num_rows(mysql_query("select * from employee where deptId='".$d->ndex."' and employmentStatus='".$es->employmentStatus."' and isActive='1'"));
		$subtotal+=$cnt;
		$data.="<td>".$cnt."</td>";
	}
	$data.="<td>".$subtotal."</td><tr>";

}

?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="manningsummary.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="6" align="center" style="font-size:14px;font-weight:bold;">Manning Summary<br> As of <?php echo date('Y-m-d');?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Dept</td>
		   <?php echo $head;?>
	  </tr>
	  <tr><td colspan="6"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




