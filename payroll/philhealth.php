<?php
$m_income=$basicpay;
$prevtblincome=0;
$q=mysql_query("SELECT * FROM tbl_philhealth  WHERE income <= '6999' ORDER BY ndex DESC limit 1",$conn);
while($dataph=mysql_fetch_array($q))
{
	$incometbl=$dataph['income'];
	$myshare=$dataph['e_share'];
	if ($m_income <= $incometbl && $m_income > $prevtblincome)
	{
		 $pph=$myshare;
	}
	$prevtblincome=$incometbl;
}
?>