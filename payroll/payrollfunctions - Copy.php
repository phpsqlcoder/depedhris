<?php
session_start();
	
	function getDeductionData($employeeId,$loanId,$type){
		$l=mysql_fetch_object(mysql_query("select * from loandeductionmaintenance where ndex=".$loanId.""));
		$loanqry=mysql_query("select * from loan_employee where employeeId=".$employeeId." and loanId=".$l->ndex." and posted='1' ORDER BY ndex");
		$totalLoan=0;
		$totalNoOfDeduction=0;
		$totalNoOfPayments=0;
		$runningBalance=0;
		$var=0;
		$totalPaymentsAmount=0;
		$loandates[$var]='2000-01-01';
		$ledger.="<table width='100%' style='font-style:Arial;font-size:14px;'><tr style='font-weight:bold;color:blue;'><td>".$l->name."</td><td>Date</td><td align='right'>Amount</td><td align='right'>Balance</td></tr><tr><td colspan='6'><hr></td></tr>";
		while($loan=mysql_fetch_object($loanqry)){
			$var++;
			$totalLoan+=$loan->loanAmount;
			$totalNoOfDeduction+=$loan->nOfDeduction;
			$loandates[$var]=$loan->dedDateStart;
			$runningBalance+=$loan->loanAmount;	
			$ledger.="<tr>	
							<td>&nbsp;</td>
							<td>".$loan->dedDateStart."</td>
							<td align='right'>".number_format($loan->loanAmount,2)."</td>
							<td align='right'>".number_format($runningBalance,2)."</td>	
				</tr>";
			$loanpaymentsqry=mysql_query("select * from loan_employee_payments where employeeId=".$employeeId." and loanId=".$l->ndex." and datePaid<='".$loandates[$var]."' and datePaid>'".$loandates[$var - 1]."' ORDER BY ndex");
			while($lp=mysql_fetch_object($loanpaymentsqry)){
				$totalNoOfPayments++;
				$totalPaymentsAmount+=$lp->amountPaid;
				$runningBalance=$runningBalance - $lp->amountPaid;
				$ledger.="<tr>
							<td>&nbsp;</td>
							<td>".$lp->datePaid."</td>
							<td align='right'>".number_format(($lp->amountPaid * -1),2)."</td>
							<td align='right'>".number_format($runningBalance,2)."</td>	
				</tr>";
			}
		}
		$ledger.="</table>";
		if($type=='ledger'){
			return $ledger;	
		}
		elseif($type=='Currect Deduction Amount'){
			$currentDeductionAmount=$runningBalance / ($totalNoOfDeduction - $totalNoOfPayments);
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

?>


