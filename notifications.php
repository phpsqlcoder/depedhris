<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("dbcon.php");

$qry="SELECT * FROM evoice_notifications";
if($_GET['act']=='search'){
	
		$qry.=" WHERE receiver_id = '".$_POST['ser']."'";
	
}

elseif($_GET['act']=='adnew'){


	$qr=mysql_query("insert into evoice_notifications (msg,sender_id,receiver_id,sent_date,tayp,`table`,is_hr_user)
	values
	('".$_POST['msg']."','".$_SESSION['ndex']."','".$_POST['emp']."','".date('Y-m-d H:i:s')."','".$_POST['tayp']."','users',1)");
	//echo "insert into evoice_notifications (msg,sender_id,receiver_id,sent_date,tayp,table)values('".$_POST['msg']."','".$_SESSION['ndex']."','".$_POST['emp']."','".date('Y-m-d H:i:s')."','".$_POST['tayp']."','users')";
	//die();
	//header("Location:notifications.php");
}
$qry.=" order by ndex desc";
//echo $qry;
$cat=mysql_query($qry);
while($c=mysql_fetch_object($cat)){
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#fdecf2';}
	if($c->is_hr_user == 1){
		$sender = mysql_fetch_array(mysql_query("select * from users where ndex='".$c->sender_id."'"));
		$sender_name = $sender['fullName'];
	}
	else{
		$sender = mysql_fetch_array(mysql_query("select * from employee where ndex='".$c->sender_id."'"));
		$sender_name = $sender['firstName']."  ".$sender['lastName'];
	}
	$receiver = mysql_fetch_array(mysql_query("select * from employee where ndex='".$c->receiver_id."'"));
	$data.="<tr style='font-size:12px;background-color:".$bgclr1s.";color:black;'>
			
				<td>".$receiver['firstName']." ".$receiver['middleName']." ".$receiver['lastName']."</td>
				<td>".$c->tayp."</td>				
				<td>".date('Y-m-d h:i A',strtotime($c->sent_date))."</td>
				<td>
					<a href='javascript:void(0)' onclick=\"$('#det".$c->ndex."').toggle();\"><img src='images/edit.png' title='View Details' height='20px;' width='20px;'></a>					
		</td>				
			</tr>
			<tr style='display:none;' id='det".$c->ndex."'>
				<td colspan='5'>
					<blockquote>
                 <p>
                     ".$c->msg."
                 </p>
                 <small>".$re['sender']." <cite title='Source Title'>".$sender_name."</cite></small>
              </blockquote>
				</td>
			</tr>";
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
      <script src="kiosk/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
</head>

<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">

<div id="rcont">
  <h2><strong>Notifications</strong></h2>
  <table width="831" border="0">
	
    <tr>
	  <td>&nbsp;</td>
      <td>Search Here:</td>
      <td><form id="form1" name="form1" method="post" action="notifications.php?act=search">
              <select name="ser">              
                <?php echo $optionemployee; ?>
              </select>
			  <input type="Submit" value="SEARCH">
      	</form>
	  </td>
    </tr>
  </table>
<br>

<table width="100%">
	<tr>
		<td style="width:50%;">
			<form name="frmadds" method="post" action="notifications.php?act=adnew" enctype="multipart/form-data">
			<table>
				<tr><td>&nbsp;</td></tr>
				
				<tr><td colspan="2"><h3>Add new notification</h3><br><br></td></tr>
				<tr>
					<td>Employee:</td>
					<td><select name="emp">              
                <?php echo $optionemployee; ?>
              </select><br><br></td>
				</tr>
				<tr>
					<td>Type:</td>
					<td><select name="tayp"> 
					<option value="">- Select Type -</option>             
                <option value="SSS Maternity">SSS Maternity</option>
                <option value="SSS Sickness">SSS Sickness</option>                       
              </select></td>
				</tr>
				<tr>
					<td valign="top">Message:</td>
					<td><textarea type="Text" name="msg" cols="10" row="30"> </textarea><br><br></td>
				</tr>
				<tr style="display:none;">
					<td>Attachments:</td>
					<td><input type="file" name="attached[]"></td>
				</tr>
				<tr><td colspan="2" align="center"><input type="Submit" value="SAVE"></td></tr>
				<tr><td>&nbsp;</td></tr>
			</table>
			</form>
		</td>
		<td style="width:50%;">
				<table width="100%">
				 	<tr style="font-weight:bold;color:blue;">
			
						<td>Recipient</td>
						<td>Type</td>
						<td>Date</td>
						<td>View</td>
					</tr>
					<tr><td colspan="5"><hr></td></tr>
					<?php echo $data;?>
				 </table>

		</td>
	</tr>
	
</table>


 
		
        <div class="container_12"><!--  PLACEHOLDER FOR FLOT - REMOVE IF NOT REQUIRED --></div>
        
        <div class="clearfix">&nbsp;</div>

        <!-- NOTIFICATION - USES CLASS OF "WARNING" and the CANHIDE ENABLES CICK TO FADE AWAY-->
        <div class="container_12">
           
<!--START NOTIFICATIONS  --><!-- INFORMATION - USES CLASS OF "IN2FORMATION" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- WARNING - USES CLASS OF "WARNING" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- SUCCESS - USES CLASS OF "SUCCESS" and the CANHIDE ENABLES CICK TO FADE AWAY--><!-- FAILURE - USES CLASS OF "FAILURE" and the CANHIDE ENABLES CICK TO FADE AWAY--></div>   
  	<!--END NOTIFICATIONS  -->
        
        
	<div class="clearfix">&nbsp;</div>
    
    
    
    
    </div>

<!-- START TABULAR DATA EXAMPLE -->
  <div class="container_12">
  
	<h2>&nbsp;</h2>

    <!-- END TABULAR DATA EXAMPLE -->

    <div class="clearfix">&nbsp;</div>
           
           
              
          
</div>

<div class="clearfix">&nbsp;</div>
<div class="container_12">
     


   <?php include "footer.php";?>    
  </div><!-- end content wrap -->


</body>
</html>


