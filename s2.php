<?php
ob_start();

	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
    include ("myfunctions.php");
	$sql=mysql_query("select * from loan_employee where loanId=3");
while($r=mysql_fetch_object($sql)){
	$l=mysql_fetch_object(mysql_query("select * from loansetup where employeeId=".$r->employeeId." and loanType='PAG-IBIGSAL'")); //PAG-IBIGSAL
	$d=mysql_fetch_object(mysql_query("select sum(amountPaid) as paid from loanpayments 
where datepaid in ('2013-12-15','2013-12-31') and employeeId=".$r->employeeId." and loanSetupId=".$l->ndex." "));
$cnt=mysql_num_rows(mysql_query("select *  from loanpayments 
where datepaid in ('2013-12-15','2013-12-31') and employeeId=".$r->employeeId." and loanSetupId=".$l->ndex." "));
	//echo $r->employeeId." - ".$d->paid." - ".$cnt."<br>";
	
	$upd=mysql_query("update loan_employee set loanAmount=(loanAmount - ".$d->paid.") where ndex=".$r->ndex."");
	//$s=mysql_fetch_object(mysql_query("select * from loan_employeebk where ndex=".$r->ndex.""));
//echo $r->employeeId." - ".$r->nOfDeduction." - ".$r->loanAmount." - ".$s->nOfDeduction." - ".$s->loanAmount."<br>";
}
?>
