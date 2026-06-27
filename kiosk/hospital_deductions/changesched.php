<?php 
ob_start();
include("../dbcon.php");
include("../employeefunctions.php");
include('../payroll/payrollfunctions.php');
include("newheader.php");
session_start();
if(!$_SESSION['ndex']){header("location:login.php");}
    $msg='';
if(isset($_GET['delete'])){
  $transfer = mysql_query("insert into kiosk_request_deleted (`ndex`, `tayp`, `empid`, `ref`, `date`, `status`, `request`, `approve1`, `approve2`, `remarks`, `isPending`)
    select `ndex`, `tayp`, `empid`, `ref`, `date`, `status`, `request`, `approve1`, `approve2`, `remarks`, `isPending` from kiosk_request where ndex='".$_GET['delete']."'
    ");
  $delete = mysql_query("delete from kiosk_request where ndex='".$_GET['delete']."'");
   $msg='<div class="alert alert-success">
                <strong>Success!</strong> The request has been deleted.
              </div>';
}

    $_GET['emp']=$_SESSION['ndex'];
    if(isset($_GET['act'])){
        date_default_timezone_set("Asia/Manila");
        if($_POST['dated']){
            $remrem = mysql_real_escape_string($_POST['aremarks']);
              if(strlen($remrem)<=0){
                die('Please Input Remarks. Click BACK button');
              }

            $ins=mysql_query("insert into kiosk_request
                (`tayp`, `empid`, `ref`, `date`, `status`,`request`,`remarks`,createdDate) VALUES
                ('Schedule','".$_GET['emp']."', '0', '".$_POST['dated']."','Save','".$_POST['shift']."','".mysql_real_escape_string($_POST['aremarks'])."','".date('Y-m-d H:i:s')."')");
            $logs = mysql_query("insert into kiosk_request_logs (action,timelog,request_id,user) VALUES ('Create Request','".date('Y-m-d H:i:s')."','".mysql_insert_id()."','".$_SESSION['eid']."')");
        }
        $msg='<div class="alert alert-success">
        <strong>Success!</strong> The request has been sent.
        </div>';
        header("location: changesched.php");
    }
    $optionsh='';
    $leave=mysql_query("SELECT name,ndex,
        CASE timeIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeIn),'%H:%i %p') END AS tymIn, 
        CASE breakOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakOut),'%H:%i %p') END as brekOut,
        CASE breakIn WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',breakIn),'%H:%i %p') END as brekIn,
        CASE timeOut WHEN '00:00:00' THEN '' ELSE DATE_FORMAT(CONCAT(CURDATE(),' ',timeOut),'%H:%i %p') END as tymOut,breakMinutes
        FROM shifting where status<>1 order by name");
    while($rsleave=mysql_fetch_object($leave)){
        $stymin=substr($rsleave->tymIn,0,2);
        $sbreakout=substr($rsleave->brekOut,0,2);
        $sbreakin=substr($rsleave->brekIn,0,2);
        $stymout=substr($rsleave->tymOut,0,2);
        if($stymin>=13){$ltymin=($stymin-12).substr($rsleave->tymIn,2);}else{$ltymin=$rsleave->tymIn;}
        if($sbreakout>=13){$lbreakout=($sbreakout-12).substr($rsleave->brekOut,2);}else{$lbreakout=$rsleave->brekOut;}
        if($sbreakin>=13){$lbreakin=($sbreakin-12).substr($rsleave->brekIn,2);}else{$lbreakin=$rsleave->brekIn;}
        if($stymout>=13){$ltymout=($stymout-12).substr($rsleave->tymOut,2);}else{$ltymout=$rsleave->tymOut;}
        if($lbreakout!=''){$brk="&nbsp;&nbsp;&nbsp; ".$lbreakout."&nbsp;&nbsp;&nbsp; ".$lbreakin;}
        else{$brk="";}
        $optionsh.="<option value='s".$rsleave->ndex."'>".$rsleave->name."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$ltymin."".$brk."&nbsp;&nbsp;&nbsp; ".$ltymout."";
    }
    $optionsh.="<option value='OFF'>OFF";
    $r=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_GET['emp'].""));

    ?>
    <div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
    <?php include "calendar.inc"; ?>
    <div class="main">
        <div class="container">
            <?php echo $msg; ?>
            <!-- BEGIN SIDEBAR & CONTENT -->
            <div class="row margin-bottom-40">
                <!-- BEGIN CONTENT -->
                <div class="col-md-12 col-sm-12">
                    <h1>Change Schedule</h1>
                    <form name="frmitem" id="frmitem">
                        <table cellpadding="5" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td> <input type="hidden" name="emp" value="<?php echo $_GET['emp'];?>">Select Date:<input type="Text" name="startDate" id="startDate" size="15" value="<?php echo $_GET['startDate'];?>"><a href="javascript:show_calendar('frmitem.startDate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a>
                                    <input type="submit" value="Change"><br><br></td>

                                </tr>
                            </form>
                            <?php if ($_GET['startDate']){ ?>

                            <?php  
                                $old_r = mysql_fetch_array(mysql_query("select * from kiosk_request where tayp='Schedule' and date='".$_GET['startDate']."' and empid='".$_GET['emp']."'"));
                                if($old_r['ndex']>0){
                                    echo '<h4 style="color:red;padding-left:400px;">You have already applied for Change Schedule on this date ('.$_GET['startDate'].').</h4>';
                                }
                                else{

                            ?>
                            <form method="post" id="frm_leave" action="changesched.php?act=send&emp=<?php echo $_GET['emp'];?>">
                                <tr>
                                    <td><br><br>
                                        <input type="hidden" name="dated" value="<?php echo $_GET['startDate'];?>">
                                    </td>
                                    <td><br><br><strong style="color:blue;font-size:15px;"><?php echo $_GET['startDate'];?></strong>&nbsp;&nbsp;&nbsp;<select name="shift"><?php echo $optionsh;?></select></td>
                                    <td valign="top"><br><br><textarea rows="5" cols="30" required name="aremarks" placeholder="Reason"></textarea></td>
                                    <td><br><br>
                                           <a href="#"  onclick='var result = confirm("Are you sure you want to continue?"); if(result){$("#frm_leave").submit();}'  class="btn green">Send Request</a>
                                    </td>
                                </tr>
                            </form>
                            <?php }} ?>

                        </table>






                        <br><br><br>

                        <div class="portlet box green">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-picture"></i>Filed Applications
                                </div>           
                            </div>
                            <div class="portlet-body">
                                <div class="table-scrollable">

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <td>Type</td>
                                                <td>Date Filed</td>
                                                <td>Date of Application</td>
                                                <td>Details</td>                    
                                                <td>Reason</td>
                                                <td>Comments</td>
                                                <td>Manager</td>
                                                <td>HR</td>
                                                 <td>Cancel</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 

                                            $xr=mysql_fetch_object(mysql_query("select * from employee where ndex=".$_GET['emp'].""));
                                            $xpq=mysql_query("Select * from kiosk_request where tayp='Schedule' and empid='".$_SESSION['ndex']."' order by ndex desc");
                                            while($xp=mysql_fetch_array($xpq)){  
                                                //$manager = ($xp['approve1'] == '1' ? '<a href="#" class="btn btn-xs green"><i class="fa fa-check"></i></a>' : '');
                                                $manager='';
  if($xp['isDisapproved'] == '1'){
    $manager='<a href="#/" title="'.$hist['remarks'].'">Disapproved</a>';
  }
  else{
   if($xp['approve1'] == '1'){
      $mnapp=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' and action='Approve Request' order by ndex desc limit 1"));
      $manager = '<a href="#" class="btn btn-xs green"><i class="fa fa-check"></i></a><br>'.date('M d h:i A',strtotime($mnapp['timelog']));
    }
    else{
      $manager='';
    }
  }
                                              if($xp['approve2'] == '1'){
    $hrapp=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' and action='Approve Request (HR)' order by ndex desc limit 1"));
    $hr='<a href="#" class="btn btn-xs green"><i class="fa fa-check"></i></a><br>'.date('M d h:i A',strtotime($hrapp['timelog']));
  }
  else{
    $hr='';
  }
                                                $str = substr($xp['request'], 1);
                                                if($xp['request']<>'OFF'){
                                                    $jk=mysql_fetch_array(mysql_query("select * from shifting where ndex='".$str."'"));
                                                    $jkk=$jk['name'].' '.$jk['timeIn'].' '.$jk['timeOut'];
                                                }
                                                else{
                                                    $jkk='OFF';
                                                }




                                                $log = mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' and action='Create Request'"));
                                                if($log['ndex']){
                                                    $date_created=$log['timelog'];
                                                }
                                                else{
                                                    $date_created='';
                                                }
                                                $hist=mysql_fetch_array(mysql_query("select * from kiosk_request_logs where request_id='".$xp['ndex']."' order by ndex desc limit 1"));
   $link="sched_edit.php";
    $cancel = ($xp['approve2'] == '1' ? '' : '<a href="#" onclick="deleter('.$xp['ndex'].');" class="btn btn-xs red">Cancel</a>');
                                                echo '<tr>
                                                <td>'.$xp['tayp'].'</td>
                                                <td>'.$date_created.'</td> 
                                                <td>'.$xp['date'].'</td>           
                                                <td>Change to: '.$jkk.' </td>           
                                                <td width="200">'.$xp['remarks'].'</td>
                                                <td><a href="#" style="color:red;" onclick=\'window.open("../online_apps/'.$link.'?id='.$xp['ndex'].'","displayWindow","toolbar=no,scrollbars=yes,width=1200,height=500")\';>'.$hist['remarks'].'</a></td>
                                                <td>'.$manager.'</td>
                                                <td>'.$hr.'</td>
                                                 <td>'.$cancel.'</td>
                                                </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>




                    </div>
                    <!-- END CONTENT -->
                </div>
                <!-- END SIDEBAR & CONTENT -->
            </div>
        </div>
        <?php include("newfooter.php");?>
        <script type="text/javascript">
  function deleter(x){
    var result = confirm("Are you sure you want to cancel this request?"); 
    if(result){
      window.location.href="changesched.php?delete="+x;
    }
    else{
      return false;
    }
  }
</script>