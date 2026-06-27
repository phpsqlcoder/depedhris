<?php
ob_start();
session_start();
if(!$_SESSION['ndex']){header("location:index.php");}
	include("../dbcon.php");
	include("../employeefunctions.php");
	error_reporting(E_ERROR | E_PARSE);
if($_GET['act']=='go'){
	$re=1;
	if($_POST['cutoff']=='PB'){
		$r = mysql_fetch_array(mysql_query("SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid, p.*
									FROM payslip_2023_pb p
									left join employee e on e.ndex=p.employee_id										
									LEFT JOIN dept d ON d.ndex = e.deptId
									WHERE e.ndex='".$_SESSION['ndex']."'"));
		
		if(!$r){
			$re=0;
		}
	}
	if($_POST['cutoff']=='IT'){
		$r = mysql_fetch_array(mysql_query("SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid, p.*
									FROM payslip_2023_incometax p
									left join employee e on e.ndex=p.employee_id										
									LEFT JOIN dept d ON d.ndex = e.deptId
									WHERE e.ndex='".$_SESSION['ndex']."'"));
		if(!$r){
			$re=0;
		}
	}

	if($_POST['cutoff']=='Leave'){
		$r = mysql_fetch_array(mysql_query("SELECT e.lastName, e.firstName, e.middleName, e.employmentStatus, e.employeeNo, d.name departmentName, p.empid, p.*
									FROM payslip_2023_leave_sl p
									left join employee e on e.ndex=p.employee_id										
									LEFT JOIN dept d ON d.ndex = e.deptId
									WHERE e.ndex='".$_SESSION['ndex']."'"));
		if(!$r){
			$re=0;
		}
	}
}

include("newheader.php");
?>
<table width="100%">
		<tr><td align="center" style="color:maroon;"><h1>Pay Slip (Annual)</h1></td></tr>
		<!-- <tr><td height="10" align="center">&nbsp;<font style="color:red;font-size:40px;font-weight:bold;"><?php echo $msg;?></font></td></tr> -->
	<tr>
		<td align="center">
				<form method="post" action="payslip_annual.php?act=go" name="frmpayslip">
				<table style="font-family:Arial Rounded MT Bold;font-size:20px;color:maroon;">
					<tr><td><select name="cutoff" onchange="document.frmpayslip.submit();" class="form-control">
						<option value="" selected="selected">- Select -</option>
						
						<option value="PB">Performance Bonus (2023)</option>
						<option value="IT">Income Tax (2023)</option>
					</select></td></tr>
				</table>
				</form>
			<?php if($_GET['act']=='go'){?>	
				<div style=" -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;width:800px;background-color:#FFF;margin-top: 20px;">	
					<?php if($re == 0){ echo "No Record Found."; } else {?>
						<?php if($_POST['cutoff']=='PB') { ?>	

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
								<tr><td colspan="15" align="right"><button onclick="window.open('payslip_annual_print.php?id=<?php echo $_SESSION['ndex'];?>&cutoff=<?php echo $_POST['cutoff'];?>','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=500')">Print</button></td></tr>
								<tr><td><br style="font-size:5px"></td></tr>
				    		</table>
			    		<?php } ?>

			    		<?php if($_POST['cutoff']=='IT') { ?>	

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
								<tr><td colspan="15" align="right"><button onclick="window.open('payslip_annual_print.php?id=<?php echo $_SESSION['ndex'];?>&cutoff=<?php echo $_POST['cutoff'];?>','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=500')">Print</button></td></tr>
								<tr><td><br style="font-size:5px"></td></tr>
				    		</table>
			    		<?php } ?>

			    		<?php if($_POST['cutoff']=='Leave') { ?>	

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
								<tr><td colspan="15" align="right"><button onclick="window.open('payslip_annual_print.php?id=<?php echo $_SESSION['ndex'];?>&cutoff=<?php echo $_POST['cutoff'];?>','displayWindow','toolbar=no,scrollbars=yes,width=1000,height=500')">Print</button></td></tr>
								<tr><td><br style="font-size:5px"></td></tr>
				    		</table>
			    		<?php } ?>

			    	<?php } ?>
				</div>
			<?php } ?>
		</td>
	</tr>
	<tr><td height="100" colspan="2">&nbsp;</td></tr>
	</table>

<?php include("newfooter.php");?>