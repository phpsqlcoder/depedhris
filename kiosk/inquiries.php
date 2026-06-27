<?php 
ob_start();
include("../dbcon.php");
include("../employeefunctions.php");
include('../payroll/payrollfunctions.php');
include("newheader.php");
session_start();
if(!$_SESSION['ndex']){header("location:login.php");}
$msg='';
if(isset($_GET['sendreply'])){
$insertq=mysql_query("INSERT INTO inquiries_reply(`msg`, `sender`,  `sent`, `inquiry_id`)VALUES('".$_POST['reply']."','".$_SESSION['lastName'].",".$_SESSION['firstName']."','".date('Y-m-d H:i:s')."','".$_POST['inquiry_id']."')");
//header("Location:inquiries.php");
   echo '<script>window.location.href = "inquiries.php?success=ok";</script>';
exit();
}

$_GET['emp']=$_SESSION['ndex'];

if(isset($_GET['success'])){
   $msg='<div class="alert alert-info">
                <strong>Success!</strong> Your reply was successfully sent.
              </div>';
}
$r=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_GET['emp'].""));


?>
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
<div class="main">
  <div class="container">
    <?php echo $msg; ?>
    <!-- BEGIN SIDEBAR & CONTENT -->
    <div class="row margin-bottom-40">
        <div class="row">
          
          <div class="col-md-6 col-sm-6">
            <h2>Inquiries</h2>
              <div class="tab-content" style="padding:0; background: #fff;">
                  <!-- START TAB 1 -->
                  <div class="tab-pane active" id="tab_1">
                      <div class="panel-group" id="accordion1">
                          <?php
                          $q=mysql_query("select * from inquiries where sender_id='".$_GET['emp']."' ORDER BY sent DESC");
                          while($r=mysql_fetch_object($q)){

                              ?>   
                              <div class="panel panel-success">
                                  <div class="panel-heading">
                                      <h4 class="panel-title">
                                          <a href="#accordion<?php echo $r->ndex;?>" data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle collapsed">
                                              <?php $s="Anonymous sender"; if($r->sender){$s=$r->sender;}?>
                                              <?php echo date('F d,Y h:i A',strtotime($r->sent));?>
                                          </a>
                                      </h4>
                                  </div>
                                  <div class="panel-collapse collapse" id="accordion<?php echo $r->ndex;?>">
                                      <div class="panel-body">                                    
                                          <blockquote>
                                              <p>
                                                  <?php echo $r->msg;?>
                                              </p>
                                              <small><?php echo $s; ?> <cite title="Source Title"><?php echo date('F d,Y h:i A',strtotime($r->sent));?></cite></small>
                                          </blockquote>
                                          <?php
                                          $replies_query = mysql_query("select * from inquiries_reply where inquiry_id='".$r->ndex."' order by ndex");
                                          while($re = mysql_fetch_array($replies_query)){
                                              $tag_as_read = mysql_query("update inquiries_reply set is_read=1 where ndex='".$re['ndex']."'");
                                              echo '<blockquote>
                                              <p>
                                              '.$re['msg'].'
                                              </p>
                                              <small>'.$re['sender'].' <cite title="Source Title">'.date('F d,Y h:i A',strtotime($re['sent'])).'</cite></small>
                                              </blockquote>';
                                          }
                                          ?>

                                          <?php if($r->sender_id > 0){ ?>
                                              <div>

                                                  <form action="inquiries.php?sendreply=ok" method="post">
                                                      <input type="hidden" name="inquiry_id" value="<?php echo $r->ndex;?>">
                                                      <h4>Add Reply</h4>
                                                      <textarea class="form-control" name="reply" id="reply" cols="30" rows="3"></textarea>
                                                      <br>
                                                      <input type="submit" value="Send" class="btn btn-sm btn-success">
                                                  </form>
                                              </div>                      
                                          <?php } ?>    

                                      </div>
                                  </div>
                              </div>
                          <?php } ?>                          
                      </div>
                  </div>
                
              </div>
          </div>
          <div class="col-md-6 col-sm-6">
            <h2>Notifications</h2>
              <div class="tab-content" style="padding:0; background: #fff;">
                  <!-- START TAB 1 -->
                  <div class="tab-pane active" id="tab_1">
                      <div class="panel-group" id="accordion1">
                          <?php
                      
                          $q=mysql_query("select * from evoice_notifications where receiver_id='".$_GET['emp']."' ORDER BY sent_date DESC");
                          while($r=mysql_fetch_object($q)){
                              $tag_as_read = mysql_query("update evoice_notifications set is_read=1 where ndex='".$r->ndex."'");

                              $sender = mysql_fetch_array(mysql_query("select * from ".$r->table." where ndex='".$r->sender_id."'"));
                              ?>   
                              <div class="panel panel-success">
                                  <div class="panel-heading">
                                      <h4 class="panel-title">
                                          <a href="#accordions<?php echo $r->ndex;?>" data-parent="#accordions1" data-toggle="collapse" class="accordion-toggle collapsed">
                                              <?php $s="Anonymous sender"; if($r->sender){$s=$r->sender;}?>
                                              <?php echo $r->tayp;?>
                                              <span style="float:right;"><?php echo date('F d,Y h:i A',strtotime($r->sent_date));?></span>
                                          </a>
                                      </h4>
                                  </div>
                                  <div class="panel-collapse collapse" id="accordions<?php echo $r->ndex;?>">
                                      <div class="panel-body">                                    
                                          <blockquote>
                                              <p>
                                                  <?php echo $r->msg;?>
                                              </p>
                                              <small><?php 
							if($r->table == 'employee'){
								echo $sender['firstName']."  ".$sender['lastName']; 
							}
							else {echo $sender['fullName']; }
						?> </small>
                                          </blockquote>
                                            

                                      </div>
                                  </div>
                              </div>
                          <?php } ?>                          
                      </div>
                  </div>
                
              </div>
          </div>
      </div>

    </div>
    <!-- END CONTENT -->
  </div>
  <!-- END SIDEBAR & CONTENT -->
</div>

<?php include("newfooter.php");?>
<script type="text/javascript">
  function deleter(x){
    var result = confirm("Are you sure you want to cancel this request?"); 
    if(result){
      window.location.href="applydrd.php?delete="+x;
    }
    else{
      return false;
    }
  }
</script>