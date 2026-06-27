<?php
include("dbcon.php");
    include ("employeefunctions.php");

    $qq = 0;
    $xxx = 0;
    $table = "payslip_2024_incometax";
    $u = mysql_query("select * from employee");
    //where ndex not in (select empid from `".$table."` ) and
        while($x=mysql_fetch_array($u)){
        $xxx++;
            $d = getID($x['employmentStatus'],$x['employeeNo']);
            
            $upd = mysql_query("update `".$table."` set employee_id='".$x['ndex']."' where empid='".$d."' and employee_id=0");
            /*
            $nr = mysql_fetch_array(mysql_query("select * from `".$table."` where b='".$d."'"));
            if(isset($nr['b'])){
                $qq++;
                $upd = mysql_query("update `".$table."` set empid='".$x['ndex']."' where b='".$d."'");
            }
            */
        }

   
?>