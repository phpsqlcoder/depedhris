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


$qry="SELECT distinct e.ndex as empe,e.firstName,e.lastName,e.middleName,e.employmentStatus,e.employeeNo,d.name as dept from dailytimesummary dt
left join employee e on e.ndex=dt.employeeId left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
$qry.=" WHERE date>='".$getInfoCutoffDates->cutoffDateStart."' and date<='".$getInfoCutoffDates->cutoffDateEnd."' and employeeId in (select ndex from employee)";
if($_POST['tdept']>='1'){
	$qry.=" and e.deptId = '".$_POST['tdept']."'";
}
if($_POST['leve']!='ALL'){
	$qry.=" and e.level=".$_POST['leve']."";
}
$qry.=" ORDER BY d.name,e.lastName,e.firstName";

$exec=mysql_query($qry);
$var=0;

while($r=mysql_fetch_object($exec)){
	 $ata="";
	 $ata.="<tr style='color:maroon;'>
			   <td>".$r->dept."</td>
			 
		       <td colspan='2'>".getID($r->employmentStatus,$r->employeeNo)." - ".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
			   </tr>
			   ";
	$rema=mysql_query("select * from dailytimesummary where date>='".$getInfoCutoffDates->cutoffDateStart."' and date<='".$getInfoCutoffDates->cutoffDateEnd."' and employeeId='".$r->empe."' ORDER BY date");	
	$remarks="";
	$tot=0;
	$tot2=0;	
	while($rem=mysql_fetch_object($rema)){
		$tot+=$rem->approvedOvertime;
		$tot2+=$rem->approvedOvertimeNightPremium;
		 $ctr1s++;
	     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		 if($rem->approvedOvertime==0 && $rem->approvedOvertimeNightPremium==0){
	     
		 }
		 else{
		 	$ata.="<tr bgcolor='".$bgclr1s."'>
		 		<td>".$rem->date."</td>
			   <td>".$rem->approvedOvertime."</td>
			   <td>".$rem->approvedOvertimeNightPremium."</td>
			   <td>".$rem->overtimeRemarks."</td>
	    	 </tr>";
		 }
	}
	
	 $ata.="
	 <tr><td colspan='10'><hr></td></tr>
	 <tr><td>Total</td><td>".$tot."</td><td>".$tot2."</td></tr>
	 <tr>
			   <td>&nbsp;&nbsp;</td>
	
			   </tr>
			   ";
	if($tot!=0 || $tot2!=0){
		$data.=$ata;
	}
}
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
	      
		   <td>Date</td>
		   <td>Overtime</td>
		   <td>Night Premium</td>
		   <td>Remarks</td>
	  </tr>
	  <tr><td colspan="6"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




