<?php
include("../employeefunctions.php");
include("dcon.php");
$tbl_name="fquestions2"; // Table name 
session_start();
// Connect to server and select databse.
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");
if(isset($_GET['act'])){
	$topic=$_POST['topic'];
	$detail=$_POST['detail'];
	$name=$_POST['name'];
	$email=$_POST['email'];

	$datetime=date("d/m/y h:i:s"); //create date time

	$sql="INSERT INTO $tbl_name(topic, detail, name, email, datetime)VALUES('$topic', '$detail', '$name', '$email', '$datetime')";
	$result=mysql_query($sql);
}
$sql="SELECT * FROM $tbl_name ORDER BY id DESC";
// OREDER BY id DESC is order result by descending

$result=mysql_query($sql);
$e=mysql_fetch_array(mysql_query("select * from employee where ndex='".$_SESSION['ndex']."'"));
include("newheader.php");
if(!isset($_GET['act'])){
?>
	
	<table width="400" border="0" align="center" cellpadding="0" cellspacing="1" class="table" bgcolor="#CCCCCC">
		<tr>
			<form id="form1" name="form1" method="post" action="new_topic.php?act=go">
				<input class="form-control" name="email" type="hidden" value="hr@ddh.com.ph" id="email" size="50" />
				<td>
					<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
						<tr>
							<td colspan="3"><h1>Create New Topic</h1> </td>
						</tr>
						<tr>
							<td width="14%"><strong>Topic</strong></td>
							<td width="2%">:</td>
							<td width="84%"><input class="form-control" name="topic" type="text" id="topic" size="50" /></td>
						</tr>
						<tr>
							<td valign="top"><strong>Detail</strong></td>
							<td valign="top">:</td>
							<td><textarea class="form-control" name="detail" cols="50" rows="3" id="detail"></textarea></td>
						</tr>
						<tr>
							<td><strong>Name</strong></td>
							<td>:</td>
							<td><input class="form-control" name="name" type="text" readonly value="<?php echo $e['firstName']." ".$e['lastName'];?>" id="name" size="50" /></td>
						</tr>
						
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td><input type="submit" class="btn blue" name="Submit" value="Submit" /> 
								<input type="reset" class="btn" name="Submit2" value="Reset" /></td>
							</tr>
						</table>
					</td>
				</form>
			</tr>
		</table>

<?php
 } else {
?>
		<div class="alert alert-success">
			<strong>Success!</strong> Your concern has been posted.
		</div>

<?php }
include("newfooter.php");?>