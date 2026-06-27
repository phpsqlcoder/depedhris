<?php
ob_start();
include_once("dbcon.php");
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}

if(isset($_GET['bm'])){
	echo dl($_GET['bm']);
}

function dl($ip){
	include_once("dbcon.php");
	//$conn=mysql_connect("localhost","root","pangitka");
	//mysql_select_db("hris",$conn);
//$conn = mysqli_connect("localhost","root","","checker");
	require 'zk/zklibrary.php';
	$zk = new ZKLibrary($ip, 4370);
	$zk->connect();

	$ats = $zk->getAttendance();
	$cntr = 0;
	foreach($ats as $a){
//echo "select * from hrinterface where log='".$a[3]."' and dtrid='".$a[1]."'<br>";
		if(date('Y-m-d H:i:s',strtotime($a[3])) > $_GET['start_date'].' 00:00:00'){
			$check_if_exist = mysql_num_rows(mysql_query("select * from hrinterface where log='".$a[3]."' and dtrid='".$a[1]."'"));
			if($check_if_exist == 0){

				if($a[2] == 2){ $a[2]=0;}

				$insert = mysql_query("insert into hrinterface (dtrid, datelog, log, in_out, isProcessed, dateDownloaded)
					values('".$a[1]."','".date('Y-m-d',strtotime($a[3]))."','".date('Y-m-d H:i:s',strtotime($a[3]))."','".$a[2]."','0','".date('Y-m-d H:i:s')."')");

				$cntr++;
			}
		}
	}

	$zk->disconnect();

	return "Successfully downloaded ".$cntr." record/s";

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 

	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" 

	/>

	<title>HRIS - Davao Doctors Hospital</title>
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	<link href="css/facebox.css" rel="stylesheet" type="text/css" 

	/>
</head>

<body>
	<?php include "header.php";?>
	<div id="main_content_wrap" class="container_12">
		<h1> Download Biometric Logs </h1>
		<form action="zk.php" method="get">
			<input type="date" name="start_date" required value="<?php echo date("Y-m-d",strtotime("-2 days")); ?>">
			<select name="bm" required id="bm">
				<option value="">- Select -</option>
				<option value="10.110.0.18"> LANANG</option>
				<option value="10.120.0.18"> ECOLAND</option>

			</select>
			<input type="submit" value="Download Logs">
		</form>
		<h2>&nbsp;</h2>
		<?php include "footer.php";?>
	</div>
</body>
</html>
