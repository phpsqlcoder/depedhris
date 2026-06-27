<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../employeefunctions.php");
include("../../../myfunctions.php");
include("../../scripts/scripts.php");

//clear Sap Payroll Journal Entry Table for the selected period
$rs = mysql_query("DELETE FROM sap_payroll_journal_entry WHERE payrolDate='".$_POST['PayrollCutoff']."'");
$rs = mysql_query("ALTER TABLE `sap_payroll_journal_entry` AUTO_INCREMENT =1");



$cutoffDate = mysql_fetch_assoc(mysql_query("SELECT * FROM cutoffdates WHERE payrollDate='".$_POST['PayrollCutoff']	."'",$conn));
for ($cx=1; $cx <= 3; $cx++){
	// Process Payroll Journal Per Company

	$subTotald_whtax = 0;
	$subTotalNetPay = 0;
	$subTotalCoop = 0;
	$subTotalAllowIncen = 0;
	$subTotalpagibigloan = 0;
	$subTotalpagibig = 0;
	$subTotald_sss = 0;
	$subTotald_sssloan = 0;
	$subTotalMortuary = 0;
	$subTotalUnionDues = 0;
	
	$sql = "SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, p.empid, p.*,
				(CASE WHEN d.name LIKE 'Annex%'	THEN 'ANNEX 4' ELSE d.name END ) AS departmentName
			FROM employee e 
				LEFT JOIN payroll p ON p.empid = e.ndex 
				LEFT JOIN dept d ON d.ndex = e.deptId
					WHERE p.pay_period='".$_POST['PayrollCutoff']."' && p.residencyTrainingProgram='' && p.basicpay<>0 && p.holdSalary<>'1'";

	if ($cx == 1){
		$sql .= " && p.level IN (0)";
		$reportTitle = 'TEMPORARY';
	} elseif ($cx == 2) {
		$sql .= " && p.level IN (1,2)";
		$reportTitle = 'RANK & FILE';
	} elseif ($cx == 3) {
		$sql .= " && p.level IN (3,4,5,6,7,8,9)";
		$reportTitle = 'SECTION HEADS AND CONFI';
	}

	echo $reportTitle."<br><br>";

	$sql.=" ORDER BY  d.name, e.lastName,e.firstName";
	$exec=mysql_query($sql);
	$var=0;
	$ln = 0;
	$rowCount = mysql_num_rows($exec);

	while($r=mysql_fetch_assoc($exec)){
		$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['payOTExc'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] - $r['payUndertime'] +  $r['adj_other'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'];
		//$grossPay = $r['netBasic'] + $r['cola'] + $r['allowance'] + $r['honorarium'] + $r['payNightPremium'] + $r['payOTReg'] + $r['payOTExc'] + $r['oth_income'] + $r['onCallOvertime'] + $r['payDutyRd'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'] - $r['payUndertime'] +  $r['adj_other'];
		$totalDeduction = $r['d_pnb'] + $r['d_parkingFee'] + $r['d_whtax'] + $r['d_sss'] + $r['d_philhealth'] + $r['pagibig'] + $r['pagibigloan'] + $r['pagibigloanh'] + $r['d_unionDues'] + $r['d_mortuary'] + $r['d_sssloan'] + $r['d_hospital'] + $r['d_cashAdvance'] + $r['d_other'] + $r['d_coopTotal'] + $r['financialAssistance'] + $r['pagibigSavings'];
		$netPay = $grossPay - $totalDeduction;
		if ($netPay < ($grossPay * 0.30)){
			if ($r['d_coopTotal'] > (($grossPay * 0.30) - $netPay)){
				$coopDedCurPayroll = $r['d_coopTotal'] - (($grossPay * 0.30) - $netPay);
				$updpayroll = mysql_query("UPDATE payroll SET d_coopTotal='".$coopDedCurPayroll."' WHERE empid='".$r['empid']."' && pay_period='".$_POST['PayrollCutoff']."'",$conn);
				//echo "UPDATE payroll SET d_coopTotal='".$coopDedCurPayroll."' WHERE empid='".$r['empid']."' && pay_period='".$_POST['PayrollCutoff']."'";
				$netPay += (($grossPay * 0.30) - $netPay);
				$r['d_coopTotal'] = $coopDedCurPayroll;
			}
		} 
		$var++;
		$ctr1s++;
		$ln++;
		
		// extract cola undertime on payUndertime
		$colaUndertimeAmount = $r['undertime'] != 0 ? ((($r['cola'] / $r['days_work']) / 8) * $r['undertime']) : 0;
		
		if($r['departmentName'] != $prevDepartment){
			if($ln != 1){
				
				$sapDeptMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM `sap_gl_departmentmapping` a, dept b WHERE b.ndex=a.departmentId AND b.name='".$prevDepartment."'"));


                //NET BASIC = Basic Pay
				if ($subTotalNetBasic <> 0){
					$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='NET BASIC'"));
					
					$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '".$subTotalNetBasic."', '', '', '".$sapDeptMapping['organization']."', '".$sapDeptMapping['department']."', '".$sapDeptMapping['profitCenter']."', '".$sapDeptMapping['section']."', '".$sapDeptMapping['IPOP']."','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
				}

                //COLA 
				if ($subTotalCola <> 0){
					$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='COLA'"));
					$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '".$subTotalCola."', '', '', '".$sapDeptMapping['organization']."', '".$sapDeptMapping['department']."', '".$sapDeptMapping['profitCenter']."', '".$sapDeptMapping['section']."', '".$sapDeptMapping['IPOP']."','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
				}

                //ALLOWANCE 
				//if ($subTotalAllowIncen <> 0){
				//	$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='ALLOWANCE'"));
				//	$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '".$subTotalAllowIncen."', '', '', '".$sapDeptMapping['organization']."', '".$sapDeptMapping['department']."', '".$sapDeptMapping['profitCenter']."', '".$sapDeptMapping['section']."', '".$sapDeptMapping['IPOP']."','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
				//}

                //HONORARIUM 
				//if ($subTotalAllowHonorarium <> 0){
				//	$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='HONORARIUM'"));
				//	$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '".$subTotalAllowHonorarium."', '', '', '".$sapDeptMapping['organization']."', '".$sapDeptMapping['department']."', '".$sapDeptMapping['profitCenter']."', '".$sapDeptMapping['section']."', '".$sapDeptMapping['IPOP']."','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
				//}

				//OVERTIME 
				if ($subTotalOvertime <> 0){
					$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='OVERTIME'"));
					$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '".$subTotalOvertime."', '', '', '".$sapDeptMapping['organization']."', '".$sapDeptMapping['department']."', '".$sapDeptMapping['profitCenter']."', '".$sapDeptMapping['section']."', '".$sapDeptMapping['IPOP']."','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
				}

				//HAZARD PAY 
				//if ($subtotalHazardPay <> 0){
				//	$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='HAZARD PAY'"));
				//	$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '".$subtotalHazardPay."', '', '', '".$sapDeptMapping['organization']."', '".$sapDeptMapping['department']."', '".$sapDeptMapping['profitCenter']."', '".$sapDeptMapping['section']."', '".$sapDeptMapping['IPOP']."','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
				//}

				//NIGHT PREMIUM   
				if ($subTotalPayNightPremium <> 0){
					$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='NIGHT PREMIUM'"));
					$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '".$subTotalPayNightPremium."', '', '', '".$sapDeptMapping['organization']."', '".$sapDeptMapping['department']."', '".$sapDeptMapping['profitCenter']."', '".$sapDeptMapping['section']."', '".$sapDeptMapping['IPOP']."','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
				}

				//INCENTIVE   
				if ($subTotalAllowIncen <> 0){
					$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='INCENTIVE'"));
					$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '".$subTotalAllowIncen."', '', '', '".$sapDeptMapping['organization']."', '".$sapDeptMapping['department']."', '".$sapDeptMapping['profitCenter']."', '".$sapDeptMapping['section']."', '".$sapDeptMapping['IPOP']."','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
				}

//*************** DEDUCTIONS  **********************************************************//

				
				



				
				$subTotalBasicPay = 0;
				$subTotalNetBasic = 0;
				$subTotalCola = 0;
				
				$subTotalAllowHonorarium = 0;
				$subTotalPayUndertime = 0;
				$subTotalPayNightPremium = 0;
				$subTotalOvertime = 0;
				$subTotalAdjustment = 0;
				$subtotalHazardPay = 0;
				$subTotalGrossPay = 0;
				//
				
				$subTotald_philhealth = 0;
				
				$subTotalUnioMort = 0;
				
				
				$subTotald_hospital = 0;
				$subTotald_cashAdvance = 0;
				
				$subTotalOthers = 0;
				$subTotalTotalDed = 0;
				
				
				
		
			}
			
		}		
		if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
				 
		$prevDepartment = $r['departmentName'];
		$subTotalBasicPay += $r['basicpay'];
		$subTotalNetBasic += $r['netBasic']  - $r['payUndertime'] + $colaUndertimeAmount;
		$subTotalCola += $r['cola'] - $colaUndertimeAmount;
		$subTotalAllowIncen += $r['allowance'] + $r['hazardPay'] + $r['incentive'] + $r['honorarium'];
		$subTotalAllowHonorarium += $r['honorarium'];
		//$subTotalPayUndertime += $r['payUndertime'];
		$subTotalPayNightPremium += $r['payNightPremium'];
		$subTotalOvertime += $r['payOTExc'] + $r['payOTReg'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payDutyRd'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'];
		$subTotalAdjustment += $r['oth_income']  + $r['onCallOvertime']  + $r['adj_other'];
		$subtotalHazardPay += $r['hazardPay'];
		$subTotalGrossPay += $grossPay;
		$subTotald_whtax += $r['d_whtax'];
		$subTotald_sss += $r['d_sss'];
		$subTotald_philhealth += $r['d_philhealth'];
		$subTotalpagibig += $r['pagibig'] + $r['pagibigSavings'];
		$subTotalUnionDues += $r['d_unionDues'];
		$subTotalMortuary += $r['d_mortuary'];
		$subTotald_sssloan += $r['d_sssloan'];
		$subTotalpagibigloan += $r['pagibigloan'] + $r['pagibigloanh'];
		$subTotald_hospital += $r['d_hospital'];
		$subTotald_cashAdvance += $r['d_cashAdvance'];
		$subTotalCoop += $r['d_coopTotal'];
		$subTotalOthers += ($r['d_other'] + $r['d_pnb'] + $r['d_parkingFee'] + $r['financialAssistance']);
		$subTotalTotalDed += $totalDeduction;
		$subTotalNetPay += $netPay;
		
		//GRAND TOTAL
		$grandTotalBasicPay += $r['basicpay'];
		$grandTotalNetBasic += $r['netBasic'] - $r['payUndertime'] + $colaUndertimeAmount;
		$grandTotalCola += $r['cola'] - $colaUndertimeAmount;
		$grandTotalAllowIncen += $r['allowance'];
		$grandTotalAllowHonorarium += $r['honorarium'];
		//$grandTotalPayUndertime += $r['payUndertime'];
		$grandTotalPayNightPremium += $r['payNightPremium'];
		$grandTotalOvertime += $r['payOTExc'] + $r['payOTReg'] + $r['paySpHoliday'] + $r['payLHoliday'] + $r['payDutyRd'] + $r['otRDLHolidayPay'] + $r['otRDSHolidayPay'] + $r['otLHolidayPay'] + $r['otSHolidayPay'] + $r['otRestDayPay'];
		$grandTotalAdjustment += $r['oth_income'] + $r['onCallOvertime']  + $r['adj_other'];
		$grandTotalHazardPay += $r['hazardPay'];
		$grandTotalGrossPay += $grossPay;
		$grandTotald_whtax += $r['d_whtax'];
		$grandTotald_sss += $r['d_sss'];
		$grandTotald_philhealth += $r['d_philhealth'];
		$grandTotalpagibig += $r['pagibig'] + $r['pagibigSavings'];
		$grandTotalUnioMort += $r['d_unionDues'] + $r['d_mortuary'];
		$grandTotald_sssloan += $r['d_sssloan'];
		$grandTotalpagibigloan += $r['pagibigloan'];
		$grandTotald_hospital += $r['d_hospital'];
		$grandTotald_cashAdvance += $r['d_cashAdvance'];
		$grandTotalCoop += $r['d_coopTotal'];
		$grandTotalOthers += ($r['d_other'] + $r['d_pnb'] + $r['d_parkingFee'] + $r['financialAssistance']);
		$grandTotalTotalDed += $totalDeduction;
		$grandTotalNetPay += $netPay;
	}

	//Witholding Tax
	if ($subTotald_whtax <> 0){
		$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='WITHHOLDING TAX'"));
		$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '', '".$subTotald_whtax."', '', 'DDHMain', '--', '---', '----', '-----','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
	}

	//UNION DUES 
	if ($subTotalUnionDues <> 0){
		$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='UNION DUES'"));
		$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '', '".$subTotalUnionDues."', '', 'DDHMain', '--', '---', '----', '-----','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
	}

	//MORTUARY
	if ($subTotalMortuary <> 0){
		$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='MORTUARY'"));
		$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '', '".$subTotalMortuary."', '', 'DDHMain', '--', '---', '----', '-----','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
	}

	//SSS SALARY LOAN  
	if ($subTotald_sssloan <> 0){
		$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='SSS SALARY LOAN'"));
		$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '', '".$subTotald_sssloan."', '', 'DDHMain', '--', '---', '----', '-----','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
	}

	//SS WITHHELD   
	if ($subTotald_sss <> 0){
		$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='SSS WITHHELD'"));
		$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '', '".$subTotald_sss."', '', 'DDHMain', '--', '---', '----', '-----','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
	}

	//PAGIBIG WITHHELD   
	if ($subTotalpagibig <> 0){
		$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='PAGIBIG WITHHELD'"));
		$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '', '".$subTotalpagibig."', '', 'DDHMain', '--', '---', '----', '-----','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
	}

	//PAGIBIG LOANS (SUMMARY OF SALARY, HOUSNG, CALAMITY)  
	if ($subTotalpagibigloan <> 0){
		$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='PAGIBIG SALARY LOAN'"));
		$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '', '".$subTotalpagibigloan."', '', 'DDHMain', '--', '---', '----', '-----','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
	}

	//AR PATIENT EMPLOYEES (HOSPITAL)   
	if ($subTotald_hospital <> 0){
		$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='AR PATIENT EMPLOYEES (HOSPITAL)'"));
		$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '', '".$subTotald_hospital."', '', 'DDHMain', '--', '---', '----', '-----','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
	}

	//DDCOOP   
	if ($subTotalCoop <> 0){
		$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='DDCOOP'"));
		$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '', '".$subTotalCoop."', '', 'DDHMain', '--', '---', '----', '-----','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
	}

	//MBTC (NETPAY)
	if ($subTotalNetPay <> 0){
		$sapAccountMapping = mysql_fetch_assoc(mysql_query("SELECT * FROM sap_gl_accountmap  WHERE payrollAccountName='MBTC'"));
		$rs = mysql_query("INSERT INTO `sap_payroll_journal_entry` (`payrolDate` , `groupLevel` , `bpCode` , `bpName` , `controlAccount` , `debit` , `credit` , `project` , `organization` , `department` , `profitCenter` , `section` , `IPOP` , `remarks` , `paymentBlock` , `blockReason` , `paymentOrderRun` , `SLCode` , `OBP` , `governmentSales` , `dateGenerated` , `generatedBy` ) VALUES ('".$_POST['PayrollCutoff']."', '".$reportTitle."', '".$sapAccountMapping['glAccountCode']."', '".$sapAccountMapping['glAccontName']."','".$sapAccountMapping['glControlAccountCode']."', '', '".$subTotalNetPay."', '', 'DDHMain', '--', '---', '----', '-----','".$reportTitle." Payroll ".date('F d, Y',strtotime($_POST['PayrollCutoff']))."', 'N', '', 'N', '', '', '',CURRENT_TIMESTAMP , '".$_SESSION['nym']."')");
	}

}

$rs = mysql_query("INSERT INTO hr_interface_db.sap_payroll_journal_entry Select a.* from hris.sap_payroll_journal_entry a WHERE ndex NOT IN (SELECT ndex FROM hr_interface_db.sap_payroll_journal_entry)");





?>
	
DONE........<br><br>
