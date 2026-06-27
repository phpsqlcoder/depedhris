<?php
session_start();
	function getDeductionData($loanId,$type){
		//$l=mysql_fetch_object(mysql_query("select * from loandeductionmaintenance where ndex=".$loanId.""));
		$loanqry=mysql_query("select l.*,m.name as deduction from loan_employee l left join loandeductionmaintenance m on m.ndex=l.loanId where l.ndex='".$loanId."' and l.isDeleted='0' and l.posted='1'");
		//$ledger="select l.*,m.name as deduction from loan_employee left join loandeductionmaintenance m on m.ndex=l.loanId where l.ndex='".$loanId."' and l.posted='1'";
		$totalLoan=0;
		$totalNoOfDeduction=0;
		$totalNoOfPayments=0;
		$runningBalance=0;
		$var=0;
		$totalPaymentsAmount=0;
		$loandates[$var]='2000-01-01';

		while($loan=mysql_fetch_object($loanqry)){
			$ledger.="<table width='100%' style='font-style:Arial;font-size:14px;'><tr style='font-weight:bold;color:blue;'><td>".$loan->deduction."</td><td>Date</td><td align='right'>Debit</td><td align='right'>Credit</td><td align='right'>Balance</td></tr><tr><td colspan='6'><hr></td></tr>";
			$var++;
			$totalLoan+=$loan->loanAmount;
			$totalNoOfDeduction+=$loan->nOfDeduction;
			$loandates[$var]=$loan->dedDateStart;
			$runningBalance+=$loan->loanAmount;	
			$ledger.="<tr>	
							<td>&nbsp;</td>
							<td>".$loan->dedDateStart."</td>
							<td align='right'>".number_format($loan->loanAmount,2)."</td>
							<td>&nbsp;</td>
							<td align='right'>".number_format($runningBalance,2)."</td>	
				</tr>";
			$loanpaymentsqry=mysql_query("select * from loan_employee_payments where loanSetupId=".$loanId." and datePaid>='".$loandates[$var]."' ORDER BY ndex");
			while($lp=mysql_fetch_object($loanpaymentsqry)){
				if($lp->remarks){
					$remark=$lp->remarks;
				}else{
					$remark='Payroll Deduction';
					$totalNoOfPayments++;
				}
				$totalPaymentsAmount+=$lp->amountPaid;
				$runningBalance=$runningBalance - $lp->amountPaid;
				$ledger.="<tr>
							<td>".$remark."</td>
							<td>".$lp->datePaid."</td>
							<td>&nbsp;</td>
							<td align='right'>".number_format(($lp->amountPaid),2)."</td>
							<td align='right'>".number_format($runningBalance,2)."</td>	
				</tr>";
			}
		}
		$ledger.="</table>";
		if($type=='ledger'){
			return $ledger;	
		}
		elseif($type=='Currect Deduction Amount'){
			if(($totalNoOfDeduction - $totalNoOfPayments)!=0){
				$currentDeductionAmount=$runningBalance / ($totalNoOfDeduction - $totalNoOfPayments);
			}
			else{
				$currentDeductionAmount=0;
			}
			return $currentDeductionAmount;
		}
		elseif($type=='current balance'){
			return $runningBalance;
		}
		elseif($type=='total payments made'){
			return $totalPaymentsAmount;
		}
		elseif($type=='total number of payments made'){
			return $totalNoOfPayments;
		}
		elseif($type=='total number of deduction'){
			return $totalNoOfDeduction;
		}
		elseif($type=='total loan amount'){
			return $totalLoan;
		}
		elseif($type=='remaining number of deduction'){
			return $totalNoOfDeduction - $totalNoOfPayments;
		}
	}

	function estimatedDateOfCompletion($loanId){
		$l=mysql_fetch_object(mysql_query("select l.*,m.name as deduction from loan_employee l left join loandeductionmaintenance m on m.ndex=l.loanId where l.ndex=".$loanId." and l.posted='1'"));
		$lastpayment=mysql_fetch_object(mysql_query("select * from loan_employee_payments where loanSetupId=".$loanId." order by datePaid DESC"));
		return $lastpayment->datePaid;
	}

	// Annual Gross Pay
	function AnnualGrossPay($employeeId,$year){  /// + `cola` + `allowance` + `honorarium` 
		$rs = mysql_fetch_object(mysql_query("SELECT SUM( grossPay) AS annualGrossPay FROM `payroll` WHERE DATE_FORMAT( `pay_period` , '%Y' ) = '{$year}' && `empid` = '{$employeeId}'"));
		return $rs->annualGrossPay;
	}

	// Annual SSS Deduction
	function AnnualSSSDeduction($employeeId,$year){
		$rs = mysql_fetch_object(mysql_query("SELECT SUM( d_sss ) AS annualSSSDeduction FROM `payroll` WHERE DATE_FORMAT( `pay_period` , '%Y' ) = '{$year}' && `empid` = '{$employeeId}'"));
		return $rs->annualSSSDeduction;
	}
	
	// Annual Philhealth Deduction
	function AnnualPHICDeduction($employeeId,$year){
		$rs = mysql_fetch_object(mysql_query("SELECT SUM( d_philhealth ) AS annualPHICDeduction FROM `payroll` WHERE DATE_FORMAT( `pay_period` , '%Y' ) = '{$year}' && `empid` = '{$employeeId}'"));
		return $rs->annualPHICDeduction;
	}
	
	// Annual Pagibig Deduction
	function AnnualPagibigDeduction($employeeId,$year){
		$rs = mysql_fetch_object(mysql_query("SELECT SUM( pagibig ) AS annualPagibigDeduction FROM `payroll` WHERE DATE_FORMAT( `pay_period` , '%Y' ) = '{$year}' && `empid` = '{$employeeId}'"));
		return $rs->annualPagibigDeduction;
	}
	
	// Annual Union Deduction
	function AnnualUnionDuesDeduction($employeeId,$year){
		$rs = mysql_fetch_object(mysql_query("SELECT SUM( d_unionDues ) AS annualUnionDuesDeduction FROM `payroll` WHERE DATE_FORMAT( `pay_period` , '%Y' ) = '{$year}' && `empid` = '{$employeeId}'"));
		return $rs->annualUnionDuesDeduction;
	}
	
	// Annual 13th Month
	function Annual13thMothPay($employeeId,$year){
		$rs = mysql_fetch_object(mysql_query("SELECT SUM( grosspay ) AS annual13thMonthPay FROM `payroll_13thmonth` WHERE DATE_FORMAT( `dyt` , '%Y' ) = '{$year}' && `empId` = '{$employeeId}'"));
		return $rs->annual13thMonthPay;
	}
	
	// Annual Tax Due Jan. To Dec.
	function AnnualTaxDue($employeeId,$year){
		$rs = mysql_fetch_object(mysql_query("SELECT SUM( d_whtax  ) AS annualTaxDue FROM `payroll` WHERE DATE_FORMAT( `pay_period` , '%Y' ) = '{$year}' && `empid` = '{$employeeId}'"));
		return $rs->annualTaxDue;
	}
	
	function UnusedLeave($employeeId,$year){
		$leave = mysql_fetch_assoc(mysql_query("SELECT SUM(leaveLimit) allowedLeave FROM employee_leave_limit WHERE employeeId='{$employeeId}' && year='{$year}' && leaveId='10'"));  // ALLOWED NO OF SICK LEAVE
		$usedLeave = mysql_fetch_assoc(mysql_query("SELECT COUNT(*) usedLeave FROM employee_leave WHERE employeeId='{$employeeId}' && YEAR(startDate)='{$year}' && leaveId='10'"));		// USED  SICK LEAVE
		$unusedLeave = $leave['allowedLeave'] - $usedLeave['usedLeave'];
		return $unusedLeave;
	}
	
	// Minimum Wage
	function GetMinimumWage($payType){
		switch ($payType) {
			case "1":
				
		}
	}
?>


