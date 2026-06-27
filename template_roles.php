<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
if($_SESSION['deptId']!=0){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
if(isset($_GET['act'])){

	$seq = 0;
	$delete = mysql_query("delete from template_roles");
	for($x=1; $x<=20; $x++){

	
		if($_POST['role'.$x] <> '' || $_POST['user'.$x] <> ''){
		
		
			$insert = mysql_query("insert into template_roles (`user_id`, `role`) VALUES 
				('".$_POST['user'.$x]."','".$_POST['role'.$x]."')");
		}

		
	}
}
$users = '';
$users_q=mysql_query("select u.*,d.name as dept from users u left join dept d on d.ndex=u.deptId where deptId<>'' order by u.fullName");
while($r=mysql_fetch_array($users_q)){
	// $deptdata='';
	// $dex=explode(",", $r['deptId']);
	// foreach($dex as $dx){
	// 	$deptq=mysql_fetch_array(mysql_query("select * from dept where ndex='".$dx."'"));
	// 	if($deptq['ndex']>0){
	// 		$deptdata.=$deptq['name'].'<br>';
	// 	}
	// }
	$users.='<option value="'.$r['ndex'].'">'.$r['fullName'].'</option>';
}
	
$assigned = '';
$assigned_t = 0;

$dd=mysql_query("select * from template_roles");
while($d=mysql_fetch_array($dd)){
	$assigned_t++;
	
	$d_display = '';
	
	if($d['type'] == 'custom'){
		$c_sel = 'selected';
	}
	if($d['type'] == 'dept'){
		$d_sel = 'selected';
		$d_display = 'style="display:none;"';
	}
	$us = mysql_fetch_array(mysql_query("select * from users where ndex='".$d['user_id']."'"));

	$assigned.='
		<tr>
			<td>'.$assigned_t.'</td>
			<td><input type="text" name="role'.$assigned_t.'" id="role'.$assigned_t.'" value="'.$d['role'].'"></td>
			<td>
				<select name="user'.$assigned_t.'" id="user'.$assigned_t.'">
					'.$users.'
					<option selected value="'.$us['ndex'].'">'.$us['fullName'].'</option>
				</select>
			</td>
			
		</tr>
	';
}

$title = '';

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
    <h2><?php echo $title; ?> Approval Template Roles</h2>   
    <div>
    	<form action="template_roles.php?act=submit&id=<?php echo $_GET['id']?>" method="post">
    	<table width="60%" style="border-spacing: 20px;border-collapse: separate;">
    		<thead>
    			<tr>
    				<th>Seq #</th>
    				<th>Role</th>
    				<th>User</th>
    			</tr>
    		</thead>
		<?php
		echo $assigned;
			for($x=($assigned_t + 1); $x<=20; $x++){
				echo '
					<tr>
						<td>'.$x.'</td>
						<td>
							<input type="text" name="role'.$x.'" id="role'.$x.'">
								
						</td>
						<td>
							<select name="user'.$x.'" id="user'.$x.'">
								<option value=""> - Select - </option>
								'.$users.'
							</select>
						</td>

						
					</tr>
				';
			}
		?>
		</table>
		<br>
		<input type="submit" value="Save">
		</form>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
<script src="jquery.js"></script>
<script>
	function tayp_change(x){
		var v = $('#tayp'+x).val();
		
		if(v == 'dept'){
			$('#user'+x).hide();
		}
		else{
			$('#user'+x).show();
		}
	}
</script>
</body>
</html>


