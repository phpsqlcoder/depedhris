<?php

session_start();

	$conn=mysql_connect("localhost","root","pangitka");
mysql_select_db("hris",$conn);
	include ("employeefunctions.php");


//$e = mysql_fetch_array(mysql_query("select e.*,d.name as department from employee e left join dept d on d.ndex=e.deptId WHERE e.ndex='".$_GET['emp']."'"));
// require_once('bir/fpdf/fpdf.php');
// require_once('bir/fpdf/fpdi/fpdi.php');
// $pdf = new FPDI('P','mm',array(220,355));
// $pageCount = $pdf->setSourceFile("contracts/pc.pdf");
// $tplIdx = $pdf->importPage(1, '/MediaBox');


// $pdf->addPage();
// $pdf->useTemplate($tplIdx);
// $pdf->SetFont('Arial','B',12);
// //year
// $pdf->SetXY(41,37);
// $pdf->Cell(10,6,'2   0    2   1');
   
// $pdf->Output("F","contract.pdf");

if($_GET['emp']){
	$e=mysql_fetch_array(mysql_query("select * from employee where ndex=".$_GET['emp'].""));
	$d=mysql_fetch_array(mysql_query("select * from dept where ndex=".$e['deptId'].""));
	$p=mysql_fetch_array(mysql_query("select * from position where ndex=".$e['position'].""));
}

?>
<?php
	if($_GET['tayp'] == 'Probationary Contract'){
?>
<style>
	table{
		font-family:Arial;
	}
</style>
	<table width="100%">
			<tr>
				<td align="center"><h3>Davao Doctors Hospital<br>
	118 E. Quirino Ave., Davao City</h3>
	</td>
			</tr>
	</table>


	<table width="100%" style="font-family:Arial;font-size:14px;">
		<tr>
			<td><strong>NAME:</strong> <?php echo $e['lastName'].", ".$e['firstName']." ".$e['middleName']?></td>
		</tr>
		<tr><td><strong>POSITION:</strong>  <?php echo $p['name']; ?></td></tr>
		<tr><td><strong>DEPARTMENT:</strong> <?php echo $d['name']; ?></td></tr>

		<tr><td><br>Subject: Probationary Contract</td></tr>
	</table>


	<table width="100%" style="font-family:Arial;font-size:14px;">
		
		<tr><td><br><br><strong>Dear :</strong>  <?php echo $e['lastName'].", ".$e['firstName']." ".$e['middleName']?></td></tr>
		<tr><td><br><br>This will confirm our agreement on your employment with Davao Doctors Hospital subject to the following terms and conditions, to wit:</td></tr>
		<tr><td><br><br>1.  You shall be hired for the position of <strong style="text-decoration:underline;"><?php echo $p['name']; ?></strong> and will work for (8) hours per day, 5 days a week.</td></tr>
		<tr><td><br>2.  Your probation for a period of SIX (6) months shall start on  <?php echo date('d F Y',strtotime($e['dateHired']))?>  until  
			<?php echo date('d F Y',strtotime("+6 months",strtotime($e['dateHired'])))?>. Evaluation shall be ON THE FIFTH (5TH) MONTH.  </td></tr>

		<tr><td><br>3. During the probationary period of your employment, you are expected to perform the duties and responsibilities assigned to you in accordance with the Key Performance Indicator/Key Results areas for the positions and company standards, which will be explained to you during the orientation by your immediate superior.  You are likewise expected to obey with all lawful orders of your superiors and comply with company rules and regulation, a copy of which shall be issued separately.  It is therefore clearly understood that if you fail to meet the work standards expected of you, or violate any law, company rules or regulations, the Hospital has the prerogative to terminate your employment or not to elevate your employment status to a regular employee upon expiration of your probationary employment.</td></tr>

		<tr><td><br>4. You are also expected to perform your work diligently to the best of your ability, maintain and establish good relations with the other employees and respect all officers, employees and clients of the Hospital.</td></tr>

		<tr><td><br>5. During your employment, you are required to join and participate to all mandatory trainings, seminars, or learning-sessions conducted by Davao Doctors Hospital by reason of the position held, pursuant to its mission of nurturing a culture of learning and develop highly effective healthcare professionals.</td></tr>

		<tr><td><br>6. Your gross salary is subject to taxes and other compulsory deductions as required by existing laws, like Social Security System, PHIC Employee's Compensation and PAG-IBIG Fund, etc. </td></tr>

		<tr><td><br>7.	Computation of salary is on a <?php echo $e['payType']?> basis and payment will be bi-monthly. Whenever necessary, you must also be willing to render overtime work based on operational exigencies for which you shall be paid accordingly. </td></tr>

		<tr><td><br>8.	While on probation, you will not be entitled to sick leave or vacation leave with pay.  Entitlement to both will be after rendering one year of service with the company.  </td></tr>

		<tr><td><br>9.	The Hospital shall have the absolute right to re-assign or transfer you to any department or section within the organization.</td></tr>

		<tr><td><br>10.	It is expressly agreed that there are no verbal agreements or understanding between you and the Hospital or any of its agents and representatives affecting this Probationary Agreement unless the same are done in writing.</td></tr>

		<tr><td><br>11.	In the case of resignation, request of any certification related to employment such as Certificate of Employment, Job Description, and others shall only be issued once cleared and complied the 30-day prior notice. </td></tr>
		

		<tr><td><br>If the foregoing terms and conditions are acceptable to you, kindly sign at the bottom hereof to signify your unqualified and unconditional acceptance and confirmation thereto.</td></tr>

		<tr><td><br>Davao City, Philippines, signed this <?php echo date('jS',strtotime(date('Y-m-d')))?> day of <?php echo date('F')?>, <?php echo date('Y')?>.</td>
		</tr>

		<tr><td><br><br><br>Very truly yours,</td>
		</tr>
		<tr><td><br>________________________________</td>
		</tr>
		<tr><td>LILYBETH P. MAGNO<br>Head, Human Resource</td>
		</tr>

		<tr><td><br><br>I hereby acknowledge receipt of a signed copy of this Agreement as well as a copy of the Davao Doctors Hospital Personnel Handbook that contain its policies, rules and regulations and unconditionally agree to accept the same.</td>
		</tr>

		<tr><td><br><br><?php echo $e['lastName'].", ".$e['firstName']." ".$e['middleName']?><br>Date:___________________</td>
		</tr>
	</table>

<?php } ?>


