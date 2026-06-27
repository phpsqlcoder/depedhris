<?php
include "dbcon.php";
if ($_GET['dateEnd'] >= $_GET['dateStart']){
	$dateEnd = $_GET['dateEnd'];
	
	$r = mysql_query("SET NAMES 'utf8'");	
	$r = mysql_query("SET CHARACTER SET utf8");
	$eeInfo = mysql_fetch_object(mysql_query("SELECT lastName as lname, firstName as fname, ndex, biometricNo FROM employee WHERE ndex=".$_GET['employeeID']."",$conn));
	
	$ln = 0;
	$widthCount = 0;
	while ($dateEnd != $movDate){
				$movDate = date('Y-m-d',strtotime($ln.' day',strtotime($_GET['dateStart'])));
		//echo $movDate."<br>";		
		$widthCount++;
		if ($widthCount == 1){
			$disp .= "<tr valign='top'>";
		}
		$disp .="<td bgcolor='#00000'><table cellpadding='2' bgcolor='#FFFFFF' width='110' height='90' border='0'><tr><td align='center' height='25' bgcolor='#000000' class='dateLevel'><b>".date('M d',strtotime($movDate))."</b></td></tr><tr valign='top'><td>";
		$sqlHrInt = "SELECT * FROM hrinterface WHERE dtrid='".$eeInfo->biometricNo."' && datelog='".$movDate."'";
		//echo "SELECT * FROM hrinterface WHERE dtrid='".$eeInfo->biometricNo."' && datelog='".$movDate."'";
		$rs = mysql_query($sqlHrInt);
		//echo mysql_num_rows($rs);
		while ($dt = mysql_fetch_object($rs)){
			if ($dt->in_out == 0) {$inOut = 'IN'; } else {$inOut = 'OUT';} 
			$disp .= date('H:i:s',strtotime($dt->log))." &nbsp;&nbsp;&nbsp;<b>".$inOut."</b><br />";
			//echo $dt->in_out."zcsdc<br>";
		}
		$dailylogs=mysql_fetch_object(mysql_query("select * from dailytimesummary where employeeId='".$eeInfo->ndex."' and  date='".$movDate."'"));
		//echo "select * from dailytimesummary where employeeId='".$eeInfo->biometricNo."' and  date='".$movDate."'<br><br>";
		//$disp.="<tr></tr>";
		if($dailylogs->isError==0){
			$dl="<tr><td style='color:blue;'>Hours Duty: ".$dailylogs->hoursDuty."</td></tr>";
		}
		$disp .= "</td></tr>".$dl."</table></td>";
		if ($widthCount == 7){
			$disp .= "</tr>";
			$widthCount = 0;
		}
		$ln++;
	}
	
	?>
	<table cellpadding="5" cellspacing="0" border='0' bordercolor='#ffcc00'>
		<tr><td colspan="7" class='dateLevel' bgcolor="#000000">
					Name: <?php echo $eeInfo->lname.", ".$eeInfo->fname;?><br />
				</td>
		</tr>
		<?php echo $disp;?>
	</table>
<?php
} else {
	echo "Invalid Date Range";
}
?>
 