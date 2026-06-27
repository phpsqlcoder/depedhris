<?php
ob_start();

	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
    include ("myfunctions.php");
/*	$asa=mysql_query("select * from `aacoop`");
       
	while($b=mysql_fetch_array($asa)){
		
		for($a=5;$a<=6;$a++){
			//if($a!='2' && $a!='3'){
				if($a==5){$lonId=$b['c'];}
				if($a==6){$lonId=$b['d'];}
				
				if($lonId!=0.0000){
					$bd=mysql_query("insert into loan_employee
					(`employeeId`, `loanId`, `loanAmount`, `nOfDeduction`, `dedDateStart`, `remarks`, `loanBalance`, `posted`, `isDeleted`, `createDate`, `postedDate`)
					VALUES
					('".$b['z']."','".$a."','".$lonId."','1','2014-01-15','Manual input 01-31-2014','".$lonId."','1','0','2014-01-11','2014-01-11')
					");
					/*echo "insert into loan_employee2 
					(`employeeId`, `loanId`, `loanAmount`, `nOfDeduction`, `dedDateStart`, `remarks`, `loanBalance`, `posted`, `isDeleted`, `createDate`, `postedDate`)
					VALUES
					('".$b['a']."','".$a."','".$lonId."','1','2014-01-15','Manual input 01-31-2014','".$lonId."','1','0','2014-01-11','2014-01-11',)
					<br>";
				}
			//}
		}
		//$updcoop=mysql_query("update payroll set d_coopTotal='".$b['y']."' where empid='".$b['z']."' and pay_period='2014-01-15'");
		
	}*/
	$c=mysql_query("select * from `aacoop`");
	while($x=mysql_fetch_object($c)){
               $updcoop=mysql_query("update payroll set d_coopTotal='".$x->d."' where empid='".$x->e."' and pay_period='2014-01-31'");
		/*$xp=explode(" ",$x->b);
                 $count=count($xp);
		$l=str_replace(" ","",$xp[0]);
		$l2=str_replace(" ","",$xp[$count - 1]);
		
		$e=mysql_fetch_object(mysql_query("select * from employee where TRIM(lastName)='".$l."' and TRIM(middleName)='".$l2."' and isActive=1"));
		//echo "select * from employee where TRIM(lastName)='".$l."' and TRIM(middleName)='".$l2."' and isActive=1 <Br>";
		$upd=mysql_query("update aacoop set e=".$e->ndex." where `i`='".$x->i."'");
		//echo $x->b." - ".$e->lastName.", ".$e->middleName."<br>";*/
	}
	
?>
