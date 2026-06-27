<?php
session_start();

ob_start();
include("dbcon.php");

$sql = mysql_query("select * from notifications order by ndex desc");

while($r = mysql_fetch_object($sql)){

	$ctr1s++;

	if ($ctr1s==2){
		$bgclr1s='#ffffff';
		$ctr1s=0;
	} else { 
		$bgclr1s='#F8F8AC';
	}


	$data.="<tr style='background-color:".$bgclr1s."'>
	<td>".$r->name."</td>
	<td>".$r->description."</td>
	<td><a href='#' title='Update Notification' onclick=\"window.location='tools_notifications.php?act=editnotication&id=".$r->ndex."'\"><img src='images/edit.png' height='15'></a>
	<a href='javascript:;' title='Delete Notification' onclick=\"notificationDelete(".$r->ndex.")\";><img src=\"images/delete.png\" height='15' width='15'></a>&nbsp;&nbsp;
	</td>
	</tr>";
}

if($_GET['act']=='editnotication'){
	$u = mysql_fetch_object(mysql_query("select * from notifications where ndex=".$_GET['id'].""));
}
elseif($_GET['act']=='upd'){

	$up = mysql_query("update notifications set name='".$_POST['notif_name']."',description='".$_POST['notif_desc']."' where ndex='".$_POST['ndex']."'");

	header("location:tools_notifications.php");
} 
elseif($_GET['act']=='addnew'){

	$addnew = mysql_query("insert into notifications (name,description)VALUES('".$_POST['notif_name']."','".$_POST['notif_desc']."')");

	header("location:tools_notifications.php");
} 
elseif($_GET['act'] == 'delnotification'){
	$del = mysql_query("delete from notifications where ndex = '".$_GET['id']."' ");
	header("location:tools_notifications.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>HRIS - Davao Doctors Hospital</title>
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<link href="css/facebox.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript">
		function notificationDelete(id){
			var r = confirm('Are you sure you want to delete this notification?');

			if(r == true){
				return window.location='tools_notifications.php?act=delnotification&id='+id;
			} else {
				return false;
			}
		}
	</script>
</head>

<body>
	<?php include "header.php";?>
	<div id="main_content_wrap" class="container_12">

		<div id="rcont">
			<h2><strong style="color:blue;">Notifications</strong></h2>
			<table width="120%">
				<tr>
					<td width='60%'>
						<table width="100%" align="center">
							<tr style="color:blue;font-weight:bold;">
								<td>Name</td>
								<td>Description</td>
								<td>Actions</td>
							</tr>
							<tr><td colspan=3><hr></td></tr>
							<?php echo $data;?>
						</table>
					</td>
					<td width='40%'>

						<?php if($_GET['act']=='editnotication'){?>
							<form action="tools_notifications.php?act=upd" method="post">
								<table width="100%"  style="color:maroon;">
									<tr>
										<td colspan="2"><h1>Update Notification</h1></td>
									</tr>
									<tr>
										<td>Name *<input type="hidden" name="ndex" value="<?php echo $_GET['id'];?>"></td>
										<td><input type="text" name="notif_name" value="<?php echo $u->name;?>"></td>
									</tr>
									<tr>
										<td>Description *</td>
										<td>
											<textarea name="notif_desc"><?php echo $u->description;?></textarea>
										</td>
									</tr>	

									<tr><td colspan=2 align="center"><input type="submit" value="UPDATE"></td></tr>
									<tr><td>&nbsp;</td></tr>
								</table>
							</form> 
						<?php }
						else {?>
							<form action="tools_notifications.php?act=addnew" method="post">
								<table width="100%"  style="color:maroon;" border="1">
									<tr>
										<td colspan="2"><h1>Add new notification</h1></td>
									</tr>
									<tr>
										<td>Name *</td>
										<td><input type="text" name="notif_name"></td>
									</tr>
									<tr>
										<td>Description *</td>
										<td>
											<textarea name="notif_desc"></textarea>
										</td>
									</tr>

									<tr><td colspan=2 align="center"><input type="submit" value="SAVE"></td></tr>
									<tr><td>&nbsp;</td></tr>
								</table>
							</form> 
						<?php }?>
					</td>
				</tr>
			</table>
			<h2>&nbsp;</h2>
			<?php include "footer.php";?>
		</div>


	</body>
</html>




