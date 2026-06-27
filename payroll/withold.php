<?php
$m_income=$income_wt;
#$prevtblincome=0;

if ((empty($dependents) || $dependents==0) && !empty($cstatus)){
	 $stat=$cstatus;
}	 
else if (!empty($dependents) && !empty($cstatus) && $dependents!='0'){
	$stat=$cstatus.$dependents;
}
else if (empty($cstatus)){
	$stat='S';
}

	$qwt=mysql_query("SELECT * FROM tblwt10 LEFT JOIN tblwt11 ON tblwt10.ndex=tblwt11.ndexwt10 WHERE tblwt10.stat='$stat' && tblwt11.income < '$m_income' ORDER BY tblwt11.income",$conn);
while($datawt=mysql_fetch_array($qwt))
{
	$incomewt=$datawt['income'];
	$t_deduct=$datawt['tax_ded'];
	$percentage=$datawt['ded_on_exc'];
	if (($m_income!=0 || !empty($m_income)) && $m_income > $incomewt){
		$wtax=$m_income-$incomewt;
		//$ttt=$wtax*$percentage;
		$wtax=round(($wtax*$percentage)+$t_deduct,2);
	}
}
?>