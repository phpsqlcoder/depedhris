<?php
include("dcon.php");

mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");

session_start();

mysql_query("update message_board set readdate = '".date('Y-m-d h:i:s')."' where ndex = '".$_GET['id']."' ");

if($_GET['act'] =='mailsent'){
    $date = date('Y-m-d h:i:s');

    $target_file = 'kiosk/attachments/mailattachments/' . basename($_FILES["attachment"]["name"]);
    move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file);

    mysql_query("insert into message_board(`to`,`from`,`subject`,`message`,`parent_id`,`senddate`,`attachment`,`is_user`,`is_deleted`)VALUES('".$_POST['to']."','".$_SESSION['ndex']."','".$_POST['subject']."','".$_POST['message']."','".$_POST['parent_id']."','".$date."','".basename($_FILES["attachment"]["tmp_name"])."',0,0)");

    header("location:mailbox.php");
}


//get all the reply mails
$d = mysql_fetch_object(mysql_query("select m.*, n.name as notif from message_board as m left join notifications as n on n.ndex = m.notification_id where m.ndex = '".$_GET['id']."' "));

// get the parent mail
$parent_mail = mysql_fetch_object(mysql_query("select * from message_board where ndex = ".$d->parent_id." "));

// get user details if sender is user
$dta_user = mysql_fetch_object(mysql_query("select fullName, email from users where ndex = ".$d->to." "));
// get employee details if sender is employee
$dta_employee = mysql_fetch_object(mysql_query("select firstName,middleName,lastName,emailAddress from employee where ndex = ".$d->to." "));

if($d->is_user == 1){
    $email = $dta_user->email;
} else {
    $email = $dta_employee->emailAddress;
}

$mails_qry = mysql_query("select count(1) from `message_board` where `to` = ".$_SESSION['ndex']." and readdate IS NULL ");
$mails = mysql_fetch_array($mails_qry);
$inbox = $mails[0];

$qry = mysql_query("select count(1) from message_board where parent_id = ".$d->parent_id." ");
$count = mysql_fetch_array($qry);
$total = $count[0];



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

	.box-footer {
		border-top-left-radius: 0;
		border-top-right-radius: 0;
		border-bottom-right-radius: 3px;
		border-bottom-left-radius: 3px;
		border-top: 1px solid #f4f4f4;
		padding: 10px;
		background-color: #fff;
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
		<div class="col-md-3">
			<a href="mailboxcompose.php" class="btn btn-primary btn-block margin-bottom">Compose</a>
			<br>
			<div class="box box-solid">
				<div class="box-header with-border">
					<div class="box-tools"></div>
				</div>
				<div class="box-body no-padding">
					<ul class="nav nav-pills nav-stacked">
						<li class="active">
							<a href="mailbox.php"><i class="fa fa-inbox"></i> Inbox <span class="label label-primary pull-right"><?php echo $inbox; ?></span></a>
						</li>
						<li><a href="sentmailbox.php"><i class="fa fa-envelope-o"></i> Sent</a></li>
						<li><a href="trashmailbox.php"><i class="fa fa-trash-o"></i> Trash</a></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="col-md-9">
			<div class="box box-primary">
				<div class="box-body no-padding">
					<div class="mailbox-read-info">
                        <h3>Subject : <?php echo ucwords($parent_mail->subject); ?></h3>
                        <h5>From: <?php 
                                        if($parent_mail->is_user == 1){ 
                                            $dta = mysql_fetch_object(mysql_query("select * from users where ndex = ".$parent_mail->from." "));

                                            echo $dta->fullName.' ( <span class="text-primary">'.$dta->email.' )</span>'; 
                                        } else { 
                                            $dta = mysql_fetch_object(mysql_query("select * from employee where ndex = ".$parent_mail->from." "));

                                            echo $dta->emailAddress;
                                        } 
                                    ?>
                        </h5>
                        <p>Date : <?php echo date('d M. Y h:i A',strtotime($parent_mail->senddate)); ?></p>
                        <p>
                        <br>
                        <p><?php echo $parent_mail->message; ?></p>

                        <ul style="list-style:none;" class="mailbox-attachments clearfix">
                            <li>
                                <div class="mailbox-attachment-info">
                                    <?php if ($rows['attachment'] != ''): ?>
                                        <a target="_blank" href="attachments/mailattachments/<?php echo $rows['attachment']; ?>" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> <?php echo $rows['attachment'] ?></a>
                                    <?php else: ?>
                                        <p style="font-style: italic;"> **** No Attachment **** </p>
                                    <?php endif ?>
                                </div>
                            </li>
                        </ul>
                    </div>

					<?php 
                        if($total >= 1){
                            $sql="select * from message_board where parent_id = ".$d->parent_id." order by ndex asc ";

                            $result = mysql_query($sql);
                                while($rows = mysql_fetch_array($result)){?>
                                    <div class="mailbox-read-message">
                                        <?php 
                                            if($rows['is_user'] == 1){ 
                                                $dta = mysql_fetch_object(mysql_query("select * from users where ndex = ".$rows['from']." "));

                                                echo 'From :'.$dta->fullName.' <span class="text-primary">('.$dta->email.' )</span>'; 
                                            } else { 
                                                $dta = mysql_fetch_object(mysql_query("select * from employee where ndex = ".$rows['from']." "));

                                                echo 'From :'.$dta->firstName.' '.$dta->lastName.' <span class="text-primary">('.$dta->emailAddress.' )</span>';
                                            } 
                                        ?>
                                        <p>Date : <?php echo date('d M. Y h:i A',strtotime($rows['senddate'])); ?></p>
                                        <br>
                                        <p><?php echo $rows['message']  ; ?></p>
                                        <ul style="list-style:none;" class="mailbox-attachments clearfix">
                                            <li>
                                                <div class="mailbox-attachment-info">
                                                    <?php if ($rows['attachment'] != ''): ?>
                                                        <a target="_blank" href="attachments/mailattachments/<?php echo $rows['attachment']; ?>" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> <?php echo $rows['attachment'] ?></a>
                                                    <?php else: ?>
                                                        <p style="font-style: italic;"> **** No Attachment **** </p>
                                                    <?php endif ?>

                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <hr>
                                <?php
                            }
                        }
                    ?>
				</div>

				<?php if ($d->parent_id != 0): ?>
                    <form action="mailboxread.php?act=mailsent" method="post" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="parent_id" value="<?php echo $d->parent_id; ?>">
                                <input type="hidden" name="to" value="<?php echo $d->from; ?>">
                                <input type="hidden" name="subject" value="<?php echo ucwords($d->subject); ?>">
                            </div>
                            <div class="form-group">
                                <textarea name="message" id="compose-textarea" class="form-control" style="height: 150px"></textarea>
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
                    </form>
                <?php endif ?>
			</div>
		</div>
	</div>
</section>

<?php include("newfooter.php");?>