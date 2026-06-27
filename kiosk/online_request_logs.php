<?php
ob_start();
error_reporting(E_ERROR | E_PARSE);
session_start();
include("../dbcon.php");
include("../employeefunctions.php");
include('../payroll/payrollfunctions.php');

include("../myfunctions.php");

if(isset($_GET['act'])){
	date_default_timezone_set("Asia/Manila");
	$nym = $_SESSION['fullName'];
	if(strlen($nym) < 2){
		$nym = $_SESSION['firstName']." ".$_SESSION['middleName']." ".$_SESSION['lastName'];
	}

	$insert = mysql_query("insert into online_request_logs (user_id,created_at,action,request_id)
		values('".$nym."','".date('Y-m-d H:i:s')."','Send Response: ".$_POST['response']."','".$_GET['id']."')");
}

$arr=0;
$data='';
$coqry=mysql_query("select * from online_request_logs where request_id='".$_GET['id']."' order by created_at DESC");
//echo "select * from onlie_request_logs where request_id='".$_GET['id']."' order by created_at DESC";
while($co=mysql_fetch_array($coqry)){

	$data.='<tr>
		<td>'.$co['user_id'].'</td>
		<td>'.$co['created_at'].'</td>
		<td>'.$co['action'].'</td>
	</tr>';
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<html>
<head>
	<title>Request Logs</title>
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
<script>

</script>
</head>
<body>
<table style="font-family:Arial;font-size:12px;" width="100%">
	
	<tr><td align="center"><u><h1>Request Logs</u><br><font size="-1"></font></h1></td></tr>	
</table>


			<table style="font-family:Arial;font-size:12px;" width="100%">
				
<tr style="font-weight:bold;color:blue">
<td align="left">User</td>
<td align="left">Time Log</td>
<td align="left">Action</td>
</tr>
<tr>
	<td colspan="3"><hr></td>
</tr>
<?php echo $data; ?>
			</table>
		
	
	
<form method="post" action="online_request_logs.php?act=sendresponse&id=<?php echo $_GET['id'];?>" style="margin:0 120px 0 20px;">
	<h3 style="font-size:12px;font-weight:bold;">Send Response</h3>
	<textarea name="response" id="response" cols="50" rows="3" class="form-control"></textarea>
	<div><br>
		<input type="submit" value="Send Response" class="btn btn-info">
	</div>
</form>
</body>
</html>
<?php ob_end_flush();//alert(document.getElementById('sel369078').value?>