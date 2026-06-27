<?php
ob_start();
session_start();

if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include ("employeefunctions.php");
$dar=explode(",",$_SESSION['deptId']);
$dd=mysql_Fetch_object(mysql_query("select * from dept where ndex in (".$_SESSION['deptId'].")"));
function removear($are){
	$remar="";
	foreach($are as $nare){
		if($nare!=73){
			$remar.=$nare.",";
		}
	}
	$remar=rtrim($remar,",");
return $remar;
}
if($_GET['act']=='aprob'){
	if (in_array("73", $dar)){
		if(count($dar)>2){
			$addqry2=" and (em.approvingOfficer=".$_SESSION['ndex']." OR em.deptId in (".removear($dar)."))";
		}
		else{
			$addqry2=" and em.approvingOfficer=".$_SESSION['ndex']."";
		}
		$aprovsh=mysql_query("update employee_shifting e left join employee em on em.ndex=e.employeeId set approvedBy='".$_SESSION['fullName']."',approvedDate='".date('Y-m-d H:i:s')."' where e.startDate>='".$_GET['startDate']."' and e.endDate<='".$_GET['endDate']."' ".$addqry2."");
		$aprovsh=mysql_query("update employee_restday e left join employee em on em.ndex=e.employeeId set approvedBy='".$_SESSION['fullName']."',approvedDate='".date('Y-m-d H:i:s')."' where e.startDate>='".$_GET['startDate']."' and e.endDate<='".$_GET['endDate']."' ".$addqry2."");
		$aprovsh=mysql_query("update employee_leave e left join employee em on em.ndex=e.employeeId set approvedBy='".$_SESSION['fullName']."',approvedDate='".date('Y-m-d H:i:s')."' where e.startDate>='".$_GET['startDate']."' and e.endDate<='".$_GET['endDate']."' ".$addqry2."");
	}
	else{
		//echo "2";
		$aprovsh=mysql_query("update employee_shifting e left join employee em on em.ndex=e.employeeId set approvedBy='".$_SESSION['fullName']."',approvedDate='".date('Y-m-d H:i:s')."' where e.startDate>='".$_GET['startDate']."' and e.endDate<='".$_GET['endDate']."' and em.deptId in (".$_GET['depdep'].")");
		$aprovsh=mysql_query("update employee_restday e left join employee em on em.ndex=e.employeeId set approvedBy='".$_SESSION['fullName']."',approvedDate='".date('Y-m-d H:i:s')."' where e.startDate>='".$_GET['startDate']."' and e.endDate<='".$_GET['endDate']."' and em.deptId in (".$_GET['depdep'].")");
		$aprovsh=mysql_query("update employee_leave e left join employee em on em.ndex=e.employeeId set approvedBy='".$_SESSION['fullName']."',approvedDate='".date('Y-m-d H:i:s')."' where e.startDate>='".$_GET['startDate']."' and e.endDate<='".$_GET['endDate']."' and em.deptId in (".$_GET['depdep'].")");
	}
}

