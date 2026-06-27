<?php
$conn=mysql_connect("localhost","root","pangitka");
//$conn=mysql_connect("localhost","it_user","it_pword987");
mysql_select_db("hris",$conn);

	$a=mysql_query("SELECT * from kiosk_request where date>='2022-10-05' and tayp='drd' and approve1=1 and approve2=1");
	while($b=mysql_fetch_array($a)){
	$dtr = mysql_fetch_array(mysql_query("select * from dailytimesummary where employeeId='".$b['empid']."' and date='".$b['date']."'"));
$upd = mysql_query("update dailytimesummary set duty_rd='".$x[0]."' where employeeId='".$b['empid']."' and date='".$b['date']."'");
		$x = explode("|",$b['request']);
		echo $b['empid']." xx ".$x[0]." xx ".$dtr['duty_rd']."<br>";
		
	}
?>
