<?php
include("dbcon.php");
include ("employeefunctions.php");

    $tables = array('payslip_2023_leave_vl');
    $qq = 0;
    $u = mysql_query("select * from employee");
        while($x=mysql_fetch_array($u)){
		if($x['employmentStatus'] == 'Probationary' || $x['employmentStatus'] == 'Temporary'){
$d = getID($x['employmentStatus'],$x['employeeNo']);
}
else{
$d = (int) getID($x['employmentStatus'],$x['employeeNo']);
}
           // $d = (int) getID($x['employmentStatus'],$x['employeeNo']);
//echo $x['employmentStatus']."-".$x['employeeNo'];
            foreach($tables as $t){
                $nr = mysql_fetch_array(mysql_query("select * from $t where empid='".$d."'"));
                if(isset($nr['id'])){
    		        // $qq++;
                    //Echo $nr['id']." - ".$."<br>";
                    $upd = mysql_query("update $t set employee_id='".$x['ndex']."' where id='".$nr['id']."'");
                }
            }
        }


/*
    $u = mysql_query("select * from payslip_2023_leave_vl");
        while($x=mysql_fetch_array($u)){
            $nr = mysql_fetch_array(mysql_query("select * from payslip_2023_leave_sl where empid='".$x['empid']."'"));
            if(isset($nr['id'])){
                // echo "update payslip_2023_leave_sl set 
                //     vl_unused='".$x['unused']."',
                //     vl_taxable='".$x['taxable']."',
                //     vl_nontaxable='".$x['nontaxable']."',
                //     vl_gross='".$x['gross']."',
                //     vl_wtax='".$x['wtax']."',
                //     vl_net='".$x['net']."',
                //     where id='".$nr['id']."'<br><br>";
                $upd = mysql_query("update payslip_2023_leave_sl set 
                    vl_unused='".$x['unused']."',
                    vl_taxable='".$x['taxable']."',
                    vl_nontaxable='".$x['nontaxable']."',
                    vl_gross='".$x['gross']."',
                    vl_wtax='".$x['wtax']."',
                    vl_net='".$x['net']."'
                    where id='".$nr['id']."'");
            }
            else{
                // echo "insert into payslip_2023_leave_sl
                //     (`empid`, `name`, `unused`, `gross`, `wtax`, `net`,  `employee_id`, `vl_unused`, `vl_taxable`, `vl_nontaxable`, `vl_gross`, `vl_wtax`, `vl_net`)
                //     values('".$x['empid']."','".$x['name']."','0','0','0','0','0',".$x['unused']."','".$x['taxable']."','".$x['nontaxable']."','".$x['gross']."','".$x['wtax']."','".$x['net']."')<br><br>";
                $upd = mysql_query("insert into payslip_2023_leave_sl
                    (`empid`, `name`, `unused`, `gross`, `wtax`, `net`,  `employee_id`, `vl_unused`, `vl_taxable`, `vl_nontaxable`, `vl_gross`, `vl_wtax`, `vl_net`)
                    values('".$x['empid']."','".$x['name']."','0','0','0','0','0','".$x['unused']."','".$x['taxable']."','".$x['nontaxable']."','".$x['gross']."','".$x['wtax']."','".$x['net']."')");
            }
           
        }
   
    */
?>