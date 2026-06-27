<?php
include("dbcon.php");
	include ("employeefunctions.php");
/*
    $qq = 0;
    $u = mysql_query("select * from employee");
        while($x=mysql_fetch_array($u)){
		
            $d = (int) getID($x['employmentStatus'],$x['employeeNo']);
            $nr = mysql_fetch_array(mysql_query("select * from aaa_newrate09142022 where b='".$d."'"));
            if(isset($nr['b'])){
		$qq++;
Echo $qq." - ".$nr['b']." - ".$d."<br>";
                $upd = mysql_query("update aaa_newrate09142022 set empid='".$x['ndex']."' where a='".$nr['a']."'");
            }
        }
   */

   $u = mysql_query("select * from aaa_newrate09142022");
        while($x=mysql_fetch_array($u)){
            $rate = $x['d'];

            $upd = mysql_query("update employee_compensation set basicPay='".$rate."' where employeeId='".$x['empid']."'");
            
        } 
	
?>