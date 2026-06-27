<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include("employeefunctions.php");
if($_GET['act']=='save'){
	$nottoinclude=array('First Name','Middle Name','Last Name','Software/Programs','Hobbies/Interests','Special Skills/Talents'); // Not to include this default values on fields.
	
// start insert employee data

	if($_POST['employmentStatus']=='Regular' || $_POST['employmentStatus']=='Probationary'){ // getting the last employee number based on their status...
		$statuscondition="('Regular','Probationary')";
	}
	else{
		$statuscondition="('".$_POST['employmentStatus']."')";
	}
	$lastempno=mysql_fetch_object(mysql_query("SELECT employeeNo FROM employee WHERE employmentStatus in ".$statuscondition." ORDER BY employeeNo DESC"));
	$t=mysql_query("SHOW COLUMNS FROM employee");
	while($q=mysql_fetch_object($t)){
		$_POST['employeeNo']=$lastempno->employeeNo + 1;
		$_POST['picture']="";
		if($_POST[$q->Field]){
			if(!in_array($_POST[$q->Field],$nottoinclude)){
				$dbfield.=$q->Field.",";
				$values.="'".$_POST[$q->Field]."',";
			}
		}
	}
	$dbfield=rtrim($dbfield,",");
	$values=rtrim($values,",");
	if($_POST['firstName'] && $_POST['firstName']!='First Name'){
		$insqry="INSERT INTO employee (".$dbfield.",isActive) VALUES (".$values.",1)";
	}
	
	$insertnewemployee=mysql_query($insqry);
	$empndex=mysql_insert_id(); // last inserted employee ndex
	// picture
	move_uploaded_file($_FILES["picture"]["tmp_name"],"picture/" . $_FILES["picture"]["name"]);
	$newfilename=$empndex.".".findexts($_FILES["picture"]["name"]);
	rename("picture/".$_FILES["picture"]["name"]."", "picture/".$newfilename."");
	$updatepicture=mysql_query("update employee set picture='".$newfilename."',password='".getID($_POST['employmentStatus'],$_POST['employeeNo'])."' where ndex=".$empndex."");
	//start dependents 
	for($aa=1;$aa<=$_POST['personalcounter'];$aa++){
		if($_POST['nameper'.$aa]!='Name' && $_POST['nameper'.$aa]){
			$dependent =  ($_POST['isDependent'.$aa] == 'on' ? '1' : '0');
			$medicalDependent =  ($_POST['isMedicalDependent'.$aa] == 'on' ? '1' : '0');
			$insertdependents=mysql_query("
				INSERT INTO empdependents (`employeeId`,`name`, `birthDate`, `relationship`, `status`, `occupation`, `company`, `isDependent`,`isMedicalDependent`)
				VALUES ('".$empndex."','".$_POST['nameper'.$aa]."','".$_POST['birthDate'.$aa]."','".$_POST['relationship'.$aa]."','1','".$_POST['occupation'.$aa]."','".$_POST['company'.$aa]."','".$dependent."','".$medicalDependent."')
			");
		}
	}
	//start education
	for($bb=1;$bb<=$_POST['educationalcounter'];$bb++){
		if($_POST['school'.$bb]!='School' && $_POST['school'.$bb]){
		$inserteducation=mysql_query("
			INSERT INTO empeducationalbg (`employeeId`, `level`, `school`, `degree`, `fromDate`, `toDate`)
			VALUES ('".$empndex."','".$_POST['level'.$bb]."','".$_POST['school'.$bb]."','".$_POST['degree'.$bb]."','".$_POST['fromDateeduc'.$bb]."','".$_POST['toDateeduc'.$bb]."')
		");
		}
	}
	//start employment
	for($cc=1;$cc<=$_POST['employmentcounter'];$cc++){
		if($_POST['companyNameemp'.$cc]){
		$insertemployment=mysql_query("
			INSERT INTO empemploymentbg (`employeeId`,`companyName`, `companyAddress`, `position`, `fromDate`, `endDate`, `supervisor`, `supervisorPosition`, `dutiesResponsibilities`, `monthlySalary`, `reasonForLeaving`)
			VALUES ('".$empndex."','".$_POST['companyNameemp'.$cc]."','".$_POST['companyAddressemp'.$cc]."','".$_POST['position'.$cc]."','".$_POST['fromDateemp'.$cc]."','".$_POST['endDateemp'.$cc]."','".$_POST['supervisor'.$cc]."','".$_POST['supervisorPosition'.$cc]."','".$_POST['dutiesResponsibilities'.$cc]."','".$_POST['monthlySalary'.$cc]."','".$_POST['reasonForLeaving'.$cc]."')
		");
		}
	}
	//start training
	for($dd=1;$dd<=$_POST['trainingcounter'];$dd++){
		if($_POST['nametraining'.$dd]){
		$inserttraining=mysql_query("
			INSERT INTO emptrainings (`employeeId`,`name`, `fromDate`, `endDate`, `venue`)
			VALUES ('".$empndex."','".$_POST['nametraining'.$dd]."','".$_POST['fromDateprof'.$dd]."','".$_POST['endDateprof'.$dd]."','".$_POST['venue'.$dd]."')
		");
		}
	}
	//start interest
	for($ee=1;$ee<=$_POST['interestcounter'];$ee++){
		if($_POST['companyName'.$ee]!='Company Name' && $_POST['companyName'.$ee]){
		$inserttraining=mysql_query("
			INSERT INTO empprofessionalinterest (`employeeId`,`companyName`, `role`, `companyAddress`)
			VALUES ('".$empndex."','".$_POST['companyName'.$ee]."','".$_POST['role'.$ee]."','".$_POST['companyAddress'.$ee]."')
		");
		}
	}
	header('location:employee_add.php');
}
$prestat=mysql_query("SELECT distinct employmentStatus FROM employee WHERE isActive=1 ORDER BY employmentStatus");
while($pr=mysql_fetch_object($prestat)){
	if($pr->employmentStatus=='Regular' || $pr->employmentStatus=='Probationary'){
		$statuscon="('Regular','Probationary','Senior Manager')";
		$statremark='REGULAR and PROBATIONARY:';
	}
	else{
		$statuscon="('".$pr->employmentStatus."')";
		$statremark=strtoupper($pr->employmentStatus);
	}
	$laststatno=mysql_fetch_object(mysql_query("SELECT employeeNo FROM employee WHERE employmentStatus in ".$statuscon." ORDER BY employeeNo DESC"));
	if($pr->employmentStatus!='Probationary'){
		$datastat.="<tr>
					<td>".$statremark."</td>
					<td>&nbsp;&nbsp;".$laststatno->employeeNo."</td>
		</tr>";
	}
}

$catqry=mysql_query("SELECT * FROM category WHERE status<>1 order by name");

while($catrs=mysql_fetch_object($catqry)){
	$copts.="<option value='".$catrs->ndex."'>".$catrs->name."";
}

$scatqry=mysql_query("SELECT * FROM category_sub WHERE status<>1 order by name");
	
while($scatrs=mysql_fetch_object($scatqry)){
	$scopts.="<option value='".$scatrs->ndex."|".$scatrs->categoryId."'>".$scatrs->name."";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
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
		</script>
	<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
</head>
<body>
	<script>
		function subcat(){
			v = document.getElementById("subcategoryIdopts").value;
			m = v.split("|");
			document.getElementById("subcategoryId").value = m[0];
			document.getElementById("categoryId").value = m[1];

		}
	</script>
<?php include "header.php";?>
<div style="position:absolute;top:400px;right:230px;"><table style="font-size:11px;"><tr><td><strong style="font-size:12px;">ID counter:</strong></td></tr><tr><td colspan="2"><hr></td></tr><?php echo $datastat;?></table></div>
<div id="main_content_wrap" class="container_12">
     <h2>New Employee</h2>
<form method="post" name="employeefrm" action="<?php echo $_SERVER['PHP_SELF']; ?>?act=save" enctype="multipart/form-data" onsubmit="return checks();">
	 <table style="font-size:11px;">
	 	<tr>
			<td>
				<table>
				 	<tr>
						<td>Full Name:&nbsp;&nbsp;</td>
						<td><input type="Text" name="firstName" size="15" value="First Name" style="color:gray;" onfocus="if(this.value=='First Name'){this.value='';}this.style.color='black';">
						<input type="Text" name="middleName" size="15" value="Middle Name" style="color:gray;" onfocus="if(this.value=='Middle Name'){this.value='';}this.style.color='black';">
						<input type="Text" name="lastName" size="15" value="Last Name" style="color:gray;" onfocus="if(this.value=='Last Name'){this.value='';}this.style.color='black';">
						<input type="hidden" id="DPC_MONTH_NAMES" value="['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']"></td>
					</tr>
					<tr><td>Position:&nbsp;&nbsp;</td><td><select name="position"><?php echo $optionposition;?></select></td></tr>
					<tr><td>Division:&nbsp;&nbsp;</td><td><select name="divisionId" onchange="dbcon('afterdivisionselectfordept','deptdiv',employeefrm);"><?php echo $optiondivision;?></select></td></tr>
					<tr><td>Department:&nbsp;&nbsp;</td><td><div id="deptdiv"><select name="deptId"><option value="">- Select Division -</select></div></td></tr>
					<tr><td>Unit:&nbsp;&nbsp;</td><td><div id="unitdiv"><select name="unitId"><option value="">- Select Department -</select></div></td></tr>
					<tr><td>Sub Category:&nbsp;&nbsp;</td><td><div id="subcategorydiv"><select name="subcategoryIdopts" id="subcategoryIdopts" onchange="subcat();"><option value="">- Select Sub Category - <?php echo $scopts;?> </select></div><input type="hidden" name="subcategoryId" id="subcategoryId"></td></tr>
					<tr><td>Category:&nbsp;&nbsp;</td><td><div id="categorydiv"><select name="categoryId" id="categoryId"><option value="">- Select Category - <?php echo $copts;?> </select></div></td></tr>
					<tr><td>Employee No:&nbsp;&nbsp;</td><td><input type="Text" name="employeeNo" size="15" readonly="readonly"></td></tr>
					<tr><td>Date Hired:&nbsp;&nbsp;</td><td><input type="Text" name="dateHired" id="dateHired" size="15"><a href="javascript:show_calendar('employeefrm.dateHired');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
					<tr><td>Bank Account No:&nbsp;&nbsp;</td><td><input type="Text" name="bankAccountNo" size="15"></td></tr>
					<tr><td>Locker No:&nbsp;&nbsp;</td><td><input type="Text" name="lockerNo" size="15"></td></tr>
					<tr><td>Employment Status:&nbsp;&nbsp;</td><td><select required="required" name="employmentStatus" id="employmentStatus"><?php echo $optionemploymentstatus;?></select></td></tr>
					<tr><td>Residency Training Program:&nbsp;&nbsp;</td><td><select name="residencyTrainingProgram"><?php echo $optionresidencytrainingprogram;?></select></td></tr>
					<tr><td>Pay Type:&nbsp;&nbsp;</td><td><select name="payType"><?php echo $optionpaytype;?></select></td></tr>
					<tr><td>Level:&nbsp;&nbsp;</td><td><select name="level"><option value='0'> - Select Level -<?php echo $optionrank;?></select></td></tr>
					<tr><td>Taxable:&nbsp;&nbsp;</td><td><input type="Radio" name="isTaxable" value="1" checked="checked">Yes<input type="Radio" name="isTaxable" value="0">No</td></tr>
					<tr><td>Union Member:&nbsp;&nbsp;</td><td><input type="Radio" name="isUnionMember" value="1">Yes<input type="Radio" name="isUnionMember" value="0" checked="checked">No</td></tr>
					<tr><td>Coop Member:&nbsp;&nbsp;</td><td><input type="Radio" name="isCoopMember" value="1">Yes<input type="Radio" name="isCoopMember" value="0" checked="checked">No</td></tr>																				
				 	<tr><td>Biometric No:&nbsp;&nbsp;</td><td><input type="Text" name="biometricNo" size="15"></td></tr>
				 </table>   
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
	 	<tr><td><strong style="font-size:12px;color:maroon;"><u>l. PERSONAL INFORMATION</u></strong></td></tr>
		<tr><td>&nbsp;</td></tr>
	 	<tr>
			<td>
				<table>
					<tr><td>Picture:&nbsp;&nbsp;</td><td><input type="file" name="picture" id="picture" /></td></tr>
					<tr><td>Nick Name:&nbsp;&nbsp;</td><td><input type="Text" name="nickName" size="15"></td></tr>
					<tr><td>Sex:&nbsp;&nbsp;</td><td><input type="Radio" name="sex" value="MALE" checked="checked">MALE<input type="Radio" name="sex" value="FEMALE">FEMALE</td></tr>
					<tr><td>Blood Type:&nbsp;&nbsp;</td><td><select name="bloodType"><?php echo $optionbloodtype; ?></select></td></tr>
					<tr><td>Civil Status:&nbsp;&nbsp;</td><td><select name="civilStatus"><?php echo $optioncivilstatus;?></select></td></tr>
					<tr><td>Present Address:&nbsp;&nbsp;</td><td><input type="Text" name="presentAddress" size="55"></td></tr>
					<tr><td>Permanent Address:&nbsp;&nbsp;</td><td><input type="Text" name="permanentAddress" size="55"></td></tr>
					<tr><td>Email Address:&nbsp;&nbsp;</td><td><input type="Text" name="emailAddress" size="35"></td></tr>
					<tr><td>Home Phone No:&nbsp;&nbsp;</td><td><input type="Text" name="homePhoneNumber" size="15"></td></tr>
					<tr><td>Mobile No:&nbsp;&nbsp;</td><td><input type="Text" name="mobilePhoneNumber" size="15"></td></tr>
					<tr><td>Birth Date:&nbsp;&nbsp;</td><td><input type="Text" name="birthDate" id="birthDate" size="15"><a href="javascript:show_calendar('employeefrm.birthDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
					<tr><td>Birth Place:&nbsp;&nbsp;</td><td><input type="Text" name="birthPlace" size="35"></td></tr>
					<tr><td>Nationality:&nbsp;&nbsp;</td><td><input type="Text" name="nationality" size="15"></td></tr>
					<tr><td>Religion:&nbsp;&nbsp;</td><td><input type="Text" name="religion" size="15"></td></tr>
					<tr><td>SSS No:&nbsp;&nbsp;</td><td><input type="Text" name="sssNumber" size="15"></td></tr>
					<tr><td>PHIC No:&nbsp;&nbsp;</td><td><input type="Text" name="phicNumber" size="15"></td></tr>
					<tr><td>Pag-ibig No:&nbsp;&nbsp;</td><td><input type="Text" name="pagibigNumber" size="15"></td></tr>
					<tr><td>TIN:&nbsp;&nbsp;</td><td><input type="Text" name="tin" size="15"></td></tr>
					<tr><td>Occupational Permit #:&nbsp;&nbsp;</td><td><input type="Text" name="occupationalPermitNo" size="15"> Date Issued:<input type="Text" name="occupationalPermitDate" id="occupationalPermitDate" size="15"><a href="javascript:show_calendar('employeefrm.occupationalPermitDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
					<tr><td>PTR #:&nbsp;&nbsp;</td><td><input type="Text" name="ptrNo" size="15"> Date Issued:<input type="Text" name="ptrNoDate" id="ptrNoDate" size="15"><a href="javascript:show_calendar('employeefrm.ptrNoDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
					<tr><td>Cedula #:&nbsp;&nbsp;</td><td><input type="Text" name="cedulaNo" size="15"> Date Issued:<input type="Text" name="cedulaDate" id="cedulaDate" size="15"><a href="javascript:show_calendar('employeefrm.cedulaDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
					<tr><td>City Health #:&nbsp;&nbsp;</td><td><input type="Text" name="cityHealth" size="15"> Date Issued:<input type="Text" name="cityHealthDate" id="cityHealthDate" size="15"><a href="javascript:show_calendar('employeefrm.cityHealthDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
					<tr><td>PRC #:&nbsp;&nbsp;</td><td><input type="Text" name="prcNo" size="15"> Date Issued:<input type="Text" name="prcDate" id="prcDate" size="15"><a href="javascript:show_calendar('employeefrm.prcDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
				 </table>   
			</td>
		</tr>
<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><strong style="font-size:12px;color:maroon;"><u>MEALS</u></strong></td></tr>
		<tr><td><input type="checkbox" name="nonPorkEater">Non Pork Eater</td></tr>
		<tr><td><input type="checkbox" name="nonBeefEater">Non Beef Eater</td></tr>
		<tr><td><input type="checkbox" name="chickenAllergic">Allergic to Chicken</td></tr>
		<tr><td><input type="checkbox" name="seafoodsAllergic">Allergic to Seafoods</td></tr>


		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><strong style="font-size:12px;color:maroon;"><u>ll. PERSONAL BACKGROUND</u></strong></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td colspan="2">Dependent|Medical <input type="Hidden" name="personalcounter" id="personalcounter" value="1"></td></tr>
		<tr>
			<td>
				<table>
					<?php 
						for($a=1;$a<=50;$a++){
							$display =  ($a <= 5 ? 'block' : 'none');
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
								<td><input type="Text" name="birthDate<?php echo $a;?>" id="birthDate<?php echo $a;?>" size="15" value="Birth Date" style="color:gray;" onfocus="if(this.value=='Birth Date'){this.value='';}this.style.color='black';"><a href="javascript:show_calendar('employeefrm.birthDate<?php echo $a;?>');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
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
					<tr><td>In case of Emergency, notify:<input type="Text" name="emergencyContactPerson" size="25">&nbsp;&nbsp;Relationship: <select name="emergencyRelationship"><?php echo $optionrelationship;?></select>	&nbsp;&nbsp;Contact#: <input type="Text" name="emergencyContactNumber" size="15"></td></tr>
				</table>				
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><strong style="font-size:12px;color:maroon;"><u>lll. EDUCATIONAL BACKGROUND</u></strong></td></tr>
		<tr><td>&nbsp;<input type="Hidden" name="educationalcounter" id="educationalcounter" value="1"></td></tr>	
		<tr>
			<td>
				<table>
					<?php 
						for($b=1;$b<=50;$b++){
							$display =  ($b <= 5 ? 'block' : 'none');
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
								<td><input type="Text" name="fromDateeduc<?php echo $b;?>" id="fromDateeduc<?php echo $b;?>" size="15" value="From" style="color:gray;" onfocus="if(this.value=='From'){this.value='';}this.style.color='black';"><a href="javascript:show_calendar('employeefrm.fromDateeduc<?php echo $b;?>');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
								<td><input type="Text" name="toDateeduc<?php echo $b;?>" id="toDateeduc<?php echo $b;?>" size="15" value="To" style="color:gray;" onfocus="if(this.value=='To'){this.value='';}this.style.color='black';"><a href="javascript:show_calendar('employeefrm.toDateeduc<?php echo $b;?>');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>								
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
					<tr><td>Professional Licensure Taken :&nbsp;&nbsp;</td><td><input type="Text" name="educProfLicensureTaken" size="15"></td></tr>
					<tr><td>Board Rating:&nbsp;&nbsp;</td><td><input type="Text" name="educBoardRating" size="15"></td></tr>
					<tr><td>Year Passed:&nbsp;&nbsp;</td><td><input type="Text" name="educYearPassed" size="15"></td></tr>
				</table>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><strong style="font-size:12px;color:maroon;"><u>lV. EMPLOYMENT BACKGROUND</u></strong></td></tr>
		<tr><td>&nbsp;<input type="Hidden" name="employmentcounter" id="employmentcounter" value="1"></td></tr>	
		<tr>
			<td>
				<table>
					<?php 
						for($c=1;$c<=50;$c++){
							$display =  ($c <= 5 ? 'block' : 'none');
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
								<td><input type="Text" name="fromDateemp<?php echo $c;?>" id="fromDateemp<?php echo $c;?>" size="6"><a href="javascript:show_calendar('employeefrm.fromDateemp<?php echo $c;?>');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a>
									 to 
									 <input type="Text" name="endDateemp<?php echo $c;?>" id="endDateemp<?php echo $c;?>" size="6"><a href="javascript:show_calendar('employeefrm.endDateemp<?php echo $c;?>');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
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
					<tr><td>Past Five Years :&nbsp;&nbsp;</td><td><input type="Text" name="honorPastFiveYears" size="45"></td></tr>
					<tr><td>During University Years:&nbsp;&nbsp;</td><td><input type="Text" name="honorUniversityYears" size="45"></td></tr>
					<tr><td>Others:&nbsp;&nbsp;</td><td><input type="Text" name="honorOthers" size="45"></td></tr>
				</table>				
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><strong style="font-size:12px;color:maroon;"><u>Vl. CONTINUOUS PROFESSIONAL DEVELOPMENT</u></strong></td></tr>
		<tr><td>&nbsp;<input type="Hidden" name="trainingcounter" id="trainingcounter" value="1"></td></tr>	
		<tr>
			<td>
				<table>
					<?php 
						for($d=1;$d<=50;$d++){
							$display =  ($d <= 5 ? 'block' : 'none');
					?>
					<tr>
						<td colspan="5">
						<div style="display:<?php echo $display;?>;" id="divprof<?php echo $d;?>">
						<table>
							<tr>
								<td>Type :&nbsp;&nbsp;</td><td><select style="font-size:11px;" name="venue<?php echo $d;?>"><option value="External">External<option value="Internal">Internal</select></td>
								<td>Description :&nbsp;&nbsp;</td><td><input type="Text" name="nametraining<?php echo $d;?>" size="45"></td>
								<td><td>Date/s :&nbsp;&nbsp;<input type="Text" name="fromDateprof<?php echo $d;?>" id="fromDateprof<?php echo $d;?>" size="6"><a href="javascript:show_calendar('employeefrm.fromDateprof<?php echo $d;?>');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a>
								 to 
								 <input type="Text" name="endDateprof<?php echo $d;?>" id="endDateprof<?php echo $d;?>" size="6">
								 <a href="javascript:show_calendar('employeefrm.endDateprof<?php echo $d;?>');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a>
								 </td></td>
							</tr>
							<tr><td><div id="divprofadd<?php echo $d;?>"><a onclick="profaddrow('<?php echo ($d+1)."','".$d;?>');" style='color:blue;'>ADD MORE</a></div>	</td></tr>		
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
					<tr><td><textarea name="technicalSkills" rows="5" cols="45" style="color:gray;" onfocus="if(this.value=='Software/Programs'){this.value='';}this.style.color='black';">Software/Programs</textarea></td></tr>					
				</table>				
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><strong style="font-size:12px;color:maroon;"><u>Vlll. PROFESSIONAL AND PERSONAL INTERESTS INFORMATION</u></strong></td></tr>
		<tr><td>&nbsp;<input type="Hidden" name="interestcounter" id="interestcounter" value="1"></td></tr>	
		<tr>
			<td>
				<table>
					<?php 
						for($e=1;$e<=10;$e++){
							$display =  ($e <= 5 ? 'block' : 'none');
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
					<tr><td><textarea name="hobbiesInterest" rows="5" cols="45" style="color:gray;" onfocus="if(this.value=='Hobbies/Interests'){this.value='';}this.style.color='black';">Hobbies/Interests</textarea></td></tr>					
				</table>	
			</td>
		</tr>
		<tr>
			<td>
				<table>
					<tr><td><textarea name="specialSkills" rows="5" cols="45" style="color:gray;" onfocus="if(this.value=='Special Skills/Talents'){this.value='';}this.style.color='black';">Special Skills/Talents</textarea></td></tr>					
				</table>	
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td colspan="6"><input type="Submit" value="SUBMIT"></td></tr>
	 </table> 
	 </form> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>


