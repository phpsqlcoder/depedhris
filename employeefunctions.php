<?php
ob_start();
include("dbcon.php");

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
	return $empID;
}
function findexts($filename) { 
	$filename = strtolower($filename) ; 
	$exts = split("[/\\.]", $filename) ; 
	$n = count($exts)-1; $exts = $exts[$n]; 
	return $exts; 
}
?>

