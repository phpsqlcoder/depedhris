<?php
include("dbcon.php");
	include ("employeefunctions.php");
/*
    $qq = 0;
    $xxx = 0;
    $u = mysql_query("select * from employee 
         isActive=1");
    //where ndex not in (select empid from `03272025` ) and
        while($x=mysql_fetch_array($u)){
		$xxx++;
            $d = getID($x['employmentStatus'],$x['employeeNo']);
//echo "select * from `03272025` where empno='".$d."'<br><br>";
            $nr = mysql_fetch_array(mysql_query("select * from `03272025` where b='".$d."'"));
            if(isset($nr['b'])){
		        $qq++;
                //Echo $qq." - ".$nr['b']." - ".$d."<br>";
                $upd = mysql_query("update `03272025` set empid='".$x['ndex']."' where b='".$d."'");
            }
        }
*/
   
/*
   $u = mysql_query("select * from `03272025` where empid>0");
        while($x=mysql_fetch_array($u)){
            $rate = $x['d'];
//echo $x['rate']."<br>";
            $upd = mysql_query("update employee_compensation set 

basicPay='".$rate."' where employeeId='".$x['empid']."'");
//echo "update employee_compensation set basicPay='".$rate."' where employeeId='".$x['empid']."'<br>";
            
        } 

	*/
?>