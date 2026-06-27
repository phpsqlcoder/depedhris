<?php
session_start();

ob_start();
include("dbcon.php");

$sql = mysql_query("select m.*, e.firstName,e.lastName, n.name as notif from message_board m left join notifications as n on n.ndex = m.notification_id left join employee as e on e.ndex=m.to where m.to = '".$_SESSION['ndex']."' order by m.ndex desc");
while($r = mysql_fetch_object($sql)){

	$ctr1s++;

	if ($ctr1s==2){
		$bgclr1s='#ffffff';
		$ctr1s=0;
	} else { 
		$bgclr1s='#F8F8AC';
	}

	$data.="<tr style='background-color:".$bgclr1s."'>
	<td>".$r->firstName." ".$r->lastName."</td>
	<td>".$r->subject."</td>
	<td><a target='_blank' href='kiosk/attachments/mailattachments/".$r->attachment."'>".$r->attachment."</a></td>
	<td>".date('d M. Y h:i A',strtotime($r->senddate))."</td>
	<td><a href='tools_mailboxreply.php?id=".$r->ndex."'>View</a></td>
	</tr>";
}

if($_GET['act'] =='send'){
	$date = date('Y-m-d h:i:s');

	$target_file = 'kiosk/attachments/mailattachments/' . basename($_FILES["attachment"]["name"]);
	move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file);

	$addnew = mysql_query("insert into message_board(`to`,`from`,`subject`,`message`,`notification_id`,`parent_id`,`senddate`,`attachment`, `is_deleted`,`is_user`)VALUES('".$_POST['to']."','".$_SESSION['ndex']."','".$_POST['subject']."','".$_POST['message']."','".$_POST['notif']."',0,'".$date."','".basename($_FILES["attachment"]["name"])."',0,1)");

	header("location:tools_mailbox.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>HRIS - Davao Doctors Hospital</title>
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<link href="css/facebox.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<?php include "header.php";?>
	<div id="main_content_wrap" class="container_12">

		<div id="rcont">
			<h2><strong style="color:blue;">Message Board</strong></h2>
			<a style="float: right;" target="_blank" href="tools_mailcompose.php">Compose</a>
			<table width="100%">
				<tr>
					<td>
						<table width="100%" align="center">
							<tr style="color:blue;font-weight:bold;">
								<td>Receiver</td>
								<td>Subject</td>
								<td>Attachment</td>
								<td>Send Date</td>
								<td>Action</td>
							</tr>
							<tr><td colspan=5><hr></td></tr>
							<?php echo $data;?>
						</table>
					</td>
				</tr>
			</table>
			<h2>&nbsp;</h2>
			<?php include "footer.php";?>
		</div>


	</body>
</html>




