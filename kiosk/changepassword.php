<?php
include("../employeefunctions.php");

include("newheader.php");
?>
<?php
	if($_GET['act']=='change'){
		$ch=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_SESSION['ndex']." and (password='".$_POST['oldpword']."' OR password='".substr($_POST['oldpword'],2)."')"));
		if($ch->ndex){
			if($_POST['newpword']==$_POST['confirmpword']){
				$upd=mysql_query("update employee set password='".$_POST['newpword']."' where ndex=".$_SESSION['ndex']."");
				$msg="You have successfully changed your password!";
			}
			else{
				$msg="The new password and confirmation password did match!";
			}
		}
		else{
			$msg="The old password that you entered did not match to your current password!";
		}
	}
?>
<form action="changepassword.php?act=change" method="post">
	<table width="100%">
		<tr><td align="center" style="color:maroon;"><h1>Change Password</h1></td></tr>
		<tr>
		<tr><td>&nbsp;</td></tr>
		<td align="center">
			<div style=" -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;width:500px;background-color:#FFFC17;">
				<table style="font-weight:bold;font-size:18px;color:brown;">
					<tr><td>Old Password:</td><td><input type="password" id="oldpword" name="oldpword" style="font-size:20px;"></td></tr>
					<tr><td>New Password:</td><td><input type="password" name="newpword" style="font-size:20px;"></td></tr>
					<tr><td>Confirm Password:</td><td><input type="password" name="confirmpword" style="font-size:20px;"></td></tr>
					<tr><td colspan="2" align="center"><input type="submit" value="CHANGE" style="font-size:20px;"></td></tr>
				</table>
			</div>
		</td>
	</table>
	<table width=100%>
		<tr><td>&nbsp;</td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr align="center" style="color:red;font-size:18px;font-weight:bold;"><td><?php echo $msg;?></td></tr>
	</table>
</form>
	<?php include("newfooter.php");?>