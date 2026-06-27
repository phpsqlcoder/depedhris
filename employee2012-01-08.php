<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
	include('scripts/my_pagina_class.php');
#############################
# This will change the employee status - reference found in employeechangestatus table.
#############################
$statusqry=mysql_query("select * from employeechangestatus where isServed=0 and effectivityDate<='".date('Y-m-d')."'");
while($st=mysql_fetch_object($statusqry)){
	if($st->changeType=='Lateral Transfer' || $st->changeType=='Promotion' || $st->changeType=='Demotion'){
		if($st->isDept==1){
			$upddept=mysql_query("update employee set deptId='".$st->newValue."' where ndex='".$st->employeeId."'");
		}
		else{
			$updstatus=mysql_query("update employee set position='".$st->newValue."' where ndex='".$st->employeeId."'");
		}
	}
	elseif($st->changeType=='Employment Status'){
		if($st->newValue=='Regular' || $st->newValue=='Probationary'){ // getting the last employee number based on their status...
			$statuscondition="('Regular','Probationary')";
		}
		else{
			$statuscondition="('".$st->newValue."')";
		}
		$lastempno=mysql_fetch_object(mysql_query("SELECT employeeNo FROM employee WHERE employmentStatus in ".$statuscondition." ORDER BY employeeNo DESC"));
		$newEmployeeNumber=$lastempno->employeeNo + 1;
		$emprec=mysql_fetch_object(mysql_query("SELECT * FROM employee WHERE ndex='".$st->employeeId."'"));
		if($emprec->employmentStatus=='Temporary' && $st->newValue=='Probationary'){			
			$updatedatestart=mysql_query("update employee set dateHired='".$st->effectivityDate."' where ndex='".$st->employeeId."'");
		}
		if($emprec->employmentStatus=='Probationary' && $st->newValue=='Regular'){
			$newEmployeeNumber=$emprec->employeeNo;
		}
		if($emprec->employmentStatus=='Regular' && $st->newValue=='Probationary'){
			$newEmployeeNumber=$emprec->employeeNo;
		}
		if($emprec->employmentStatus=='Regular' && $st->newValue=='Regular'){
			$newEmployeeNumber=$emprec->employeeNo;
		}
		if($emprec->employmentStatus=='Probationary' && $st->newValue=='Probationary'){
			$newEmployeeNumber=$emprec->employeeNo;
		}
		//echo "SELECT employeeNo FROM employee WHERE employmentStatus in ".$statuscondition." ORDER BY employeeNo DESC";
		$updstatus=mysql_query("update employee set employmentStatus='".$st->newValue."',employeeNo='".$newEmployeeNumber."' where ndex='".$st->employeeId."'");
	}
	elseif($st->changeType=='Resignation' || $st->changeType=='Retirement' || $st->changeType=='End of Contract' || $st->changeType=='Termination' || $st->changeType=='Separation'){
		$updstatus=mysql_query("update employee set endDate='".$st->effectivityDate."' where ndex='".$st->employeeId."'");
	}
	$updstattable=mysql_query("update employeechangestatus set isServed=1 where ndex=".$st->ndex."");
}