if($_GET['startDate']){
	date_default_timezone_set("Asia/Manila");
	$start = strtotime($_GET['startDate']);
	$end = strtotime($_GET['endDate']);
	 $data.="<tr style='color:blue;font-weight:bold;'>
	 			<td>ID</td>
		       <td>Name</td>
		       <td>Position</td>
			   <td>Dept</td>";
			   
	for ( $ab = $start; $ab <= $end; $ab += 86400 ){
		$detb=date('Y-m-d',$ab);
		$data.="<td align='center'>".date('M-d',strtotime($detb))."</td>";
	}
	$data.="<tr><td colspan='30'><hr></td></tr></tr>";
	if($_GET['depsel']!='all'){
		$adqry=" and d.ndex=".$_GET['depsel']."";
		$dip=$_GET['depsel'];
	}
	else{
		$adqry="";
		$dip=$_SESSION['deptId'];
	}
	$qry="SELECT e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.ndex as empid,e.employmentStatus,e.employeeNo from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
	if (in_array("73", $dar)){
		if(count($dar)>2){
			$addqry=" and (e.approvingOfficer=".$_SESSION['ndex']." OR e.deptId in (".removear($dar)."))";
		}
		else{
			$addqry=" and e.approvingOfficer=".$_SESSION['ndex']."";
		}
		
		if($_SESSION['ndex']==111){
			$qry.=" WHERE e.isActive=1 ".$adqry."";
		}
		else{
			$qry.=" WHERE e.isActive=1 ".$adqry." ".$addqry."";
		}
	}
	else{
	 	$qry.=" WHERE e.isActive=1 ".$adqry." and d.ndex in (".$_SESSION['deptId'].")";
	}
	$qry.=" ORDER BY d.ndex,e.lastName,e.firstName";
echo $qry;
	$exec=mysql_query($qry);
	$var=0;
	while($r=mysql_fetch_object($exec)){
		  $var++;
		  $data.="<tr style='color:black;'>
		  		<td>".getID($er->employmentStatus,$r->employeeNo)."</td>
		       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
		       <td>".$r->position."</td>
			   <td>".$r->dept."</td>
			   ";
	     	date_default_timezone_set("Asia/Manila");
		for ( $a = $start; $a <= $end; $a += 86400 ){
			$ctr1s++;
		    if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
			$det=date('Y-m-d',$a);
			$shift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$r->empid." and '".$det."' between startDate and endDate"));
			$lve=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId=".$r->empid." and '".$det."' between startDate and endDate"));
			$rd=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$r->empid." and '".$det."' between startDate and endDate"));
			if($shift->approvedDate!='0000-00-00 00:00:00'){$remark="color:maroon;";}else{$remark="color:gray;";}
			if($lve->approvedDate!='0000-00-00 00:00:00'){$remark2="color:maroon;";}else{$remark2="color:gray;";}
			if($rd->approvedDate!='0000-00-00 00:00:00'){$remark3="color:maroon;";}else{$remark3="color:gray;";}
			if($shift->shiftingId){
				$rsleave=mysql_fetch_object(mysql_query("select CASE timeIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeIn),'%H:%i %p') END AS tymIn, 
				CASE breakOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakOut),'%H:%i %p') END as brekOut,
				CASE breakIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakIn),'%H:%i %p') END as brekIn,
				CASE timeOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeOut),'%H:%i %p') END as tymOut from shifting where ndex=".$shift->shiftingId.""));
				$stymin=substr($rsleave->tymIn,0,2);
				$sbreakin=substr($rsleave->brekIn,0,2);
				$sbreakout=substr($rsleave->brekOut,0,2);
				$stymout=substr($rsleave->tymOut,0,2);
				if($stymin>=13){$ltymin=($stymin-12).substr($rsleave->tymIn,2);}else{$ltymin=$rsleave->tymIn;}
				if($sbreakout>=13){$lbreakout=($sbreakout-12).substr($rsleave->brekOut,2);}else{$lbreakout=$rsleave->brekOut;}
				if($sbreakin>=13){$lbreakin=($sbreakin-12).substr($rsleave->brekIn,2);}else{$lbreakin=$rsleave->brekIn;}
				if($stymout>=13){$ltymout=($stymout-12).substr($rsleave->tymOut,2);}else{$ltymout=$rsleave->tymOut;}
				if($lbreakout!=''){$brk="&nbsp;".$lbreakout."&nbsp;".$lbreakin;}
				else{$brk="";}
				$optionsh="<td style='font-size:11px;font-family:Agency FB;".$remark."'>".$ltymin."".$brk."&nbsp;".$ltymout."</td>";
				
				//$optionsh=$sc->name;
				
			}
			elseif($lve->leaveId){
				$lv=mysql_fetch_object(mysql_query("select * from `leave` where ndex=".$lve->leaveId.""));
				//$optionsh=$lv->code;
				$optionsh="<td style='".$remark2."'>".$lv->code."</td>";
			}
			elseif($rd->ndex){
				//$optionsh="OFF";
				$optionsh="<td style='".$remark3."'>OFF</td>";
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
}
$dept=mysql_query("SELECT * FROM dept WHERE status<>1 and ndex in (".$_SESSION['deptId'].") order by name");
while($rsdept=mysql_fetch_object($dept)){
	$optiondepts.="<option value='".$rsdept->ndex."'>".$rsdept->name."";
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>DTR System - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include "headerperdept.php";?>
<div id="main_content_wrap" class="container_12">

<div id="rcont">
  <h2><strong>Approve Shifting</strong></h2>
 <div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
     <?php

	?>
     <?php
	 if(!$_GET['startDate']){
	 ?>
	  <form action="tools_approveshiftingperdept.php" method="get" name="sadsa">
     <table width="100%" style="font-family:Arial;font-size:12px;">
	
	 <tr>
	 	<td colspan="5">
		<table><tr>
		<td>Start Date:<input type="Text" name="startDate" id="startDate" size="15" value="<?php echo $_GET['startDate'];?>"><a href="javascript:show_calendar('sadsa.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		<td>End Date:<input type="Text" name="endDate" id="endDate" size="15" value="<?php echo $_GET['endDate'];?>"><a href="javascript:show_calendar('sadsa.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		<td>Select Dept: <select name="depsel"><option value="all">-All Assigned Dept-<?php echo $optiondepts;?></select></td>
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
		$filename =$dd->name."schedule.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
	<form name="frmaproveshfting" action="tools_approveshiftingperdept.php" method="get">
	<table width="150%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="50" align="center" style="font-size:14px;font-weight:bold;">PERSONNEL SCHEDULE SHEET</td>
	  </tr>
	   <tr><td colspan="5" style="font-weight:bold;">
	   <?php
	   	
	   ?>
	   <input type="Hidden" name="act" value="aprob">
	   <input type="Hidden" name="startDate" value="<?php echo $_GET['startDate'];?>">
	     <input type="Hidden" name="endDate" value="<?php echo $_GET['endDate'];?>">
		 <input type="Hidden" name="depdep" value="<?php echo $dip;?>">
	  Legend: <font color="maroon">Maroon</font> = Approved&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <font color="gray">Gray = Unapprove</font><br><br><br></td></tr>
	  
	  <tr><td colspan='10' style="color:black;font-weight:bold;">Date: <?php echo date('F d',strtotime($_GET['startDate']))." - ".date('F d, Y',strtotime($_GET['endDate'])); ?></td></tr>
	
	  <tr><td>&nbsp;</td></tr>
	  <?php echo $data;?>
	   <tr><td>&nbsp;<br><br><br></td></tr>
	  <tr><td><input type="Submit" value="Approve Schedule"></td></tr>
	 </table>
	</form>
	  
     
	
 <?php } ?>

		
        <div class="container_12"><!--  PLACEHOLDER FOR FLOT - REMOVE IF NOT REQUIRED --></div>
        
        <div class="clearfix">&nbsp;</div>

        <!-- NOTIFICATION - USES CLASS OF "WARNING" and the CANHIDE ENABLES CICK TO FADE AWAY-->
        <div class="container_12">
           
<!--START NOTIFICATIONS  --><!-- INFORMATION - USES CLASS OF "IN2FORMATION" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- WARNING - USES CLASS OF "WARNING" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- SUCCESS - USES CLASS OF "SUCCESS" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- FAILURE - USES CLASS OF "FAILURE" and the CANHIDE ENABLES CICK TO FADE AWAY--></div>   
  	<!--END NOTIFICATIONS  -->
        
        
	<div class="clearfix">&nbsp;</div>
    
    
    
    
    </div>

<!-- START TABULAR DATA EXAMPLE -->
  <div class="container_12">
  
	<h2>&nbsp;</h2>

    <!-- END TABULAR DATA EXAMPLE -->

    <div class="clearfix">&nbsp;</div>
           
           
              
          
</div>

<div class="clearfix">&nbsp;</div>
<div class="container_12">
     


<?php include "footer.php";?>     
  </div><!-- end content wrap -->


</body>
</html>


