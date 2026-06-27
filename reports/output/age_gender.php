<?php
ob_start();
session_start();
include("../../dbcon.php");
include("../../chart/Code/PHP/Includes/FusionCharts.php");
if($_POST['dep']!='on'){
	$tf=0;
	$tm=0;
	$tu=0;
	$t=0;
	$graphdata="<tr>";
	for($b=1;$b<=$_POST['cntr'];$b++){
		if($_POST['fr'.$b]!=0 || $_POST['to'.$b]!=0){
			$qry=mysql_query("select *,EXTRACT(YEAR FROM (FROM_DAYS(DATEDIFF(NOW(),birthDate))))+0 AS employeeage from employee where isActive=1");
			$empnumber[$b]=0;
			$male[$b]=0;
			$uns[$b]=0;
			$female[$b]=0;
			while($r=mysql_fetch_object($qry)){
				if($r->employeeage>=$_POST['fr'.$b] && $r->employeeage<=$_POST['to'.$b]){
					$empnumber[$b]++;
					if($r->sex=='MALE'){$male[$b]++;$tm++;}
					elseif($r->sex=='FEMALE'){$female[$b]++;$tf++;}
					else{$uns[$b]++;$tu++;}
					$t++;
				}
			}
		$strXML[$b]  = "";
		$strXML[$b] .= "<graph caption='".$_POST['fr'.$b]." - ".$_POST['to'.$b]."' decimalPrecision='0' formatNumberScale='0' showNames='1'>";
		$strXML[$b] .= "<set name='Male' value='".$male[$b]."' color='669966' />";
		$strXML[$b] .= "<set name='Female' value='".$female[$b]."' color='F6BD0F' />";
		$strXML[$b] .= "</graph>";
		$data.="<tr>
				<td>".$_POST['fr'.$b]." - ".$_POST['to'.$b]."</td>
				<td align='center'>".$male[$b]."</td>
				<td align='center'>".$female[$b]."</td>
				<td align='center'>".$uns[$b]."</td>
				<td align='center' style='font-weight:bold;'>".$empnumber[$b]."</td>
		</tr>";
		}
		$graphdata.="<td colspan='3'>".renderChartHTML("../../chart/Charts/FCF_Pie2D.swf", "", $strXML[$b], "myNext", 400, 200)."</td>"; 
	}
	$graphdata.="</tr>";
	$data.="<tr><td colspan='5'><hr></td></tr><tr style='font-weight:bold;'>
				<td align='left'>Total</td>
				<td align='center'>".$tm."</td>
				<td align='center'>".$tf."</td>
				<td align='center'>".$tu."</td>
				<td align='center'>".$t."</td>
	</tr>";
}
elseif($_POST['dep']=='on'){
	$gtf=0;
	$gtm=0;
	$gtu=0;
	$gt=0;
	$div=mysql_query("select * from division where status<>1 order by name");
	while($di=mysql_fetch_object($div)){
		$stf=0;
		$stm=0;
		$stu=0;
		$st=0;
		$data.="<tr><td style='color:maroon;font-weight:bold;'>".$di->name."</td></tr>";
		$dept=mysql_query("select * from dept where divisionId=".$di->ndex." and status<>1 order by name");
		while($de=mysql_fetch_object($dept)){
			$data.="<tr><td style='color:black;font-weight:bold;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$de->name."</td></tr>";
						$tf=0;
						$tm=0;
						$tu=0;
						$t=0;
					for($b=1;$b<=$_POST['cntr'];$b++){
						if($_POST['fr'.$b]!=0 || $_POST['to'.$b]!=0){
							$qry=mysql_query("select *,EXTRACT(YEAR FROM (FROM_DAYS(DATEDIFF(NOW(),birthDate))))+0 AS employeeage from employee where isActive=1 and deptId=".$de->ndex." and divisionId=".$di->ndex."");
							$empnumber[$b]=0;
							$male[$b]=0;
							$female[$b]=0;
							$uns[$b]=0;
							while($r=mysql_fetch_object($qry)){
								if($r->employeeage>=$_POST['fr'.$b] && $r->employeeage<=$_POST['to'.$b]){
									$empnumber[$b]++;
									$t++;
									$st++;
									$gt++;
									if($r->sex=='MALE'){$male[$b]++;$tm++;$stm++;$gtm++;}
									elseif($r->sex=='FEMALE'){$female[$b]++;$tf++;$stf++;$gtf++;}
									else{$uns[$b]++;$tu++;$stu++;$gtu++;}
									
								}
							}
						$data.="<tr>
								<td>".$_POST['fr'.$b]." - ".$_POST['to'.$b]."</td>
								<td align='center'>".$male[$b]."</td>
								<td align='center'>".$female[$b]."</td>
								<td align='center'>".$uns[$b]."</td>
								<td align='center' style='font-weight:bold;'>".$empnumber[$b]."</td>
						</tr>";
						}
					}
					$data.="<tr><td colspan='5'><hr></td></tr><tr style='font-weight:bold;'>
								<td align='left'>Total</td>
								<td align='center'>".$tm."</td>
								<td align='center'>".$tf."</td>
								<td align='center'>".$tu."</td>
								<td align='center'>".$t."</td>
					</tr>";
		}
		$data.="<tr><td colspan='5'><hr></td></tr><tr style='font-weight:bold;font-size:13px;'>
								<td align='left'>Sub total (".$di->name.")</td>
								<td align='center'>".$stm."</td>
								<td align='center'>".$stf."</td>
								<td align='center'>".$stu."</td>
								<td align='center'>".$st."</td>
					</tr>";
	}
	$data.="<tr><td colspan='5'><hr><br><hr></td></tr><tr style='font-weight:bold;font-size:14px;'>
								<td align='left'>Grand total</td>
								<td align='center'>".$gtm."</td>
								<td align='center'>".$gtf."</td>
								<td align='center'>".$gtu."</td>
								<td align='center'>".$gt."</td>
					</tr>";
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
	       <td colspan="5" align="center" style="font-size:14px;font-weight:bold;">Report by Age and Gender<br> <?php echo $_POST['sex'];?></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <?php if($_POST['grap']=='on' && $_POST['dep']!='on'){ echo $graphdata;}?>
	  <tr align='center' style="font-weight:bold;color:blue;">
	       <td align="left">Age</td>
	       <td>Male</td>
		   <td>Female</td>
		   <td>Unspecified</td>
	       <td>Total</td>
	  </tr>
	  <tr><td colspan="5"><hr></td></tr>
	  <?php echo $data;?>
      </table>
</BODY>
</HTML>
     
	  <?php include("../rptfooter.php");?>




