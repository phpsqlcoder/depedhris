<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include("scripts/scripts.php");
include ("employeefunctions.php");

function number_to_words($number)
{
    if ($number > 999999999)
    {
       throw new Exception("Number is out of range");
    }

    $Gn = floor($number / 1000000);  /* Millions (giga) */
    $number -= $Gn * 1000000;
    $kn = floor($number / 1000);     /* Thousands (kilo) */
    $number -= $kn * 1000;
    $Hn = floor($number / 100);      /* Hundreds (hecto) */
    $number -= $Hn * 100;
    $Dn = floor($number / 10);       /* Tens (deca) */
    $n = $number % 10;               /* Ones */
	$cn = round(($number-floor($number))*100); /* Cents */
    $result = ""; 

    if ($Gn)
    {  $result .= number_to_words($Gn) . " Million";  } 

    if ($kn)
    {  $result .= (empty($result) ? "" : " ") . number_to_words($kn) . " Thousand"; } 

    if ($Hn)
    {  $result .= (empty($result) ? "" : " ") . number_to_words($Hn) . " Hundred";  } 

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
        "Nineteen");
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
        "Seventy", "Eigthy", "Ninety"); 

    if ($Dn || $n)
    {
       if (!empty($result))
       {  $result .= " and ";
       } 

       if ($Dn < 2)
       {  $result .= $ones[$Dn * 10 + $n];
       }
       else
       {  $result .= $tens[$Dn];
          if ($n)
          {  $result .= "-" . $ones[$n];
          }
       }
    }

    if ($cn)
    {
       if (!empty($result))
       {  $result .= ' and ';
       }
       $title = $cn==1 ? 'cent ': 'cents';
       $result .= strtolower(number_to_words($cn)).' '.$title;
    }

    if (empty($result))
    {  $result = "zero"; } 

    return $result;
}
//echo number_to_words(10111.23);


$emp=mysql_fetch_object(mysql_query("select e.*,d.name as dep,p.name as pos,p.ndex as pnd from employee e left join dept d on d.ndex=e.deptId left join position p on p.ndex=e.position where e.ndex='".$_GET['id']."'"));
$p=mysql_fetch_object(mysql_query("select * from employee_compensation where employeeId=".$emp->ndex.""));
$basicpay=$p->basicPay + $p->cola + $p->honorarium;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Employee Contract</title>
	<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
