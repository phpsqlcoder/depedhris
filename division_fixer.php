<?php
include("dbcon.php");
	

   $u = mysql_query("select e.ndex as endex,e.divisionId,e.deptId,d.ndex as dept_index
   , di.ndex as division_ndex from `employee` e left join
   dept d on d.ndex=e.deptId
   left join division di on di.ndex=d.divisionId");
        while($x=mysql_fetch_array($u)){
            echo $x['deptId']." xx ".$x['divisionId']," xx ".$x['division_ndex']."<br>";
//echo $x['rate']."<br>";
            $upd = mysql_query("update employee set divisionId='".$x['division_ndex']."' where ndex='".$x['endex']."'");
            
        } 

	
?>