<?php
include("dcon.php");

mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");

session_start();

if($_GET['act'] =='mailsent'){
	$date = date('Y-m-d h:i:s');

	$target_file = 'attachments/mailattachments/' . basename($_FILES["attachment"]["name"]);
	move_uploaded_file($_FILES["attachment"]["name"], $target_file);

	mysql_query("insert into message_board(`to`,`from`,`subject`,`message`,`senddate`,`attachment`,`parent_id`,`is_user`,is_deleted`)VALUES('".$_POST['to']."','".$_POST['from']."','".$_POST['subject']."','".$_POST['message']."','".$date."','".basename($_FILES["attachment"]["name"])."',0,0,0)");

	header("location:mailbox.php");
}

$sql_to = mysql_query("select ndex,fullName from users order by fullName desc");
while($t = mysql_fetch_object($sql_to)){
	$userdata.="<option value=".$t->ndex.">".$t->fullName."</option>";
}

include("newheader.php");
?>

<style type="text/css">
	.box {
		position: relative;
		border-radius: 3px;
		background: #ffffff;
		margin-bottom: 20px;
		width: 100%;
		box-shadow: 0 1px 1px rgba(0,0,0,0.1);
	}

	.mailbox-read-message {
		padding: 10px;
	}

	.box-header {
		color: #444;
		display: block;
		padding: 10px;
		position: relative;
	}

	.box-footer {
		border-top-left-radius: 0;
		border-top-right-radius: 0;
		border-bottom-right-radius: 3px;
		border-bottom-left-radius: 3px;
		border-top: 1px solid #f4f4f4;
		padding: 10px;
		background-color: #fff;
	}

	.box-body {
		border-top-left-radius: 0;
		border-top-right-radius: 0;
		border-bottom-right-radius: 3px;
		border-bottom-left-radius: 3px;
		padding: 10px;
	}

	.mailbox-read-info {
		border-bottom: 1px solid #f4f4f4;
		padding: 10px;
	}
	.mailbox-controls {
		padding: 5px;
	}

</style>
<!-- Main content -->
<section class="content">
	<div class="row">
		<form action="reply_mail.php?act=mailsent" method="post" enctype="multipart/form-data">
			<div class="col-md-3">
				<a href="compose.html" class="btn btn-primary btn-block margin-bottom">Compose</a>
				<br>
				<div class="box box-solid">
					<div class="box-header with-border">
						<div class="box-tools"></div>
					</div>
					<div class="box-body no-padding">
						<ul class="nav nav-pills nav-stacked">
							<li class="active">
								<a href="mailbox.php"><i class="fa fa-inbox"></i> Inbox <span class="label label-primary pull-right">12</span></a>
							</li>
							<li><a href="sentmailbox.php"><i class="fa fa-envelope-o"></i> Sent</a></li>
							<li><a href="trashmailbox.php"><i class="fa fa-trash-o"></i> Trash</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Compose New Message</h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<input type="hidden" name="from" value="<?php echo $_SESSION['ndex']; ?>">
							<select class="form-control" name="to">
								<?php echo $userdata; ?>
							</select>
						</div>
						<div class="form-group">
							<input class="form-control" name="subject" placeholder="Subject:">
						</div>
						<div class="form-group">
							<textarea name="message" id="compose-textarea" class="form-control" style="height: 300px"></textarea>
						</div>
						<div class="form-group">
							<div class="btn btn-default btn-file">
								<input type="file" name="attachment">
							</div>
						</div>
					</div>
					<div class="box-footer">
						<div class="pull-right">
							<button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
						</div>
						<a href="mailbox.php" class="btn btn-default"><i class="fa fa-times"></i> Discard</a>
					</div>
				</div>
			</div>
		</form>
	</div>
</section>

<?php include("newfooter.php");?>