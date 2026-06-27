<?php
ob_start();
session_start();
include("../../dbcon.php");
$qry="SELECT e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
$qry.=" WHERE e.ndex=".$_POST['id']." and e.isActive=1";
$r=mysql_fetch_object(mysql_query($qry));
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="reportbysex.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr><td>&nbsp;</td></tr>
	 <tr><td>Name:</td><td colspan="4"><?php echo $r->lastName." , ".$r->firstName." ".$r->middleName;?></td></tr>
	 <tr><td>Position:</td><td colspan="4"><?php echo $r->position;?></td></tr>
	 <tr><td>Dept:</td><td colspan="4"><?php echo $r->dept;?></td></tr>
	 <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td colspan="4" align="center" style="font-size:14px;font-weight:bold;">Performance History<br> </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>

	  <tr><td colspan="4"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




