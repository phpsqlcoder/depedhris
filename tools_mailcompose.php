<?php
include("kiosk/dcon.php");

mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");

session_start();

if($_GET['act'] =='mailsent'){
    $date = date('Y-m-d h:i:s');

    $target_file = 'kiosk/attachments/mailattachments/' . basename($_FILES["attachment"]["name"]);
    move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file);

    mysql_query("insert into message_board(`to`,`from`,`subject`,`message`,`notification_id`,`senddate`,`attachment`,`parent_id`,`is_user`,`is_deleted`)VALUES('".$_POST['to']."','".$_SESSION['ndex']."','".$_POST['subject']."','".$_POST['message']."','".$_POST['notifid']."','".$date."','".basename($_FILES["attachment"]["name"])."',0,1,0)");

    header("location:tools_mailbox.php");
}

$sql_to = mysql_query("select ndex,fullName from users order by fullName desc");
while($t = mysql_fetch_object($sql_to)){
    $userdata.="<option value=".$t->ndex.">".$t->fullName."</option>";
}

$sql_notif = mysql_query("select ndex, name from notifications order by ndex desc");
while($n = mysql_fetch_object($sql_notif)){
  $notifdata.="<option value=".$n->ndex.">".$n->name."</option>";
}

?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>E-Voice</title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <meta content="Metronic Shop UI description" name="description">
    <meta content="Metronic Shop UI keywords" name="keywords">
    <meta content="keenthemes" name="author">

    <meta property="og:site_name" content="-CUSTOMER VALUE-">
    <meta property="og:title" content="-CUSTOMER VALUE-">
    <meta property="og:description" content="-CUSTOMER VALUE-">
    <meta property="og:type" content="website">
    <meta property="og:image" content="-CUSTOMER VALUE-"><!-- link to image for socio -->
    <meta property="og:url" content="-CUSTOMER VALUE-">

    <link rel="shortcut icon" href="favicon.ico">

    <!-- Fonts START -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|PT+Sans+Narrow|Source+Sans+Pro:200,300,400,600,700,900&amp;subset=all" rel="stylesheet" type="text/css">
    <!-- Fonts END -->

    <!-- Global styles START -->          
    <link href="kiosk/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="kiosk/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Global styles END --> 

    <!-- Theme styles START -->
    <link href="kiosk/assets/global/css/components.css" rel="stylesheet">
    <link href="kiosk/assets/frontend/layout/css/style.css" rel="stylesheet">
    <link href="kiosk/assets/frontend/layout/css/style-responsive.css" rel="stylesheet">
    <link href="kiosk/assets/frontend/layout/css/themes/green.css" rel="stylesheet" id="style-color">

    <link href="kiosk/assets/global/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet">
    <!-- Theme styles END -->
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
</head>
<body class="corporate" style="background-color:#eaf2dd;">
    <br>
    <div class="main">
        <div class="container">
            <section class="content">
                <div class="row">
                    <form action="mailcompose.php?act=mailsent" method="post" enctype="multipart/form-data">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Compose New Message</h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <select class="form-control" name="to">
                                            <?php echo $userdata; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control" name="notifid">
                                            <option value="">-- NONE --</option>
                                            <?php echo $notifdata; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" name="subject" placeholder="Subject:">
                                    </div>
                                    <div class="form-group">
                                        <textarea name="message" id="compose-textarea" class="form-control" style="height: 250px"></textarea>
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
                                    <a href="tools_mailbox.php" class="btn btn-default"><i class="fa fa-times"></i> Discard</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <center><p>&copy; Copyright 2011 DAVAO DOCTORS HOSPITAL</p></center>
            </section>

        <script src="kiosk/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>   
        <script src="kiosk/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>      

        <script src="kiosk/assets/global/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <script type="text/javascript">
            $(function () {
                $("#compose-textarea").wysihtml5();
            });
        </script>
    </body>
</html>