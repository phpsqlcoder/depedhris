<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
if($_POST['catd']!='all'){
	$divqry=" and d.categoryId=".$_POST['catd']."";
}
else{
	$divqry="";
}
if($_POST['subcatd']!='all'){
	$depqry=" and e.subcategoryId=".$_POST['subcatd']."";
}
else{
	$depqry="";
}
$qry="SELECT di.name as division,e.firstName,e.lastName,e.middleName,e.employmentStatus,e.employeeNo,cs.name as subcat, c.name as cat from employee e left join dept d on d.ndex=e.deptId left join division di on di.ndex=d.divisionId 
					   left join category_sub cs on cs.ndex=e.subcategoryId
					   left join category c on c.ndex=e.categoryId";
$qry.=" WHERE e.isActive=1 ".$divqry."".$depqry."";
$qry.=" ORDER BY c.name,cs.name,e.lastName,e.firstName";
//echo $qry;
$exec=mysql_query($qry);
$var=0;
while($r=mysql_fetch_object($exec)){
     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
     $data.="<tr bgcolor='".$bgclr1s."'>
	       <td>".$var."</td>
		   <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
	       <td>".$r->lastName." , ".$r->firstName." ".$r->middleName."</td>
		     <td>".$r->cat."</td>
			<td>".$r->subcat."</td>
	      
     </tr>";
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="reportbycategory.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="6" align="center" style="font-size:14px;font-weight:bold;">Employee List by Division/Dept<br></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
		   <td>ID</td>
	       <td>Name</td>
		   <td>Category</td>
		   <td>Sub Category</td>
	       
	  </tr>
	  <tr><td colspan="6"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




