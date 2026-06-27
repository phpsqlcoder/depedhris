<?php
include("../employeefunctions.php");
include("dcon.php");
$tbl_name="fquestions"; // Table name 
session_start();
// Connect to server and select databse.
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");



if(strlen($_POST['concern'])>0){
	$insertq=mysql_query("INSERT INTO inquiries(`msg`, `sender`, `recipient`, `sent`, `sender_ip`, `category`, `sender_id`)
		VALUES('".$_POST['concern']."','".$_SESSION['lastName'].",".$_SESSION['firstName']." ".$_SESSION['middleName']."','HR','".date('Y-m-d H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".$_POST['category']."', '".$_SESSION['ndex']."')");
}
?>
<?php include("newheader.php");?>
<BR>
	<table width="100%" class="table" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
		<tr>
			<td><div class="alert alert-success">
								<strong>Success!</strong> Your concern has been submitted.
							</div>
</td>
		</tr>
	</table>
	<?php include("newfooter.php");?>