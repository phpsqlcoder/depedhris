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
	
	$sql = "select SUM(p.pagibig + p.pagibigSavings) pagibig, e.level,e.lastName,e.firstName,e.middleName, e.sssNumber,e.pagibigNumber,e.birthDate 
				from payroll p left join employee e on e.ndex=p.empid 
					where date_format(p.pay_period,'%Y-%m')='".date('Y-m',strtotime($_POST['monthyear']))."' 
							and p.basicpay>0 and e.residencyTrainingProgram<>'ROD'
							and p.pagibig<>0
						group by e.lastName,date_format(p.pay_period,'%Y-%m'),e.level,e.firstName,e.middleName 
							order by e.lastName,e.firstName,e.sssNumber,e.pagibigNumber,e.birthDate";
	//echo $sql;
	$rs = mysql_query($sql);
	$ln = 0;
	while($r=mysql_fetch_assoc($rs)){
		$monthN = date('n',strtotime($_POST['monthyear']));
		$ln++;
		$cntr++;
		if($r['pagibigNumber']!=''){$pagibig=$r['pagibigNumber'];}else{$pagibig=$r['birthDate'];}
		$data .="<tr align='left'>
					<td>".$ln."</td>
<td>".$pagibig."</td>
					<td>".$r['lastName']."</td>
					<td>".$r['firstName']."</td>
<td>".$r['middleName']."</td>
					<td align='right'>".number_format($r['pagibig'] ,2)."</td>
					<td align='right'>".number_format(100,2)."</td>
					<td align='right'>".number_format($r['pagibig'] + 100,2)."</td>
				</tr>";
		$totalEshare += number_format($r['pagibig'],2,'.','');
		$totalCshare += number_format(100,2,'.','');
		$totalpC += number_format($r['pagibig'] + 100,2,'.','');
		
	}
	
if($_POST['eksel']=='on'){
		$filename ="hdmfreport.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
}	
?>
<html>
	<head>
		<title>HDMF Remittance Report</title>
		<link rel=stylesheet href="mycss.css" type="text/css"> 
		<style>
  		a {text-decoration: none;color:#000000;font-size:$fonsize px;} a:hover {text-decoration: none; color:red; background-color: #ffff00}
 		</style>
	</head>
<body topmargin=0>	
	HDMF Remittance Report <br /><br />		
	Davao Doctors Hospital<br />
	Employer ID no.: 09-0448000-1<br />
	Period: <?php echo date('F Y',strtotime($_POST['monthyear']));?> <br />
		
		<table cellpadding="3" width="95%" style="font-family:Arial;font-size:11px;" border="0">
		<tr align="center">
			<td></td>
<td>Pagibig</td>
			<td align="left">FAMILY NAME</td>
			<td align="left">GIVEN NAME</td>
<td align="left">MIDDLE NAME</td>
			<td>EMPLOYEE <br />SHARE</td>
			<td>EMPLOYER <br />SHARE</td>
			<td>TOTAL</td>
		<?php echo $data;?>
		<td></td>
			<td colspan="2">TOTAL</td>
<td></td>
<td></td>
			<td align='right'><?php echo number_format($totalEshare,2);?></td>
			<td align='right'><?php echo number_format($totalCshare,2);?></td>
			<td align='right'><?php echo number_format($totalpC,2);?></td>
	</table>
<?php ob_end_flush();?>

