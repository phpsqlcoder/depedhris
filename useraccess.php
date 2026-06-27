<?php
	ob_start();
	include("dbcon.php");
	$id=$_GET['id'];
	$user=mysql_fetch_object(mysql_query("select * from users where ndex=".$id.""));
	if($_GET['act']=='ad'){
		$del=mysql_query("delete from user_access where userId=".$id."");
		$ps=mysql_query("select * from access");
		while($rs=mysql_fetch_object($ps)){
			//echo $rs->ndex."<br>";
			if($_POST[$rs->ndex]=='on'){
				$ins=mysql_query("insert into user_access(userId,accessId)VALUES('".$id."','".$rs->ndex."')");
			}
		}
		header("Location:useraccess.php?id=".$id."");
	}
	$p=mysql_query("select * from access");
	while($r=mysql_fetch_object($p)){
		$s=mysql_num_rows(mysql_query("select * from user_access where userid=".$id." and accessId=".$r->ndex.""));
		if($s==0){$chk="";}else{$chk=" checked";}
		$ctr1s++;
		if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		  $data.="<tr style='background-color:".$bgclr1s."'>
					<td><input type='Checkbox' name='".$r->ndex."' ".$chk."></td>
					<td>".$r->name."</td>
		</tr>";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>User Access</title>
</head>

<body>
<table><tr><td colspan="10" align="center" style="font-size:18px;color:maroon;">User Access</td></tr><tr><td>&nbsp;</td></tr></table>
<table style="font-size:12px;font-family:Arial;" width="50%">
	<tr style="color:black;font-weight:bold;">
		<td>User: </td>
		<td><?php echo $user->fullName;?></td>
	</tr>
</table>
<form action="useraccess.php?act=ad&id=<?php echo $id;?>" method="post">
<table style="font-size:12px;font-family:Arial;" width="100%">
	<tr style="color:blue;">
		<td>Allow</td>
		<td>Name</td>
	</tr>
	<tr><td colspan="2"><hr></td></tr>
	<?php echo $data;?>
	<tr><td colspan="2" align="center"><input type="Submit"></td></tr>
</table>
</form>

</body>
</html>
