    <div id="footer" class="grid_12">
    
        <p>&copy; Copyright 2011 DAVAO DOCTORS HOSPITAL </p>
        
	</div>   
	<?php
	$insert =  mysql_query("INSERT INTO viewingLogs(ipAddress,pageViewed) VALUES('".$_SERVER['REMOTE_ADDR']."','".$_SERVER['PHP_SELF']."')");
	?>