<?php
ob_start();
include("../../../dbcon.php");
include("../../../scripts/scripts.php");
include ("../../../myfunctions.php");
include("../../../employeefunctions.php");

function cutline($filename,$line_no=-1) {   	// BEGIN funtion for delete file.txt per line
	$strip_return=FALSE; 
	$data=file($filename); 
	$pipe=fopen($filename,'w'); 
	$size=count($data); 
	if($line_no==-1) $skip=$size-1; 
	else $skip=$line_no-1; 
	for($line=0;$line<$size;$line++) 
		if($line!=$skip) 
			fputs($pipe,$data[$line]); 
		else 
			$strip_return=TRUE; 
	return $strip_return; 
}

	//$sql="select p.*,e.bankAccountNo,e.level,e.lastName,e.firstName,e.middleName,e.employment from payroll p left join employee e on e.ndex=p.empid where p.pay_period='".$_POST['PayrollCutoff']."' and p.basicpay>0 and e.bankAccountNo<>'' and e.employmentStatus <> 'Temporary' and e.residencyTrainingProgram<>'ROD' order by e.lastName,e.firstName";
	
	$sql = "SELECT SUM(p.grossPay) grossPaymonthly, e.level,e.lastName,e.firstName,e.middleName, e.sssNumber,e.employmentStatus,e.employeeNo 
				FROM payroll p LEFT JOIN employee e ON e.ndex=p.empid 
					WHERE DATE_FORMAT(p.pay_period,'%Y-%m')='".date('Y-m',strtotime($_POST['monthyear']))."' 
							AND p.basicpay>0 
							AND e.residencyTrainingProgram<>'ROD'
						GROUP BY e.lastName,DATE_FORMAT(p.pay_period,'%Y-%m'),e.level,e.firstName,e.middleName 
							ORDER BY e.lastName,DATE_FORMAT(p.pay_period,'%Y-%m'),e.firstName,e.sssNumber";
	
	$rs = mysql_query($sql);
	$ln = 0;
	while($r=mysql_fetch_assoc($rs)){
		$monthN = date('n',strtotime($_POST['monthyear']));
		$ln++;
		$sss = sssPremium($r['grossPaymonthly']);     
		$sssContribution = $sss['eeShare'] + $sss['companyShare'];
		$cntr++;
		if ($r['grossPaymonthly'] >= '14750.00'){
			$ec = 30.00;
		} else {
			$ec = 10.00;
		}
		
		$data .="<tr align='left'>
					<td>".$ln."</td>
					<td>".getID($r['employmentStatus'],$r['employeeNo'])."&nbsp; </td>
					<td>".$r['lastName']."</td>
					<td>".$r['firstName']."</td>
					<td>".substr($r['middleName'],0,1).$r['sssNumber']."</td>
					<td align='right'>".number_format($sss['eeShare'] ,2)."</td>
					<td align='right'>".number_format($sss['companyShare'],2)."</td>
					<td align='right'>".number_format($sssContribution,2)."</td>
					<td align='right'>".number_format($ec,2)."</td>
				</tr>";
				

		
		$totalEshare += number_format($sss['eeShare'],2,'.','');
		$totalCshare += number_format($sss['companyShare'],2,'.','');
		$totalEC += number_format($ec,2,'.','');
		$totalpC += number_format($sssContribution + 100,2,'.','');
	}
?>
<?php
if($_POST['eksel']=='on'){
		$filename ="sssr3report.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
}	
?>
<html>
	<head>
		<title>SSS Remittance Report</title>
		<link rel=stylesheet href="mycss.css" type="text/css"> 
		<style>
  		a {text-decoration: none;color:#000000;font-size:$fonsize px;} a:hover {text-decoration: none; color:red; background-color: #ffff00}
 		</style>
	</head>
<body topmargin=0>	
	
	 
		<table cellpadding="3" width="95%" style="font-family:Arial;font-size:11px;" border="0">
		<thead>
		<tr><td colspan="6">SSS Remittance Report<br><br></td></tr>
		<tr><td colspan="3">REGISTERED EMPLOYER NAME:</td><td colspan="4">DAVAO DOCTORS HOSPITAL</td></tr>
		<tr><td colspan="3">EMPLOYER ID NUMBER:</td><td colspan="4">09-0448000-1</td></tr>
		<tr><td colspan="3">ADDRESS</td><td colspan="4">118 E. QUIRINO AVENUE, DAVAO CITY, DAVAO DEL SUR:</td></tr>
		<tr><td colspan="3">PERIOD:</td><td colspan="4"><?php echo date('F Y',strtotime($_POST['monthyear']));?> <br><br></td></tr>	
		
		<tr align="center">
			<td></td>
<td>ID</td>
			<td>FAMILY NAME</td>
			<td>GIVEN NAME</td>
			<td>MI SS NUMBER</td>
			<td>EMPLOYEE <br />SHARE</td>
			<td>EMPLOYER <br />SHARE</td>
			<td>TOTAL</td>
			<td>ECC</td>
		</tr>
		</thead>
		<tbody>
		<?php echo $data;?>
		<td></td>
			<td colspan="3">TOTAL</td>
			<td align='right'><?php echo number_format($totalEshare,2);?></td>
			<td align='right'><?php echo number_format($totalCshare,2);?></td>
			<td align='right'><?php echo number_format($totalEC,2);?></td>
			<td align='right'><?php echo number_format($totalpC,2);?></td>
		</tbody>
	</table>
<?php ob_end_flush;?>