$endqry=mysql_query("UPDATE employee set isActive='0' WHERE isActive<>'0' and (endDate<CURDATE() and endDate<>'0000-00-00')");
############################
# start employee query
###########################
$qry="select e.*,d.name as department from employee e left join dept d on d.ndex=e.deptId WHERE e.ndex<>''";
if($_GET['searchtype']){
	if($_GET['txtsearch']=='Search here..'){$_GET['txtsearch']='';}
	if($_GET['searchtype']=='nym'){
		$qry.=" and (e.firstName like '%".$_GET['txtsearch']."%' OR e.middleName like '%".$_GET['txtsearch']."%' OR e.lastName like '%".$_GET['txtsearch']."%')";
	}
	elseif($_GET['searchtype']=='department'){
		$qry.=" and (d.name like '%".$_GET['txtsearch']."%')";
	}
}
if($_GET['includeInactive']!='on'){
		$qry.=" and e.isActive='1'";
}
if($_GET['namestart']){
		$qry.=" and e.lastName like '".$_GET['namestart']."%'";
}
$qry.=" ORDER BY e.lastName,e.firstName,e.middleName";

	$test = new MyPagina();
				$test->number_links = 3;
				$test->sql =$qry; // the (basic) sql statement (use the SQL whatever you like)
				$result = $test->get_page_result(); // result set
				$num_rows = $test->get_page_num_rows(); // number of records in result set 
				$nav_info = $test->page_info("Result: %d - %d of %d records"); // information about the number of records on page (example: "Result: 10 - 20 of 100 records")
				$nav_links = $test->navigation(" | ", "currentStyle", false, false, false, true); // the navigation links (define a CSS class selector for the current link)
				$numbers_only = $test->navigation("", "current", true); // navigation links using number only
				$simple_nav_links = $test->back_forward_link(true); // the navigation with only the back and forward links, use true to use images
				$test->forw = "&#9658;";
				$test->back = "&#9668;";
				$simple_nav_txt_links = $test->back_forward_link();
				$total_recs = $test->get_total_rows(); // the total number of records (not used with this example)
				
	for ($i = 0; $i < $num_rows; $i++) {
		$employmentStatus = mysql_result($result, $i, "employmentStatus");
		$employeeNo = mysql_result($result, $i, "employeeNo");
		$lastName = mysql_result($result, $i, "lastName");
		$firstName = mysql_result($result, $i, "firstName");
		$middleName = mysql_result($result, $i, "middleName");
		$department = mysql_result($result, $i, "department");
		$ndex = mysql_result($result, $i, "ndex");
		$isActive = mysql_result($result, $i, "isActive");
		$ctr1s++;
		if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		if($isActive=='0'){$bgclr1s='red';}
	$data.="<tr style='background-color:".$bgclr1s.";font-size:12px;'>
				<td>".getID($employmentStatus,$employeeNo)."</td>
				<td><strong>".$lastName.", ".$firstName."&nbsp;".$middleName."</strong></td>
				<td> ".$department."</td>
				<td><a href='#' title='Edit Employee 201' onclick=\"window.location.href='employee_edit.php?id=".$ndex."'\";><img src=\"images/edit.png\" height='15' width='15'></a>&nbsp;&nbsp;
				<a href='#' title='Daily Time Record' onclick=\"window.open('employeelogs.php?id=".$ndex."','displayWindow','toolbar=no,scrollbars=yes,width=1110,height=500')\";><img src=\"images/view.png\" height='15' width='15'></a>&nbsp;&nbsp;
				<a href='#' title='Change Shifting Schedule' onclick=\"window.open('employeeshiftingschedule.php?id=".$ndex."','displayWindow','toolbar=no,scrollbars=yes,width=600,height=500')\";><img src=\"images/shifting.png\" height='15' width='15'></a>&nbsp;&nbsp;
				<a href='#' title='Change Employment Status' onclick=\"window.open('employeechangestatus.php?id=".$ndex."','displayWindow','toolbar=no,scrollbars=yes,width=600,height=500')\";><img src=\"images/employmentstatus.png\" height='15' width='15'></a>&nbsp;&nbsp;
				<a href='#' title='Code of Discipline' onclick=\"window.open('employeecod.php?id=".$ndex."','displayWindow','toolbar=no,scrollbars=yes,width=600,height=500')\";><img src=\"images/cod.png\" height='15' width='15'></a>&nbsp;&nbsp;
				<a href='#' title='Print Contract' onclick=\"window.open('employeecontract.php?id=".$ndex."','displayWindow','toolbar=no,scrollbars=yes,width=600,height=500')\";><img src=\"images/usercontract.png\" height='15' width='15'></a>&nbsp;&nbsp;</td>
	</tr>";
}
$namearray=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
foreach($namearray as $arr){
	$namelink.="<a href='employee.php?namestart=".$arr."' style='font-size:12px;'>".$arr."</a>&nbsp;";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
        <h2>Employees &nbsp;&nbsp;&nbsp;&nbsp; <button onclick="window.open('employee_add.php','_self');">Add Employee</button></h2>
		<h2><form method="get" action="employee.php" name="frmsearch">			
			<select name="searchtype">
				<option value="nym">Name
				<option value="department">Department
			</select>
			<input name="txtsearch" value="Search here.." style="color:gray;" onfocus="if(this.value=='Search here..'){this.value='';}this.style.color='black';">
			<input type="Checkbox" name="includeInactive"><font style="font-size:12px;color:black;font-family:Arial;">Include inactive employees</font>
			<input type="Submit" value="SEARCH">
			&nbsp;<?php echo $namelink;?>
		</form>
		</h2>
		<table width="90%">
			<tr style="font-weight:bold;color:maroon;">
				<td>Employee No</td>
				<td>Name</td>
				<td>Dept</td>
				<td>Action</td>
			</tr>
			<tr><td colspan="4"><hr></td></tr>
			<?php echo $data;?>
			<tr><td colspan="4"><hr></td></tr>
			<tr><td colspan="4" align="right"><?php  echo $nav_links;?></td></tr>
		</table>
	<h2>&nbsp;</h2>
	
	<?php include "footer.php";?>
</div>
</body>
</html>


