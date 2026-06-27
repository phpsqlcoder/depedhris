<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");

$_GET['id']=$_POST['id'];
$rs=mysql_fetch_object(mysql_query("SELECT d.name as dept,di.name as division,e.*,p.name as pos from 
 employee e 
 left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId left join division di on di.ndex=e.divisionId WHERE e.ndex = '".$_POST['id']."'"));
	$qry="SELECT 
		e.*,
	p.name as position,d.name as dept,di.name as division,u.name as unit,e.employmentStatus,e.employeeNo,e.ndex
			from employee e 
			left join position p on p.ndex=e.position 
			left join dept d on d.ndex=e.deptId
			left join division di on di.ndex=e.divisionId
			left join unit u on u.ndex=e.unitId 
		WHERE e.ndex<>''";
	$qry.=" and e.ndex=".$_POST['id']."";
	$qry.=" order by e.lastName,e.firstName";
	//$exec=mysql_query($qry);
	$r=mysql_fetch_object(mysql_query($qry));
	
	$dtab=mysql_query("DROP TABLE IF EXISTS `demphistory`");
	// Create temporary table
	$tmptable=mysql_query("
	
	CREATE TEMPORARY TABLE `demphistory` (
					`ndex` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`employeeId` INT NOT NULL,
					`tayp` VARCHAR(200) NOT NULL, 
					`oldn` VARCHAR(200) NOT NULL, 
					`newn` VARCHAR(200) NOT NULL, 
					`remarks` VARCHAR(200) NOT NULL,
					`fr` VARCHAR(200) NOT NULL,					
					`createdBy` VARCHAR(200) NOT NULL,		
					`dyt` DATE NOT NULL
	)");
	$tesq=mysql_query("select * from employeechangestatus where employeeId=".$_GET['id']." and changeType in ('Resignation','End of Contract','Separation','Lateral Transfer',
'employmentStatus','Demotion','Promotion','End of Residency Training','Retirement','Termination','Employment Status','position','employmentStatus','deptId')");
while($tsq=mysql_fetch_object($tesq)){
	//echo "insert into demphistory(employeeId,tayp,oldn,newn,remarks,dyt,fr,createdBy)VALUES('".$tsq->employeeId."','".$tsq->changeType."','','".$tsq->newValue."','".$tsq->remarks."','".$tsq->effectivityDate."','employeechangestatus','".$tsq->createdBy."')<br>";
	$instsq=mysql_query("insert into demphistory(employeeId,tayp,oldn,newn,remarks,dyt,fr,createdBy)VALUES('".$tsq->employeeId."','".$tsq->changeType."','','".$tsq->newValue."','".$tsq->remarks."','".$tsq->effectivityDate."','employeechangestatus','".$tsq->createdBy."')");
}
//echo "select * from employee_edit_logs where employeeId=".$_GET['id']." and fieldName in ('deptId','position')";
$tetq=mysql_query("select * from employee_edit_logs where employeeId=".$_GET['id']." and fieldName in ('position')");
while($ttq=mysql_fetch_object($tetq)){
	$insttq=mysql_query("insert into demphistory(employeeId,tayp,oldn,newn,remarks,dyt,fr,createdBy)VALUES('".$ttq->employeeId."','".$ttq->fieldName."','".$ttq->oldValue."','".$ttq->newValue."','','".$ttq->effectivityDate."','employee_edit_logs','".$ttq->updatedBy."')");
}

$c=0;
$p=0;
$d=0;
$position=$rs->pos;
$dep=$rs->dept;
$upda=mysql_query("update demphistory set tayp='position' where tayp in('position','Promotion')");
$sqry=mysql_query("select distinct employeeId,tayp,newn,dyt from demphistory where employeeId=".$_GET['id']." order by dyt");
while($r=mysql_fetch_object($sqry)){
	$c++;
	$newvalue=$r->newn;
	$oldvalue=$r->oldn;
	
	
	
	//echo $tayp."<br>";
	//if($r->tayp)
//	$oldvalue=
	if($r->tayp=='Lateral Transfer' || $r->tayp=='Promotion' || $r->tayp=='Demotion' || $r->tayp=='position'){
		$p++;
		$val=mysql_fetch_object(mysql_query("select * from position where ndex='".$r->newn."'"));
		$valo=mysql_fetch_object(mysql_query("select * from position where ndex='".$r->oldn."'"));
		
		if($p==1){
			$poldvalue=$val->name;
			$oldvalue="";
		}
		else{
			$oldvalue=$poldvalue;
		}
		$newvalue=$val->name;
		$position=$newvalue;
		$xdep="<td>".$dep."</td><td>&nbsp;</td>";
		$xpos="<td>".$position."</td>";
		if($poldvalue!=$val->name){$poldvalue=$val->name;}
	}
	elseif($r->tayp=='deptId'){
		$d++;
		
		$val=mysql_fetch_object(mysql_query("select * from dept where ndex='".$r->newn."'"));
		$valo=mysql_fetch_object(mysql_query("select * from dept where ndex='".$r->oldn."'"));
		if($d==1){
			$doldvalue=$val->name;
			$oldvalue="";
		}
		else{
			$oldvalue=$doldvalue;
		}
		$newvalue=$val->name;		
		if($doldvalue!=$val->name){$doldvalue=$val->name;}
		$xdep="<td>".$oldvalue."</td><td>".$newvalue."</td>";
		$xpos="<td>".$position."</td>";
		$dep=$newvalue;
	}
	else{
		$xdep="<td>".$oldvalue."</td><td>".$newvalue."</td>";
		$xpos="<td>".$position."</td>";
	}
	$type=$r->tayp;
	if($r->tayp=='deptId'){
		$type='DEPT';
	}
	$ctr1s++;
	//if($c==1){$tayp=$r->dyt;}
	//if($tayp!=$r->dyt || $c==1){
	/*<td>Type</td>
					<td>Dept</td>
			       <td>Transfer To</td>
				   <td>Effectivity Date</td>
			      <td>Position</td>
				   <td>Remarks</td>
				   <td>Encoded By</td>*/
	$rq=mysql_fetch_object(mysql_query("select * from demphistory where employeeId='".$r->employeeId."' and tayp='".$r->tayp."' and newn='".$r->newn."' and dyt='".$r->dyt."'"));
		if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		$data.="<tr style='background-color:".$bgclr1s.";'>
					<td>".strtoupper($type)."</td>
					".$xdep."
					<td>".$r->dyt."</td>
					".$xpos."
					<td>".$rq->remarks."</td>
					<td>".$rq->createdBy."</td>
					
		</tr>";
	$tayp=$r->dyt;
	//}
	
}

?>

     <?php
if($_POST['eksel']=='on'){
		$filename ="personnelmovement.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="4" align="center" style="font-size:14px;font-weight:bold;">EMPLOYMENT RECORD<br></td>
	  </tr>
	  <tr><td>ID:</td><td><?php echo getID($rs->employmentStatus,$rs->employeeNo);?></td></tr>
	  <tr><td>Name:</td><td><?php echo $rs->lastName." , ".$rs->firstName." ".$rs->middleName;?></td></tr>
	 <tr><td>Department:</td><td><?php echo $rs->dept;?></td></tr>
	  <tr><td>Division:</td><td><?php echo $rs->division;?></td></tr> 

	  <tr><td>&nbsp;</td></tr>
	  <tr>
	  	<td colspan="10">
			<table width="100%">
				<tr>
					
				<td>Type</td>
					<td>Dept</td>
			       <td>Transfer To</td>
				   <td>Effectivity Date</td>
			      <td>Position</td>
				   <td>Remarks</td>
				   <td>Encoded By</td>
				</tr>
				<tr><td colspan="10"><hr></td></tr>
				  <?php echo $data;?>
			</table>
		</td>
	      
	  </tr>
	  
      </table>
	  <?php include("../rptfooter.php");?>




