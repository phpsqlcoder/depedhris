<?php
ob_start();

$conn=mysql_connect("localhost","root","pangitka");
mysql_select_db("hris",$conn);
include ("../options.php");



include("../scripts/scripts.php");
include ("../myfunctions.php");
function getID($empStatus,$empNo){
	if($empStatus=='Regular'){
		$tayp='';
	}
	elseif($empStatus=='Temporary'){
		$tayp='TMP';
	}
	elseif($empStatus=='Reliever'){
		$tayp='REL';
	}
	elseif($empStatus=='Senior Manager'){
		$tayp='SM';
	}
	elseif($empStatus=='Probationary'){
		$tayp='PRO';
	}
	else{$tayp='';}
	$len=strlen($empNo);
	$len=6-$len;
	for($i=1;$i<=$len;$i++){
		$num.="0";
	}
	$empID=$tayp.$num.$empNo;
	if(substr($empID,0,2) == '00'){
		$empID = substr($empID, 2);
	}
	return $empID;

}
$_POST['cutoffDate'] = "2019-07-15";
$sql = "SELECT p.*, e.lastName, e.firstName, e.employmentStatus as est, e.employeeNo as eno FROM payroll p 
										  LEFT JOIN employee e ON e.ndex=p.empid WHERE e.isActive='1' && p.pay_period='".$_POST['cutoffDate']."' ORDER BY e.lastName";
$rs = mysql_query($sql,$conn);


while ($dt = mysql_fetch_array($rs)){
	$id = getID($dt['est'],$dt['eno']);
	$dd = mysql_fetch_array(mysql_query("select * from aaa_1 where b='".$id."'",$conn));
	
	if((float)$dd['d'] > 0){
		echo $id." aa ".$dd['d']."<br>";
		$updateCoopPayroll = mysql_query("UPDATE payroll SET adj_other='".$dd['d']."'  WHERE ndex='".$dt['ndex']."'",$conn);
	}
	//$updateCoopPayroll = mysql_query("UPDATE payroll SET adj_other='".$dd['d']."'  WHERE ndex='".$dt['ndex']."'",$conn);

}
?>