</head>
<body style="font-family:Arial;font-size:15px;">
<?php if($_GET['act']=='adnew'){ 
	if($emp->employmentStatus=='Regular'){
	$regulartype =  ($_POST['regtayp'] == 'monthly' ? 'monthly' : 'basic');
?>
<table cellpadding="0" cellspacing="0" style='font-family:Arial;font-size:15px;letter-spacing:1.8px;'>
	<tr><td>Name: <?php echo $emp->lastName.", ".$emp->firstName." ".$emp->middleName."<br>";?></td></tr>
	<tr><td>Position: <?php echo $emp->pos."<br>";?></td></tr>
	<tr><td>Department: <?php echo $emp->dep."<br><br>";?></td></tr>
	
	<tr><td>Subject: Regularization Contract<br><br></td></tr>
	
	<tr><td>Dear <u><?php echo $emp->lastName.", ".$emp->firstName." ".$emp->middleName;?></u>,<br><br></td></tr>
	
	<tr><td align="justify">We are pleased to inform you that you have successfully reached the standards 
	required for your position. You are now a regular employee effective 
	<u><?php echo $_POST['startDate'];?></u>. <br><br></td></tr>
	
	<tr><td align="justify">As a regular employee, you will be receiving a <?php echo $regulartype;?> rate of <u>Php <?php echo number_format($basicpay,2);?></u> 
	inclusive of COLA/Honorarium only monthly only for eight hours of work. Your 
	working schedule shall depend on the plotted schedule submitted by your department
	head or supervisor.<br><br></td></tr>

	<tr><td align="justify">It is understood that you will continue to be bound by the Confidentiality Agreement
	and Code of Discipline along with the provisions stated in the Probationary
	Employment Contract that you signed with us. Such provisions shall be deemed
	incorporated as part of the terms and conditions of your regular employment with us.<br><br></td></tr>
	
	<tr><td>We look forward to more years of fruitful partnership with you.<br><br></td></tr>
		
	
	<tr><td>Very truly yours,</td></tr>
	
	<tr><td>______________</td></tr>
	<tr><td>Mirasol B. Tiu</td></tr>
	<tr><td>Human Resource Director</td></tr>
	<tr><td>Davao Doctors Hospital</td></tr>
	
	
	<tr><td>WITH MY CONFORMITY:</td></tr>

	<tr><td><?php echo "<u>".$emp->lastName.", ".$emp->firstName." ".$emp->middleName."</u>";?></td></tr>
	<tr><td>____________________</td></tr>
</table>

<?php }
elseif($emp->employmentStatus=='Temporary'){
	if($_POST['jd']==""){?>
<table cellpadding="0" cellspacing="0" style='font-family:Arial;font-size:15px;letter-spacing:1.8px;'>

					<tr><td colspan="2" align="center">DAVAO DOCTORS HOSPITAL</td></tr>
				<tr><td colspan="2" align="center">118 E. Quirino Avenue, Davao City<br><br></td></tr>

					<tr><td colspan="2" align="center"><strong>TEMPORARY EMPLOYMENT</strong><br><br></td></tr>

<tr><td width="50%">TO: <?php echo $emp->lastName.", ".$emp->firstName." ".$emp->middleName;?></td><td>            DATE:  <?php echo date('Y-m-d');?><br><br></td></tr>

<tr><td colspan="2">POSITION : <?php echo $emp->pos;?><br><br></td></tr>


<tr><td colspan="2" align="justify">This will confirm your engagement as <u><?php echo $emp->pos;?></u> to <u><?php echo $emp->dep;?></u>. 
Your employment shall be effective for a period not exceeding 
<?php echo $_POST['tmpmonth'];?> month(s), starting on <u><?php echo date('F d,Y',strtotime($_POST['startDate']));?></u> up to <u><?php echo date('F d,Y',strtotime($_POST['endDate']));?></u>, 
or upon completion of your assigned work/project, whichever comes first. 
Your temporary employment will be subject to the following terms and conditions: <br><br></td></tr>

<tr><td colspan="2">1.	Working days 	 <u><?php echo $_POST['tmpdays'];?> days/week</u></td></tr>
<tr><td colspan="2">2.	Working hours  	 <u><?php echo $_POST['tmphours'];?> hours/day</u></td></tr>
<tr><td colspan="2">3.	Salary	       	 <u>Php <?php echo number_format($_POST['tmpsalary'],2);?>/<?php echo $_POST['tmpdaily'];?></u></td></tr>
<tr><td colspan="2">4.	COLA             <u>Php <?php echo number_format($_POST['tmpallowance'],2);?>/<?php echo $_POST['tmpdaily'];?></u></td></tr>
<?php if($_POST['tempnurse']=='on'){?>
<tr><td colspan="2" align="justify">5.	Your salary/wages will be subject to Social Security Premium deductions as well as 
	other legal deductions.</td></tr>
<tr><td colspan="2" align="justify">6.	Being a project/contractual employee, it is understood that your employment shall be 
	valid only for the duration of the project/the completion of your assigned work. Thus, it 
	shall be deemed automatically terminated upon the completion of the project/your 
	assigned work, without obligation on the part of Davao Doctors Hospital to give you 
	notice or to renew your contract. Nevertheless, DDH reserves the right to terminate your 
	employment even before the completion of the project/your assigned work either for 
	legal violation of company rules and regulations, procedures, policies and practices, all 
	of which has been explained to you, and for poor work performance.</td></tr>
<tr><td colspan="2" align="justify">7.	Until otherwise directed, you shall be assigned to work at the <u><?php echo $emp->dep;?></u> 
	department/section. </td></tr>
<?php } else{?>
<tr><td colspan="2" align="justify">5.	Being a temporary employee, it is understood that your employment shall be 
	valid only for the duration of the project/the completion of your assigned work. Thus, it 
	shall be deemed automatically terminated upon the completion of the project/your 
	assigned work, without obligation on the part of Davao Doctors Hospital to give you 
	notice or to renew your contract. Nevertheless, DDH reserves the right to terminate your 
	employment even before the completion of the project/your assigned work either for 
	legal violation of company rules and regulations, procedures, policies and practices, all 
	of which has been explained to you, and for poor work performance.</td></tr>
<tr><td colspan="2" align="justify">6.	Until otherwise directed, you shall be assigned to work at the <u><?php echo $emp->dep;?></u> 
	department/section.<br><br> </td></tr>
<?php } ?>

	<tr><td colspan="2" align="justify">Please indicate your acceptance to the foregoing conditions by signing your name on the 
space provided for below.<br><br></td></tr>

<tr><td>DAVAO DOCTORS HOSPITAL						</td><td> ACCEPTANCE :<br><br></td></tr>

<tr><td>By:								</td><td> I understand the contents 
								hereof and I hereby ACCEPT AND 
								AGREE to the terms and conditions
								of this employment.<br><br></td></tr>


<tr><td>MIRASOL B. TIU							</td><td><u><?php echo $emp->lastName.", ".$emp->firstName." ".$emp->middleName;?></u></td></tr>
<tr><td colspan="2">Human Resource Director<br><br></td></tr>		  

								<tr><td>&nbsp;</td><td> SSS No.  : <u><?php echo $emp->sssNumber;?></u></td></tr>
								<tr><td>&nbsp;</td><td> TIN No.  : <u><?php echo $emp->tin;?></u></td></tr>
								<tr><td>&nbsp;</td><td> Birthday : <u><?php echo $emp->birthDate;?></u><br><br></td></tr>
    

<tr><td>HRD-SF-021 						</td><td> fn:contractofemporarycontractualemployment.doc</td></tr>
			
</table>
<?php }
else{ ?>
<table cellpadding="0" cellspacing="0" style='font-family:Arial;font-size:15px;letter-spacing:1.8px;'>

					<tr><td colspan="2" align="center">DAVAO DOCTORS HOSPITAL</td></tr>
				<tr><td colspan="2" align="center">118 E. Quirino Avenue, Davao City<br><br></td></tr>

					<tr><td colspan="2" align="center"><strong>PROJECT EMPLOYMENT</strong><br><br></td></tr>

<tr><td width="50%">TO: <?php echo $emp->lastName.", ".$emp->firstName." ".$emp->middleName;?></td><td>            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DATE:  <?php echo date('Y-m-d');?><br><br></td></tr>

<tr><td colspan="2">POSITION : <?php echo $emp->pos;?><br><br></td></tr>


<tr><td colspan="2" align="justify">This will confirm your engagement as <u><?php echo $emp->pos;?></u> to <u><?php echo strtoupper($_POST['jd']);?></u>. 
Your employment shall be effective for a period not exceeding 
<?php echo $_POST['tmpmonth2'];?> month(s), starting on <u><?php echo date('F d,Y',strtotime($_POST['startDate']));?></u> up to <u><?php echo date('F d,Y',strtotime($_POST['endDate2']));?></u>, 
or upon completion of your assigned work/project, whichever comes first. 
Your project employment will be subject to the following terms and conditions: <br><br></td></tr>

<tr><td colspan="2">1.	Working days 	 <u><?php echo $_POST['tmpdays2'];?> days/week</u></td></tr>
<tr><td colspan="2">2.	Working hours  	 <u><?php echo $_POST['tmphours2'];?> hours/day</u></td></tr>
<tr><td colspan="2">3.	Salary	       	 <u>Php <?php echo number_format($_POST['tmpsalary2'],2);?>/<?php echo $_POST['tmpdaily2'];?></u></td></tr>
<tr><td colspan="2">4.	COLA             <u>Php <?php echo number_format($_POST['tmpallowance2'],2);?>/<?php echo $_POST['tmpdaily2'];?></u></td></tr>
<?php if($_POST['tempnurse2']=='on'){?>
<tr><td colspan="2" align="justify">5.	Your salary/wages will be subject to Social Security Premium deductions as well as 
	other legal deductions.</td></tr>
<tr><td colspan="2" align="justify">6.	Being a project employee, it is understood that your employment shall be 
	valid only for the duration of the project/the completion of your assigned work. Thus, it 
	shall be deemed automatically terminated upon the completion of the project/your 
	assigned work, without obligation on the part of Davao Doctors Hospital to give you 
	notice or to renew your contract. Nevertheless, DDH reserves the right to terminate your 
	employment even before the completion of the project/your assigned work either for 
	legal violation of company rules and regulations, procedures, policies and practices, all 
	of which has been explained to you, and for poor work performance.</td></tr>
<tr><td colspan="2" align="justify">7.	Until otherwise directed, you shall be assigned to work at the <u><?php echo $emp->dep;?></u> 
	department/section. </td></tr>
<?php } else{?>
<tr><td colspan="2" align="justify">5.	Being a project/contractual employee, it is understood that your employment shall be 
	valid only for the duration of the project/the completion of your assigned work. Thus, it 
	shall be deemed automatically terminated upon the completion of the project/your 
	assigned work, without obligation on the part of Davao Doctors Hospital to give you 
	notice or to renew your contract. Nevertheless, DDH reserves the right to terminate your 
	employment even before the completion of the project/your assigned work either for 
	legal violation of company rules and regulations, procedures, policies and practices, all 
	of which has been explained to you, and for poor work performance.</td></tr>
<tr><td colspan="2" align="justify">6.	Until otherwise directed, you shall be assigned to work at the <u><?php echo $emp->dep;?></u> 
	department/section.<br><br> </td></tr>
<?php } ?>

	<tr><td colspan="2" align="justify">Please indicate your acceptance to the foregoing conditions by signing your name on the 
space provided for below.<br><br></td></tr>

<tr><td>DAVAO DOCTORS HOSPITAL						</td><td> ACCEPTANCE :<br><br></td></tr>

<tr><td>By:								</td><td> I understand the contents 
								hereof and I hereby ACCEPT AND 
								AGREE to the terms and conditions
								of this employment.<br><br></td></tr>


<tr><td>MIRASOL B. TIU							</td><td><u><?php echo $emp->lastName.", ".$emp->firstName." ".$emp->middleName;?></u></td></tr>
<tr><td colspan="2">Human Resource Director<br><br></td></tr>		  

								<tr><td>&nbsp;</td><td> SSS No.  : <u><?php echo $emp->sssNumber;?></u></td></tr>
								<tr><td>&nbsp;</td><td> TIN No.  : <u><?php echo $emp->tin;?></u></td></tr>
								<tr><td>&nbsp;</td><td> Birthday : <u><?php echo $emp->birthDate;?></u><br><br></td></tr>
    

<tr><td>HRD-SF-021 						</td><td> fn:contractofemporarycontractualemployment.doc</td></tr>
			
</table>
<?php }} 
elseif($emp->employmentStatus=='Probationary'){
$probatype =  ($_POST['proba'] == 'monthly' ? 'Monthly' : 'Basic');
$cola =  ($_POST['proba'] == 'monthly' ? 'Php 456.25/mo' : 'Php 15/day');
$posarr=array('75','79','88','95','118','169','171','211','253');
?>

<table cellpadding="0" cellspacing="0" style='font-family:Arial;font-size:14px;'>

						<tr><td colspan="2" align="center">DAVAO DOCTORS HOSPITAL</td></tr>
						<tr><td colspan="2" align="center">118 E. Quirino Avenue</td></tr>
							<tr><td colspan="2" align="center">Davao City<br><br></td></tr>

					<tr><td colspan="2" align="center"><strong>PROBATIONARY EMPLOYMENT AGREEMENT</strong><br><br></td></tr>

  <tr><td colspan="2">Dear <u><?php echo $emp->lastName.", ".$emp->firstName." ".$emp->middleName;?></u>,<br><br></td></tr>

       <tr><td colspan="2" align="justify">This will confirm our agreement on your employment with Davao Doctors Hospital 
  subject to the following terms and conditions, to wit:<br><br></td></tr>
<?php if($_POST['proba']=='daily'){ ?>
  <tr><td>1.</td><td align="justify">You shall be hired for the position of <u><?php echo $emp->pos;?></u>.</td></tr>
<?php } elseif($_POST['proba']=='monthly') {
				if($_POST['probationarynurse']=='on'){?>

  <tr><td>1.</td><td align="justify">You shall be hired for the position of <u><?php echo $emp->pos;?></u>.</td></tr>
<?php } else{?>

 <tr><td> 1.</td><td align="justify">You shall be hired for the position of <u><?php echo $emp->pos;?></u>.</td></tr>
<?php }} ?>
 <tr><td> 2.</td><td align="justify"><?php echo $probatype;?> Salary of: <u><?php echo number_to_words($p->basicPay);?></u> <u><?php echo "(Php ".number_format($p->basicPay,2).")";?></u>.
   <?php if($_POST['probationarycola']!='on'){ ?><?php } else {?> COLA: <u><?php echo $cola;?></u>. <?php } ?>
	</td></tr>
 <tr><td valign="top"> 3.</td><td align="justify">Working hours: <u>eight(8) hours a day; five(5) days a week</u>.</td></tr>
 <tr><td valign="top"> 4.</td><td align="justify">Your employment with the Hospital shall commence on <u><?php echo $_POST['startDate'];?></u> 
    and shall be on probation for a period of SIX  (6) months. Evaluation for regularization 
    shall be on the third and fifth month.
<?php if($_POST['probationarynurse']=='on' || in_array($emp->pnd,$posarr)){?>
    You have committed to work for at least one (1) year at Davao Doctors Hospital, only after which a 
    certificate of employment may be issued.<?php }?></td></tr>
 <tr><td valign="top"> 5.</td><td align="justify">During the probationary period of your employment, you are expected to perform the duties and 
    responsibilities assigned to you in accordance with the Key Performance Indicator/Key Results 
    areas for the positions and company standards, which will be explained to you during the 
    orientation by your immediate superior.  You are likewise expected to obey all lawful 
    orders of your superiors and comply with company rules and regulation, a copy of which 
    shall be issued separately.  It is therefore clearly understood that if you fail to meet 
    the work standards expected of you, or violate any law, company rules or regulations, the 
    Hospital has the prerogative to terminate your employment.</td></tr>

 <tr><td valign="top"> 6.</td><td align="justify">You are also expected to perform your work diligently to the best of your ability, maintain 
    and establish good relations with the other employees and respect all officers, employees 
    and clients of the Hospital.</td></tr>

 <tr><td valign="top"> 7.</td><td align="justify">Your gross salary is subject to taxes and other compulsory deductions as required by existing 
    laws,like Social Security System, PHIC Employee's Compensation and PAG-IBIG Fund, etc.</td></tr>
<?php if($_POST['proba']=='daily'){ ?>

  <tr><td valign="top">8.</td><td align="justify">Computation of salary is on daily basis: no work, no pay; payment will be bi-monthly. Whenever 
    necessary, you must also be willing to render overtime work based on operational exigencies for 
    which you shall be paid accordingly.</td></tr>
<?php } elseif($_POST['proba']=='monthly') {?>

  <tr><td valign="top">8.</td><td align="justify">Computation of salary is on a monthly basis and payment will be bi-monthly. Whenever 
    necessary, you must also be willing to render overtime work based on operational exigencies for 
    which you shall be paid accordingly.</td></tr>
<?php } ?>

  <tr><td valign="top">9.</td><td align="justify">While on probation, you will not be entitled to sick leave or vacation leave with pay.  
    Entitlement to both will be after rendering one year of service with the company. </td></tr>

  <tr><td style="text-align:left;">10.</td><td align="justify">The Hospital shall have the absolute right to re-assign or transfer you to any department 
    or section within the organization.</td></tr>

  <tr><td style="text-align:left;width:30px;">11.</td><td align="justify">It is expressly agreed that there are no verbal agreements or understanding between you and 
    the Hospital or any of its agents and representatives affecting this Probationary Agreement 
    unless the same are done in writing.</td></tr>
	<tr><td align="justify" colspan="2">    If the foregoing terms and conditions are acceptable to you, kindly sign at the bottom 
    hereof to signify your unqualified and unconditional acceptance and confirmation thereto.<br><br></td></tr>
	
    <tr><td colspan="2">Davao City, Philippines, signed this _____________________________.<br><br><br><br></td></tr>


			
     

    <tr><td colspan="2">__________________________	<br><br></td></tr>
				
	<tr><td colspan="2">MIRASOL B. TIU		</td></tr>	                                                 
   <tr><td colspan="2"> Human Resource Director 	<br><br></td></tr>        		                         
                                                                                    
       <tr><td colspan="2" align="justify">I hereby acknowledge receipt of a signed copy of this Agreement as well as a copy of the 
    Davao Doctors Hospital Personnel Handbook that contain its policies, rules and regulations and 
    unconditionally agree to accept the same.<br><br><br><br></td></tr>

								<tr><td colspan="2" align="right"><?php echo $emp->lastName.", ".$emp->firstName." ".$emp->middleName;?>  </td></tr>
													
              							<tr><td colspan="2" align="right">Date: __________________	<br><br></td></tr>

    <tr><td colspan="2">Cc: 201 file/Payroll</td></tr>
	<tr><td colspan="2">fn: probation.doc</td></tr>

</table>

<?php }} 
else {
?>
<table style="font-family:Arial;font-size:12px;" width="100%">
	<tr><td><?php echo $msg;?></td></tr>
	<tr><td align="center"><u><h1>Employee Contract</h1></u></td></tr>	
</table>
<form name="frmcompo" action="employeecontract.php?act=adnew&id=<?php echo $_GET['id'];?>" method="post">
<table style="font-family:Arial;font-size:12px;">
	<tr><td colspan="2"><strong><?php echo getID($emp->employmentStatus,$emp->employeeNo);?>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo $emp->lastName.",".$emp->firstName." ".$emp->middleName;?></strong></td></tr>
	<tr><td>Start Date: </td><td><input type="Text" name="startDate" id="startDate" size="15"><a href="javascript:show_calendar('frmcompo.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
	
	<tr><td colspan="2" align="center"><br><u><strong>If Regular Status:</strong></u></td></tr>
	<tr><td colspan="2"><input type="Radio" name="regtayp" value="daily">Daily <input type="Radio" name="regtayp" value="monthly">Monthly</td></tr>
	
	<tr><td colspan="2" align="center"><br><u><strong>If on Probationary Status:</strong></u></td></tr>
	<tr><td colspan="2"><input type="Radio" name="proba" value="daily">Daily <input type="Radio" name="proba" value="monthly">Monthly</td></tr>
	<tr><td colspan="2"><input type="Checkbox" name="probationarynurse">Check this if in Nursing position:</td></tr>
	<tr><td colspan="2"><input type="Checkbox" name="probationarycola">Check this if employee have COLA:</td></tr>
	
	<tr><td colspan="2" align="center"><br><u><strong>If on Temporary Status:</strong></u></td></tr>
	<tr><td colspan="2"><input type="Checkbox" name="tempnurse">Check this if in Nursing position:</td></tr>
	<tr><td>Working Days:</td><td><input name="tmpdays"></td></tr>
	<tr><td>Working Hours:</td><td><input name="tmphours"></td></tr>
	<tr><td>Salary:</td><td><input name="tmpsalary" value="0"></td></tr>
	<tr><td>Living Allowance:</td><td><input name="tmpallowance" value="0"></td></tr>
	<tr><td># of month(s):</td><td><input name="tmpmonth" value="5"></td></tr>
	<tr><td colspan="2"><input type="Radio" name="tmpdaily" value="day">Daily <input type="Radio" name="tmpdaily" value="month">Monthly</td></tr>
	<tr><td>End Date: </td><td><input type="Text" name="endDate" id="endDate" size="15"><a href="javascript:show_calendar('frmcompo.endDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
	
	
	<tr><td colspan="2" align="center"><br><u><strong>If on Project Temporary Status:</strong></u></td></tr>
	<tr><td colspan="2"><input type="Checkbox" name="tempnurse2">Check this if in Nursing position:</td></tr>
	<tr><td>Job Description:</td><td><input name="jd"></td></tr>
	<tr><td>Working Days:</td><td><input name="tmpdays2"></td></tr>
	<tr><td>Working Hours:</td><td><input name="tmphours2"></td></tr>
	<tr><td>Salary:</td><td><input name="tmpsalary2" value="0"></td></tr>
	<tr><td>Living Allowance:</td><td><input name="tmpallowance2" value="0"></td></tr>
	<tr><td># of month(s):</td><td><input name="tmpmonth2" value="5"></td></tr>
	<tr><td colspan="2"><input type="Radio" name="tmpdaily2" value="day">Daily <input type="Radio" name="tmpdaily" value="month">Monthly</td></tr>
	<tr><td>End Date: </td><td><input type="Text" name="endDate2" id="endDate2" size="15"><a href="javascript:show_calendar('frmcompo.endDate2');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
	<tr><td colspan="2" align="center"><input type="Submit" value="Print"></td></tr>
</table>
</form>
<?php } ?>
</body>
</html>
<?php ob_end_flush();?>