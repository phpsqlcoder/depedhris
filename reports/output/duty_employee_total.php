<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../employeefunctions.php");


$dpos='ALL';
$ddept='ALL';
$ddiv='ALL';

$qry="SELECT 
e.ndex as emp,e.firstName,e.lastName,e.middleName,p.name as position,d.name as dept,di.name as division,
e.employmentStatus,e.employeeNo,sum(t.days_work) as tots
from dailytimesummary t
left join employee e on e.ndex=t.employeeId
left join position p on p.ndex=e.position 
left join dept d on d.ndex=e.deptId
left join division di on di.ndex=e.divisionId
where t.date>='".$_POST['startdate']."' and t.date<='".$_POST['enddate']."'
";

if($_POST['division']<>'ALL'){
	$qry.=" and di.ndex=".$_POST['division'];
	$ed = mysql_fetch_array(mysql_query("select * from division where ndex='".$_POST['division']."'"));
	$ddiv=$ed['name'];
}

if($_POST['dept']<>'ALL'){
	$qry.=" and d.ndex=".$_POST['dept'];
	$ede = mysql_fetch_array(mysql_query("select * from dept where ndex='".$_POST['dept']."'"));
	$ddept=$ede['name'];
}

if($_POST['position']<>'ALL'){
	$qry.=" and p.ndex=".$_POST['position'];
	$ep = mysql_fetch_array(mysql_query("select * from position where ndex='".$_POST['position']."'"));
	$dpos=$ep['name'];
}
$qry.=" group by e.ndex,e.firstName,e.lastName,e.middleName,p.name,d.name,di.name,e.employmentStatus,e.employeeNo having sum(t.days_work)>0";
//echo $qry;

$qryex=mysql_query($qry);
	$var=0;
	while($s=mysql_fetch_object($qryex)){
	     $var++;
	     $ctr1s++;
	     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	     $data.="<tr bgcolor='".$bgclr1s."'>
		       <td>".$s->division."</td>
			   <td>".$s->dept."</td>
			   <td>".getID($s->employmentStatus,$s->employeeNo)."</td>
		       <td>".$s->lastName.", ".$s->firstName." ".$s->middleName."</td>
			   <td>".$s->position."</td>
			   <td>".$s->tots."</td>
	     </tr>";
	}

?>
     <?php
	if($_POST['eksel']=='on'){
		$filename ="hris.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="100%" style="font-family:Arial;font-size:12px;">
	 <tr><td>&nbsp;</td></tr>
	 <tr><td colspan="5">Date Range:<?php echo date('Y-m-d',strtotime($_POST['startdate']))." to ".date('Y-m-d',strtotime($_POST['enddate']));?></td></tr>
	 <tr><td colspan="5">Position:<?php echo $dpos;?></td></tr>
	 <tr><td colspan="5">Division:<?php echo $ddiv;?></td></tr>
	 <tr><td colspan="5">Dept:<?php echo $ddept;?></td></tr>
	 <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td colspan="7" align="center" style="font-size:14px;font-weight:bold;">Total Duty per Employee</td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr style="font-weight:bold;">
	       <td>Division</td>
	       <td>Dept</td>
		   <td>ID</td>
	       <td>Name</td>
		   <td>Position</td>
		   <td>Total no. of days</td>
	  </tr>
	  <tr><td colspan="7"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>