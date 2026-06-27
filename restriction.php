<?php
function pagename() {
 	return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}
$c=mysql_fetch_object(mysql_query("select * from access where page='".pagename()."'"));
if($c->ndex){
	$s=mysql_num_rows(mysql_query("select * from user_access where accessId=".$c->ndex." and userId=".$_SESSION['ndex'].""));
	if($s==0){
		
		echo "<font color='red' size='4' face='Arial Rounded MT Bold'>You are not allowed to access this page!<br>
		Click <a style='color:blue;' onclick=\"javascript:history.go(-1);\">here</a> to go back
		</font>";
		die();
	}
}
?>