<?php
include("dbcon.php");
$data = '';
$qry = mysqli_query($conn,"select e.ndex as endex,e.firstName,e.lastName,e.middleName,d.name as deptname, h.AR_No, h.Batch_No, sum(h.ndex) as total_trx from ar_hospital_ee_trx h left join employee e on e.ndex=h.employeeId
	left join dept d on d.ndex=e.deptId 
	group by e.ndex,e.firstName,e.lastName,e.middleName,d.name
	order by e.lastName,e.firstName,e.middleName
	");
while($r = mysqli_fetch_array($qry)){
	$data.='<tr>
				<td>'.$r['lastName'].', '.$r['firstName'].' '.$r['middleName'].'</td>
				<td>'.$r['deptname'].'</td>
				<td>'.$r['total_trx'].'</td>
				<td><a href="emp_whospital_individual.php?id='.$r['endex'].'">View</a></td>
	</tr>';
}
?>

<html>
	<head></head>
	<body>
		<h3><?php echo $r['lastName'].', '.$r['firstName'].' '.$r['middleName'];?></h3>
		<h4><?php echo $r['deptname'];?></h4>
		<table width="100%">
			<thead>
				<tr>
					<th>Name</th>
					<th>Dept</th>
					<th>Total</th>
					<th>View</th>
				</tr>
			</thead>
			<tbody>
				<?php echo $data;?>
			</tbody>
		</table>
	</body>
</html>