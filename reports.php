<?php include "header.php";?>
<form id="frmrpt" name="frmrpt"></form>
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-2">
		<div>
			<h1 class="h4 fw-bold text-dark mb-1">Reports</h1>
			<p class="text-muted small mb-0">DTR and Record processing tools</p>
		</div>
		
	</div>
	<div class="workspace-card p-4">
<div id="rcont">

<table width="100%">
  <tr>
  	<td style="width:50px;">&nbsp;</td>
    <td valign="top">
      <table style="color:maroon;font-weight:bold;font-size:12px;">
	<tr style="height:30px;font-size:14px;"><td align="center"><u>Employee 201 Reports</u></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('bi');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;User Define Report</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('birthday');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Birthday(Monthly)</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('sex');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;By Gender</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('divdept');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;By Div/Dept</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('position');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;By Position</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('category');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;By Category</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('paytype');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;By Paytype</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('bloodtype');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;By BloodType</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('civilstatus');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;By Civil Status</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('dependents');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;By Dependents</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('nationality');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;By Nationality</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('religion');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;By Religion</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('age_gender');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;By Age and Gender</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('los');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;LOS</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('employmentbackground');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Employment Background</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('educationalbackground');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Educational Background</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('employeecount');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Manning Headcount</a></td></tr>

	<tr style="height:30px"><td><a href="#" onclick="report('promotionhistory');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Promotion History</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('personnelmovement');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Employment Record</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('incaseofemergency');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Employee Contacts In Case of Emergency</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('trainingrecord');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Employee Training Record</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('medicalrecord');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Employee Medical Record</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('specialskills');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Special Skills and Talents</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('resignlist');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;List of Resigned Employees</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('dependents_health');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Dependents (Hospital Benefits)</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('leavelimit');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Leave Ledger</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('employee201');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Employee 201</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('deleted_logs');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Deleted Logs</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('ptsr_inday');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;PTSR (Date Range)</a></td></tr>
	
	<tr style="height:30px"><td><a href="#" onclick="report('manningsummary');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Summary of Manning</a></td></tr>
	<tr style="height:30px"><td><a href="#" onclick="report('meals');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Meals Report</a></td></tr>
      </table>
    </td>
    <td valign="top">
      <table style="color:maroon;font-weight:bold;font-size:12px;">
	  <tr style="height:30px;font-size:14px;"><td align="center"><u>Monthly Reports</u></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('time_summary');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Time Summary Report</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('late_monthly');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Late Report</a></td></tr>
	  
	  <tr style="height:30px"><td><a href="#" onclick="report('nolate_monthly');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;No Late Report</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('absent_monthly');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Absent Report</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('absent');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Absent Report (Marecil)</a></td></tr>
		<tr style="height:30px"><td><a href="#" onclick="report('leave_monthly');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Leave Report</a></td></tr>
		<tr style="height:30px"><td><a href="#" onclick="report('leave_yearly');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Sick Leave Report (Yearly)</a></td></tr>
		<tr style="height:30px"><td><a href="#" onclick="report('usedleave');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Used Leave Report</a></td></tr>
		<tr style="height:30px"><td><a href="#" onclick="report('kiosk_logs');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Kiosk Visit Logs</a></td></tr>
		<tr style="height:30px"><td><a href="#" onclick="report('kiosk_login');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Kiosk Login Logs</a></td></tr>
		<tr style="height:30px"><td><a href="#" onclick="report('online_application');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Online Application Summary</a></td></tr>
		<tr style="height:30px"><td><a href="#" onclick="report('sickleaves');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Sick Leave Report</a></td></tr>
	
      </table>
<br><br>
           <table style="color:maroon;font-weight:bold;font-size:12px;">
	  <tr style="height:30px;font-size:14px;"><td align="center"><u>Annual Actual Duty Reports</u></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('duty_employee_total');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Total Duty per Employee</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('duty_employee_details');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Total Duty per Employee (Detailed)</a></td></tr>
	  
	  <tr style="height:30px"><td><a href="#" onclick="report('duty_dept_total');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Total Duty per Dept</a></td></tr>
      <tr style="height:30px"><td><a href="#" onclick="report('duty_division_total');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Total Duty per Division</a></td></tr>
	   <tr style="height:30px"><td><a href="#" onclick="report('duty_position_total');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Total Duty per Position</a></td></tr>
      </table>
    </td>
    <td valign="top">
      <table style="color:maroon;font-weight:bold;font-size:12px;">
	  <tr style="height:30px;font-size:14px;"><td align="center"><u>Payroll Reports</u></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('payrollTimeSummary');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Payroll Time Summary Report</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('overtime');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Overtime Report</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('overtimedetailed');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Overtime Report(Detailed)</a></td></tr>
      </table>
    </td>
    <td valign="top">
      <table style="color:maroon;font-weight:bold;font-size:12px;">
	  <tr style="height:30px;font-size:14px;"><td align="center"><u>Online Application Reports</u></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_ot');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Overtime</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_log');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Forgot to Log</a></td></tr>	
	  <tr style="height:30px"><td><a href="#" onclick="report('app_schedule');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Change Schedule</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_leave');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Leaves</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_drd');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Duty Restday</a></td></tr>
		
      </table>
      <br><br>
      <table style="color:maroon;font-weight:bold;font-size:12px;">
	  <tr style="height:30px;font-size:14px;"><td align="center"><u>Online Late Application Reports</u></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_otl');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Overtime</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_logl');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Forgot to Log</a></td></tr>	
	  <tr style="height:30px"><td><a href="#" onclick="report('app_schedulel');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Change Schedule</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_leavel');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Leaves</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_drdl');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Duty Restday</a></td></tr>
		
      </table>
       <br><br>
       <table style="color:maroon;font-weight:bold;font-size:12px;">
	  <tr style="height:30px;font-size:14px;"><td align="center"><u>Online Application Reports (Unapproved)</u></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_ot_u');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Overtime</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_log_u');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Forgot to Log</a></td></tr>	
	  <tr style="height:30px"><td><a href="#" onclick="report('app_schedule_u');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Change Schedule</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_leave_u');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Leaves</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_drd_u');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Duty Restday</a></td></tr>
		
      </table>

       <br><br>
       <table style="color:maroon;font-weight:bold;font-size:12px;">
	  <tr style="height:30px;font-size:14px;"><td align="center"><u>Online Application Reports (Validator)</u></td></tr>

	  <tr style="height:30px"><td><a href="#" onclick="report('app_ot_p');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Overtime</a></td></tr>

	  <tr style="height:30px"><td><a href="#" onclick="report('app_log_p');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Forgot to Log</a></td></tr>	
	  <tr style="height:30px"><td><a href="#" onclick="report('app_schedule_p');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Change Schedule</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_leave_p');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Leaves</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_drd_p');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Duty Restday</a></td></tr>
	  <tr style="height:30px"><td><a href="#" onclick="report('app_ot_double_p');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Double Application</a></td></tr>		
      </table>
    </td>
  </tr>
</table> 
  <h2>&nbsp;</h2>
</div>
          <?php include "footer.php";?>
    
  </div>



</body>
</html>


