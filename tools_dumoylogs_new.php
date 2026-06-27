<?php
ob_start();
session_start();
//if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	//include("dbcon.php");
	$conn=mysql_connect("localhost","root","pangitka");
	mysql_select_db("hris",$conn);
	//$conn_dumoy=mysql_connect("192.168.2.2","root","root");
	//mysql_select_db("hris",$conn_dumoy);
	
	include("scripts/scripts.php");
	include ("employeefunctions.php");
if($_GET['act']=='sabmet'){
    // mysql_select_db("hris",$conn_dumoy);
     $dumoy=mysql_query("select * from hrinterface_dumoy_new where dtrid>804");
     while($d=mysql_fetch_object($dumoy)){
	  //mysql_select_db("hris",$conn_hr);
	  $chk=mysql_fetch_object(mysql_query("select * from hrinterface where dtrid=".$d->dtrid."
					      and datelog='".$d->datelog."' and log='".$d->log."' and in_out='".$d->in_out."'"));
	  if(!$chk->hrint_id){
	  	//echo $chk->hrint_id."<br>";
	      $insho=mysql_query("insert into hrinterface (`dtrid`, `datelog`, `log`, `in_out`, `isProcessed`)
				 VALUES(".$d->dtrid.",'".$d->datelog."','".$d->log."','".$d->in_out."','')
				 "); 
	  }
     }
     $msg="Successfully processed Dumoy timelogs!";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
    <div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Process Dumoy Timelogs</h2>   
    <div class="clearfix">
	<form name="frmemp" action="tools_dumoylogs_new.php?act=sabmet" method="post">
		<table width="800">
			 <tr><td style="color:red;font-size:14px;"><?php echo $msg;?></td></tr>
			<tr><td>Folder name: <input style='display:none;' type="text" name="fld"></td><td><input type="Submit" value="Process"></td></tr>
			
		</table>
	</form>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
