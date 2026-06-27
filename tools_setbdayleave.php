<?php
ob_start();
session_start();
include("dbcon.php");
if(isset($_GET['months'])){
	$qry="SELECT e.ndex as eid,e.employmentStatus,e.employeeNo,di.name as divi,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,e.birthDate,DATE_FORMAT(e.birthDate,'%b') as mant,DATE_FORMAT(e.birthDate,'%d') as de from employee e left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId
	left join division di on di.ndex=d.divisionId";

	$qry.=" WHERE DATE_FORMAT(e.birthDate,'%M') = '".$_GET['months']."' and e.isActive=1 and e.employmentStatus='Regular'";
	$qry.=" ORDER BY DATE_FORMAT(e.birthDate,'%d')";
	//echo $qry;
	$data='';
	$var=0;
	$exec=mysql_query($qry);
	while($r = mysql_fetch_array($exec)){
		$eid = $r['eid'];
		$btn = '<input type="checkbox" name="cb'.$r['eid'].'" checked="checked">';

		$bdate = date('Y-m-d',strtotime($r['mant']." ".$r['de'].", ".date('Y')));
		if(isset($_POST['cb'.$eid])){
				// /echo $eid."<br>";
				$ckrd=mysql_fetch_object(mysql_query("select * from employee_restday where employeeId=".$eid." and '".$bdate."' between startDate and endDate"));
				if($ckrd->ndex){
					$del=mysql_query("delete from employee_restday where ndex=".$ckrd->ndex."");
				}
				$ckshift=mysql_fetch_object(mysql_query("select * from employee_shifting where employeeId=".$eid." and '".$bdate."' between startDate and endDate"));
				if($ckshift->ndex){
					$del=mysql_query("delete from employee_shifting where ndex=".$ckshift->ndex."");
				}
				$ckleave=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId=".$eid." and '".$bdate."' between startDate and endDate"));
				
				if(!$ckleave->ndex){
					$ins=mysql_query("insert into employee_leave (`employeeId`, `leaveId`, `startDate`, `endDate`)
						 VALUES (".$eid.",'9','".$bdate."','".$bdate."')");		
				}
					
		}

		
		$ckleavew=mysql_fetch_object(mysql_query("select * from employee_leave where employeeId=".$eid." and '".$bdate."' between startDate and endDate"));
			if($ckleavew->ndex){
				$j = mysql_fetch_array(mysql_query("select * From `leave` where ndex='".$ckleavew->leaveId."'"));
				$btn = 'Filed ('.$j['name'].')';
			}	

		$ctr1s++;
		$var++;
     	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		$data.='<tr style="background-color:'.$bgclr1s.'">
				<td> '.$var.'</td>
				<td>'.$r['lastName'].', '.$r['firstName'].' '.$r['middleName'].'</td>
	     		<td>'.$r['dept'].'</td>
	     		<td>'.$r['mant'].' '.$r['de'].'</td>
	     		<td align="center">'.$btn.'</td>
		</tr>';
	}
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
	function searchitems(){
	//alert();
		mynodes=frmitem.serialize();	
		new Ajax.Updater('listitem','tools_setshifting.php?ser=searching',{
			method: 'get',
			parameters: mynodes
		});	
	}
    </script>

    <div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
   
    <div class="clearfix">
     <h2>Set Birthday Leave</h2>
     <form name="frmrpt" action="tools_setbdayleave.php" method="get">
     <table width="80%">
	  <tr>
		  <td>Month: <select name="months"><?php echo $optionmonths;?></select></td>

	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>
      	<?php 
		if(isset($_GET['months'])){
			?>
     <form method="post" action="tools_setbdayleave.php?go=go&months=<?php echo $_GET['months'];?>">
     <table width="100%">
     	<tr>
     		<td>Seq</td>
     		<td>Name</td>
     		<td>Dept</td>
     		<td>Bday</td>
     		<td align="center">File Leave</td>
     	</tr>
     	<tr><td colspan="5"><hr></td></tr>
    
		<?php echo $data; ?>
		
		
		<tr><td colspan="5" align="right"><input type="submit" value="Submit"></td></tr>
     </table>

 	</form>
 	<?php } ?>

  </div>
</body>
</html>

