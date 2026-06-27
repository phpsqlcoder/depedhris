
    <div id="footer" class="grid_12">
    
        <p>&copy; Copyright 2011 DAVAO DOCTORS HOSPITAL </p>
        
	</div>   
	
	<?php
	date_default_timezone_set("Asia/Manila");
	$insert =  mysql_query("INSERT INTO viewingLogs(ipAddress,pageViewed,user,newDateViewed) VALUES('".$_SERVER['REMOTE_ADDR']."','".$_SERVER['PHP_SELF']."','".$_SESSION['fullName']."','".date('Y-m-d H:i:s')."')");
//echo "INSERT INTO viewingLogs(ipAddress,pageViewed,dateViewed,user,newDateViewed) VALUES('".$_SERVER['REMOTE_ADDR']."','".$_SERVER['PHP_SELF']."','".$_SESSION['fullName']."','".date('Y-m-d H:i:s')."')";
	?>