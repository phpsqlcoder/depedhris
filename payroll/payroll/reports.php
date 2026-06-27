<?php
session_start();
if(!$_SESSION['ndex']){header("location:../");}
if($_SESSION['deptId']!=0){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.";die();}
ob_start();
include("../dbcon.php");
include("../myfunctions.php");
include("scripts/scripts.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript">
		function addrow(ndex,ndexadd){
				$(window['dev' + ndex]).style.display='block';
				$(window['cn' + ndexadd]).style.display='none';
				$(cntr).value=parseInt($(cntr).value)+1;
		}
	</script>  
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "../calendar.inc"; ?>
	<link href="../css/styles.css" rel="stylesheet" type="text/css" />
    <link href="../css/facebox.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php include "header.php";?>
<form id="frmrpt" name="frmrpt"></form>
<div id="main_content_wrap" class="container_12">
<div id="rcont">
  <h2>Reports</h2>
<table width="100%">
  <tr>
  	<td style="width:50px;">&nbsp;</td>
    <td>
      <table style="color:maroon;font-weight:bold;font-size:12px;">
				<tr style="height:30px;font-size:14px;"><td align="center"><u>Basic Reports</u></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('compensation');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Compensation Master List</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('13thmonthreport');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;13th Month Report</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('bankReport13thmonth');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;13th Month Bank Report</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('bankcopy13thmonth');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;13th Month Bank Copy</a></td></tr>
      </table>
    </td>
	<td>
      <table style="color:maroon;font-weight:bold;font-size:12px;">
			  <tr style="height:30px;font-size:14px;"><td align="center"><u>Government Reports</u></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('sssr3report');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;SSS R3 </a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('phicreport');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;PHIC R3 </a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('hdmfreport');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;HDMF R3 </a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('birreport');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;BIR </a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('phicSummaryreport');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;PHIC Summary by Dept </a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('sssSummaryreport');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;SSS Summary by Dept </a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('hdmfSummaryreport');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;HDMF Summary by Dept </a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('birSummaryreport');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;BIR Summary by Dept </a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('birForm1604CFSchedule73');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;BIR FORM 1604CF SCHEDULE 7.3 </a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('birForm1604CFSchedule71');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;BIR FORM SCHEDULE 7.1 </a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('birForm1604CFSchedule75');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;BIR FORM SCHEDULE 7.5 </a></td></tr>
				<!--<tr style="height:30px"><td><a href="#" onclick="report('SSS_salaryLoan');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;SSS Salaryy Loan</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('SSS_emergencyLoan');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;SSS Emergency Loan</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('HDMF MultipurposeLoanMP3');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;HDMF Multi-Purpose Loans</a></td></tr>-->
      </table>
    </td>
    <td>
      <table style="color:maroon;font-weight:bold;font-size:12px;">
			  <tr style="height:30px;font-size:14px;"><td align="center"><u>Payroll Reports</u></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payrollRegister');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payroll Register</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payrollRegistersalaryonhold');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payroll Register Salary On Hold</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payrollRegistersummarydept');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payroll Register Summary Per Department</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('bankReport');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Bank Report</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('bankcopy');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Generate Bank Copy</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('bankcopyexcel');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Generate Bank Copy (Excel)</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('overtimeReport');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Overtime Report</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payslip');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payslip</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payslip13thMonth');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payslip 13Th Month</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('localcopy');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Received Copy for Payroll </a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payrollRegisterOnIncomeTaxWithHeld');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payroll Register On Income Tax Withheld</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payrollRegisterOnMortuaryAndUnionDues');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payroll Register On MORTUARY AND UNION DUES</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payrollRegisterOnNetEarnings');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payroll Register On Net Earnings</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payrollRegisterOnGrossEarnings');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payroll Register On Gross Earnings</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payrollRegisterOnSSSDeduction');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payroll Register On SSS Deduction</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payrollRegisterOnHDMFDeduction');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payroll Register On HDMF Deduction</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payrollRegisterOnPhilhealthDeduction');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payroll Register On Philhealth Deduction</a></td></tr>
				
				
      </table>
    </td>
	<td>
      <table style="color:maroon;font-weight:bold;font-size:12px;">
			  <tr style="height:30px;font-size:14px;"><td align="center"><u>Deduction Reports</u></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payrollDeductionReport');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payroll Deduction Report</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('otherDeductionReport');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Other Deduction Report</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('deductionCoop');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Coop Deduction Report </a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('deductionMortuary');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Mortuary Deduction Report </a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('deductionUniodDues');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Union Dues Deduction Report </a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('deductionFinancialAssistance');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Financial Assistance Deduction Report </a></td></tr>
      </table>
    </td>
   
  </tr>
  <!-- Next Row-->
   <tr>
  	<td style="width:50px;">&nbsp;</td>
	 <td>
      <table style="color:maroon;font-weight:bold;font-size:12px;">
			  <tr style="height:30px;font-size:14px;"><td align="center"><u>New Reports</u></td></tr>
              	<tr style="height:30px"><td><a href="#" onclick="report('payrollRegistern');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payroll Register</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payrollDeductionReportn');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payroll Deduction Report</a></td></tr>
			<tr style="height:30px"><td><a href="#" onclick="report('payrollDeductionReportnpreview');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Preview Payroll Deduction Report</a></td></tr>
				
      </table>
    </td>
    <td>
      <table style="color:maroon;font-weight:bold;font-size:12px;">
				<tr style="height:30px;font-size:14px;"><td align="center"><u>Annual Reports</u></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('sickLeaveConversion');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Sick Leave Conversion</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('payslipSickLeaveConversion');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Payslip SL Conversion</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('YTDPayrollRegister');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;YTD Payroll Register</a></td></tr>
				<tr style="height:30px"><td><a href="#" onclick="report('employeeYTDPayrollRegister');" style="text-decoration:none;"><img src="../images/report1.png" height="15" width="15">&nbsp;Employee YTD Payroll Register</a></td></tr>
				
      </table>
    </td>
	 <td></td>
    <td></td> <td></td>
    <td></td>
	
  </tr>
</table> 
  <h2>&nbsp;</h2>
	</div>
    <?php include "footer.php";?>
  </div>
</body>
</html>
