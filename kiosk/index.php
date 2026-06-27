<?php 
//error_reporting(E_ALL);
ob_start();
include("../dbcon.php");
include("newheader.php");
if($_GET['act']=='registertoslide'){
  $ck = mysql_num_rows(mysql_query("select * from kiosk_registered where slideId='".$_GET['id']."' and empid='".$_SESSION['ndex']."'"));
  if($ck==0){
    date_default_timezone_set("Asia/Manila");
    $dd=mysql_query("insert into kiosk_registered (slideId,empid,regDate) VALUES ('".$_GET['id']."','".$_SESSION['ndex']."','".date('Y-m-d H:i:s')."')");
    header("Location: index.php?success=go");
  }
  else{
    header("Location: index.php?existed=go");
  }
}
?>

<div class="main">
  <div class="container">
    <?php
    if($_GET['success']){
      echo '<div class="alert alert-danger"><Strong>Success!</strong> You are now registered.</div>';
    }
    if($_GET['existed']){
      echo '<div class="alert alert-danger">You are already registered.</div>';
    }
    ?>
    <!-- BEGIN SIDEBAR & CONTENT -->
    <div class="row margin-bottom-40">
      <!-- BEGIN CONTENT -->
      <div class="col-md-12 col-sm-12">
        <div class="row">
          <div class="col-md-9 col-sm-9">
            <h1>Announcements & Events</h1>
          </div>
          <div class="col-md-3 col-sm-3">
            <h3>MEMORANDUM</h3>
          </div>
        </div>
        <div class="content-page">


          <div class="modal fade" id="mynotif" tabindex="-1" role="mynotif" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                  <h4 class="modal-title">Notifications</h4>
                </div>
                <div class="modal-body">
                  You have <?php echo $_GET['n'];?> new message/s 
                  <?php
                    
                  ?>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn default" data-dismiss="modal">Close</button>
                  <a href="inquiries.php" class="btn blue">Open</a>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>


          <div class="row">
            <!-- BEGIN LEFT SIDEBAR -->            
            <div class="col-md-9 col-sm-9 blog-item">
              <div class="blog-item-img">
                <!-- BEGIN CAROUSEL -->            
                <div class="front-carousel">
                  <div id="myCarousel" class="carousel slide" >
                    <!-- Carousel items -->
                    <div class="carousel-inner">
                      <?php 
                      $x=0;

                      $q=mysql_query("Select * from kiosk_slide where active=1 order by seq desc");
                      while($a=mysql_fetch_array($q)){
                        $x++;
                        $y='';
                        if($x==1){
                          $y="active";
                        }
                        $xxxxx='';
                        if($a['registration']==1){
                          if($_SESSION['ndex']){
                            $xxxxx='<br><a href="#" onclick=\'var result = confirm("Are you sure you want to join this event? By confirming, you have authorized the HRD to deduct the equivalent amount of one meal in case you fail to attend to the above mentioned activity. Thank you."); if(result){window.open("index.php?act=registertoslide&id='.$a['id'].'","_self");}\' class="btn green" style="width:100%"><span class="fa fa-edit"></span>&nbsp;Register</a>';
                          }
                          else{
                            $xxxxx='<br><a href="#" class="btn green" style="width:100%" onclick=\'alert("You need to login before you can register!");\'><span class="fa fa-edit"></span>&nbsp;Register</a>';
                          }

                        }
                        echo '<div class="item '.$y.'">
                        <img src="../excel/'.$a['image'].'" style="height:450px;" alt="">
                        '.$xxxxx.'

                        </div>';

                      } ?>

                    </div>
                    <!-- Carousel nav -->
                    <a class="carousel-control left" href="#myCarousel" data-slide="prev">
                      <i class="fa fa-angle-left"></i>
                    </a>
                    <a class="carousel-control right" href="#myCarousel" data-slide="next">
                      <i class="fa fa-angle-right"></i>
                    </a>
                  </div>                
                </div>
                <!-- END CAROUSEL -->             
              </div>

            </div>
            <!-- END LEFT SIDEBAR -->

            <!-- BEGIN RIGHT SIDEBAR -->            
            <div class="col-md-3 col-sm-3 blog-sidebar">                 
              <ul class="nav sidebar-categories margin-bottom-40">
                <?php 
                $xxx=0;
                $q=mysql_query("Select * from kiosk_form  order by ndex desc");
                while($w=mysql_fetch_array($q)){			
                  $xxx++;
                  if($xxx>10){
                    $clas=' class="docs hide"';
                  }
                  else{
                    $clas=' class="show"';
                  }
                  echo '<li '.$clas.'><a href="../excel/'.$w['document'].'" target="_blank">'.$w['name'].'</a></li>';

                } ?>

                <li id="showmore"><a href="#/" style="color:blue;" onclick="showm();">Show more..</a></li>
                <li id="showless" class="hide"><a href="#/" style="color:blue;" onclick="hidem();">Show less..</a></li>

              </ul>

            </div>
            <!-- END RIGHT SIDEBAR -->            
          </div>
        </div>
      </div>
      <!-- END CONTENT -->
    </div>
    <!-- END SIDEBAR & CONTENT -->
  </div>
</div>

<?php include("newfooter.php");?>

<script>
  function showm(){

    $(".docs").removeClass("hide");
    $("#showless").removeClass("hide");
    $("#showmore").addClass("hide");
  }
  function hidem(){

    $(".docs").addClass("hide");
    $("#showless").addClass("hide");
    $("#showmore").removeClass("hide");
  }
  <?php if($_GET['n'] > 0){ ?>
      jQuery(document).ready(function() {   
	  
        $('#mynotif').modal('show');
      });
    <?php }  ?>
</script>
