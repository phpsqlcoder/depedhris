<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
$dd=mysql_Fetch_object(mysql_query("select * from dept where ndex in (".$_SESSION['deptId'].")"));

if($_GET['startDate']){
	$start = strtotime($_GET['startDate']);
	$end = strtotime($_GET['endDate']);
	 $data.="<tr style='color:blue;font-weight:bold;'>
		       <td>Name</td>
		      
			   <td>Dept</td>
			    <td>Date</td>";
			   
	for ( $ab = $start; $ab <= $end; $ab += 86400 ){
		$detb=date('Y-m-d',$ab);
		$data.="<td align='center'>".date('M-d',strtotime($detb))."</td>";
	}
	$data.="<tr><td colspan='30'><hr></td></tr></tr>";
	$qry="SELECT e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.ndex as empid,es.startDate from employee_shifting es left join employee e on e.ndex=es.employeeId
	 left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
	$qry.=" WHERE e.isActive=1 and es.approvedDate='0000-00-00 00:00:00' and es.startDate between '".$_GET['startDate']."' and '".$_GET['endDate']."'";
	$qry.=" ORDER BY d.ndex,e.lastName,e.firstName";
	//echo $qry;
	$exec=mysql_query($qry);
	$var=0;
	while($r=mysql_fetch_object($exec)){
		  $var++;
		  $data.="<tr style='color:black;'>
		       <td style='font-weight:bold;' width='200px'>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
			   <td>".$r->dept."</td>
			   <td>".$r->startDate."</td>
			   ";
		  $data.="</tr>";
	}
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
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">

<div id="rcont">
  <h2><strong>Unapprove Shifting</strong></h2>
 <div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
     <?php

	?>
     <?php
	 if(!$_GET['startDate']){
	 ?>
	  <form action="tools_viewunapprovedshifting.php" method="get" name="sadsa">
     <table width="100%" style="font-family:Arial;font-size:12px;">
	
	 <tr>
	 	<td colspan="5">
		<table><tr>
		<td>Start Date:<input type="Text" name="startDate" id="startDate" size="15" value="<?php echo $_GET['startDate'];?>"><a href="javascript:show_calendar('sadsa.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		<td>End Date:<input type="Text" name="endDate" id="endDate" size="15" value="<?php echo $_GET['endDate'];?>"><a href="javascript:show_calendar('sadsa.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
		
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
		$filename =$dd->name."unapproveshifting.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>

	<table width="150%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="50" align="center" style="font-size:14px;font-weight:bold;">Unapprove Shifting</td>
	  </tr>
	   <tr><td colspan="5" style="font-weight:bold;">  
	  <tr><td colspan='10' style="color:black;font-weight:bold;">Date: <?php echo date('F d',strtotime($_GET['startDate']))." - ".date('F d, Y',strtotime($_GET['endDate'])); ?></td></tr>
	
	  <tr><td>&nbsp;</td></tr>
	  <?php echo $data;?>
	   <tr><td>&nbsp;<br><br><br></td></tr>

	 </table>
	
	  
     
	
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


