<?php
include("dbcon.php");
$data = '';
$qry = mysql_query("select distinct e.ndex as endex,e.firstName,e.lastName,e.middleName,d.name as deptname,count(h.ndex) as total_trx from ar_hospital_ee_trx h left join employee e on e.ndex=h.employeeId
	left join dept d on d.ndex=e.deptId 
	group by e.ndex,e.firstName,e.lastName,e.middleName,d.name
	order by e.lastName,e.firstName,e.middleName
	");
$ctr1s=0;
while($r = mysql_fetch_array($qry)){
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	$data.='<tr style="background-color:'.$bgclr1s.';font-size:12px;">
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