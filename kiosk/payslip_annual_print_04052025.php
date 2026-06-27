<?php
ob_start();
session_start();
if(!$_SESSION['ndex']){header("location:index.php");}
	include("../dbcon.php");
	include("../employeefunctions.php");

	$re=1;
	if($_GET['cutoff']=='PB'){
		$r = mysql_fetch_array(mysql_query("SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid, p.*
									FROM payslip_2023_pb p
									left join employee e on e.ndex=p.employee_id										
									LEFT JOIN dept d ON d.ndex = e.deptId
									WHERE e.ndex='".$_SESSION['ndex']."'"));
		
		if(!$r){
			$re=0;
		}
	}
	if($_GET['cutoff']=='IT'){
		$r = mysql_fetch_array(mysql_query("SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid, p.*
									FROM payslip_2023_incometax p
									left join employee e on e.ndex=p.employee_id										
									LEFT JOIN dept d ON d.ndex = e.deptId
									WHERE e.ndex='".$_SESSION['ndex']."'"));
		if(!$r){
			$re=0;
		}
	}

	// if($_GET['cutoff']=='Leave'){
	// 	$r = mysql_fetch_array(mysql_query("SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid, p.*
	// 								FROM payslip_2023_leave_sl p
	// 								left join employee e on e.ndex=p.employee_id										
	// 								LEFT JOIN dept d ON d.ndex = e.deptId
	// 								WHERE e.ndex='".$_SESSION['ndex']."'"));
	// 	if(!$r){
	// 		$re=0;
	// 	}
	// }
	if($_GET['cutoff']=='Leave'){

		$r = mysql_fetch_array(mysql_query("SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid, p.*
									FROM payslip_2023_leave_sl p
									left join employee e on e.ndex=p.employee_id										
									LEFT JOIN dept d ON d.ndex = e.deptId
									WHERE e.ndex='".$_SESSION['ndex']."'"));
		if(!$r){
			$re=0;
		}
	}

	if($_GET['cutoff']=='VLeave'){
		$r = mysql_fetch_array(mysql_query("SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid, p.*
									FROM payslip_2023_leave_vl p
									left join employee e on e.ndex=p.employee_id										
									LEFT JOIN dept d ON d.ndex = e.deptId
									WHERE e.ndex='".$_SESSION['ndex']."'"));
		if(!$r){
			$re=0;
		}
	}

?>
<html>
<body onload="window.print();">	
	<?php if($re == 0){ echo "No Record Found."; } else {?>
		<?php if($_GET['cutoff']=='PB') { ?>	

			<table cellspacing="1" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%">
			  	<tr>								
			    	<td colspan="2" width="100%" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>PERFORMANCE BONUS (2023)<br></td>
			  	</tr>
				<tr valign="TOP">
					<td colspan="2"><br><br></td>
			  	</tr>
				<tr valign="TOP">
					<td width="50%">
						EMPNO: <?php echo getID($r['employmentStatus'],$r['employeeNo']);?> <br>
						NAME: <?php echo $r['lastName'].", ".$r['firstName'];?> <br>
															
					</td>
					<td width="50%">
						<table cellpadding="4" cellspacing="5" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%"0>			<tr>
									<td>Gross Performance Bonus Amount</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['gross'],2);?></td>
								</tr>								
								<tr>
									<td>Withholding Tax</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['wtax'],2);?></td>	
								</tr>	
								<tr>
									<td>Hospital Bill</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['hospital_bill'],2);?></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td align="right">--------------------</td>
								</tr>
								<tr>
									<td>Net</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['net'],2);?></td>
								</tr>
							</table>	
					</td>
				</tr>
				<tr><td colspan="15"><br><br><br><hr><br><br><br></td></tr>
				
    		</table>
		<?php } ?>

		<?php if($_GET['cutoff']=='IT') { ?>	

			<table cellspacing="1" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%">
			  	<tr>								
			    	<td colspan="2" width="100%" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>INCOME TAX (2023)<br></td>
			  	</tr>
				<tr valign="TOP">
					<td colspan="2"><br><br></td>
			  	</tr>
				<tr valign="TOP">
					<td width="50%">
						EMPNO: <?php echo getID($r['employmentStatus'],$r['employeeNo']);?> <br>
						NAME: <?php echo $r['lastName'].", ".$r['firstName'];?> <br>
														
					</td>
					<td width="50%">
						<table cellpadding="4" cellspacing="5" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%"0>			<tr>
									<td>Net Taxable Income</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['net_taxable_income'],2);?></td>
								</tr>								
								<tr>
									<td>Tax Due</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['tax_due'],2);?></td>	
								</tr>	
								<tr>
									<td>Total Tax Withheld</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['total_tax_wheld'],2);?></td>
								</tr>
								<tr>
									<td>Income Tax Payable</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['income_tax_payable'],2);?></td>
								</tr>
								<tr>
									<td>Income Tax Refundable</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['income_tax_refundable'],2);?></td>
								</tr>
							</table>	
					</td>
				</tr>
				<tr><td colspan="15"><br><br><br><hr><br><br><br></td></tr>
				
    		</table>
		<?php } ?>

		<?php if($_GET['cutoff']=='Leavexxx') { ?>	

			<table cellspacing="1" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%">
			  	<tr>								
			    	<td colspan="2" width="100%" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>LEAVE CONVERSION (2023)<br></td>
			  	</tr>
				<tr valign="TOP">
					<td colspan="2"><br><br></td>
			  	</tr>
			  	<tr>
			  		<td>
						EMPNO: <?php echo getID($r['employmentStatus'],$r['employeeNo']);?> <br>
						NAME: <?php echo $r['lastName'].", ".$r['firstName'];?> <br>
						NET: <?php echo number_format(($r['vl_net'] + $r['net']),2);?><br><br>							
					</td>
			  	</tr>
				<tr valign="TOP">
					
					<td width="50%">
						<table cellpadding="4" cellspacing="5" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%"0>		 <tr>
									<td colspan="3">SICK LEAVE <br></td>
								</tr>
								<tr>
									<td>Unused</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['unused'],2);?></td>
								</tr>								
								<tr>
									<td>Gross</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['gross'],2);?></td>	
								</tr>	
								<tr>
									<td>Withholding Tax</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['wtax'],2);?></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td align="right">--------------------</td>
								</tr>
								<tr>
									<td>Net of SL</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['net'],2);?></td>
								</tr>
																			
							</table>	
					</td>

					<td width="50%">
						<table cellpadding="4" cellspacing="5" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;margin-left: 20px;" border="0" width="80%"0>		 <tr>
									<td colspan="3">VACATION LEAVE <br></td>
								</tr>
								<tr>
									<td>Unused</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['vl_unused'],2);?></td>
								</tr>
								<tr>
									<td>Taxable Amount</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['vl_taxable'],2);?></td>	
								</tr>
								<tr>
									<td>Non Taxable Amount</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['vl_nontaxable'],2);?></td>	
								</tr>								
								<tr>
									<td>Gross</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['vl_gross'],2);?></td>	
								</tr>	
								<tr>
									<td>Withholding Tax</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['vl_wtax'],2);?></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td align="right">--------------------</td>
								</tr>
								<tr>
									<td>Net of VL</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['vl_net'],2);?></td>
								</tr>												
							</table>	
					</td>
				</tr>

				<tr><td colspan="15"><br><br><br><hr><br><br><br></td></tr>
				
    		</table>
		<?php } ?>

		<?php if($_GET['cutoff']=='Leave') { ?>	

			<table cellspacing="1" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%">
			  	<tr>								
			    	<td colspan="2" width="100%" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>SICK LEAVE CONVERSION (2023)<br></td>
			  	</tr>
				<tr valign="TOP">
					<td colspan="2"><br><br></td>
			  	</tr>
			  
				<tr valign="TOP">
					
					<td>
						EMPNO: <?php echo getID($r['employmentStatus'],$r['employeeNo']);?> <br>
						NAME: <?php echo $r['lastName'].", ".$r['firstName'];?> <br>							
					</td>
					<td width="50%">
						<table cellpadding="4" cellspacing="5" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%"0>	
								<tr>
									<td>Unused</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['unused'],2);?></td>
								</tr>								
								<tr>
									<td>Gross</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['gross'],2);?></td>	
								</tr>	
								<tr>
									<td>Withholding Tax</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['wtax'],2);?></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td align="right">--------------------</td>
								</tr>
								<tr>
									<td>Net of SL</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['net'],2);?></td>
								</tr>
																			
							</table>	
					</td>

					
				</tr>

				<tr><td colspan="15"><br><br><br><hr><br><br><br></td></tr>
				
    		</table>
		<?php } ?>

		<?php if($_GET['cutoff']=='VLeave') { ?>	

			<table cellspacing="1" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%">
			  	<tr>								
			    	<td colspan="2" width="100%" align="center" style="font-size:13px;">DAVAO DOCTORS HOSPITAL<br>VACATION LEAVE CONVERSION (2023)<br></td>
			  	</tr>
				<tr valign="TOP">
					<td colspan="2"><br><br></td>
			  	</tr>
			  
				<tr valign="TOP">
					
					<td>
						EMPNO: <?php echo getID($r['employmentStatus'],$r['employeeNo']);?> <br>
						NAME: <?php echo $r['lastName'].", ".$r['firstName'];?> <br>							
					</td>
					<td width="50%">
						<table cellpadding="4" cellspacing="5" style="font-family:tahoma, Arial;font-stretch:condensed;font-size:12px;" border="0" width="100%"0>		
								<tr>
									<td>Unused</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['unused'],2);?></td>
								</tr>		
								<tr>
									<td>Taxable</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['taxable'],2);?></td>
								</tr>	
								<tr>
									<td>Non Taxable</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['nontaxable'],2);?></td>
								</tr>							
								<tr>
									<td>Gross</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['gross'],2);?></td>	
								</tr>	
								<tr>
									<td>Withholding Tax</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['wtax'],2);?></td>
								</tr>
								<tr>
									<td></td>
									<td></td>
									<td align="right">--------------------</td>
								</tr>
								<tr>
									<td>Net</td>
									<td>=</td>
									<td align="right"><?php echo number_format($r['net'],2);?></td>
								</tr>
																			
							</table>	
					</td>

					
				</tr>

				<tr><td colspan="15"><br><br><br><hr><br><br><br></td></tr>
				
    		</table>
		<?php } ?>

	<?php } ?>
</body>
</html>