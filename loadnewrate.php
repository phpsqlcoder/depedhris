<?php
ob_start();
session_start();
//if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");
include ("employeefunctions.php");


 $h= mysql_query("select * from employee where isactive<>10000");
 $xx = 0;
 while($d = mysql_fetch_array($h)){
 	$empid = getID($d['employmentStatus'],$d['employeeNo']);
 	$r=mysql_fetch_array(mysql_query("select * from `03112020` where empno='".$empid."'"));
 	if(isset($r['rate'])){
 		$xx++;
 		//echo $empid." = ".$r['rate']."<br>";
$upd=mysql_query("update employee_compensation set basicPay='".$r['rate']."',honorarium='".$r['honorarium']."' where employeeId='".$d['ndex']."'");
 	}

 }
 echo $xx;
/*

$h= mysql_query("select * from `03112020`");
$xx = '';

while($d = mysql_fetch_array($h)){
	$u = 0;
	$hh = mysql_query("select * from employee where isactive=1");
	while($rr = mysql_fetch_array($hh)){
		$empid = getID($d['employmentStatus'],$d['employeeNo']);
		if($empid == $d['empno']){
			$u = 1;
		}
	}
	if($u == 0){
		$xx.=$d['empno']."<br>";
	}

}
echo $xx;
*/
$hh = mysql_query("select * from employee where lastName='agravante' and isactive=1");
	while($rr = mysql_fetch_array($hh)){
		$empid = getID($rr['employmentStatus'],$rr['employeeNo']);
echo $rr['lastName'].$empid."<br>";
		if(trim((string) $empid) == '201394'){
			echo $empid;
		}
	}

?>
