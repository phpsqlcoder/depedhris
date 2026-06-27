<?php
ob_start();
session_start();
include("../../../dbcon.php");
include("../../../scripts/scripts.php");
include ("../../../myfunctions.php");
include ("../../../employeefunctions.php");

//echo $_POST['employeeId'];
$cutoff = mysql_fetch_assoc(mysql_query("SELECT * FROM payroll order by ndex desc limit 1"));
if($_POST['dep']!='on'){
		
	$ee=mysql_fetch_assoc(mysql_query("select  e.*, e.ndex employeeId from  employee e
													where e.ndex='".$_POST['employeeId']."'"));
	$sql =  "select p.*, ec.* from payroll p 
						LEFT JOIN employee_compensation ec ON ec.employeeId = p.empid
					where p.empid='".$ee['ndex']."' ORDER BY p.pay_period DESC";
	$exec=mysql_query($sql);
	while($r=mysql_fetch_assoc($exec)){
		
	if ($prevBasicPay != $r['basicpay'] && $r['basicpay'] !=0){
			$dept=mysql_fetch_assoc(mysql_query("select  dl.*, d.name deptName, d.* employeeId from  employee_edit_logs dl
																				left join dept d on d.ndex=dl.newValue
													where dl.employeeId='".$ee['employeeId']."' && dl.fieldName='deptid'"));

			$changeDate = mysql_fetch_assoc(mysql_query("select p.*, ec.* from payroll p LEFT JOIN employee_compensation ec ON ec.employeeId = p.empid
					where p.empid='".$ee['ndex']."' && p.basicpay='".$r['basicpay']."' ORDER BY p.pay_period ASC LIMIT 1"));

			$eeCurrDept = mysql_fetch_assoc(mysql_query("SELECT e. * , d.name deptName
															FROM `employee_edit_logs` e
																LEFT JOIN dept d ON d.ndex = e.newValue
																	WHERE e.`fieldName` LIKE 'deptId'
																		AND e.`employeeId` = '".$ee['employeeId']."'
																		AND '".$changeDate['pay_period']."' >= e.`effectivityDate`
																		ORDER BY e.effectivityDate DESC , updatedDate DESC
																		LIMIT 1 "));
			
			$departmentValue = 	$eeCurrDept['deptName'] =='' ? $preDepartment : $eeCurrDept['deptName'];
			//$departmentValue = 	$eeCurrDept['deptName'];

			if (!empty($eeCurrDept['deptName'])) {$preDepartment = $eeCurrDept['deptName']; }

					
			$changePosition = mysql_fetch_assoc(mysql_query("SELECT  dl.*, d.* employeeId from  employee_edit_logs dl left join position d on d.ndex=dl.newValue where dl.employeeId='".$ee['ndex']."' && dl.fieldName='position' && dl.effectivityDate <= '".$changeDate['pay_period']."' order by dl.effectivityDate DESC LIMIT 1"));
								
			$lastPOsistion = mysql_fetch_assoc(mysql_query("SELECT dl . * , d . * employeeId FROM employee_edit_logs dl LEFT JOIN position d ON d.ndex = dl.newValue WHERE dl.employeeId =  '".$ee['employeeId']."' && dl.fieldName =  'position' ORDER BY dl.`effectivityDate` LIMIT 1"));
			$changePosition['name'] = $changePosition['name'] == '' ? $lastPOsistion['name'] : $changePosition['name'];

			//LEVEL
			$changeLevel = mysql_fetch_assoc(mysql_query("select  dl.* employeeId from  employee_edit_logs dl 
								 where dl.employeeId='".$ee['ndex']."' && dl.fieldName='level' && dl.updatedDate  <= '".$changeDate['pay_period']."' order by dl.updatedDate  DESC LIMIT 1"));

			$lastLevel = mysql_fetch_assoc(mysql_query("SELECT dl.*  employeeId FROM employee_edit_logs dl 
												 WHERE dl.employeeId =  '".$ee['employeeId']."' && dl.fieldName =  'level' ORDER BY dl.`updatedDate` ASC LIMIT 1"));

			$changeLevel['newValue'] = $changeLevel['newValue'] == '' ? $lastLevel['newValue'] : $changeLevel['newValue'];

			//Basic Pay Edited Date
			$editedDate = mysql_fetch_assoc(mysql_query("select  dl.* employeeId from  employee_edit_logs dl 
								 where dl.employeeId='".$ee['ndex']."' && dl.fieldName='basicPay' && dl.updatedDate  <= '".$changeDate['pay_period']."' order by dl.updatedDate  DESC LIMIT 1"));

			$editedDate['updatedDate'] = $editedDate['updatedDate'] == '' ? $changeDate['pay_period'] : $editedDate['updatedDate'];

			$prevBasicPay = $r['basicpay'];
			$data .= "  <tr>
							<td>".$changeDate['pay_period']."</td>
							<td>".$departmentValue."</td>
							<td>".$editedDate['updatedDate']."</td>
							<td>".$changePosition['name']."</td>
							<td>".$changeLevel['newValue']."</td>
							<td align='right'>".$r['basicpay']."</td>
							<td align='right'>".$r['cola']."</td>
							<td align='right'>".$r['allowance']."</td>
							<td align='right'>".$r['honorarium']."</td>
						</tr>
					";
		}
	}
		
	
	
}
elseif($_POST['dep']=='on'){
		
}
?>
<HTML>
<HEAD>
</HEAD>

<BODY>
     <?php
if($_POST['eksel']=='on'){
		$filename ="compensationMasterFile.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="95%" style="font-family:Arial;font-size:11px;">
	 <thead>
	  <tr>
	       <td colspan="11" align="center" style="font-size:11px;font-weight:bold;">Employee Compensation History<br> <?php echo $reportTitle;?><br></td>
	  </tr><tr>
		   <td colspan="11" align="left" style="font-size:11px;font-weight:bold;">Name: <?php echo $ee['lastName'].", ".$ee['firstName'];	 ?><br></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr align='center' style="font-weight:bold;color:blue;">
			<td>Date Effect</td>
			<td>Department</td>
			<td>Date Edited</td>
			<td>Position</td>
			<td>Level</td>
			<td>Basic</td></td>
			<td>Cola</td>
			<td>Allowance</td>
			<td>Honorarium</td>
	  </tr>
	  <tr><td colspan="10"><hr></td></tr>
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
</BODY>
</HTML>
     
	  <?php //include("../rptfooter.php");?>




