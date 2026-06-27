<?php
ob_start();
include("../../../dbcon.php");
include("../../../scripts/scripts.php");
include ("../../../myfunctions.php");

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

	//$sql="select p.*,e.bankAccountNo,e.level,e.lastName,e.firstName,e.middleName from payroll p left join employee e on e.ndex=p.empid where p.pay_period='".$_POST['PayrollCutoff']."' and p.basicpay>0 and e.bankAccountNo<>'' and e.employmentStatus <> 'Temporary' and e.residencyTrainingProgram<>'ROD' order by e.lastName,e.firstName";
	
	$sql = "select sum(p.d_whtax) taxWithHeld, e.level,e.lastName,e.firstName,e.middleName, e.tin 
				from payroll p left join employee e on e.ndex=p.empid 
					where date_format(p.pay_period,'%Y-%m')='".date('Y-m',strtotime($_POST['monthyear']))."' and p.basicpay>0 and e.residencyTrainingProgram<>'ROD'
						group by e.lastName,date_format(p.pay_period,'%Y-%m'),e.level,e.firstName,e.middleName 
							order by e.lastName,date_format(p.pay_period,'%Y-%m'),e.firstName,e.sssNumber";
	
	$rs = mysql_query($sql);
	$ln = 0;
	while($r=mysql_fetch_assoc($rs)){
		$monthN = date('n',strtotime($_POST['monthyear']));
		$ln++;
				
		$data .="<tr align='left'>
					<td>".$ln."</td>
					<td>".$r['lastName']."</td>
					<td>".$r['firstName']."</td>
					<td>".substr($r['middleName'],0,1).$r['tin']."</td>
					<td align='right'>".number_format($r['taxWithHeld'] ,2)."</td>
				</tr>";
				

		
		$totalTaxWithHeld += number_format($r['taxWithHeld'],2,'.','');
				
	}
?>
<?php
if($_POST['eksel']=='on'){
		$filename ="birreport.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
}	
?>
<html>
	<head>
		<title>BIR Remittance Report</title>
		<link rel=stylesheet href="mycss.css" type="text/css"> 
		<style>
  		a {text-decoration: none;color:#000000;font-size:$fonsize px;} a:hover {text-decoration: none; color:red; background-color: #ffff00}
 		</style>
	</head>
<body topmargin=0>	
	
	 
		<table cellpadding="3" width="95%" style="font-family:Arial;font-size:11px;" border="0">
		<thead>
		<tr><td colspan="6">BIR Remittance Report<br><br></td></tr>
		<tr><td colspan="3">REGISTERED EMPLOYER NAME:</td><td colspan="4">DAVAO DOCTORS HOSPITAL</td></tr>
		<tr><td colspan="3">EMPLOYER ID NUMBER:</td><td colspan="4">005-985-874</td></tr>
		<tr><td colspan="3">ADDRESS</td><td colspan="4">118 E. QUIRINO AVENUE, DAVAO CITY, DAVAO DEL SUR:</td></tr>
		<tr><td colspan="3">PERIOD:</td><td colspan="4"><?php echo date('F Y',strtotime($_POST['monthyear']));?> <br><br></td></tr>	
		
		<tr align="center">
			<td></td>
			<td>FAMILY NAME</td>
			<td>GIVEN NAME</td>
			<td>TIN NUMBER</td>
			<td>TAX WITHHELD</td>
		</tr>
		</thead>
		<tbody>
		<?php echo $data;?>
		<td></td>
			<td colspan="3">TOTAL</td>
			<td align='right'><?php echo number_format($totalTaxWithHeld,2);?></td>
		</tbody>
	</table>
<?php ob_end_flush;?>
