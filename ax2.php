<?php
include("dbcon.php");
include ("employeefunctions.php");
include ("myfunctions.php");
date_default_timezone_set("Asia/Manila");
$in='2015-03-07 22:31:11';
$out='2015-03-08 07:46:16';
$shift='105';
$s=mysql_fetch_object(mysql_query("select * from shifting where ndex=".$shift.""));
############## Getting the Start Time #############
	$start=getStartTime($in,$out,$shift);
############# End Start Time #################
############# Getting the End Time #################
	$end=getEndTime($in,$out,$shift);
############# End of End Time #################
	echo $start." - ".$end."<br>";
	$total_hour_duty=timeDiffinSeconds($start,$end);
	$total_hour_duty=$total_hour_duty/3600;
	echo $total_hour_duty;
######### Subtract Break Time ######
if($s->breakMinutes!=0){
	$break=$s->breakMinutes/60;
	if($total_hour_duty>5){
		$total_hour_duty=$total_hour_duty-$break;
	}
}
else{
	if($s->breakOut!='00:00:00'){
		$total_hour_duty=$total_hour_duty-1;
	}
	else{
		$total_hour_duty=$total_hour_duty;
	}
	/*if($total_hour_duty>=9){
		$total_hour_duty=$total_hour_duty-1;
	}*/
}
	return $total_hour_duty;
?>