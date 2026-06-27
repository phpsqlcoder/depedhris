<?php
include("dbcon.php");
    include ("employeefunctions.php");

    $qq = 0;
    $xxx = 0;
    $table = "payslip_2024_leave_vl";
    $u = mysql_query("select * from employee where ndex not in (select employee_id from `".$table."`)");
        while($x=mysql_fetch_array($u)){
        $xxx++;
            $d = getID($x['employmentStatus'],$x['employeeNo']);
            if($d[0] == 'T' || $d[0] == 'P' || $d[0] == 'R'){
                $d = substr($d, 3);
            }
            if($d[0] == 'S'){
                $d = substr($d, 2);
            }
            $upd = mysql_query("update `".$table."` set employee_id='".$x['ndex']."' where empid='".$d."'");
           
        }

   
?>