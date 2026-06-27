<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");
$ckqry=mysql_num_rows(mysql_query("SELECT e.firstName,e.lastName,e.middleName,d.name as dept from dailytimesummary dt
left join employee e on e.ndex=dt.employeeId left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId
WHERE e.deptId = '".$_POST['tdept']."' and dt.isError=1 and e.isActive=1
ORDER BY e.lastName,e.firstName
"));
$sqry="select * from dept where ndex<>0";
if($_POST['tdept']!='ALL'){
	$sqry.=" and ndex=".$_POST['tdept']."";
}
$dp=mysql_fetch_object(mysql_query($sqry));
$dqry="select * from dept where ndex<>0";
if($_POST['tdept']!='ALL'){
	$dqry.=" and ndex=".$_POST['tdept']."";
	$hdr=$dp->name;
}
else{
	$hdr="ALL DEPT";
}
$fd= date('Y-m-01', strtotime($_POST['monthyear']));
$ld= date('Y-m-t', strtotime($_POST['monthyear'])); 
$execdqry=mysql_query($dqry." ORDER BY name");

while($d=mysql_fetch_object($execdqry)){

	$qry="SELECT e.ndex as empid,e.firstName,e.lastName,e.middleName,e.employeeNo,e.employmentStatus,d.name as dept,
	sum(dt.pagibigloanh) as amt
	
	 from payroll dt
	left join employee e on e.ndex=dt.empid left join position p on p.ndex=e.position left join dept d on d.ndex=e.deptId";
	$qry.=" WHERE dt.pay_period>='".$fd."' and dt.pay_period<='".$ld."' and e.deptId = '".$d->ndex."' and dt.pagibigloanh>0";
	$qry.=" GROUP BY e.firstName,e.lastName,e.middleName,d.name
	ORDER BY e.lastName,e.firstName";
	//echo $qry."<br><br><br>";
	$exec=mysql_query($qry);
	$data.="<tr>
				<td>&nbsp;</td>
				<td colspan='5' style='font-weight:bold;font-size:13px;color:maroon;'>".$d->name."</td>
	</tr>";
	$var=0;
	
	$amttotal=0;
	while($r=mysql_fetch_object($exec)){
		 $var++;
	     $ctr1s++;
	     $amttotal+=$r->amt;
	     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		
	     $data.="<tr bgcolor='".$bgclr1s."'>
		       <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
		       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
			
			   <td>".$r->amt."</td>
			   
	     </tr>";
	}
	$data.="
	<tr><td colspan='20'><hr></td></tr>
	<tr>
				<td>Total ".$var."</td>
				<td>&nbsp;</td>
				
				<td>".$amttotal."</td>
			
	</tr><tr><td>&nbsp;</td></tr>";
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="hdmfhousing.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="20" align="center" style="font-size:14px;font-weight:bold;">Monthly HDMF Housing Loan<br> <?php echo $fd." to ".$ld."<br>".$hdr;?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
	       <td>Name</td>
		   <td>HDMF Housing Loan</td>
		   
	  </tr>
	  <tr><td colspan="20"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




