<?php
// if(isset($_GET['bm'])){
// 	echo dl($_GET['bm']);
// }

if(isset($_GET['go'])){
	$cntr = 0;
	$conn = mysqli_connect("localhost","root","","checker");
	
	$handle = fopen($_FILES['log']['tmp_name'], "r");
	if ($handle) {
    	while (($line = fgets($handle)) !== false) {
        	$d = preg_split('/\s+/', $line);

        	$stat = ($d[6] == 'I') ? 0:1; 

        	$check_if_exist = mysqli_fetch_array(mysqli_query($conn,"select * from hr_interface where log='".date('Y-m-d H:i:s',strtotime($d[1]." ".$d[2]))."' and dtrid='".$d[0]."'"));
			if(!isset($check_if_exist)){
				$insert = mysqli_query($conn,"insert into hr_interface (dtrid, datelog, log, in_out, isProcessed, dateDownloaded)
				values('".$d[0]."','".date('Y-m-d',strtotime($d[1]." ".$d[2]))."','".date('Y-m-d H:i:s',strtotime($d[1]." ".$d[2]))."','".$stat."','0','".date('Y-m-d H:i:s')."')
				");

				$cntr++;
			}
        }
    }

    /*
	$cntr = 0;
	foreach($ats as $a){
		$check_if_exist = mysqli_fetch_array(mysqli_query($conn,"select * from hr_interface where log='".$a[3]."' and dtrid='".$a[1]."'"));
		if(!isset($check_if_exist)){
			
		
			$insert = mysqli_query($conn,"insert into hr_interface (dtrid, datelog, log, in_out, isProcessed, dateDownloaded)
				values('".$a[1]."','".date('Y-m-d',strtotime($a[3]))."','".date('Y-m-d H:i:s',strtotime($a[3]))."','".$a[2]."','0','".date('Y-m-d H:i:s')."')
				");
		
			$cntr++;
		}
	}

	*/
	echo "Successfully downloaded ".$cntr." record/s";
	
}



?>

<form action="index.php?go=1" method="post" enctype="multipart/form-data">

	<table width="50%">
		<tr>
			<td>Select Log file:</td>
			<td><input type="file" name="log" id="log"></td>
		</tr>
		<tr><td><input type="submit"></td></tr>
	</table>
	
</form>