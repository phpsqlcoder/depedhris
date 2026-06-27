<?php
ob_start();
session_start();
include("dbcon.php");
include("employeefunctions.php");
date_default_timezone_set("Asia/Manila");
//$dd=mysql_Fetch_object(mysql_query("select * from dept where ndex in (".$_SESSION['deptId'].")"));
if($_GET['startDate']){
	$start = strtotime($_GET['startDate']);
	$end = strtotime($_GET['endDate']);
	 $data.="<tr style='color:blue;font-weight:bold;'>
	 			<td>ID</td>
		       <td>Name</td>
		       <td>Position</td>
			   <td>Dept</td>
			   ";
			   
	for ( $ab = $start; $ab <= $end; $ab += 86400 ){
		$detb=date('Y-m-d',$ab);
		$data.="<td align='center'>".date('d',strtotime($detb))."</td>";
	}
	$data.="<tr><td colspan='30'><hr></td></tr></tr>";
	$qry="SELECT e.firstName,e.lastName,e.middleName,e.employeeNo,e.employmentStatus,p.name as position,d.name as dept,e.ndex as empid from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
	$qry.=" WHERE e.isActive=1";
	if($_GET['dept']!='all'){
		$qry.=" and d.ndex=".$_GET['dept']."";
	}
	$qry.=" ORDER BY d.name,e.lastName,e.firstName";
	$exec=mysql_query($qry);
	$var=0;
	while($r=mysql_fetch_object($exec)){
		  $var++;
		  $data.="<tr style='color:black;'>
		  		<td>".getID($r->employmentStatus,$r->employeeNo)."</td>
		       <td style='font-weight:bold;' width='200px'>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
		       <td>".$r->position."</td>
			   <td>".$r->dept."</td>
			   ";
	     
		for ( $a = $start; $a <= $end; $a += 86400 ){
			$ctr1s++;
		    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
			$det=date('Y-m-d',$a);
			$shift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$r->empid." and '".$det."' between startDate and endDate"));
			$lve=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId=".$r->empid." and '".$det."' between startDate and endDate"));
			$rd=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$r->empid." and '".$det."' between startDate and endDate"));
			if($shift->shiftingId){
				$rsleave=mysql_fetch_object(mysql_query("select CASE timeIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeIn),'%H:%i %p') END AS tymIn, 
				CASE breakOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakOut),'%H:%i %p') END as brekOut,
				CASE breakIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakIn),'%H:%i %p') END as brekIn,
				CASE timeOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeOut),'%H:%i %p') END as tymOut from shifting where ndex=".$shift->shiftingId.""));
				$stymin=substr($rsleave->tymIn,0,2);
				$sbreakout=substr($rsleave->brekOut,0,2);
				$sbreakin=substr($rsleave->brekIn,0,2);
				$stymout=substr($rsleave->tymOut,0,2);
				if($stymin>=13){$ltymin=($stymin-12).substr($rsleave->tymIn,2);}else{$ltymin=$rsleave->tymIn;}
				if($sbreakout>=13){$lbreakout=($sbreakout-12).substr($rsleave->brekOut,2);}else{$lbreakout=$rsleave->brekOut;}
				if($sbreakin>=13){$lbreakin=($sbreakin-12).substr($rsleave->brekIn,2);}else{$lbreakin=$rsleave->brekIn;}
				if($stymout>=13){$ltymout=($stymout-12).substr($rsleave->tymOut,2);}else{$ltymout=$rsleave->tymOut;}
				if($lbreakout!=''){$brk="&nbsp;".$lbreakout."&nbsp;".$lbreakin;}
				else{$brk="";}
				$optionsh="<td style='font-size:12px;font-family:Agency FB;'>".$ltymin."".$brk."&nbsp;".$ltymout."</td>";
				
				//$optionsh=$sc->name;
				
			}
			elseif($lve->leaveId){
				$lv=mysql_fetch_object(mysql_query("select * from `leave` where ndex=".$lve->leaveId.""));
				//$optionsh=$lv->code;
				$optionsh="<td>".$lv->code."</td>";
			}
			elseif($rd->ndex){
				//$optionsh="OFF";
				$optionsh="<td>OFF</td>";
			}
			else{
				$optionsh="<td>NOT SET</td>";
			}
			$data.=$optionsh;
			// bgcolor='".$bgclr1s."'
			/*$data.="<tr>
				<td>&nbsp;</td>
		       <td>".$det."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$optionsh."</td>
	    	 </tr>";*/
		}
		$data.="</tr>";
	}
	$data.="<tr><td colspan='30'><hr></td></tr><tr><td colspan='5'>Total: ".$var."</td></tr>";
}
?>
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
     <?php

	?>
     <?php include("reports/rptheader.php");
	 if(!$_GET['startDate']){
	 ?>
	  <form action="tools_reportshiftingalldept.php" method="get" name="sadsa">
     <table width="80%" style="font-family:Arial;font-size:12px;">
	
	 <tr>
	 	<td colspan="5">
		<table><tr>
		<td>Dept:<select name="dept"><option value="all"> - ALL DEPT -<?php echo $optiondept;?></select></td>
		<td>Start Date:<input type="Text" name="startDate" id="startDate" size="15" value="<?php echo $_GET['startDate'];?>"><a href="javascript:show_calendar('sadsa.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		<td>End Date:<input type="Text" name="endDate" id="endDate" size="15" value="<?php echo $_GET['endDate'];?>"><a href="javascript:show_calendar('sadsa.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
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



