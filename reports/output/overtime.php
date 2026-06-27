<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
$ckqry=mysql_num_rows(mysql_query("SELECT e.firstName,e.lastName,e.middleName,d.name as dept from dailytimesummary dt
left join employee e on e.ndex=dt.employeeId left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId
WHERE e.deptId = '".$_POST['tdept']."' and dt.isError=1
ORDER BY e.lastName,e.firstName
"));
$getInfoCutoffDates = mysql_fetch_object(mysql_query("SELECT * FROM cutoffdates WHERE ndex='".$_POST['cutoff']."'",$conn));


$dqry="SELECT distinct d.ndex as deptd from dailytimesummary dt
left join employee e on e.ndex=dt.employeeId left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
$dqry.=" WHERE date>='".$getInfoCutoffDates->cutoffDateStart."' and date<='".$getInfoCutoffDates->cutoffDateEnd."' and employeeId in (select ndex from employee)";
if($_POST['tdept']>='1'){
	$dqry.=" and e.deptId = '".$_POST['tdept']."'";
}
if($_POST['leve']!='ALL'){
	$dqry.=" and e.level=".$_POST['leve']."";
}
$gtot=0;
$gtnpot=0;
$dq=mysql_query($dqry);
while($dr=mysql_fetch_object($dq)){
	
	$qry="SELECT e.ndex as empe,e.firstName,e.lastName,e.middleName,e.employmentStatus,e.employeeNo,d.name as dept,sum(approvedOvertime) as ot,sum(approvedOvertimeNightPremium) as npot from dailytimesummary dt
	left join employee e on e.ndex=dt.employeeId left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
	$qry.=" WHERE date>='".$getInfoCutoffDates->cutoffDateStart."' and d.ndex='".$dr->deptd."' and date<='".$getInfoCutoffDates->cutoffDateEnd."' and employeeId in (select ndex from employee)";
	if($_POST['leve']!='ALL'){
		$qry.=" and e.level=".$_POST['leve']."";
	}
	$qry.=" GROUP BY e.firstName,e.lastName,e.middleName,d.name,e.ndex,e.employmentStatus,e.employeeNo
	ORDER BY d.name,e.lastName,e.firstName";
	//echo $qry;
	$exec=mysql_query($qry);
	$var=0;
	$tot=0;
	$tnpot=0;
	while($r=mysql_fetch_object($exec)){
		$rema=mysql_query("select * from dailytimesummary where date>='".$getInfoCutoffDates->cutoffDateStart."' and date<='".$getInfoCutoffDates->cutoffDateEnd."' and employeeId='".$r->empe."' and overtimeRemarks<>''");	
		$remarks="";
		while($rem=mysql_fetch_object($rema)){
			$remarks.=$rem->overtimeRemarks.",";
		}
		//$remarks=rtrim(",",$remarks);
	     $var++;
	     $ctr1s++;
	     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		 if($r->ot==0 && $r->npot==0){
		 
		 }
		 else{
		 $tot+=$r->ot;
		$tnpot+=$r->npot;
		$gtot+=$r->ot;
		$gtnpot+=$r->npot;
	     $data.="<tr bgcolor='".$bgclr1s."'>
		       <td>".$var."</td>
			   <td>".$r->dept."</td>
			    <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
		       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
			   <td>".$r->ot."</td>
			   <td>".$r->npot."</td>
			   <td>".$remarks."</td>
	     </tr>";
		 }
	}

	$data.="<tr><td colspan='7'><hr></td></tr><tr bgcolor='".$bgclr1s."'>
		       <td colspan='3'>SUB TOTAL</td>
			  
		       <td></td>
			   <td>".$tot."</td>
			   <td>".$tnpot."</td>
			   <td></td>
	     </tr>";
}
$data.="<tr><td colspan='7'><hr></td></tr><tr bgcolor='".$bgclr1s."'>
		       <td colspan='3'>GRAND TOTAL</td>
			 
		       <td></td>
			   <td>".$gtot."</td>
			   <td>".$gtnpot."</td>
			   <td></td>
	     </tr>";
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="overtime.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="6" align="center" style="font-size:14px;font-weight:bold;">Overtime Report<br> <?php echo $getInfoCutoffDates->cutoffDateStart." to ".$getInfoCutoffDates->cutoffDateEnd;?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
		   <td>Dept</td>
		   <td>ID</td>
	       <td>Name</td>
		   <td>Overtime</td>
		   <td>Night Premium</td>
		   <td>Remarks</td>
	  </tr>
	  <tr><td colspan="7"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




