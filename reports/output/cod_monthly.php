<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../chart/Code/PHP/Includes/FusionCharts.php");
	$data.="<tr><td>&nbsp;</td>";
	$uis=mysql_query("select distinct typeOfOffense from employeecod where dateOfIncident>='".$_POST['startdate']."' and dateOfIncident<='".$_POST['enddate']."' order by typeOfOffense");
			while($rs=mysql_fetch_object($uis)){
				$data.="<td>".$rs->typeOfOffense."</td>";
			}
	$data.="</tr><tr><td colspan='10'><hr></td></tr>";
	$a=0;
	$div=mysql_query("select * from division where status<>1 order by name");
	while($di=mysql_fetch_object($div)){
		$data.="<tr><td style='color:maroon;font-weight:bold;'>".$di->name."</td></tr>";
		$dept=mysql_query("select * from dept where divisionId=".$di->ndex." and status<>1 order by name");
		while($de=mysql_fetch_object($dept)){
			$data.="<tr><td style='color:black;font-weight:bold;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$de->name."</td>";
			$ui=mysql_query("select c.typeOfOffense,count(c.ndex)as cnt from employeecod c left join employee e on e.ndex=c.employeeId where c.dateOfIncident>='".$_POST['startdate']."' and c.dateOfIncident<='".$_POST['enddate']."' and e.deptId=".$de->ndex." group by typeOfOffense order by typeOfOffense");
				while($r=mysql_fetch_object($ui)){
					$a++;
					$v[$a]+=$r->cnt;
							$data.="
										<td>".$r->cnt."</td>
							";
					/*$strXML[$b]  = "";
					$strXML[$b] .= "<graph caption='".$_POST['fr'.$b]." - ".$_POST['to'.$b]."' decimalPrecision='0' formatNumberScale='0' showNames='1'>";
					$strXML[$b] .= "<set name='Male' value='".$male[$b]."' color='669966' />";
					$strXML[$b] .= "<set name='Female' value='".$female[$b]."' color='F6BD0F' />";
					$strXML[$b] .= "</graph>";*/
			
					//$graphdata.="<td colspan='3'>".renderChartHTML("../../chart/Charts/FCF_Pie2D.swf", "", $strXML[$b], "myNext", 400, 200)."</td>"; 
				}
				$data.="</tr>";
				/*$data.="<tr><td colspan='5'><hr></td></tr><tr style='font-weight:bold;'>
							<td align='left'>Total</td>";
				for($b=0;$b<=$a;$b++){
					$data.="<td>".$v[$b]."</td>";
				}
				$data.="</tr>";*/
		}
	}

?>
<HTML>
<HEAD>
  <SCRIPT LANGUAGE="Javascript" SRC="../../chart/Code/FusionCharts/FusionCharts.js"></SCRIPT>
</HEAD>

<BODY>
     <?php
if($_POST['eksel']=='on'){
		$filename ="reportbyage_gender.xls";
				header('Content-type: application/ms-excel');
				header('Content-Disposition: attachment; filename='.$filename);
	}
	?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="5" align="center" style="font-size:14px;font-weight:bold;">Monthly Infraction Report<br> <?php echo $_POST['sex'];?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <?php echo $data;?>
      </table>
</BODY>
</HTML>
     
	  <?php include("../rptfooter.php");?>




