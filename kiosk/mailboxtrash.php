<?php
include("dcon.php");

mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");

session_start();

if($_GET['act'] == 'restore'){
	$del = mysql_query("update message_board set is_deleted = 0 where ndex = '".$_POST['mail_id']."' ");
	
	header("location:mailbox.php");
}

$mails_qry = mysql_query("select count(1) from `message_board` where `to` = ".$_SESSION['ndex']." and readdate IS NULL ");
$mails = mysql_fetch_array($mails_qry);
$inbox = $mails[0];

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

</style>
<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-3">
			<a href="mailboxcompose.php" class="btn btn-primary btn-block margin-bottom">Compose</a>
			<br>
			<div class="box box-solid">
				<div class="box-header with-border">
					<div class="box-tools">
					</div>
				</div>
				<div class="box-body no-padding">
					<ul class="nav nav-pills nav-stacked">
						<li>
							<a href="mailbox.php"><i class="fa fa-inbox"></i> Inbox <span class="pull-right"><?php echo $inbox; ?></span></a>
						</li>
						<li><a href="mailboxsent.php"><i class="fa fa-envelope-o"></i> Sent</a></li>
						<li class="active"><a href="mailboxtrash.php"><i class="fa fa-trash-o"></i> Trash</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="box">
				<div class="box-body no-padding">
					<div class="table-responsive mailbox-messages">
						<table class="table table-hover table-striped">
							<thead>
								<th>Sender</th>
								<th>Subject</th>
								<th>Send Date</th>
								<th>Actions</th>
							</thead>
							<tbody>
								<?php

								$sql="select m.*, u.fullName, n.name as notif from message_board m left join notifications as n on n.ndex = m.notification_id left join users as u on u.ndex=m.from where m.is_deleted = 1	 order by ndex desc";

								$result = mysql_query($sql);
								while($rows = mysql_fetch_array($result)){
									if($rows['attachment'] != ""){
										$attachment = '<i class="fa fa-paperclip"></i>';
									} else {
										$attachment = "";
									}
									?>
									<tr>
										<td class="mailbox-name"><?php echo $rows['fullName']; ?></td>
										<td class="mailbox-subject"><b><?php echo $rows['subject']; ?>
									</td>
									<td class="mailbox-date"><?php echo date('d M. Y h:i A',strtotime($rows['senddate'])); ?></td>
									<td>
										<a href="javascript:;" onclick="mail_restore(<?php echo $rows['ndex'] ?>);" title="Restor Message" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i></a>
									</td>
								</tr>

								<?php
							}
							mysql_close();
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</section>

<div class="modal fade" id="modal-restore">
  	<div class="modal-dialog">
	    <div class="modal-content">
		    <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title">Confirmation</h4>
		    </div>
		    <form action="mailboxtrash.php?act=restore" method="post">
		    	<div class="modal-body">
			        <p>You are about to restore this email. Do you want to continue?</p>
			        <input type="hidden" name="mail_id" id="mail_id">
			    </div>
			    <div class="modal-footer">
			        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
			        <button type="submit" class="btn btn-primary">Yes, Restore</button>
			    </div>
		    </form>
	    </div>
  	</div>
</div>

<?php include("newfooter.php");?>

<script>
	function mail_restore(id){
		$('#modal-restore').modal('show');
		$('.modal-body #mail_id').val(id);
	}
</script>