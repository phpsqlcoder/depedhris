<?php
ob_start();
$conn=mysql_connect("localhost","root","pangitka");
mysql_select_db("hris",$conn);
function getID($empStatus,$empNo){
	if($empStatus=='Regular'){
		$tayp='';
	}
	elseif($empStatus=='Temporary'){
		$tayp='TMP';
	}
	elseif($empStatus=='Reliever'){
		$tayp='REL';
	}
	elseif($empStatus=='Probationary'){
		$tayp='PRO';
	}
	else{$tayp='';}
	$len=strlen($empNo);
	$len=6-$len;
	for($i=1;$i<=$len;$i++){
		$num.="0";
	}
	$empID=$tayp.$num.$empNo;
	return $empID;
}
require_once '../../../excel/Classes/PHPExcel.php';
	
$objPHPExcel = new PHPExcel();
$titled=$_POST['division']." - ".$_POST['PayrollCutoff'];
$objPHPExcel->getProperties()->setCreator("HRIS System")
							 ->setLastModifiedBy("HRIS System")
							 ->setTitle($titled)
							 ->setSubject($titled)
							 ->setDescription($titled)
							 ->setKeywords($titled)
							 ->setCategory($titled);




$folder=$_POST['division'];
	if($folder=='contractual'){
		$genqry="select p.*,e.bankAccountNo,e.level,e.lastName,e.firstName,e.middleName,e.ndex as empId,e.employmentStatus,e.employeeNo from payroll p left join employee e on e.ndex=p.empid  where p.pay_period='".$_POST['PayrollCutoff']."' and p.basicpay>0 and p.holdSalary<>'1' and e.bankAccountNo<>'' and e.`level`=0 and e.employmentStatus='Temporary' and e.residencyTrainingProgram<>'ROD' order by e.lastName,e.firstName";
		$filename="bankcopy/contractual/".$_POST['PayrollCutoff']."";
		$lvl='DAVAO DOCTORS - CONTRACTUAL';
		$companycode="00005";
		$endcode="";
	}
	elseif($folder=='rankandfile'){
		$genqry="select p.*,e.bankAccountNo,e.level,e.lastName,e.firstName,e.middleName,e.ndex as empId,e.employmentStatus,e.employeeNo from payroll p left join employee e on e.ndex=p.empid  where p.pay_period='".$_POST['PayrollCutoff']."' and p.basicpay>0 and e.bankAccountNo<>'' and p.holdSalary<>'1'  and e.`level` in (1,2) and e.employmentStatus <> 'Temporary' and e.residencyTrainingProgram<>'ROD' order by e.lastName,e.firstName";
		$filename="bankcopy/rankandfile/".$_POST['PayrollCutoff']."";
		$lvl='DAVAO DOCTORS - RANK AND FILE';
		$companycode="00004";
		$endcode="";
	}
	elseif($folder=='resident'){
		$genqry="select p.*,e.bankAccountNo,e.level,e.lastName,e.firstName,e.middleName,e.ndex as empId,e.employmentStatus,e.employeeNo from payroll p left join employee e on e.ndex=p.empid  where p.pay_period='".$_POST['PayrollCutoff']."' and p.basicpay>0 and e.bankAccountNo<>'' and p.holdSalary<>'1'  and e.`level`=0 and e.employmentStatus<>'Temporary' and e.residencyTrainingProgram='ROD' order by e.lastName,e.firstName";
		$filename="bankcopy/resident/".$_POST['PayrollCutoff']."";
		$lvl='DAVAO DOCTORS - RESIDENT';
		$companycode="00003";
		$endcode="";
	}
	elseif($folder=='sectionheads'){
		$genqry="select p.*,e.bankAccountNo,e.level,e.lastName,e.firstName,e.middleName,e.ndex as empId,e.employmentStatus,e.employeeNo from payroll p left join employee e on e.ndex=p.empid  where p.pay_period='".$_POST['PayrollCutoff']."' and p.basicpay>0 and e.bankAccountNo<>'' and p.holdSalary<>'1'  and e.`level` in (3,4,5) and e.employmentStatus <> 'Temporary' and e.residencyTrainingProgram<>'ROD' order by e.lastName,e.firstName";
		$filename="bankcopy/sectionheads/".$_POST['PayrollCutoff']."";
		$lvl='DAVAO DOCTORS - OFFICERS';
		$companycode="00003";
		$endcode="";
	}
	
	
	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Company Name')
            ->setCellValue('A2', 'Company Code')
            ->setCellValue('A3', "Company's Depository Branch Code")
            ->setCellValue('A4', 'Effectivity Date(MM/DD/YYYY)')
			 ->setCellValue('B1', $lvl)
            ->setCellValue('B2', $companycode)
            ->setCellValue('B3', '667')
            ->setCellValue('B4', date('m/d/Y',strtotime($_POST['PayrollCutoff'])))
			->setCellValue('A6', 'Employee Code')
			->setCellValue('B6', 'Employee Name')
			->setCellValue('C6', 'Branch Code')
			->setCellValue('D6', 'Payroll Acct. No.')
			->setCellValue('E6', 'Amount')
			;
	$seq=6;
	$total=0;
	$totot=0;
	$qry=mysql_query($genqry); 
	while($r=mysql_fetch_object($qry)){
		$grossPay = $r->netBasic + $r->cola + $r->allowance + $r->honorarium + $r->payNightPremium + $r->payOTReg + $r->payOTExc + $r->oth_income + $r->onCallOvertime + $r->payDutyRd + $r->paySpHoliday + $r->payLHoliday - $r->payUndertime + $r->adj_other + $r->otRDLHolidayPay + $r->otRDSHolidayPay + $r->otLHolidayPay + $r->otSHolidayPay + $r->otRestDayPay + $r->hazardPay + $r->incentive + $r->adj_13th_mon_pay;
		//$grossPay = $r->netBasic + $r->cola + $r->allowance + $r->honorarium + $r->payNightPremium + $r->payOTReg + $r->oth_income + $r->onCallOvertime + $r->payDutyRd + $r->paySpHoliday + $r->payLHoliday + $r->payOTExc + $r->otRDLHolidayPay + $r->otRDSHolidayPay + $r->otLHolidayPay + $r->otSHolidayPay + $r->otRestDayPay - $r->payUndertime + $r->adj_other;
//		$totalDeduction = $r->d_pnb + $r->d_parkingFee + $r->d_whtax + $r->d_sss + $r->d_philhealth + $r->pagibig + $r->d_unionDues + $r->d_mortuary + $r->d_sssloan + $r->d_hospital + $r->d_cashAdvance + $r->d_other + $r->d_coopTotal  + $r->financialAssistance;
		$totalDeduction = $r->d_pnb + $r->d_parkingFee + $r->d_whtax + $r->d_sss + $r->d_philhealth + $r->pagibig + $r->pagibigloan + $r->pagibigloanh +$r->d_unionDues + $r->d_mortuary + $r->d_sssloan+ $r->d_hospital + $r->d_cashAdvance + $r->d_other + $r->d_coopTotal + $r->financialAssistance + $r->['pagibigSavings'];
		$netPay = $grossPay - $totalDeduction;
		$empaccount=str_replace("-", "", $r->bankAccountNo);
		if ($r->bankAccountNo!=''){
			$seq++;
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A$seq",  getID($r->employmentStatus,$r->employeeNo))
			->setCellValue("B$seq", $r->lastName." ".$r->firstName." ".$r->middleName)
			->setCellValue("C$seq", '667')
			->setCellValue("D$seq", $empaccount)
			->setCellValue("E$seq", number_format($netPay,2));
			$totot+=$netpay;
		}
		
	}
	$seq++;
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$seq", number_format($totot,2));
$objPHPExcel->getActiveSheet()->setTitle('Data');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$titled.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>

