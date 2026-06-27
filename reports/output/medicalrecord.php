<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");
$addqry="";
if($_POST['id']!='ALL'){
  $addqry.=" and e.ndex='".$_POST['id']."'";
}
if($_POST['division']!='ALL'){
  $addqry.=" and di.ndex='".$_POST['division']."'";
}
$rs=mysql_fetch_object(mysql_query("select e.*,p.name as pp,d.name as dd from employee e left join
position p on p.ndex=e.position
left join dept d on d.ndex=e.deptId
left join division di on di.ndex=e.divisionId
    where e.isActive=1"));
$sqry=mysql_query("SELECT em.*,e.*,d.name as dd,di.name as divisionname from employee_medical em 
left join employee e on e.ndex=em.employeeId
left join dept d on d.ndex=e.deptId
left join division di on di.ndex=e.divisionId
where em.employeeId='".$_POST['id']."' ORDER BY em.orDate DESC");
while($r=mysql_fetch_object($sqry)) {
    $ctr1s++;
    if ($ctr1s == 2) {
        $bgclr1s = '#ffffff';
        $ctr1s = 0;
    } else {
        $bgclr1s = '#F8F8AC';
    }
    $data .= "<tr style='background-color:" . $bgclr1s . ";'>
        <td>" . getID($r->employmentStatus,$r->employeeNo) . "</td>
        <td>" . $r->lastName." , ".$r->firstName." ".$r->middleName . "</td>
        <td>" . $r->dd . "</td>
        <td>" . $r->divisionname . "</td>
				<td>" . $r->bmi . "</td>
				<td>" . $r->fecalysis . "</td>
				<td>" . $r->urinalysis . "</td>
				<td>" . $r->xray . "</td>
				<td>" . $r->bloodCount . "</td>
				<td>" . $r->remarks . "</td>
				<td>" . $r->cedula . "</td>
				<td>" . $r->cedulaDate . "</td>
				<td>" . $r->orHC . "</td>
				<td>" . $r->orDate . "</td>
				<td>" . $r->prcLicense . "</td>
				<td>" . $r->receiptNo . "</td>
				";
    /*if($_SESSION['ndex']=='19'){
        $data.="<td><a href='#' title='Edit Cod' onclick=\"window.location.href='employeecod.php?aa=edit&id=".$_GET['id']."&codid=".$r->ndex."'\";><img src=\"images/edit.png\" height='15' width='15'></a></td>";
    }*/
    $data .= "</tr>";
}
?>
     <?php
if($_POST['eksel']=='on'){
		$filename ="medicalrecord.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
<table style="font-family:Arial;font-size:12px;" width="100%">
     <tr><td>&nbsp;</td></tr>
     
    
     <tr><td>&nbsp;</td></tr>
    <tr style="color:blue;font-weight:bold;">
        <td>ID</td>
        <td>Name</td>
        <td>Dept</td>
        <td>Division</td>
        <td>BMI</td>
        <td>Fecalysis</td>
        <td>Urinalysis</td>
        <td>X-ray</td>
        <td>Blood Count</td>
        <td>Remarks</td>
        <td>Cedula No</td>
        <td>Issued Date</td>
        <td>OR No. (HC)</td>
        <td>Issued Date</td>
        <td>PRC License No</td>
        <td>Receipt No. PTR</td>
    </tr>
    <tr><td colspan="20"><hr></td></tr>
    <?php echo $data;?>
</table>
	  <?php include("../rptfooter.php");?>




