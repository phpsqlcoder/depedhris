<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../myfunctions.php");
include("../../employeefunctions.php");

$_GET['id']=$_POST['id'];
	$qry="SELECT 
		e.*,
	p.name as positionn,d.name as dept,di.name as division,u.name as unit,e.employmentStatus,e.employeeNo,e.ndex
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
$upda=mysql_query("update demphistory set tayp='position' where tayp in('position','Promotion')");
$sqry=mysql_query("select distinct employeeId,tayp,newn,dyt from demphistory where employeeId=".$_GET['id']." and tayp='position' order by dyt");
while($r=mysql_fetch_object($sqry)){
	$c++;
	$newvalue=$r->newn;
	$oldvalue=$r->oldn;
	
	
	
	//echo $tayp."<br>";
	//if($r->tayp)
//	$oldvalue=
	if($r->tayp=='Promotion' || $r->tayp=='position'){
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
		
		if($poldvalue!=$val->name){$poldvalue=$val->name;}
	}
	
	$type=$r->tayp;
	
	$ctr1s++;
	//if($c==1){$tayp=$r->dyt;}
	//if($tayp!=$r->dyt || $c==1){
		if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		$data.="<tr style='background-color:".$bgclr1s.";'>
					<td>".$r->dyt."</td>
					<td>".strtoupper($type)."</td>
					<td>".$oldvalue."</td>
					<td>".$newvalue."</td>
					
		</tr>";
	$tayp=$r->dyt;
	//}
	
}
$r=mysql_fetch_object(mysql_query("SELECT d.name as dept,di.name as division,e.*,p.name as positionn from 
 employee e 
 left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId left join division di on di.ndex=e.divisionId WHERE e.ndex = '".$_POST['id']."'"));
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="promotionhistory.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
   <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">
	 <tr><td>ID</td><td><?php echo getID($r->employmentStatus,$r->employeeNo);?></td></tr>
	 <tr><td>Name:</td><td colspan="4"><?php echo $r->lastName." , ".$r->firstName." ".$r->middleName;?></td></tr>
	 <tr><td>Position:</td><td colspan="4"><?php echo $r->positionn;?></td></tr>
	 <tr><td>Dept:</td><td colspan="4"><?php echo $r->dept;?></td></tr>
	 <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td colspan="7" align="center" style="font-size:14px;font-weight:bold;">Promotion History Report</td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr style="font-weight:bold;">
	       <td>Effectivity Date</td>
		<td>Status</td>
		<td>Old Value</td>
		<td>New Value</td>
		
	      
	  </tr>
	  <tr><td colspan="7"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




