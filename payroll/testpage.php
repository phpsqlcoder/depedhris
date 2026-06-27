<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../employeefunctions.php");
include ("../myfunctions.php");
include('payrollfunctions.php');
echo getDeductionData(11,'ledger');

echo '<br>Currect Deduction Amount:'.getDeductionData(11,'Currect Deduction Amount');
echo '<br>current balance:'.getDeductionData(11,'current balance');
echo '<br>total payments made:'.getDeductionData(11,'total payments made');
echo '<br>total number of payments made:'.getDeductionData(6,'total number of payments made');
echo '<br>total loan amount:'.getDeductionData(11, 'total loan amount');
echo '<br>total number of deduction:'.getDeductionData(11, 'total number of deduction');
echo estimatedDateOfCompletion(8);