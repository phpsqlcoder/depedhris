<?php
$m_income=$income;
$qsss=mysql_query("SELECT * FROM tblsss ORDER BY ndex ASC",$conn);
while($datasss=mysql_fetch_array($qsss))
{
	$str_range=$datasss['str_range'];
	$end_range=$datasss['end_range'];
	$myshare=$datasss['e_share'];
	if ($m_income >= $str_range && $m_income <= $end_range)
	{
		$psss= $myshare;
	}
}
?>