<?php
	if($_GET['tayp'] == 'Regularization Contract'){
?>
<style>
	table{
		font-family:Arial;
	}
</style>
	<table width="100%">
			<tr>
				<td align="center"><h3>Davao Doctors Hospital<br>
	118 E. Quirino Ave., Davao City</h3>
	</td>
			</tr>
	</table>


	<table width="100%" style="font-family:Arial;font-size:14px;">
		<tr>
			<td><strong>NAME:</strong> <?php echo $e['lastName'].", ".$e['firstName']." ".$e['middleName']?></td>
			
			
		</tr>
		<tr><td><strong>POSITION:</strong>  <?php echo $p['name']; ?></td></tr>
		<tr><td><strong>DEPARTMENT:</strong> <?php echo $d['name']; ?></td></tr>


	</table>


	<table width="100%" style="font-family:Arial;font-size:14px;">
		
		<tr><td><br><br><strong>Dear :</strong>  <?php echo $e['lastName'].", ".$e['firstName']." ".$e['middleName']?></td></tr>
		<tr><td><br><br>We are pleased to inform you that you have successfully reached the standards required for your position. You are now a regular employee effective <?php echo date('d F Y',strtotime($e['dateHired']))?>.</td></tr>

		<tr><td><br>As a regular employee,  you are required to work for eight (8) hours per day, five (5) days a week. Your working schedule shall depend on the plotted schedule submitted by your department head or supervisor.</td></tr>

		<tr><td><br>It is understood that you will continue to be bound by the provisions as stipulated in your probationary employment contract, Confidentiality Agreement, company standards and policies and Code of Discipline along with the following provisions:</td></tr>

		<tr><td style="padding-left:30px;"><br>1. During the period of your employment, you are expected to continue to perform the duties and responsibilities assigned to you in accordance with your Job Description, the Key Performance Indicator/Key Results and/ or new assignments.  </td></tr>

		<tr><td style="padding-left:30px;"><br>2. You are also expected to perform your work diligently to the best of your ability, maintain and establish good relations with the other employees and respect all officers, employees and clients of the Hospital.  </td></tr>
		

		<tr><td style="padding-left:30px;"><br>3. Your gross salary is subject to taxes and other compulsory deductions as required by existing laws, like Social Security System, PHIC Employee’s Compensation and PAG-IBIG Fund, etc.  </td></tr>

		<tr><td style="padding-left:30px;"><br>4. Computation of salary is on a monthly basis and payment will be bi-monthly. Whenever necessary, you must also be willing to render overtime work and on-call duty based on operational exigencies for which you shall be paid accordingly.</td></tr>

		<tr><td style="padding-left:30px;"><br>5. The Hospital shall have the absolute right to re-assign or transfer you to any department or section within the organization as may be deemed necessary in the operation of the Hospital.</td></tr>

		<tr><td style="padding-left:30px;"><br>6. It is expressly agreed that there are no verbal agreements or understanding between you and the Hospital or any of its agents and representatives affecting this Agreement unless the same are done in writing.</td></tr>

		<tr><td style="padding-left:30px;"><br>7. In the case of resignation, request of any certification related to employment such as Certificate of Employment, Job Description, and others shall only be issued once cleared and complied the 30-day prior notice. </td></tr>

		<tr><td><br>If the foregoing terms and conditions are acceptable to you, kindly sign at the bottom hereof to signify your unqualified and unconditional acceptance and confirmation thereto.</td></tr>

		<tr><td><br>We look forward to more years of fruitful partnership with you.</td>
		</tr>

		<tr><td><br><br><br>Very truly yours,</td>
		</tr>
		<tr><td><br>________________________________</td>
		</tr>
		<tr><td>LILYBETH P. MAGNO<br>Head, Human Resource</td>
		</tr>

		<tr><td><br><br>WITH MY CONFORMITY:</td>
		</tr>

		<tr><td><br><br><?php echo $e['lastName'].", ".$e['firstName']." ".$e['middleName']?></td>
		</tr>
	</table>

<?php } ?>

