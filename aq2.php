<?php
ob_start();
include("dbcon.php");
$dd2=mysql_query("select * from loandeductionmaintenance where ndex in (5,6)");
$datah="<tr><td colspan='2'>&nbsp;</td>";
while($d2=mysql_fetch_object($dd2)){
	$datah.="<td>".$d2->name."</td>";
}

$a=mysql_query("select * from employee where isActive=1");
while($r=mysql_fetch_object($a)){
	$data.="<tr><td>".$r->ndex."</td><td>".$r->lastName.", ".$r->firstName." ".$r->middleName."</td>";
		$dd=mysql_query("select * from loandeductionmaintenance where ndex in (5,6)");
		while($d=mysql_fetch_object($dd)){
			$data.="<td>0</td>";
		}
	$data.="</tr>";
}

?>
<table style="font-family:arial;font-size:10px;" width="150%">
	<?php echo $datah;?>
	<?php echo $data;?>
</table>
