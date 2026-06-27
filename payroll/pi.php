<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");
include ("payrollfunctions.php");
/*$p=mysql_query("select * from payroll where pay_period='2014-01-15'");
while($a=mysql_fetch_object($p)){
    $f=mysql_fetch_object(mysql_query("select sum(p.amountPaid) as amt from loan_employee_payments p left join loan_employee e on e.ndex=p.loanSetupId where e.employeeId=".$a->empid." and e.loanId in ('5','6') and e.dedDateStart>='2014-01-15' and e.posted='1'"));  
    //echo "select sum(p.amountPaid) as amt from loan_employee_payments p left join loan_employee e on e.ndex=p.loanSetupId where e.employeeId=".$a->empid." and e.loanId in ('5','6') and e.dedDateStart>='2014-01-15' and e.posted='1'<br>";
    if($f->amt>0){
    //echo $a->empid." - ".$f->amt."<br>";
        $upd=mysql_query("update payroll set d_hospital='".$f->amt."' where ndex='".$a->ndex."'");
    }
}*/echo getDeductionData(833,'current balance');
?>