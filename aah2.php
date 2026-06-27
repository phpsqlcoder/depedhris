<?php
include("dbcon.php");
	

   $u = mysql_query("select * from `aaa_deptchange`");
        while($x=mysql_fetch_array($u)){
            echo $x['a']." xx ".$x['b'];
//echo $x['rate']."<br>";
            $upd = mysql_query("update employee set deptId='".$x['b']."', unitId=0 where ndex='".$x['a']."'");
            
        } 

	
?>