<?php
	if($_GET['tayp'] == 'Fixed Term'){
?>
	<table width="100%">
			<tr>
				<td align="center"><h3>FIXED TERM EMPLOYMENT CONTRACT</h3></td>
			</tr>
	</table>

	<table width="100%">
			<tr>
				<td align="left">KNOW ALL MEN BY THESE PRESENTS:</td>
			</tr>
	</table>
	<table width="100%" style="padding-left:70px;padding-right: 70px;">
			<tr>
				<td align="left"><br><strong>THIS CONTRACT</strong>, made and entered into this <strong style="text-decoration:underline;"><?php echo $_GET['ft_date']?></strong> in Davao City by and between:</td>
			</tr>
	</table>

	<table width="100%" style="padding-left:70px;padding-right: 70px;">
			<tr>
				<td><br><strong>DAVAO DOCTORS HOSPITAL (Clinica Hilario), INC.</strong>, a corporation duly organized and existing under and by virtue of the laws of the Philippines, with postal address at 118 Quirino Avenue, Davao City, represented herein by its Chief Operating Officer, MIRASOL B. TIU, hereinafter referred to as <strong>"DDH"</strong></td>
			</tr>
			<tr>
				<td align="center"><br>-and-<br></td>
			</tr>
			<tr>
				<td><br><strong style="text-decoration:underline;"><?php echo $e['lastName'].", ".$e['firstName']." ".$e['middleName']?></strong> of legal age, <?php strtolower($e['civilStatus'])?>, and with postal address at <strong style="text-decoration:underline;"><?php echo $e['presentAddress']?></strong>, hereinafter referred to as <strong>"FIXED-TERM EMPLOYEE"</strong>.<br></td>
			</tr>
			<tr>
				<td align="center"><br><strong>WITNESSETH THAT:</strong><br></td>
			</tr>
			<tr>
				<td><br>WHEREAS, DDH is in need of a temporary employee who shall perform the services of its regular employee who is on leave of absence due to sickness, professional development or any other reason.<br></td>
			</tr>
			<tr>
				<td><br>WHEREAS, the FIXED-TERM EMPLOYEE, who represented himself/herself to be qualified and competent to perform the job, has undertaken to render the needed services for a fixed period.<br></td>
			</tr>
			<tr>
				<td><br>NOW, THEREFORE, parties agree as follows:<br></td>
			</tr>
	</table>

	<br><br>
	<table width="100%">
			<tr>
				<td align="left"><strong>1.	JOB TITLE AND JOB DESCRIPTION</strong> </td>
			</tr>
			<tr>
				<td align="left" style="padding-left:30px;">1.1.	The FIXED-TERM EMPLOYEE is hired as <strong style="text-decoration:underline;"><?php echo $p['name']; ?></strong>. A detailed description of the duties and responsibilities of the said position is hereto attached as Annex "A" and made an integral part of this Contract. The said description of the duties and responsibilities including the working hours may be subject to change upon due notice to the FIXED-TERM EMPLOYEE. Any and all change/s will also form an integral part of this contract. </td>
			</tr>
			<tr>
				<td align="left" style="padding-left:30px;">1.2.	The FIXED-TERM EMPLOYEE shall render said services for (8) hours per day, 5 days a week. The shift/schedule shall be agreed upon by the parties. </td>
			</tr>

			<tr>
				<td align="left"><strong>2.	TERM  </strong> </td>
			</tr>
			
			<tr>
				<td align="left" style="padding-left:30px;">2.1.	This Contract shall be for a fixed period commencing <strong style="text-decoration:underline;"><?php echo $_GET['st_date_st']; ?></strong> and ending on <strong style="text-decoration:underline;"><?php echo $_GET['ft_date_end']; ?></strong>, unless sooner terminated either for any just or authorized cause, or violation of the company’s Code of Discipline.  </td>
			</tr>


			<tr>
				<td align="left"><strong>3.	COMPENSATION </strong> </td>
			</tr>
			
			<tr>
				<td align="left" style="padding-left:30px;">3.1.	For services rendered, the FIXED-TERM EMPLOYEE shall receive the amount of <strong style="text-decoration:underline;"><?php echo $_GET['ft_compensation']; ?></strong> as MONTHLY pay payable on <strong style="text-decoration:underline;"><?php echo $_GET['ft_payable']; ?></strong> of each month, subject to government-mandated tax and other deductions.  </td>
			</tr>
			<tr>
				<td align="left" style="padding-left:30px;">3.2.	The FIXED-TERM EMPLOYEE shall likewise be entitled to government-mandated benefits.  </td>
			</tr>
			<tr>
				<td align="left" style="padding-left:30px;">3.3.	The FIXED-TERM EMPLOYEE shall not be entitled to benefits of a regular employee.</td>
			</tr>


			<tr>
				<td align="left"><strong>4.	EXCLUSIVITY AND NO CONFLICT OF INTEREST  </strong> </td>
			</tr>
			<tr>
				<td align="left" style="padding-left:30px;">4.1.	The FIXED-TERM EMPLOYEE hereby agrees not to accept or continue in any job, employment, or engagement that may conflict with his/her duties and responsibilities to the DDH, nor engage in any work, paid or unpaid, that creates an actual conflict of interest with DDH. Such work shall include, but is not limited to, directly or indirectly competing with DDH in any way, or acting in any position of any business enterprise of the same nature as, or which is in direct competition with, the business in which DDH is now engaged or in which DDH becomes engaged during your engagement with DDH, as may be determined by the DDH in its sole discretion. If the DDH believes such a conflict exists, the DDH may terminated this Contract.  </td>
			</tr>
			<tr>
				<td align="left" style="padding-left:30px;">4.2.	This Contract is entered into on a fair and arms-length basis, without duress or coercion, and is to be interpreted as an agreement between two parties of equal bargaining capacities.  </td>
			</tr>


			<tr>
				<td align="left"><strong>5.	ANTI-BRIBERY AND ANTI-CORRUPTION CLAUSES </strong> </td>
			</tr>
			<tr>
				<td align="left" style="padding-left:30px;">5.1.	The FIXED-TERM EMPLOYEE warrants sand represents that he/she have not taken and will not take any action that would constitute a violation, or implicate the DDH in a violation, of any law of any jurisdiction in which it performs business, including any anti-corruption and anti-bribery law of any jurisdiction in which Consultant performs business, or of the United States, of the European Union, or of the United Kingdom, including without limitation, the Korean Criminal Code and the Act on the Prohibition of Improper Solicitation and Provision/Receipt of Money and Valuables (i.e. the Kim Yong-Ran Act), the Foreign Corrupt Practices Act of 1977, as amended, the U.K. Bribery Act of 2010, any anti-corruption instruments of the European Union and where applicable, legislation enacted by member states and signatories implementing the OECD Convention Combating Bribery of Foreign Officials ("Anti-Corruption Laws"). </td>
			</tr>
			<tr>
				<td align="left" style="padding-left:30px;">5.2.	The FIXED-TERM EMPLOYEE further represents, warrants, and agrees that:  </td>
			</tr>
			<tr>
				<td align="left" style="padding-left:60px;">5.2.1.	He/she currently is not (i) an officer, agent or employee of a government, government-owned enterprise (or any agency, department or instrumentality thereof), political party or public international organization, (ii) a candidate for government or political office, or (iii) an agent, officer, or employee of any entity owned by a government (collectively, "Government Official").  If he/she  becomes a Government Official during the term covered by this document, she/he shall notify the DDH immediately;</td>
			</tr>
			<tr>
				<td align="left" style="padding-left:60px;">5.2.2.	He/she has not  promised to make, will promise to make, or will cause to be made any bribe, improper rebate, payoff, influence payment, kickback, or gift of anything of value ("Payments") (i) to or for the use or benefit of any Government Official; (ii) to any other person either for an advance or reimbursement, if it knows or has reason to know that any part of such Payment will be directly or indirectly given or paid by such other person, or will reimburse such other person for Payments previously made, to any Government Official; or (iii) to any other person or entity, to obtain or keep business or to secure some other improper advantage, the payment of which would violate applicable Anti-Corruption Laws;</td>
			</tr>
			<tr>
				<td align="left" style="padding-left:60px;">5.2.3.	Any compensation provided by the DDH is for his/her sole benefit and he/she shall not make any Payments to other third parties on behalf of the DDH; and</td>
			</tr>
			<tr>
				<td align="left" style="padding-left:60px;">5.2.4.	His/her services have been conducted at all times in compliance with applicable financial recordkeeping and reporting requirements of the U.S. Currency and Foreign Transaction Reporting Act of 1970, as amended, the U.S. Money Laundering Control Act of 1986, as amended, and all money laundering-related laws of all jurisdictions where you conduct business or owns assets, and any related or similar Law issued, administered or enforced by any Government Authority (collectively, the "Money Laundering Laws"). </td>
			</tr>


			<tr>
				<td align="left"><strong>6.	CONFIDENTIALITY AND DATA PRIVACY </strong> </td>
			</tr>
			<tr>
				<td align="left" style="padding-left:30px;">6.1.	The FIXED-TERM EMPLOYEE shall treat in strict confidence all information that he/she may acquire about the business and personal information of the DDH and its affiliates or business partners, employees, clients, and patients, including the contents of this Contract.  The FIXED-TERM EMPLOYEE warrants that he/she will not at any time in any manner whatsoever, directly or indirectly, disclose to any person or entity any information of any kind relating to the business of the DDH, its affiliates and business partners, including but not limited to any of their clients or any other information concerning their businesses, manner of operation, plans, processes, or data/information of any kind which he/she may acquire, learn and receive during and by reason of his/her engagement with the DDH. The FIXED-TERM EMPLOYEE agrees to ensure the secrecy of confidential information even after the termination of his/her contract with the DDH.  </td>
			</tr>
			<tr>
				<td align="left" style="padding-left:30px;">6.2.	Whenever applicable, in performing its obligations under this Agreement, parties shall, at all times, comply with the provisions of Republic Act No. 10173 or "the Data Privacy Act of 2012," its implementing rules and regulations, and all other laws and government issuances which are now or will be promulgated relating to data privacy and the protection of personal information. Hence, parties shall likewise execute the Data Sharing Agreement, herein attached as Annex "B".  </td>
			</tr>
	</table>

	<table width="100%" style="padding-left:70px;padding-right: 70px;">
			<tr>
				<td align="left"><br>IN WITNESS WHEREOF, the parties have hereunto affixed their signatures this <strong style="text-decoration:underline;"><?php echo $_GET['ft_date']?></strong> at Davao City, Philippines.</td>
			</tr>
	</table>

	<table width="100%" style="padding-left:70px;padding-right: 70px;">
			<tr>
				<td align="center"><br><strong>DAVAO DOCTORS HOSPITAL <br> (CLINICA HILARIO), INC.</strong><br>by:</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="center"><br>______________________________<br><strong>LILYBETH P. MAGNO</strong><br>HEAD, HUMAN RESOURCE</td>
				<td align="center"><br>______________________________<br><strong><?php echo $e['lastName'].", ".$e['firstName']." ".$e['middleName']?></strong><br><?php echo $p['name']?></td>
			</tr>
	</table>

<?php } ?>
