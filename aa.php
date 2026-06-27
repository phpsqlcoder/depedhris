<?php
include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
    include ("myfunctions.php");
	
$data='';
	$a=mysql_query("SELECT * from kiosk_request where approve2=1 and tayp='Overtime'");
	while($r=mysql_fetch_array($a)){
		$i=mysql_fetch_array(mysql_query("select * from dailytimesummary where date='".$r['date']."' and approvedOvertime='0.00' and employeeId='".$r['empid']."'"));
		//echo "select * from dailytimesummary where date='".$r['date']."' and approvedOvertime='0.00' and employeeId='".$r['empid']."'<br>";
		if($i['ndex']>0){
			$e = mysql_fetch_array(mysql_query("select * from employee where ndex='".$r['empid']."'"));
			$data.='<tr>
						<td>'.getID($e['employmentStatus'],$e['employeeNo']).'</td>
						<td>'.$e['lastName'].', '.$e['firstName'].' '.$e['middleName'].'</td>
						<td>'.$r['date'].'</td>
						<td>'.$r['request'].'</td>
						<td>'.$r['remarks'].'</td>
						<td>'.$i['approvedOvertime'].'</td>
						<td>'.$i['approvedOvertimeNightPremium'].'</td>
			</tr>';
			
		}
	//echo $i['employeeId']." aa ".$i['startDate']." aa ".$i['ndex']."<br>";
	}
	
?>
<table width="80%" style="font-family:Arial;font-size:12px;">
	<tr>
		<td>ID</td>
		<td>Employee</td>
		<td>Date</td>
		<td>Request</td>
		<td>OT</td>
		<td>NP</td>
	</tr>
	<?php echo $data; ?>
</table>