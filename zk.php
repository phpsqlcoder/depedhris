<?php
ob_start();
include_once("dbcon.php");
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}


if(isset($_GET['go'])){
	$cntr = 0;
	//$conn = mysqli_connect("localhost","root","","checker");
	
	$handle = fopen($_FILES['log']['tmp_name'], "r");
	if ($handle) {
    	while (($line = fgets($handle)) !== false) {
        	$d = preg_split('/\s+/', $line);

        	$stat = ($d[6] == 'I') ? 0:1; 
//echo "select * from hr_interface where log='".date('Y-m-d H:i:s',strtotime($d[1]." ".$d[2]))."' and dtrid='".$d[0]."'<br>";
        	$check_if_exist = mysql_num_rows(mysql_query("select * from hrinterface where log='".date('Y-m-d H:i:s',strtotime($d[1]." ".$d[2]))."' and dtrid='".$d[0]."'"));
			if($check_if_exist==0){
				
$insert = mysql_query("insert into hrinterface (dtrid, datelog, log, in_out, isProcessed, dateDownloaded)
				values('".$d[0]."','".date('Y-m-d',strtotime($d[1]." ".$d[2]))."','".date('Y-m-d H:i:s',strtotime($d[1]." ".$d[2]))."','".$stat."','0','".date('Y-m-d H:i:s')."')
				");


				$cntr++;
			}
        }
    }

    /*
	$cntr = 0;
	foreach($ats as $a){
		$check_if_exist = mysql_fetch_array(mysql_query("select * from hr_interface where log='".$a[3]."' and dtrid='".$a[1]."'"));
		if(!isset($check_if_exist)){
			
		
			$insert = mysql_query("insert into hr_interface (dtrid, datelog, log, in_out, isProcessed, dateDownloaded)
				values('".$a[1]."','".date('Y-m-d',strtotime($a[3]))."','".date('Y-m-d H:i:s',strtotime($a[3]))."','".$a[2]."','0','".date('Y-m-d H:i:s')."')
				");
		
			$cntr++;
		}
	}

	*/
	echo "Successfully downloaded ".$cntr." record/s";
	
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
		<h1> Upload Biometric Logs </h1>
		
<form action="zk.php?go=1" method="post" enctype="multipart/form-data">

	<table width="50%">
		<tr>
			<td>Select Log file:</td>
			<td><input type="file" name="log" id="log"></td>
		</tr>
		<tr><td><input type="submit"></td></tr>
	</table>
	
</form>
		<h2>&nbsp;</h2>
		<?php include "footer.php";?>
	</div>
</body>
</html>
