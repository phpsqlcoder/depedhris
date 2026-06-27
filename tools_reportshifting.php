<?php
ob_start();
session_start();
include("dbcon.php");
include ("employeefunctions.php");
//$dd=mysql_Fetch_object(mysql_query("select * from dept where ndex in (".$_SESSION['deptId'].")"));
if($_GET['startDate']){
	$start = strtotime($_GET['startDate']);
	$end = strtotime($_GET['endDate']);
	 $data.="<tr style='color:blue;font-weight:bold;'>
	 			<td>ID</td>
		       <td>Name</td>
			   <td>Date</td>
		       <td>Position</td>
			   <td>Division</td>
			   <td>Dept</td>
			   <td>Shift</td>
			   <td>Meals</td>
			   <td>Food Allergies</td>
			   ";
			   
	/*for ( $ab = $start; $ab <= $end; $ab += 86400 ){
		$detb=date('Y-m-d',$ab);
		$data.="<td align='center'>".date('d',strtotime($detb))."</td>";
	}*/
	$data.="<tr><td colspan='30'><hr></td></tr></tr>";
	$qry="SELECT e.allowedFood,e.allergicFood,e.firstName,s.name as scode,e.lastName,e.middleName,e.employmentStatus,e.employeeNo,p.name as position,d.name as dept,d.divisionId as divid,e.ndex as empid,es.startDate as std from 
	employee_shifting es left join	employee e on e.ndex=es.employeeId
	left join shifting s on s.ndex=es.shiftingId
	left join position p on p.ndex=e.position 
	left join dept d on d.ndex=e.deptId";
	$qry.=" WHERE e.isActive=1";
	if($_GET['dept']!='all'){
		$qry.=" and d.ndex=".$_GET['dept']."";
	}
	if($_GET['shift']!='all'){
		$qry.=" and s.ndex='".$_GET['shift']."'";
	}
	
	$qry.=" and es.startDate between '".$_GET['startDate']."' and '".$_GET['endDate']."' and es.approvedDate<>'0000-00-00 00:00:00' ORDER BY d.name,s.name,e.lastName,e.firstName";
	//echo $qry;
	$exec=mysql_query($qry);
	$var=0;
	while($r=mysql_fetch_object($exec)){
			$di=mysql_fetch_object(mysql_query("select * from division where ndex=".$r->divid.""));
		  $var++;
		  $data.="<tr style='color:black;'>
		  		<td>".getID($r->employmentStatus,$r->employeeNo)."</td>
		       <td style='font-weight:bold;' width='200px'>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
			   <td>".$r->std."</td>
		       <td>".$r->position."</td>
			   <td>".$di->name."</td>
			   <td>".$r->dept."</td>
			   <td>".$r->scode."</td>
			   <td>".str_replace('|',',',$r->allowedFood)."</td>
	       		<td>".str_replace('|',',',$r->allergicFood)."</td>
			   ";
			$data.="</tr>";
	}
	$data.="<tr><td colspan='10'><hr></td></tr><tr><td colspan='5'>Total: ".$var."</td></tr>";
}
$leave=mysql_query("SELECT * FROM `shifting` order by name");
while($rsleave=mysql_fetch_object($leave)){
	$optionsh.="<option value='".$rsleave->ndex."'>".$rsleave->name."";
}
?>
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
     <?php

	?>
     <?php include("reports/rptheader.php");
	 if(!$_GET['startDate']){
	 ?>
	  <form action="tools_reportshifting.php" method="get" name="sadsa">
     <table width="80%" style="font-family:Arial;font-size:12px;">
	
	 <tr>
	 	<td colspan="5">
		<table>
		<tr>
			<td>Dept:<select name="dept"><option value="all"> - ALL DEPT -<?php echo $optiondept;?></select></td>
			<td>Shifting:<select name="shift"><option value="all">- ALL SHIFT -<?php echo $optionsh;?></select></td>
		</tr>
		<tr>
		<td>Start:<input type="Text" name="startDate" id="startDate" size="15" value="<?php echo $_GET['startDate'];?>"><a href="javascript:show_calendar('sadsa.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		<td>End:<input type="Text" name="endDate" id="endDate" size="15" value="<?php echo $_GET['endDate'];?>"><a href="javascript:show_calendar('sadsa.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		
		<td><input type="Checkbox" name="eksels">Result to excel</td>
		<td><input type="Submit" value="View"></td>
		</tr></table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	 </table>
	</form>
	<?php } else {?>
	<?php
if($_GET['eksels']=='on'){
		$filename ="schedule.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
	<table width="100%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="50" align="center" style="font-size:14px;font-weight:bold;">PERSONNEL SCHEDULE SHEET</td>
	  </tr>
	  <tr><td colspan='2'>Date: <?php echo date('F d',strtotime($_GET['startDate']))." - ".date('F d, Y',strtotime($_GET['endDate'])); ?></td></tr>
	  <tr><td>&nbsp;</td></tr>
	  <?php echo $data;?>
	 </table>
	
	  
     
	
	  <?php include("reports/rptfooter.php");?>
 <?php } ?>



