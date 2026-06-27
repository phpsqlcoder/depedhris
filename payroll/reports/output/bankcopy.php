<?php
include("../../../dbcon.php");
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
$rundate=gmdate('m d, Y');
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

<?php
$folders=array('contractual','rankandfile','resident','sectionheads');
foreach($folders as $folder){
	if($folder=='contractual'){
		$genqry="select p.*,e.bankAccountNo,e.level,e.lastName,e.firstName,e.middleName from payroll p left join employee e on e.ndex=p.empid  where p.pay_period='".$_POST['PayrollCutoff']."' and p.basicpay>0 and p.holdSalary<>'1' and e.bankAccountNo<>'' and e.`level`=0 and e.employmentStatus='Temporary' and e.residencyTrainingProgram<>'ROD' order by e.lastName,e.firstName";
		$filename="bankcopy/contractual/".$_POST['PayrollCutoff']."";
		$lvl='DAVAO DOCTORS - CONTRACTUAL';
		$companycode="00005";
		$endcode="";
	}
	elseif($folder=='rankandfile'){
		$genqry="select p.*,e.bankAccountNo,e.level,e.lastName,e.firstName,e.middleName from payroll p left join employee e on e.ndex=p.empid  where p.pay_period='".$_POST['PayrollCutoff']."' and p.basicpay>0 and e.bankAccountNo<>'' and p.holdSalary<>'1'  and e.`level` in (1,2) and e.employmentStatus <> 'Temporary' and e.residencyTrainingProgram<>'ROD' order by e.lastName,e.firstName";
		$filename="bankcopy/rankandfile/".$_POST['PayrollCutoff']."";
		$lvl='DAVAO DOCTORS - RANK AND FILE';
		$companycode="00004";
		$endcode="";
	}
	elseif($folder=='resident'){
		$genqry="select p.*,e.bankAccountNo,e.level,e.lastName,e.firstName,e.middleName from payroll p left join employee e on e.ndex=p.empid  where p.pay_period='".$_POST['PayrollCutoff']."' and p.basicpay>0 and e.bankAccountNo<>'' and p.holdSalary<>'1'  and e.`level`=0 and e.employmentStatus<>'Temporary' and e.residencyTrainingProgram='ROD' order by e.lastName,e.firstName";
		$filename="bankcopy/resident/".$_POST['PayrollCutoff']."";
		$lvl='DAVAO DOCTORS - RESIDENT';
		$companycode="00003";
		$endcode="";
	}
	elseif($folder=='sectionheads'){
		$genqry="select p.*,e.bankAccountNo,e.level,e.lastName,e.firstName,e.middleName from payroll p left join employee e on e.ndex=p.empid  where p.pay_period='".$_POST['PayrollCutoff']."' and p.basicpay>0 and e.bankAccountNo<>'' and p.holdSalary<>'1'  and e.`level` in (3,4,5,6,7,8,9) and e.employmentStatus <> 'Temporary' and e.residencyTrainingProgram<>'ROD' order by e.lastName,e.firstName";
		$filename="bankcopy/sectionheads/".$_POST['PayrollCutoff']."";
		$lvl='DAVAO DOCTORS - OFFICERS';
		$companycode="00003";
		$endcode="";
	}
	$data.="
			<tr><td colspan='5' style='font-size:14px;font-weight:bold;font-family:Arial;'>".$lvl."</td></tr><tr><td colspan='5'><hr></td></tr>
	";
//	echo $genqry; die();
	$seq=0;
	$total=0;
	if (!is_dir($filename)) {
    	mkdir($filename);
	}
	$filename=$filename."/payroll.dat";
	$handle = fopen($filename, 'a');
	$cnt = count(file($filename));
	for ($line=1;$line <= $cnt;$line++){
		cutline($filename,1);
	}
	
	$qry=mysql_query($genqry); //
	while($r=mysql_fetch_object($qry)){
	/************************************************************************
	*	Formatting and writing of information needed for PAYROLL.DAT file 	*
	************************************************************************/
		$grossPay = $r->netBasic + $r->cola + $r->allowance + $r->honorarium + $r->payNightPremium + $r->payOTReg + $r->payOTExc + $r->oth_income + $r->onCallOvertime + $r->payDutyRd + $r->paySpHoliday + $r->payLHoliday - $r->payUndertime + $r->adj_other + $r->otRDLHolidayPay + $r->otRDSHolidayPay + $r->otLHolidayPay + $r->otSHolidayPay + $r->otRestDayPay + $r->hazardPay + $r->incentive + $r->adj_13th_mon_pay;
		//$grossPay = $r->netBasic + $r->cola + $r->allowance + $r->honorarium + $r->payNightPremium + $r->payOTReg + $r->oth_income + $r->onCallOvertime + $r->payDutyRd + $r->paySpHoliday + $r->payLHoliday + $r->payOTExc + $r->otRDLHolidayPay + $r->otRDSHolidayPay + $r->otLHolidayPay + $r->otSHolidayPay + $r->otRestDayPay - $r->payUndertime + $r->adj_other;
//		$totalDeduction = $r->d_pnb + $r->d_parkingFee + $r->d_whtax + $r->d_sss + $r->d_philhealth + $r->pagibig + $r->d_unionDues + $r->d_mortuary + $r->d_sssloan + $r->d_hospital + $r->d_cashAdvance + $r->d_other + $r->d_coopTotal  + $r->financialAssistance;
		$totalDeduction = $r->d_pnb + $r->d_parkingFee + $r->d_whtax + $r->d_sss + $r->d_philhealth + $r->pagibig + $r->pagibigloan + $r->pagibigloanh +$r->d_unionDues + $r->d_mortuary + $r->d_sssloan+ $r->d_hospital + $r->d_cashAdvance + $r->d_other + $r->d_coopTotal + $r->financialAssistance + $r->pagibigSavings;
		$netPay = $grossPay - $totalDeduction;
		
		/*       whewwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwwww
		if ($netPay < ($grossPay * 0.30)){
			if ($r->d_coopTotal > (($grossPay * 0.30) - $netPay)){
				$coopDedCurPayroll = $r->d_coopTotal - (($grossPay * 0.30) - $netPay);
				//$updpayroll = mysql_query("UPDATE payroll SET d_coopTotal='".$coopDedCurPayroll."' WHERE empid='".$r['empid']."' && pay_period='".$_POST['PayrollCutoff']."'",$conn);
				//echo "UPDATE payroll SET d_coopTotal='".$coopDedCurPayroll."' WHERE empid='".$r['empid']."' && pay_period='".$_POST['PayrollCutoff']."'";
				$netPay += (($grossPay * 0.30) - $netPay);
				$r->d_coopTotal = $coopDedCurPayroll;
			}
		} 
		*/
		
		$seq++;
		$total+=$netPay;
		$data.="<tr>
					<td> </td>
					<td>".$seq."</td>
					<td>".$r->lastName." ".$r->firstName." ".$r->middleName."</td>
					<td>".$r->bankAccountNo."</td>
					<td align='right'>".number_format($netPay,2)."</td>
		</tr>";
		$diffchar=40 - strlen($lvl);
		$blankSpaceCompanyName="";
		for($dif=1;$dif<=$diffchar;$dif++){
			$blankSpaceCompanyName.=" ";
		}
		
		$net_expl=explode(".",number_format($netPay,2,".",""));
		$netinc1_rnd=$net_expl[0].$net_expl[1];
		$netinc1_strln=strlen($netinc1_rnd);
		$xf=15-$netinc1_strln;
		$zerofill='';
		for ($x=1;$x <= $xf;$x++){
			$zerofill.='0';
		}
		$zerofill.=$netinc1_rnd;
		$empaccount=str_replace("-", "", $r->bankAccountNo);
		$a_col='2';											// default
		$a_col.='667';									  // Servicing branch code
		$a_col.='26';										// Bankcode
		$a_col.='001';										// default
		$a_col.='667';									 // payroll branch code
		$a_col.='0000000';								// default
		$a_col.=$lvl;		// Company name
		$a_col.=$blankSpaceCompanyName;
		$a_col.=$empaccount;								// Emmployee account number
		$a_col.=$zerofill;											// payroll amount
		$a_col.='9';														// default
		$a_col.=$companycode;												// Company code
		$a_col.=gmdate('mdY');																//date
		$a_col.=$endcode;	
		$a_col.="\r\n";
		if ($r->bankAccountNo!='') fwrite($handle, $a_col);     /// if account number is not empty write to payroll.dat file
			$cntr++;
	}
	$data.="
	<tr><td colspan='5'><hr></td></tr>
	<tr style='font-weight:bold;font-size:14px;'><td>Total</td><td>".$seq."</td><td colspan='3' align='right'>".number_format($total,2)."</td>
	</tr><tr><td> </td></tr><tr><td> </td></tr>";
		chmod("./",0777);	fclose($handle);
}
echo "<table width='100%' style='font-family:Arial;font-size:12px;'>
<tr><td colspan='5' style='color:red;font-size:13px;'><br><br> Successfully generated bank copy!<br>See below for the readable copy.<br><br><br><br></td></tr>
".$data."</table>";
?>

