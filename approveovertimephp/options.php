<?php
//family relationship
$optionrelationship="
	<option value=''> - Relationship -
	<option value='FATHER'>Father
	<option value='MOTHER'>Mother
	<option value='BROTHER'>Brother
	<option value='SISTER'>Sister
	<option value='SPOUSE'>Spouse
	<option value='CHILD'>Child
	<option value='COUSIN'>Cousin
	<option value='NEPHEW'>Nephew
	<option value='MOTHER IN LAW'>Mother in Law
	<option value='FATHER IN LAW'>Father in Law
	<option value='UNCLE'>Uncle
	<option value='AUNT'>Aunt
	<option value='OTHER'>Other
	
";
//education level
$optioneducationlevel="
	<option value=''> - Level -
	<option value='Post Graduate'>Post Graduate
	<option value='College'>College
	<option value='High School'>High School
	<option value='Elementary'>Elementary
";
//civil status
$optioncivilstatus="
	<option value=''> - Select Status -
	<option value='SINGLE'>SINGLE
	<option value='MARRIED'>MARRIED
	<option value='DIVORCED'>DIVORCED
	<option value='WIDOWED'>WIDOWED
	<option value='COHABITING'>COHABITING
	<option value='CIVIL UNION'>CIVIL UNION
	<option value='DOMESTIC PARTNERSHIP'>DOMESTIC PARTNERSHIP
	<option value='UNMARRIED PARTNERS'>UNMARRIED PARTNERS
";
//paytype
$optionpaytype="
	<option value=''> - Select Pay Type -
	<option value='Monthly'>Monthly
	<option value='Daily'>Daily
	<option value='Confidential'>Confidential
";
//employment status
$optionemploymentstatus="
	<option value=''> - Select Status -
	<option value='Regular'>Regular
	<option value='Contractual'>Contractual
	<option value='Reliever'>Reliever
	<option value='Probationary'>Probationary
	<option value='Project Based'>Project Based
";
//rtp
$optionresidencytrainingprogram="
	<option value=''> - Select RTP -
	<option value='PGI'>PGI
	<option value='Adjunct'>Adjunct
	<option value='ROD'>ROD
";
//sex
$optionsex="
	<option value=''> - Select SEX -
	<option value='MALE'>MALE
	<option value='FEMALE'>FEMALE
";
//taxable
$optiontaxable="
	<option value=''> - Select option-
	<option value='1'>YES
	<option value='0'>NO
";
//division
$divqry=mysql_query("SELECT * FROM division WHERE status<>1");
	$optiondivision="<option value=''> - Select Division -";
while($rsdiv=mysql_fetch_object($divqry)){
	$optiondivision.="<option value='".$rsdiv->ndex."'>".$rsdiv->name."";
}
//dept
$dept=mysql_query("SELECT * FROM dept WHERE status<>1 order by name");
	$optiondept="<option value=''> - Select Dept -";
while($rsdept=mysql_fetch_object($dept)){
	$optiondept.="<option value='".$rsdept->ndex."'>".$rsdept->name."";
}
//unit
$unit=mysql_query("SELECT * FROM unit WHERE status<>1");
	$optionunit="<option value=''> - Select Unit -";
while($rsunit=mysql_fetch_object($unit)){
	$optionunit.="<option value='".$rsunit->ndex."'>".$rsunit->name."";
}
//position
$position=mysql_query("SELECT * FROM position order by name");
	$optionposition="<option value=''> - Select Position -";
while($rsposition=mysql_fetch_object($position)){
	$optionposition.="<option value='".$rsposition->ndex."'>".$rsposition->name."";
}
//bloodtype
$bloodtype=mysql_query("SELECT distinct bloodType FROM employee order by bloodType");
	$optionbloodtype="<option value=''> - Select Blood type -";
while($rsbloodtype=mysql_fetch_object($bloodtype)){
	$optionbloodtype.="<option value='".$rsbloodtype->bloodType."'>".$rsbloodtype->bloodType."";
}
//nationality
$nationality=mysql_query("SELECT distinct nationality FROM employee order by nationality");
	$optionnationality="<option value=''> - Select Nationality -";
while($rsnationality=mysql_fetch_object($nationality)){
	$optionnationality.="<option value='".$rsnationality->nationality."'>".$rsnationality->nationality."";
}
//religion
$religion=mysql_query("SELECT distinct religion FROM employee order by religion");
	$optionreligion="<option value=''> - Select Religion -";
while($rsreligion=mysql_fetch_object($religion)){
	$optionreligion.="<option value='".$rsreligion->religion."'>".$rsreligion->religion."";
}
// Months
$optionmonths="
	<option value=''> - Select Month -
	<option value='January'>January
	<option value='February'>February
	<option value='March'>March
	<option value='April'>April
	<option value='May'>May
	<option value='June'>June
	<option value='July'>July
	<option value='August'>August
	<option value='September'>September
	<option value='October'>October
	<option value='November'>November
	<option value='December'>December
";
// Hours
for($a=0;$a<=11;$a++){
	if($a<10){$hour="0".$a;}else{$hour=$a;}
	$optionhour.="<option value='".$hour."'>".$hour."";
}
// minutes
for($a=0;$a<=59;$a++){
	if($a<10){$tym="0".$a;}else{$tym=$a;}
	$optionminute.="<option value='".$tym."'>".$tym."";
}
// am pm
$optionampm="
	<option value='AM'>AM
	<option value='PM'>PM
";
// Shifting
$shifting=mysql_query("SELECT 
		CASE timeIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeIn),'%H:%i %p') END AS tymIn, 
		CASE breakOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakOut),'%H:%i %p') END as brekOut,
		CASE breakIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakIn),'%H:%i %p') END as brekIn,
		CASE timeOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeOut),'%H:%i %p') END as tymOut,breakMinutes,ndex
		FROM shifting");
	$optionshifting="<option value=''> - Select Shift -";
while($rsshifting=mysql_fetch_object($shifting)){
	$optionshifting.="<option value='".$rsshifting->ndex."'>".$rsshifting->tymIn." - ".$rsshifting->brekOut." - ".$rsshifting->brekIn." - ".$rsshifting->tymOut."";
}
//leave
$leave=mysql_query("SELECT * FROM `leave` order by name");
	$optionleave="<option value=''> - Select Leave -";
while($rsleave=mysql_fetch_object($leave)){
	$optionleave.="<option value='".$rsleave->ndex."'>".$rsleave->name."";
}
//employee list
$employee=mysql_query("SELECT * FROM employee where isActive=1 order by lastName,firstName,middleName");
	$optionemployee="<option value=''> - Select Employee -";
while($rsemployee=mysql_fetch_object($employee)){
	$optionemployee.="<option value='".$rsemployee->ndex."'>".$rsemployee->lastName." ".$rsemployee->firstName." ".$rsemployee->middleName." ";
}
//Rank
	$optionrank="
	<option value='1'>Level 1 - Rank and File
	<option value='2'>Level 2 - Rank and File
	<option value='3'>Level 3 - Rank and File
	<option value='4'>Level 4 - Confidential/Dept. Heads
	<option value='5'>Level 5 - Confidential/Dept. Heads
	<option value='6'>Level 6 - Senior Manager
	<option value='7'>Level 7 - Senior Manager
";
?>



