<?php
include("../dbcon.php");
//$r = mysql_query("SET NAMES 'utf8'");	
//$r = mysql_query("SET CHARACTER SET utf8");

if ($ajaxAct == 'displayeeloanlistList'){
	$sql = "SELECT l.*, e.lastName, e.firstName FROM loansetup l LEFT JOIN employee e ON e.ndex=l.employeeId WHERE e.firstName LIKE '%".strtoupper($searchText)."%' || e.lastName LIKE '%".$searchText."%' ORDER BY e.lastName";
	$rs = mysql_query($sql);
	$cnt = 0;
	while($dt = mysql_fetch_assoc($rs)){
		$cnt++;
		if ($cnt == 1){ $tr_bcg = "row1"; } else { $tr_bcg = "row2"; $cnt = 0;}
		if ($dt['posted'] != '1'){
			$edit = "<a href='?myact=edit&id=".$dt['ndex']."'>Edit</a> ";
			//$delete = "<a href='?pageact=delete&id=".$dt['ndex']."'>Delete</a> ";
			$post = "<a href='?myact=postloan&id=".$dt['ndex']."'>post</a> ";
			$post .= " | ".$edit." | ";
		} 
	$viewloans = "<a href='?myact=viewloans&id=".$dt['ndex']."'>view loans</a> ";
		$sRes .= "<tr class='".$tr_bcg."'>
			<td>".$dt['lastName'].", ".$dt['firstName']." </td>
			<td align='right'>".$dt['loanAmount']." </td>
			<td align='right'>".$dt['nOfDeduction']." </td>
			<td align='right'>".date('M d, Y',strtotime($dt['dedDateStart']))." </td>
			<td align='right'>".$post.$viewloans."</td>
		</tr>";
	}
} 

?>
<?if ($ajaxAct == 'displayeeloanlistList'){?>
	<table cellpadding="5" cellspacing="0" border="0" width="100%">
		<tr class="columnheader"><td>Name</td><td align="right">LoanAmount</td><td align="right">N of ded<br />(mos)</td><td  align="right">Start of Ded<br />(Date)</td><td align="right">Action</td></tr>
		<?=$sRes;?>
	</table>
<?} elseif($ajaxAct == 'displayUserAbilability'){
echo $availability;}?>

