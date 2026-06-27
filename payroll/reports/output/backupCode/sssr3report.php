<?php
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
	
	$sql = "select sum(p.grossPay) grossPaymonthly, e.level,e.lastName,e.firstName,e.middleName, e.sssNumber 
				from payroll p left join employee e on e.ndex=p.empid 
					where date_format(p.pay_period,'%Y-%m')='".date('Y-m',strtotime($_POST['monthyear']))."' and p.basicpay>0 and e.residencyTrainingProgram<>'ROD'
						group by e.lastName,date_format(p.pay_period,'%Y-%m'),e.level,e.firstName,e.middleName 
							order by e.lastName,date_format(p.pay_period,'%Y-%m'),e.firstName,e.sssNumber";
	
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
		
		
		
		if ($monthN == 1 || $monthN == 4 || $monthN == 7 || $monthN == 10){
			$s1 = $sssContribution;
			$m1 = 0;
			$e1 = $ec;
		} elseif ($monthN == 2 || $monthN == 5 || $monthN == 8 || $monthN == 11){
			$s2 = $sssContribution;
			$m2 = 0;
			$e2 = $ec;
		} elseif ($monthN == 3 || $monthN == 6 || $monthN == 9 || $monthN == 12){
			$s3 = $sssContribution;
			$m3 = 0;
			$e3 = $ec;
		}
		
		
		
		$data .="<tr align='left'>
					<td>".$ln."</td>
					<td>".$r['lastName']."$monthN</td>
					<td>".$r['firstName']."</td>
					<td>".substr($r['middleName'],0,1).$r['sssNumber']."</td>
					<td>".number_format($s1,2)."</td>
					<td>". number_format($s2,2)."</td>
					<td>".number_format($s3,2)."</td>
					<td>".number_format($m1,2)."</td>
					<td>".number_format($m2,2)."</td>
					<td>".number_format($m3,2)."</td>
					<td>".number_format($e1,2)."</td>
					<td>".number_format($e2,2)."</td>
					<td>".number_format($e3,2)."</td>
					<td>N</td>
				</tr>";
	}
?>
<html>
	<head>
		<title>Metrobank Payroll Report</title>
		<link rel=stylesheet href="mycss.css" type="text/css"> 
		<style>
  		a {text-decoration: none;color:#000000;font-size:$fonsize px;} a:hover {text-decoration: none; color:red; background-color: #ffff00}
 		</style>
	</head>
<body topmargin=0>			

	<table cellpadding="3" width="95%" style="font-family:Arial;font-size:11px;" border="0">
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td colspan="3" align="center">SOCIAL SECURITY</td>
			<td colspan="3" align="center">MEDICARE</td>
			<td colspan="3" align="center">EMPLOYEE COMPENSATION</td>
			<td>REMARK</td>
		</tr>
		<tr align="center">
			<td></td>
			<td>FAMILY NAME</td>
			<td>GIVEN NAME</td>
			<td>MI SS NUMBER</td>
			<td>1ST <br>MONTH</td>
			<td>2ND <br>MONTH</td>
			<td>3RD <br>MONTH</td>
			<td>1ST <br>MONTH</td>
			<td>2ND <br>MONTH</td>
			<td>3RD <br>MONTH</td>
			<td>1ST <br>MONTH</td>
			<td>2ND <br>MONTH</td>
			<td>3RD <br>MONTH</td>
			<td>REMARKS</td>
		</tr>
		<?php echo $data;?>
	</table>


