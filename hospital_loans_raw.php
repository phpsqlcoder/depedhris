<?php

$conn=mysql_connect("localhost","root","pangitka");
mysql_select_db("hris", $conn);
$data='';
$d = mysql_query("select h.*,e.firstName,e.middleName,e.lastName from ar_hospital_ee_trx h left join employee e on e.ndex=h.employeeId");
while($r = mysql_fetch_array($d)){
	$data.='<tr>
			<td>'.$r['lastName'].', '.$r['firstName'].' '.$r['middleName'].'</td>
			<td>'.$r['Pat_No'].'</td>
			<td>'.$r['Batch_No'].'</td>
			<td>'.$r['AR_No'].'</td>
			<td>'.$r['Status'].'</td>
			<td>'.$r['Trx_type'].'</td>
			<td>'.$r['Amount'].'</td>
			<td>'.$r['TrxDate'].'</td>
			<td>'.$r['doctorId'].'</td>
			<td>'.$r['priorityNo'].'</td>
	</tr>';
}
?>

<table width="100%">
	<tr>
		<td>Name</td>
		<td>Patient no</td>
		<td>Batch no</td>
		<td>AR no</td>
		<td>Status</td>
		<td>Trx Type</td>
		<td>Amount</td>
		<td>Trx Date</td>
		<td>Doctor ID</td>
		<td>Priority no</td>
	</tr>
	<tr>
		<td colspan="9"><hr></td>
	</tr>
	<?php echo $data;?>
</table>