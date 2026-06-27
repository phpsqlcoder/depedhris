<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../myfunctions.php");
include("../../employeefunctions.php");
$sql="";
if ($_POST['tdept']!='ALL'){$sql = " && e.deptId='".$_POST['tdept']."'";}
	$qry=mysql_query("SELECT 
		e.level,e.lastName,e.firstName,e.middleName,
d.name as dept,di.name as division,u.name as unit,e.employmentStatus,e.employeeNo,e.ndex,p.employmentStatus as emps,p.*
			from performance p
			left join employee e on e.ndex=p.employeeId
			left join dept d on d.ndex=e.deptId
			left join division di on di.ndex=e.divisionId
			left join unit u on u.ndex=e.unitId 
		WHERE e.ndex<>'' and p.yer='".$_POST['yer']."' ".$sql." order by e.lastName,e.firstName");

$var=0;
	while($r=mysql_fetch_object($qry)){
		 $ctr1s++;
		 $var++;
		     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		 if($r->emps=='Regular'){
		     $data.="<tr bgcolor='".$bgclr1s."'>
			 		<td>".$var."</td>
			 		<td>".$r->level."</td>
				   <td>".$r->dateHired."</td>
				   <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
				   <td>".$r->lastName.", ".$r->firstName." ".$r->middleName."</td>
				   <td>&nbsp;</td>
				   <td>&nbsp;</td>
				   <td>".($r->first * 100)."%</td>			      
				   <td>".($r->second * 100)."%</td>			      
		     </tr>";
		}
		else{
			$data.="<tr bgcolor='".$bgclr1s."'>
			 		<td>".$var."</td>
			 		<td>".$r->level."</td>
				   <td>".$r->dateHired."</td>
				   <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
				   <td>".$r->lastName.", ".$r->firstName." ".$r->middleName."</td>
				   <td>".($r->first * 100)."%</td>			      
				   <td>".($r->second * 100)."%</td>	
				   <td>&nbsp;</td>
				   <td>&nbsp;</td>		      
		     </tr>";
		}
	}
	
	
	
		    
		 
	


?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="incaseofemergency.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
   <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">
	
	 <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td colspan="10" align="center" style="font-size:14px;font-weight:bold;">Performance History Report<br><?php echo $_POST['yer']?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr style="font-weight:bold;">
	       <td>Seq</td>
	       <td>Level</td>
		   <td>DateHired</td>
		   <td>ID</td>
		   <td>Name</td>
		   <td>Probationary<br>3rd month</td>
		   <td>Probationary<br>5th month</td>
		   <td>Regular<br>1st Half</td>
		   <td>Regular<br>2nd Half</td>
	      
	  </tr>
	  <tr><td colspan="10"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




