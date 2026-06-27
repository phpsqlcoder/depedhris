<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../myfunctions.php");
include("../../employeefunctions.php");


	$qry="SELECT 
		e.*,
	p.name as position,d.name as dept,di.name as division,u.name as unit,e.employmentStatus,e.employeeNo,e.ndex
			from employee e 
			left join position p on p.ndex=e.position 
			left join dept d on d.ndex=e.deptId
			left join division di on di.ndex=e.divisionId
			left join unit u on u.ndex=e.unitId 
		WHERE e.ndex<>''";
	
	
	if($_POST['tdept']>='1'){
		$qry.=" and e.deptId = '".$_POST['tdept']."'";
	}
	if($_POST['tdiv']>='1'){
		$qry.=" and di.ndex = '".$_POST['tdiv']."'";
	}
	if($_POST['id']>='1'){
		$qry.=" and e.ndex=".$_POST['id']."";
	}
	$qry.=" order by d.name,e.lastName,e.firstName";
	//echo $qry;
	$exec=mysql_query($qry);
	while($r=mysql_fetch_object($exec)){
		$qq=mysql_query("SELECT * FROM  `employee_edit_logs` WHERE  `fieldName` LIKE  'position' and employeeId='".$r->ndex."' and effectivityDate>='".$_POST['startdates']."' and effectivityDate<='".$_POST['enddates']."' ORDER BY effectivityDate DESC");
		while($q=mysql_fetch_object($qq)){
			$oldp=mysql_fetch_object(mysql_query("select * from position where ndex='".$q->oldValue."'"));
			$newp=mysql_fetch_object(mysql_query("select * from position where ndex='".$q->newValue."'"));
		    
		     $ctr1s++;
		     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		     $data.="<tr>
			 		<td>".getID($r->employmentStatus,$r->employeeNo)."</td>
					<td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
					
					<td>".$r->dept."</td>
				   <td>".$q->effectivityDate."</td>
				   <td>".$oldp->name."</td>
				   <td>".$newp->name."</td>			      
		     </tr>";
		 }
	}


?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="incaseofemergency.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
   <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">

	 <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td colspan="7" align="center" style="font-size:14px;font-weight:bold;">Promotion History Report<br><?php echo $_POST['startdates']." ".$_POST['enddates'];?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr style="font-weight:bold;">
	  		<td>ID</td>
			<td>Name</td>
			
			<td>Dept</td>
	       <td>Effectivity</td>
	       <td>Old Value</td>
		   <td>New Value</td>
	      
	  </tr>
	  <tr><td colspan="7"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




