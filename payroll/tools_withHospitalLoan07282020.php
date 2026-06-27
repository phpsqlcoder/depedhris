<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");
include ("payrollfunctions.php");


$data = '';
$ctr1s=0;
$q = "";
if(isset($_GET['emp'])){
	if($_GET['emp'] == 'all')
		$q = "";
	else
		$q = " and e.ndex='".$_GET['emp']."'";
}

$opt_qry = mysql_query("select distinct e.ndex as endex,e.firstName,e.lastName,e.middleName,d.name as deptname,count(h.ndex) as total_trx from ar_hospital_ee_trx h left join employee e on e.ndex=h.employeeId
	left join dept d on d.ndex=e.deptId
	group by e.ndex,e.firstName,e.lastName,e.middleName,d.name
	order by e.lastName,e.firstName,e.middleName
	");
$opt = '';
while($optr = mysql_fetch_array($opt_qry)){
	$opt.='<option value="'.$optr['endex'].'">'.$optr['lastName'].', '.$optr['firstName'].' '.$optr['middleName'].'</option>';
}

$qry = mysql_query("select distinct e.ndex as endex,e.firstName,e.lastName,e.middleName,d.name as deptname,count(h.ndex) as total_trx from ar_hospital_ee_trx h left join employee e on e.ndex=h.employeeId
	left join dept d on d.ndex=e.deptId where e.ndex>0 ".$q."
	group by e.ndex,e.firstName,e.lastName,e.middleName,d.name
	order by e.lastName,e.firstName,e.middleName
	");

while($r = mysql_fetch_array($qry)){
	
	$total = mysql_fetch_array(mysql_query("select sum(Amount) as balance from ar_hospital_ee_trx where employeeId='".$r['endex']."'"));
	$paid = mysql_fetch_array(mysql_query("select sum(amountPaid) as paid from ar_hospital_ee_payment_ledger where employeeId='".$r['endex']."'"));
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	$data.='<tr style="background-color:'.$bgclr1s.'">
				<td>'.$r['lastName'].', '.$r['firstName'].' '.$r['middleName'].'</td>
				<td>'.$r['deptname'].'</td>
				<td align="right">'.number_format($total['balance'],2).'</td>
				<td align="right">'.number_format($paid['paid'],2).'</td>
				<td align="right">'.number_format(($total['balance'] - $paid['paid']),2).'</td>
				<td align="right">'.$r['total_trx'].'</td>
				<td align="center"><a href="tools_withHospitalLoan_individual.php?id='.$r['endex'].'">View</a></td>
				
	</tr>';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- <title>HRIS - Davao Doctors Hospital</title> -->
    <link href="../css/styles.css" rel="stylesheet" type="text/css" />
    <link href="../css/facebox.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
	<div id="row">
		<div class="col-md-6">
			<h2>Employee with Hospital Deductions</h2>
		</div>	
		<div class="col-md-6">
			<form action="tools_withHospitalLoan.php" method="get" id="search_form">
			    <table width="100%">
			    	<tr>
			    		<td>
			    			<select name="emp" id="emp" required="required" class="form-control" onchange="document.getElementById('search_form').submit()">
			    				<option value=""> - Select Employee- </option>
			    				<option value="all"> - All Employee - </option>
			    				<?php echo $opt;?>
			    			</select>
			    			<br><br>
			    		</td>    	
			    	</tr>
			    </table> 
			</form>
		</div>	
		
	</div>
	
    
    <table width="100%">
			<thead>
				<tr>
					<th align="left" style="width:30%">Name</th>
					<th style="width:30%">Dept</th>
					<th align="right" style="width:8%">Total Amt</th>
					<th align="right" style="width:8%">Payments</th>
					<th align="right" style="width:8%">Balance</th>
					<th align="right" style="width:8%">Total Txn</th>
					<th align="right" style="width:8%">View</th>
				</tr>
			</thead>
			<tbody>
				<tr><td colspan="7"><hr></td></tr>
				<?php echo $data;?>
			</tbody>
		</table>
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>