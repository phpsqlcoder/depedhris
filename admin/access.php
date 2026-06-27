<?php
	ob_start();
	include("../dbcon.php");
	if($_POST['pageaddress']){
		$ins=mysql_query("insert into access(page,name)VALUES('".$_POST['pageaddress']."','".$_POST['pagenym']."')");
		header("Location:access.php");
	}
	$p=mysql_query("select * from access");
	while($r=mysql_fetch_object($p)){
		$data.="<tr>
					<td>".$r->name."</td>
					<td>".$r->page."</td>
		</tr>";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Access Page</title>
</head>

<body>
<table><tr><td colspan="10" align="center" style="font-size:18px;color:maroon;">Access Pages</td></tr><tr><td>&nbsp;</td></tr></table>
<form action="access.php" method="post">
<table style="font-size:12px;font-family:Arial;">
	<tr><td colspan="2" style="font-weight:bold;">Add New Page</td></tr>
	<tr>
		<td>Name:</td>
		<td><input type="Text" name="pagenym" size="15"></td>		
	</tr>
	<tr>
		<td>Address:</td>
		<td><input type="Text" name="pageaddress" size="30"></td>		
	</tr>
	<tr><td><input type="Submit"></td></tr>
	<tr><td>&nbsp;</td></tr>
</table>
</form>
<table style="font-size:12px;font-family:Arial;" width="50%">

	<tr style="color:blue;">
		<td>Name</td>
		<td>Address</td>
	</tr>
	<tr><td colspan="2"><hr></td></tr>
	<?php echo $data;?>
</table>


</body>
</html>
