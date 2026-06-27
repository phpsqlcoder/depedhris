<?php
ob_start();
	include("../dbcon.php");
	include ("../employeefunctions.php");

//	$_GET['key'] = 'hr15k3y';
	$secret_key = "hr15k3y";	

	$key = $_GET['key'];

	if($key == $secret_key){		


		$qry="SELECT `ndex`, `firstName`, `lastName`, `middleName`, `nickName`, `sex`, `civilStatus`, `bloodType`, `religion`, `presentAddress`, `permanentAddress`, `emailAddress`, `homePhoneNumber`, `mobilePhoneNumber`, `birthDate`, `birthPlace`, `nationality`, `sssNumber`, `phicNumber`, `pagibigNumber`, `tin`, `class`, `endDate`, `isActive`, `technicalSkills`, `hobbiesInterest`, `specialSkills`, `employeeNo`, `dateHired`, `bankAccountNo`,  `lockerNo`, `isTaxable`, `payType`, `employmentStatus`, `residencyTrainingProgram`, `emergencyContactPerson`, `emergencyContactNumber`, `emergencyRelationship`, `educProfLicensureTaken`, `educBoardRating`, `educYearPassed`, `honorPastFiveYears`, `honorUniversityYears`, `honorOthers`, `picture`, `biometricNo`, `level`, `isUnionMember`, `occupationalPermitNo`, `occupationalPermitDate`, `occupationalPermitExpiration`, `ptrNo`, `ptrNoDate`, `cedulaNo`, `cedulaDate`, `prcNo`, `prcDate`, `cityHealth`, `cityHealthDate`, `isCoopMember`, `approvingOfficer`, `scheduler`, `isCleared`, `awol`, `daynotice`, `empfiles`, `cedulaDateExpiration`, `ptrNoDateExpiration`, `cityHealthDateExpiration`, `prcDateExpiration`, `nonPorkEater`, `nonBeefEater`, `chickenAllergic`, `seafoodsAllergic`, `allowedFood`, `allergicFood`, `authorizer` FROM `employee` WHERE isActive=1 ORDER BY lastName,firstName";
		
		
		$exec=mysql_query($qry);
		$var=0;
		while($r=mysql_fetch_object($exec)){
			$dept = mysql_fetch_object(mysql_query("select * from dept where ndex='".$r->deptId."'"));
			$division = mysql_fetch_object(mysql_query("select * from division where ndex='".$r->divisionId."'"));
			$position = mysql_fetch_object(mysql_query("select * from position where ndex='".$r->position."'"));
			$r->dept = $dept->name;
			$r->division = $division->name;
			$r->unit = $unit->name;
			$r->position = $position->name;
			$result[] = $r;				
			
		}

			
	}
	else{
		echo 'Invalid access';
	}
	print json_encode($result);


?>



