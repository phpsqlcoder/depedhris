<?php
ob_start();
session_start();
include("dbcon.php");
if($_GET['act']=='save'){
	$eed=mysql_query("SELECT * FROM `employee` where deptId=73 order by lastName,firstName");
	while($ed=mysql_fetch_object($eed)){
		$upd=mysql_query("update employee set approvingOfficer=".$_POST['ap'.$ed->ndex].",scheduler=".$_POST['au'.$ed->ndex]." where ndex=".$ed->ndex."");
	}
}
function guser($id){
	$u=mysql_fetch_object(mysql_query("select * from users where ndex=".$id.""));
	return "<option selected value='".$u->ndex."' selected>".$u->fullName."";
}

$ee=mysql_query("SELECT * FROM `employee` where deptId=73 and isACtive=1 order by lastName,firstName");
while($e=mysql_fetch_object($ee)){
	if($e->approvingOfficer==0){
		$ap="<option selected value='0'>- Select Authorizer -".$optionusers."";
	}
	else{
		$ap=guser($e->approvingOfficer).".$optionusers.";
	}
	if($e->scheduler==0){
		$au="<option selected value='0'>- Select Maker -".$optionusers."";
	}
	else{
		$au=guser($e->scheduler).".$optionusers.";
	}
	
	$empdata.="<tr><td>".$e->lastName.", ".$e->firstName." ".$e->middleName."</td><td><select name='au".$e->ndex."'>".$au."</select></td><td><select name='ap".$e->ndex."'>".$ap."</select></td></tr>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
	  <form action="tools_setmarketing.php?act=save" method="post" name="sadsa">
  		<table>
			<tr>
				<td>Employee</td>
				<td>Maker</td>
				<td>Authorizer</td>
			</tr>
			<tr><td colspan="3"><hr></td></tr>
			<?php echo $empdata;?>
			<tr><td colspan="3"><hr></td></tr>
			<tr><td><input type="Submit" value="SAVE"></td></tr>
		</table>
	</form>
	
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>

ss
</body>
</html>




