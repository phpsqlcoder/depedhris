<?php
include("dbcon.php");
    include ("employeefunctions.php");

    $qq = 0;
    $xxx = 0;
    $table = "payslip_2024_leave_vl";
    $u = mysql_query("select * from $table where employee_id=0");
        while($x=mysql_fetch_array($u)){
            if(strlen($x['empid']) == 3){
                $f = '000'.$x['empid'];
            }
            if(strlen($x['empid']) == 4){
                $f = '00'.$x['empid'];
            }
            echo $f."<br>";
            //$d = getID($x['employmentStatus'],$x['employeeNo']);
            
            $upd = mysql_query("update `".$table."` set empid='".$f."' where id='".$x['id']."'");
           
        }

   
?>