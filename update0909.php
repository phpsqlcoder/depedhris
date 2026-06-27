<?php
ob_start();
	include("dbcon.php");


	$rs=mysql_query("SELECT * FROM hrinterface090413 where dtrid='30076' && datelog >= '2013-08-21' and datelog <= '2013-09-07'",$conn);
	while ($dt = mysql_fetch_assoc($rs)){
		$insertData = mysql_query("UPDATE hrinterface set log='".$dt['log']."' WHERE hrint_id='".$dt['hrint_id']."'",$conn);
	}

echo 	"successfull!";

?>



