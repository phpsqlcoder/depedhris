<?php
ob_start();
session_start();
include("../../dbcon.php");
	include("../../scripts/scripts.php");
	include("../../employeefunctions.php");


$_GET['id']=$_POST['id'];
$id=$_GET['id'];


	// employee data
	$em=mysql_fetch_object(mysql_query("select e.*,d.name as department,dd.name as division,u.name as unit,p.name as newposition from employee e
					   left join dept d on d.ndex=e.deptId
					   left join division dd on dd.ndex=e.divisionId
					   left join unit u on u.ndex=e.unitId
					   left join position p on p.ndex=e.position
					   where e.ndex='".$id."'"));
	//picture
	if($em->picture){
		$img=$em->picture;
	}
	else{
		$img="blank-user.gif";
	}
	// dependents 
	$depqry=mysql_query("select * from empdependents where employeeId=".$id."");
	$deppart=0;
	while($a=mysql_fetch_object($depqry)){
		$deppart++;
		$check =  ($a->isDependent == 1 ? "checked='checked'" : "");
		$medicalcheck =  ($a->isMedicalDependent == 1 ? "checked='checked'" : "");
		$dependents.="
			<tr>
						<td colspan='6'>
						<div style='display:block;'>
						<table>					
							<tr>
								<td>
									<input type='Checkbox' name='".$deppart."isDependent'".$check.">
									<input type='Checkbox' name='".$deppart."isMedicalDependent' ".$medicalcheck.">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</td>
								<td><select name='".$deppart."relationship'>".$optionrelationship."<option selected='selected' value='".$a->relationship."'>".$a->relationship."</select></td>
								<td><input type='Text' name='".$deppart."name' size='35' value='".$a->name."'></td>
								<td><input type='Text' name='".$deppart."birthDate' id='".$deppart."birthDate' size='15' value='".$a->birthDate."' style='color:gray;' onfocus='if(this.value=='Birth Date'){this.value='';}this.style.color='black';'></td>
								<td><input type='Text' name='".$deppart."occupation' size='25' value='".$a->occupation."'></td>
								<td><input type='Text' name='".$deppart."company' size='35' value='".$a->company."'><input type='hidden' name='".$deppart."perid' size='25' value='".$a->ndex."'></td>
							</tr>
						</table>
						</div>
						</td>
			</tr>	
		";
	}
	$dependents.="<tr><td colspan='6'><input type='hidden' name='numdep' value=".$deppart."><div style='display:block;'><table><tr><td><div id='divpersonalbgadd100'><a onclick=\"personalbgaddrow('1','100');\" style='color:blue;'>ADD MORE</a></div></td></tr></table></div></td></tr>";
	//education
	$eduqry=mysql_query("select * from empeducationalbg where employeeId=".$id."");
	$edupart=0;
	while($b=mysql_fetch_object($eduqry)){
	$edupart++;
	$education.="<tr>
						<td colspan='5'>
						<div style='display:block;'>
						<table>					
							<tr><input type='hidden' name='".$edupart."eduid' size='25' value='".$b->ndex."'>
								<td><select name='".$edupart."level'>".$optioneducationlevel."<option selected='selected' value='".$b->level."'>".$b->level."</select></td>
								<td><input type='Text' name='".$edupart."school' size='40' value='".$b->school."'><input type='hidden' name='".$deppart."eduid' size='25' value='".$a->ndex."'></td>
								<td><input type='Text' name='".$edupart."degree' size='40' value='".$b->degree."'></td>
								<td><input type='Text' name='".$edupart."fromDateedu' value='".$b->fromDate."' id='".$edupart."fromDateedu' size='15'></td>
								<td><input type='Text' name='".$edupart."toDateedu' id='".$edupart."toDateedu' size='15' value='".$b->toDate."'></td>								
							</tr>
						</table>
						</div>
						</td>
					</tr>";
	}
	$education.="<tr><td colspan='5'><input type='hidden' name='numedu' value=".$edupart."><div style='display:block;'><table><tr><td><div id='diveducationalbgadd100'><a onclick=\"educationalbgaddrow('1','100');\" style='color:blue;'>ADD MORE</a></div></td></tr></table></div></td></tr>";
	//employment
	$empqry=mysql_query("select * from empemploymentbg where employeeId=".$id."");
	$emppart=0;
	while($c=mysql_fetch_object($empqry)){
	$emppart++;
	$employment.="
			<tr>
						<td colspan='5'>
						<div style='display:block;'>
						<table>
							<tr><input type='hidden' name='".$emppart."empid' size='25' value='".$c->ndex."'>
								<td>Company Name:</td>							
								<td>Position Held:</td>							
								<td>Inclusive Dates:</td>							
							</tr>
							<tr>							
								<td><input type='Text' name='".$emppart."companyNameemp' size='35' value='".$c->companyName."'></td>							
								<td><input type='Text' name='".$emppart."position' size='25' value='".$c->position."'></td>								
								<td><input type='Text' name='".$emppart."fromDateemp' id='".$emppart."fromDateemp' size='10' value='".$c->fromDate."'>
									 to 
									 <input type='Text' name='".$emppart."endDateemp' id='".$emppart."endDateemp' size='10' value='".$c->endDate."'></td>
							</tr>
							<tr>
								<td>Company Address:</td>
								<td>Supervisor:</td>
								<td>Position:</td>
							</tr>
							<tr>
								<td><input type='Text' name='".$emppart."companyAddressemp' size='45' value='".$c->companyAddress."'></td>
								<td><input type='Text' name='".$emppart."supervisor' size='25' value='".$c->supervisor."'></td>
								<td><input type='Text' name='".$emppart."supervisorPosition' size='25' value='".$c->supervisorPosition."'></td>
							</tr>
							<tr>
								<td valign='top'>Main Duties and Responsibilities:</td>								
							</tr>
							<tr>								
								<td><textarea name='".$emppart."dutiesResponsibilities' cols='30' rows='2'>".$c->dutiesResponsibilities."</textarea></td>								
							</tr>
							<tr>								
								<td>Monthly Salary:</td>
							</tr>
							<tr>								
								<td><input type='Text' name='".$emppart."monthlySalary' size='15' value='".$c->monthlySalary."'></td>							
							</tr>
							<tr>								
								<td>Reason/s for Leaving:</td>
							</tr>
							<tr>								
								<td colspan='3'>
									<textarea name='".$emppart."reasonForLeaving' cols='30' rows='2'>".$c->reasonForLeaving."</textarea>	
								</td>								
							</tr>
							<tr><td colspan='5'><hr></td></tr>
								
						</table>
						</div>
						</td>
					</tr>	
	";
	}
	$employment.="<tr><td colspan='5'><input type='hidden' name='numemp' value=".$emppart."><div style='display:block;'><table><tr><td><div id='divemploymentbgadd100'><a onclick=\"employmentbgaddrow('1','100');\" style='color:blue;'>ADD MORE</a></div></td></tr></table></div></td></tr>";
	//training
	$traqry=mysql_query("select * from emptrainings where employeeId=".$id."");
	$trapart=0;
	while($d=mysql_fetch_object($traqry)){
	$trapart++;
	$trainings.="
		<tr>
			<td colspan='5'>
			<div style='display:block;'>
			<table>
				<tr><input type='hidden' name='".$trapart."traid' size='25' value='".$d->ndex."'>
					<td><select style='font-size:11px;' name='".$trapart."venue'><option selected='selected' value='".$d->venue."'>".$d->venue."<option value='External'>External<option value='Internal'>Internal</select></td>
					<td>Description :&nbsp;&nbsp;</td><td><input type='Text' name='".$trapart."nametraining' size='55' value='".$d->name."'></td>
					<td><td>Date/s :&nbsp;&nbsp;<input type='Text' name='".$trapart."fromDateprof' id='".$trapart."fromDateprof' value='".$d->fromDate."' size='10'>
					 to 
					 <input type='Text' name='".$trapart."endDateprof' id='".$trapart."endDateprof' size='10' value='".$d->endDate."'>
					
					 </td></td>
				</tr>
						
			</table>
			</div>
			</td>
		</tr>		
	";
	}
	$trainings.="<tr><td colspan='5'><input type='hidden' name='numtra' value=".$trapart."><div style='display:block;'><table><tr><td><div id='divprofadd100'><a onclick=\"profaddrow('1','100');\" style='color:blue;'>ADD MORE</a></div>	</td></tr>		</table></div></td></tr>";
	//interest
	$intqry=mysql_query("select * from empprofessionalinterest where employeeId=".$id."");
	$intpart=0;
	while($e=mysql_fetch_object($intqry)){
	$intpart++;
	$interest.="
		<tr>
			<td colspan='5'>
			<div style='display:block;'>
			<table>					
				<tr><input type='hidden' name='".$intpart."intid' size='25' value='".$e->ndex."'>
					<td><input type='Text' size='25' name='".$intpart."companyName' value='".$e->companyName."'>
					</td>
					<td><input type='Text' size='15' name='".$intpart."role' value='".$e->role."'></td>
					<td><input type='Text' size='35' name='".$intpart."companyAddress' value='".$e->companyAddress."'></td>								
				</tr>
			</table>
			</div>
			</td>
		</tr>				
	";
	}
	$interest.="<tr><td colspan='5'><input type='hidden' name='numint' value=".$intpart."><div style='display:block;'><table><tr><td><div id='divinterestadd100'><a onclick=\"interestaddrow('1','100');\" style='color:blue;'>ADD MORE</a></div>		</td></tr>		</table></div></td></tr>";
	$deptqry=mysql_query("SELECT * FROM dept WHERE status<>1 and divisionId='".$em->divisionId."'");
	while($dep=mysql_fetch_object($deptqry)){
		$optdept.="<option value='".$dep->ndex."'>".$dep->name."";
	}
	$optdept.="<option selected='selected' value='".$em->deptId."'>".$em->department."";
	$unitqry=mysql_query("SELECT * FROM unit WHERE status<>1 and departmentId='".$em->departmentId."'");
	while($un=mysql_fetch_object($unitqry)){
		$optunit.="<option value='".$un->ndex."'>".$un->name."";
	}
	$optunit.="<option selected='selected' value='".$em->unitId."'>".$em->unit."";
	//if($em->employeeNo>0){ $optionemploymentstatus="<option value='".$em->employmentStatus."'>".$em->employmentStatus.""; }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="../../css/styles.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../scripts/datepickercontrol/datepickercontrol.css">  
	<style>
#main_content_wrap input[type="text"]:disabled { 
    color : black;
   
	background-color:transparent

border: none;
}
</style>
	<script type="text/javascript" src="../../scripts/datepickercontrol/datepickercontrol.js"></script>
	<script type="text/javascript" src="../../jquery.js"></script>
		<script type="text/javascript">
			function personalbgaddrow(ndex,ndexadd){
				$(window['divpersonalbg' + ndex]).style.display='block';
				$(window['divpersonalbgadd' + ndexadd]).style.display='none';
				$(personalcounter).value=parseInt($(personalcounter).value)+1;
			}
			function educationalbgaddrow(ndex,ndexadd){
				$(window['diveducationalbg' + ndex]).style.display='block';
				$(window['diveducationalbgadd' + ndexadd]).style.display='none';
				$(educationalcounter).value=parseInt($(educationalcounter).value)+1;
			}
			function employmentbgaddrow(ndex,ndexadd){
				$(window['divemploymentbg' + ndex]).style.display='block';
				$(window['divemploymentbgadd' + ndexadd]).style.display='none';
				$(employmentcounter).value=parseInt($(employmentcounter).value)+1;
			}
			function profaddrow(ndex,ndexadd){
				$(window['divprof' + ndex]).style.display='block';
				$(window['divprofadd' + ndexadd]).style.display='none';
				$(trainingcounter).value=parseInt($(trainingcounter).value)+1;
			}
			function interestaddrow(ndex,ndexadd){
				$(window['divinterest' + ndex]).style.display='block';
				$(window['divinterestadd' + ndexadd]).style.display='none';
				$(interestcounter).value=parseInt($(interestcounter).value)+1;
			}
			function checks(){
				var stat=document.getElementById('employmentStatus');
				if(stat.value==""){
					alert('You need to select employment status!');
					return false;
				}
			}
			function showEffectivityDate(feld){
				//alert(feld);
				document.getElementById(feld).style.display='block';
			}
		</script>
		<script>
			function disableall(){
				$('#main_content_wrap :input').prop("disabled", true);
			}
		</script>
	<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "../../calendar.inc"; ?>
</head>
<body onload="disableall();">

<div style="position:absolute;top:270px;right:100px;"><img height="300" width="250" src="../../picture/<?php echo $img;?>"></div>
<div id="main_content_wrap" class="container_12">
     <h2>Employee 201 File&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--<img height="50" width="60" onsubmit="return checks();" src="picture/<?php //echo $img;?>">--></h2>
<form method="post" name="employeefrm" action="<?php echo $_SERVER['PHP_SELF']; ?>?act=save&id=<?php echo $id;?>" enctype="multipart/form-data">
	 <table style="font-size:11px;">
	 	<tr>
			<td>
				<table>
				 	<tr>
						<td>Full Name:&nbsp;&nbsp;</td>
						<td><input type="Text" name="firstName" size="15" value="<?php echo $em->firstName;?>">
						<input type="Text" name="middleName" size="15" value="<?php echo $em->middleName;?>">
						<input type="Text" name="lastName" size="15" value="<?php echo $em->lastName;?>"></td>
					</tr>
					<tr><td>Position:&nbsp;&nbsp;</td><td><select name="position" onchange="showEffectivityDate('positionDatediv');"><?php echo $optionposition;?><option selected="selected" value="<?php echo $em->position;?>"><?php echo $em->newposition;?></select>
							<div id="positionDatediv" style="font-size:10p;display:none;"><strong style="color:blue;">Effectivity Date:</strong><input type="Text" name="positionDate" id="positionDate" size="15" value="<?php echo date('Y-m-d');?>"></div>
							</td></tr>					
					<tr><td>Division:&nbsp;&nbsp;</td><td><select name="divisionId" onchange="dbcon('afterdivisionselectfordept','deptdiv',employeefrm);"><?php echo $optiondivision;?><option selected="selected" value="<?php echo $em->divisionId;?>"><?php echo $em->division;?></select></td></tr>
					<tr><td>Department:&nbsp;&nbsp;</td><td><div id="deptdiv"><select name="deptId" onchange="showEffectivityDate('deptIdDatediv');"><option value="">- Select Division -<?php echo $optdept;?></select></div>
							<div id="deptIdDatediv" style="font-size:10p;display:none;"><strong style="color:blue;">Effectivity Date:</strong><input type="Text" name="deptIdDate" id="deptIdDate" size="15" value="<?php echo date('Y-m-d');?>"></div>
							</td></tr>
					<tr><td>Unit:&nbsp;&nbsp;</td><td><div id="unitdiv"><select name="unitId"><option value="">- Select Department -<?php echo $optunit;?></select></div></td></tr>
					
					
					<tr><td>Employee No:&nbsp;&nbsp;</td><td><input type="Text" name="employeeNo" size="15" <?php if($_SESSION['ndex']!='15'){echo "readonly='readonly'";}?> value="<?php echo $em->employeeNo;?>"></td></tr>
					<tr><td>Date Hired:&nbsp;&nbsp;</td><td><input type="Text" name="dateHired" id="dateHired" value="<?php echo $em->dateHired;?>" size="15"></td></tr>
					<tr><td>Bank Account No:&nbsp;&nbsp;</td><td><input type="Text" name="bankAccountNo" <?php if($_SESSION['ndex']!='15'){echo "readonly='readonly'";}?> value="<?php echo $em->bankAccountNo;?>" size="15"></td></tr>
					<tr><td>Locker No:&nbsp;&nbsp;</td><td><input type="Text" name="lockerNo" value="<?php echo $em->lockerNo;?>" size="15"></td></tr>
					<tr><td>Employment Status:&nbsp;&nbsp;</td><td><select readonly="readonly" disable="disable" name="employmentStatus" id="employmentStatus" onchange="showEffectivityDate('employmentStatusDatediv');" <?php echo $employeeNoLock; ?>><?php echo $optionemploymentstatus;?><option selected="selected" value="<?php echo $em->employmentStatus;?>"><?php echo $em->employmentStatus;?></select>
							<div id="employmentStatusDatediv" style="font-size:10p;display:none;"><strong style="color:blue;">Effectivity Date:</strong><input type="Text" name="employmentStatusDate" id="employmentStatusDate" size="15" value="<?php echo date('Y-m-d');?>"></div>
							</td></tr>
					<tr><td>Residency Training Program:&nbsp;&nbsp;</td><td><select name="residencyTrainingProgram"><?php echo $optionresidencytrainingprogram;?><option selected="selected" value="<?php echo $em->residencyTrainingProgram;?>"><?php echo $em->residencyTrainingProgram;?></select></td></tr>
					<tr><td>Pay Type:&nbsp;&nbsp;</td><td><select name="payType"><?php echo $optionpaytype;?><option selected="selected" value="<?php echo $em->payType;?>"><?php echo $em->payType;?></select></td></tr>
					<tr><td>Level:&nbsp;&nbsp;</td><td><select name="level"><?php echo $optionrank;?><option value="<?php echo $em->level;?>" selected="selected"><?php echo "Level ".$em->level;?></select></td></tr>
					<tr><td>Taxable:&nbsp;&nbsp;</td><td><input type="Radio" name="isTaxable" value="1" <?php if($em->isTaxable==1){echo "checked='checked'";}?>>Yes<input type="Radio" name="isTaxable" value="2" <?php if($em->isTaxable==0){echo "checked='checked'";}?>>No</td></tr>					
					<tr><td>Union Member:&nbsp;&nbsp;</td><td><input type="Radio" name="isUnionMember" value="1" <?php if($em->isUnionMember==1){echo "checked='checked'";}?>>Yes<input type="Radio" name="isUnionMember" value="2" <?php if($em->isUnionMember==0){echo "checked='checked'";}?>>No</td></tr>					
					<tr><td>Coop Member:&nbsp;&nbsp;</td><td><input type="Radio" name="isCoopMember" value="1" <?php if($em->isCoopMember==1){echo "checked='checked'";}?>>Yes<input type="Radio" name="isCoopMember" value="2" <?php if($em->isCoopMember==0){echo "checked='checked'";}?>>No</td></tr>					
					<tr><td>Biometric No:&nbsp;&nbsp;</td><td><input type="Text" name="biometricNo" size="15" value="<?php echo $em->biometricNo;?>"></td></tr>
					<tr><td>Kiosk Password:&nbsp;&nbsp;</td><td><input type="password" name="password" size="15" value="<?php echo $em->password;?>"></td></tr>
					
				 </table>   
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
	 	<tr><td><strong style="font-size:12px;color:maroon;"><u>l. PERSONAL INFORMATION</u></strong></td></tr>
		<tr><td>&nbsp;</td></tr>
	 	<tr>
			<td>
				<table>
					<tr><td>Picture:&nbsp;&nbsp;</td><td><input type="file" name="picture" id="picture" /><input type="Hidden" name="oldpicture" id="oldpicture" value="<?php echo $em->picture;?>"></td></tr>
					<tr><td>Nick Name:&nbsp;&nbsp;</td><td><input type="Text" name="nickName" size="15" value="<?php echo $em->nickName;?>"></td></tr>
					<tr><td>Sex:&nbsp;&nbsp;</td><td><input type="Radio" name="sex" value="MALE" <?php if($em->sex=='MALE'){echo "checked='checked'";}?>>MALE<input type="Radio" name="sex" value="FEMALE" <?php if($em->sex=='FEMALE'){echo "checked='checked'";}?>>FEMALE</td></tr>
					<tr><td>Blood Type:&nbsp;&nbsp;</td><td><select name="bloodType"><option value="<?php echo $em->bloodType;?>"><?php echo $em->bloodType;?><?php echo $optionbloodtype; ?></select></td></tr>
					<tr><td>Civil Status:&nbsp;&nbsp;</td><td><select name="civilStatus"><?php echo $optioncivilstatus;?><option selected="selected" value="<?php echo $em->civilStatus;?>"><?php echo $em->civilStatus;?></select></td></tr>
					<tr><td>Present Address:&nbsp;&nbsp;</td><td><input type="Text" name="presentAddress" size="75" value="<?php echo $em->presentAddress;?>"></td></tr>
					<tr><td>Permanent Address:&nbsp;&nbsp;</td><td><input type="Text" name="permanentAddress" size="75" value="<?php echo $em->permanentAddress;?>"></td></tr>
					<tr><td>Email Address:&nbsp;&nbsp;</td><td><input type="Text" name="emailAddress" size="35" value="<?php echo $em->emailAddress;?>"></td></tr>
					<tr><td>Home Phone No:&nbsp;&nbsp;</td><td><input type="Text" name="homePhoneNumber" size="15" value="<?php echo $em->homePhoneNumber;?>"></td></tr>
					<tr><td>Mobile No:&nbsp;&nbsp;</td><td><input type="Text" name="mobilePhoneNumber" size="15" value="<?php echo $em->mobilePhoneNumber;?>"></td></tr>
					<tr><td>Birth Date:&nbsp;&nbsp;</td><td><input type="Text" name="birthDate" id="birthDate" size="15" value="<?php echo $em->birthDate;?>"></td></tr>
					<tr><td>Birth Place:&nbsp;&nbsp;</td><td><input type="Text" name="birthPlace" size="75" value="<?php echo $em->birthPlace;?>"></td></tr>
					<tr><td>Nationality:&nbsp;&nbsp;</td><td><input type="Text" name="nationality" size="45" value="<?php echo $em->nationality;?>"></td></tr>
					<tr><td>Religion:&nbsp;&nbsp;</td><td><input type="Text" name="religion" size="45" value="<?php echo $em->religion;?>"></td></tr>
					<tr><td>SSS No:&nbsp;&nbsp;</td><td><input type="Text" name="sssNumber" size="45" value="<?php echo $em->sssNumber;?>"></td></tr>
					<tr><td>PHIC No:&nbsp;&nbsp;</td><td><input type="Text" name="phicNumber" size="45" value="<?php echo $em->phicNumber;?>"></td></tr>
					<tr><td>Pag-ibig No:&nbsp;&nbsp;</td><td><input type="Text" name="pagibigNumber" size="45" value="<?php echo $em->pagibigNumber;?>"></td></tr>
					<tr><td>TIN:&nbsp;&nbsp;</td><td><input type="Text" name="tin" size="45" value="<?php echo $em->tin;?>"></td></tr>
					<tr><td>Occupational Permit #:&nbsp;&nbsp;</td><td><input type="Text" name="occupationalPermitNo" size="45" value="<?php echo $em->occupationalPermitNo;?>"> Date Issued:<input value="<?php echo $em->occupationalPermitDate;?>" type="Text" name="occupationalPermitDate" id="occupationalPermitDate" size="15"></td></tr>
					<tr><td>PTR #:&nbsp;&nbsp;</td><td><input type="Text" name="ptrNo" size="45" value="<?php echo $em->ptrNo;?>"> Date Issued:<input type="Text" name="ptrNoDate" id="ptrNoDate" size="15" value="<?php echo $em->ptrNoDate;?>"></td></tr>
					<tr><td>Cedula #:&nbsp;&nbsp;</td><td><input type="Text" name="cedulaNo" size="45" value="<?php echo $em->cedulaNo;?>"> Date Issued:<input type="Text" name="cedulaDate" id="cedulaDate" size="15" value="<?php echo $em->cedulaDate;?>"></td></tr>
					<tr><td>City Health #:&nbsp;&nbsp;</td><td><input type="Text" name="cityHealth" size="45" value="<?php echo $em->cityHealth;?>"> Date Issued:<input type="Text" name="cityHealthDate" id="cityHealthDate" size="15" value="<?php echo $em->cityHealthDate;?>"></td></tr>
					<tr><td>PRC #:&nbsp;&nbsp;</td><td><input type="Text" name="prcNo" size="45" value="<?php echo $em->prcNo;?>"> Date Issued:<input type="Text" name="prcDate" id="prcDate" size="15" value="<?php echo $em->prcDate;?>"></td></tr>
				 </table>   
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><strong style="font-size:12px;color:maroon;"><u>ll. PERSONAL BACKGROUND</u></strong></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td colspan="2">Dependent|Medical <input type="Hidden" name="personalcounter" id="personalcounter" value="0"></td></tr>
		<tr>
			<td>
				<table>
					<?php
					echo $dependents;
						for($a=1;$a<=50;$a++){
							$display =  ($a == 0 ? 'block' : 'none');
					?>
					<tr>
						<td colspan="6">
						<div style="display:<?php echo $display;?>;" id="divpersonalbg<?php echo $a;?>">
						<table>					
							<tr>
								<td>
									<input type="Checkbox" name="isDependent<?php echo $a;?>">
									<input type="Checkbox" name="isMedicalDependent<?php echo $a;?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<div id="divpersonalbgadd<?php echo $a;?>">
										<a onclick="personalbgaddrow('<?php echo ($a+1)."','".$a;?>');" style='color:blue;'>ADD MORE</a>
									</div>							
								</td>
								<td><select name="relationship<?php echo $a;?>"><?php echo $optionrelationship;?></select></td>
								<td><input type="Text" name="nameper<?php echo $a;?>" size="25" value="Name" style="color:gray;" onfocus="if(this.value=='Name'){this.value='';}this.style.color='black';"></td>
								<td><input type="Text" name="birthDate<?php echo $a;?>" id="birthDate<?php echo $a;?>" size="15" value="Birth Date" style="color:gray;" onfocus="if(this.value=='Birth Date'){this.value='';}this.style.color='black';"></td>
								<td><input type="Text" name="occupation<?php echo $a;?>" size="25" value="Occupation" style="color:gray;" onfocus="if(this.value=='Occupation'){this.value='';}this.style.color='black';"></td>
								<td><input type="Text" name="company<?php echo $a;?>" size="25" value="Company" style="color:gray;" onfocus="if(this.value=='Company'){this.value='';}this.style.color='black';"></td>								
							</tr>
						</table>
						</div>
						</td>
					</tr>	
					<?php 
						}
					?>
					<tr><td>&nbsp;</td></tr>
					<tr><td>In case of Emergency, notify:<input type="Text" name="emergencyContactPerson" size="25" value="<?php echo $em->emergencyContactPerson;?>">&nbsp;&nbsp;Relationship: <select name="emergencyRelationship"><?php echo $optionrelationship;?><option selected="selected" value="<?php echo $em->emergencyRelationship;?>"><?php echo $em->emergencyRelationship;?></select>	&nbsp;&nbsp;Contact#: <input type="Text" name="emergencyContactNumber" size="15" value="<?php echo $em->emergencyContactNumber;?>"></td></tr>
				</table>				
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><strong style="font-size:12px;color:maroon;"><u>lll. EDUCATIONAL BACKGROUND</u></strong></td></tr>
		<tr><td>&nbsp;<input type="Hidden" name="educationalcounter" id="educationalcounter" value="0"></td></tr>	
		<tr>
			<td>
				<table>
					<?php 
					echo $education;
						for($b=1;$b<=50;$b++){
							$display =  ($b == 0 ? 'block' : 'none');
					?>
					<tr>
						<td colspan="5">
						<div style="display:<?php echo $display;?>;" id="diveducationalbg<?php echo $b;?>">
						<table>					
							<tr>
								<td><select name="level<?php echo $b;?>"><?php echo $optioneducationlevel;?></select>
									<div id="diveducationalbgadd<?php echo $b;?>">
										<a onclick="educationalbgaddrow('<?php echo ($b+1)."','".$b;?>');" style='color:blue;'>ADD MORE</a>
									</div>									
								</td>
								<td><input type="Text" name="school<?php echo $b;?>" size="25" value="School" style="color:gray;" onfocus="if(this.value=='School'){this.value='';}this.style.color='black';"></td>
								<td><input type="Text" name="degree<?php echo $b;?>" size="25" value="Degree Earned" style="color:gray;" onfocus="if(this.value=='Degree Earned'){this.value='';}this.style.color='black';"></td>
								<td><input type="Text" name="fromDateedu<?php echo $b;?>" id="fromDateedu<?php echo $b;?>" size="15" value="From" style="color:gray;" onfocus="if(this.value=='From'){this.value='';}this.style.color='black';"></td>
								<td><input type="Text" name="toDateedu<?php echo $b;?>" id="toDateedu<?php echo $b;?>" size="15" value="To" style="color:gray;" onfocus="if(this.value=='To'){this.value='';}this.style.color='black';"></td>								
							</tr>
						</table>
						</div>
						</td>
					</tr>					
					<?php 
						}
					?>
				</table>				
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td>
				<table>
					<tr><td>Professional Licensure Taken :&nbsp;&nbsp;</td><td><input type="Text" name="educProfLicensureTaken" size="15" value="<?php echo $em->educProfLicensureTaken;?>"></td></tr>
					<tr><td>Board Rating:&nbsp;&nbsp;</td><td><input type="Text" name="educBoardRating" size="15" value="<?php echo $em->educBoardRating;?>"></td></tr>
					<tr><td>Year Passed:&nbsp;&nbsp;</td><td><input type="Text" name="educYearPassed" size="15" value="<?php echo $em->educYearPassed;?>"></td></tr>
				</table>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><strong style="font-size:12px;color:maroon;"><u>lV. EMPLOYMENT BACKGROUND</u></strong></td></tr>
		<tr><td>&nbsp;<input type="Hidden" name="employmentcounter" id="employmentcounter" value="0"></td></tr>	
		<tr>
			<td>
				<table>
					<?php 
						echo $employment;
						for($c=1;$c<=50;$c++){
							$display =  ($c == 0 ? 'block' : 'none');
					?>
					<tr>
						<td colspan="5">
						<div style="display:<?php echo $display;?>;" id="divemploymentbg<?php echo $c;?>">
						<table>
							<tr>
								<td>Company Name:</td>							
								<td>Position Held:</td>							
								<td>Inclusive Dates:</td>							
							</tr>
							<tr>							
								<td><input type="Text" name="companyNameemp<?php echo $c;?>" size="35"></td>							
								<td><input type="Text" name="position<?php echo $c;?>" size="25"></td>								
								<td><input type="Text" name="fromDateemp<?php echo $c;?>" id="fromDateemp<?php echo $c;?>" size="6">
									 to 
									 <input type="Text" name="endDateemp<?php echo $c;?>" id="endDateemp<?php echo $c;?>" size="6"></td>
							</tr>
							<tr>
								<td>Company Address:</td>
								<td>Supervisor:</td>
								<td>Position:</td>
							</tr>
							<tr>
								<td><input type="Text" name="companyAddressemp<?php echo $c;?>" size="35"></td>
								<td><input type="Text" name="supervisor<?php echo $c;?>" size="25"></td>
								<td><input type="Text" name="supervisorPosition<?php echo $c;?>" size="25"></td>
							</tr>
							<tr>
								<td valign="top">Main Duties and Responsibilities:</td>								
							</tr>
							<tr>								
								<td><textarea name="dutiesResponsibilities<?php echo $c;?>" cols="30" rows="2"></textarea></td>								
							</tr>
							<tr>								
								<td>Monthly Salary:</td>
							</tr>
							<tr>								
								<td><input type="Text" name="monthlySalary<?php echo $c;?>" size="15"></td>							
							</tr>
							<tr>								
								<td>Reason/s for Leaving:</td>
							</tr>
							<tr>								
								<td colspan="3">
									<textarea name="reasonForLeaving<?php echo $c;?>" cols="30" rows="2"></textarea>	
								</td>								
							</tr>
							<tr><td colspan="5"><hr></td></tr>
							<tr><td><div id="divemploymentbgadd<?php echo $c;?>"><a onclick="employmentbgaddrow('<?php echo ($c+1)."','".$c;?>');" style='color:blue;'>ADD MORE</a></div>	</td></tr>		
						</table>
						</div>
						</td>
					</tr>					
					<?php 
						}
					?>
				</table>				
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><strong style="font-size:12px;color:maroon;"><u>V. MERITORIOUS/HONORS BACKGROUND </u></strong></td></tr>
		<tr><td>&nbsp;</td></tr>	
		<tr>
			<td>
				<table>
					<tr><td>Past Five Years :&nbsp;&nbsp;</td><td><input type="Text" name="honorPastFiveYears" size="45" value="<?php echo $em->honorPastFiveYears;?>"></td></tr>
					<tr><td>During University Years:&nbsp;&nbsp;</td><td><input type="Text" name="honorUniversityYears" size="45" value="<?php echo $em->honorUniversityYears;?>"></td></tr>
					<tr><td>Others:&nbsp;&nbsp;</td><td><input type="Text" name="honorOthers" size="45" value="<?php echo $em->honorOthers;?>"></td></tr>
				</table>				
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><strong style="font-size:12px;color:maroon;"><u>Vl. CONTINUOUS PROFESSIONAL DEVELOPMENT</u></strong></td></tr>
		<tr><td>&nbsp;<input type="Hidden" name="trainingcounter" id="trainingcounter" value="0"></td></tr>	
		<tr>
			<td>
				<table>
					<?php 
						echo $trainings;
						for($d=1;$d<=50;$d++){
							$display =  ($d == 0 ? 'block' : 'none');
					?>
					<tr>
						<td colspan="5">
						<div style="display:<?php echo $display;?>;" id="divprof<?php echo $d;?>">
						<table>
							<tr>
								<td>Description :&nbsp;&nbsp;</td><td><input type="Text" name="nametraining<?php echo $d;?>" size="45"></td>
								<td><td>Date/s :&nbsp;&nbsp;<input type="Text" name="fromDateprof<?php echo $d;?>" id="fromDateprof<?php echo $d;?>" size="6">
								 to 
								 <input type="Text" name="endDateprof<?php echo $d;?>" id="endDateprof<?php echo $d;?>" size="6">
								
								 </td></td>
							</tr>
							<tr><td><div id="divprofadd<?php echo $d;?>"></div>	</td></tr>		
						</table>
						</div>
						</td>
					</tr>					
					<?php 
						}
					?>
				</table>				
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><strong style="font-size:12px;color:maroon;"><u>Vll. TECHNICAL/TECHNOLOGICAL PROFICIENCY </u></strong></td></tr>
		<tr><td>&nbsp;</td></tr>	
		<tr>
			<td>
				<table>
					<tr><td><textarea name="technicalSkills" rows="5" cols="45" style="color:gray;" onfocus="if(this.value=='Software/Programs'){this.value='';}this.style.color='black';"><?php if($em->technicalSkills){ echo $em->technicalSkills;}else{ echo "Software/Programs";}?></textarea></td></tr>					
				</table>				
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><strong style="font-size:12px;color:maroon;"><u>Vlll. PROFESSIONAL AND PERSONAL INTERESTS INFORMATION</u></strong></td></tr>
		<tr><td>&nbsp;<input type="Hidden" name="interestcounter" id="interestcounter" value="0"></td></tr>	
		<tr>
			<td>
				<table>
					<?php 
						echo $interest;
						for($e=1;$e<=50;$e++){
							$display =  ($e == 0 ? 'block' : 'none');
					?>
					<tr>
						<td colspan="5">
						<div style="display:<?php echo $display;?>;" id="divinterest<?php echo $e;?>">
						<table>					
							<tr>
								<td><input type="Text" size="25" name="companyName<?php echo $e;?>" value="Company Name" style="color:gray;" onfocus="if(this.value=='Company Name'){this.value='';}this.style.color='black';">
									<div id="divinterestadd<?php echo $e;?>">
										<a onclick="interestaddrow('<?php echo ($e+1)."','".$e;?>');" style='color:blue;'>ADD MORE</a>
									</div>									
								</td>
								<td><input type="Text" size="15" name="role<?php echo $e;?>" value="Role" style="color:gray;" onfocus="if(this.value=='Role'){this.value='';}this.style.color='black';"></td>
								<td><input type="Text" size="35" name="companyAddress<?php echo $e;?>" value="Address" style="color:gray;" onfocus="if(this.value=='Address'){this.value='';}this.style.color='black';"></td>								
							</tr>
						</table>
						</div>
						</td>
					</tr>					
					<?php 
						}
					?>
				</table>				
			</td>
		</tr>
		<tr>
			<td>
				<table>
					<tr><td><textarea name="hobbiesInterest" rows="5" cols="45" style="color:gray;" onfocus="if(this.value=='Hobbies/Interests'){this.value='';}this.style.color='black';"><?php if($em->hobbiesInterest){ echo $em->hobbiesInterest;}else{ echo "Hobbies/Interests";}?></textarea></td></tr>					
				</table>	
			</td>
		</tr>
		<tr>
			<td>
				<table>
					<tr><td><textarea name="specialSkills" rows="5" cols="45" style="color:gray;" onfocus="if(this.value=='Special Skills/Talents'){this.value='';}this.style.color='black';"><?php if($em->specialSkills){ echo $em->specialSkills;}else{ echo "Special Skills/Talents";}?></textarea></td></tr>					
				</table>	
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		
	 </table> 
	 </form> 
	<h2>&nbsp;</h2>
	<?php include "../../footer.php";?>
  </div>





