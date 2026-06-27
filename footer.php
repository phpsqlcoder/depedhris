</main>
<!-- Bootstrap 5 Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
	
	<?php
	date_default_timezone_set("Asia/Manila");
	$insert =  mysql_query("INSERT INTO viewingLogs(ipAddress,pageViewed,user,newDateViewed) VALUES('".$_SERVER['REMOTE_ADDR']."','".$_SERVER['PHP_SELF']."','".$_SESSION['fullName']."','".date('Y-m-d H:i:s')."')");
//echo "INSERT INTO viewingLogs(ipAddress,pageViewed,dateViewed,user,newDateViewed) VALUES('".$_SERVER['REMOTE_ADDR']."','".$_SERVER['PHP_SELF']."','".$_SESSION['fullName']."','".date('Y-m-d H:i:s')."')";
	?>