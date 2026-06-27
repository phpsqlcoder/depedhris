<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../chart/Code/PHP/Includes/FusionCharts.php");
function random_hex_color(){
    return sprintf("%02X%02X%02X", mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
	}
$qry="SELECT 
	count(e.ndex) as cnt,
	".$_POST['feld']." as sel
		from employee e 
		left join position p on p.ndex=e.position 
		left join dept d on d.ndex=e.deptId
		left join division di on di.ndex=e.divisionId
		left join unit u on u.ndex=e.unitId 
	WHERE e.isActive='1' GROUP BY ".$_POST['feld']."";
$exec=mysql_query($qry);
$var=0;
$tot=0;
$graphdata="<graph caption='".$_POST['fr'.$b]." - ".$_POST['to'.$b]."' decimalPrecision='0' formatNumberScale='0' showNames='1'>";
while($r=mysql_fetch_object($exec)){
     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	 if($r->sel==''){$r->sel='UNCATEGORIZED';}
	 $graphdata.="<set name='".$r->sel."' value='".$r->cnt."' color='".random_hex_color()."' />";
     $data.="<tr bgcolor='".$bgclr1s."'>
	       <td>".$var."</td>
		    <td>".$r->sel."</td>
	       <td>".$r->cnt."</td>
     </tr>";
	 $tot+=$r->cnt;
}
$graphdata.="</graph>";
$graph="<tr><td colspan='4' align='center'>".renderChartHTML("../../chart/Charts/".$_POST['grap'].".swf", "", $graphdata, "myNext", 800, 400)."</td></tr>"; 
$data.="<tr><td colspan='4'><hr></td></tr>
<tr>
	<td>Total</td>
	<td colspan='3' align='right'>".$tot."</td>
</tr>";
?>

     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="4" align="center" style="font-size:14px;font-weight:bold;">Manning Count<br></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <?php echo $graph;?>
	   <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
	       <td>Type</td>
		   <td>Count</td>
	  </tr>
	  <tr><td colspan="4"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




