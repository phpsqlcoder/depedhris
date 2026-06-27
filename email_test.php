<?php 
	ini_set('SMTP','10.0.1.215');
	$msg = "This is a test message";
	$header = "From: mtmanligoy@ddh.com.ph";
	mail("jbpelicano@ddh.com.ph","HRIs",$msg,$header);
?>