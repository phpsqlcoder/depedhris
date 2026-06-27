<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
$sql = "SELECT distinct e.employmentStatus,e.employeeNo,e.lastName, e.firstName, e.middleName, e.ndex,
							 d.name as departmentName
									FROM employee_leave l
										LEFT JOIN employee e ON e.ndex=l.employeeId
										LEFT JOIN dept d ON d.ndex = e.deptId 
												WHERE l.startDate>='".$_POST['startdate']."' && l.endDate<='".$_POST['enddate']."' and l.leaveId='".$_POST['lev']."'";

if ($_POST['mbtcCompany'] == 1){
	$sql .= " && e.level IN (0)";
	$reportTitle = 'TEMPORARY';
} elseif ($_POST['mbtcCompany'] == 2) {
	$sql .= " && e.level IN (1,2)";
	$reportTitle = 'RANK & FILE';
} elseif ($_POST['mbtcCompany'] == 3) {
	$sql .= " && e.level IN (3,4,5)";
	$reportTitle = 'SECTION HEADS AND CONFI';
} elseif ($_POST['mbtcCompany'] == 0) {
	$reportTitle = 'ALL EMPLOYEE';
}
if ($_POST['division']){$sql .= " && e.divisionId='".$_POST['division']."'";}
//echo $division." --".$PayrollCutoff."<br>".$sql.mysql_num_rows(mysql_query($sql));
$sql.=" ORDER BY d.name, e.lastName,e.firstName";
//echo $sql;
$exec=mysql_query($sql);
$var=0;
$var2=0;
$ah=0;
$rowCount = mysql_num_rows($exec);
while($r=mysql_fetch_object($exec)){
     $var++;
	 $var2++;
     $ctr1s++;
		 $ln++;
		
		 if($r->departmentName != $prevDepartment){
		 		
		 		$data .= "<tr><td colspan='20'><hr></td></tr>";
				if($ln != 1){
					$ah++;
					if($ah==1){$var2=$var2-1;}
					$data.="<tr>
							   <td colspan='2'><strong>Total: ".$var2."</strong></td>
						     </tr>";
		 			$var2=0;
				}
				/*else{
					$data.="<tr>
							   <td colspan='2'>".($var2 - 1)."</td>
						     </tr>";
		 			$var2=0;
				}*/
		 		$data .= " <tr><td colspan='13' align='left' style='font-size:14px;font-weight:bold;'>".$r->departmentName."</td></tr>";
		 }
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	 $llqry=mysql_query("SELECT l.* FROM employee_leave l										
						WHERE l.employeeId='".$r->ndex."'  && (l.startDate>='".$_POST['startdate']."'
						&& l.endDate<='".$_POST['enddate']."') and l.leaveId='".$_POST['lev']."'");
						$levconsumed=0;
	while($ll=mysql_fetch_object($llqry)){
		$lstart = strtotime($ll->startDate);
		$lend = strtotime($ll->endDate);
			for ( $la = $lstart; $la <= $lend; $la += 86400 ){
				$levconsumed++;
				//echo $r->ndex." aa ".date('Y-m-d',$la)."<br>";
				$leavedates.="<tr><td colspan='2' align='center'>".date('Y-m-d',$la)."</td></tr>";
			}
	}
	
     $data.="<tr bgcolor='".$bgclr1s."'>
	 				<td>&nbsp;</td>
	      		  <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
	     		  <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
		 		  <td align='center'>".$levconsumed."</td>
   		  </tr>";
		$prevDepartment = $r->departmentName;
		/*if ($ln == $rowCount){
			$data .= "<tr><td colspan='20'><hr></td></tr>";
			$data.="<tr>
					       <td colspan='2'></td>
						   <td align='right'></td>
				     </tr>";
				$data .= "<tr><td colspan='20'><hr></td></tr>";
				$data.="<tr>
					       <td colspan='2'></td>
						   <td align='right'></td>
					     </tr>";
		}*/
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="reportbyposition.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	$lns=mysql_fetch_object(mysql_query("select * from `leave` where ndex=".$_POST['lev'].""));
	?>
     <?php include("../rptheader.php");?>
     <table width="95%" style="font-family:Tahoma, Arial;font-size:12px;">
	 <thead>
	  <tr>
	       <td colspan="14" align="center" style="font-size:14px;font-weight:bold;">Used Leave Report<br><?php echo $reportTitle;?><br>
				 				<?php echo date('F d, Y',strtotime($_POST['startdate']))." to ".date('F d, Y',strtotime($_POST['enddate']));?> </td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	
	  <tr>
	  	<td>Dept</td>
	     	<td>ID</td>
	      <td>Name</td>
		  <td><?php echo $lns->name;?></td>
			
				
	  </tr>
	  
	  </thead>
	  <tbody>
	  <?php echo $data;?>
	  </tbody>
      </table>
	  <?php include("../rptfooter.php");?>




