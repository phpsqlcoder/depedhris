<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
if($_SESSION['deptId']!=0){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
if(isset($_GET['act'])){

	$seq = 0;
	$delete = mysql_query("delete from templates_approvers where template_id='".$_GET['id']."'");
	for($x=1; $x<=30; $x++){

		$rem = 1;
		$fuser = $_POST['user'.$x];
		if($_POST['tayp'.$x] == 'dept'){
			$fuser = '';
		}
		if($_POST['tayp'.$x] == 'custom' && $_POST['user'.$x] == ''){
			$rem = 0;
		}

		if($rem == 1){
			$seq++;
			$can_override = 0;
			$is_role = 0;
			
			if(isset($_POST['allow_override'.$x])){
				$can_override = 1;
			}

			if($_POST['tayp'.$x] <> 'custom' && $_POST['tayp'.$x] <> 'dept'){
				
				$exp = explode("|", $_POST['tayp'.$x]);

				$fuser = $exp[0];			
				$is_role = $exp[1];
	
				$_POST['tayp'.$x] = 'custom';
			}

			$insert = mysql_query("insert into templates_approvers (`seq`, `user_id`, `type`, `template_id`, can_override, is_role, dept) VALUES 
				('".$seq."','".$fuser."','".$_POST['tayp'.$x]."','".$_GET['id']."','".$can_override."','".$is_role."','".$_POST['dept'.$x]."' )");
			
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


$roles = '';
$roles_q=mysql_query("select u.fullName as fname,r.* from template_roles r left join users u on u.ndex=r.user_id");
while($ro=mysql_fetch_array($roles_q)){	
	
	$roles.='<option value="'.$ro['user_id'].'|'.$ro['ndex'].'">'.$ro['role'].' - '.$ro['fname'].'</option>';
}

$deptq = mysql_query("select name from dept order by name");
$deptlist = '';
while($dq=mysql_fetch_array($deptq)){	
	$deptlist.='<option value="'.$dq['name'].'">'.$dq['name'].'</option>';
}
	
$assigned = '';
$assigned_t = 0;
$dd=mysql_query("select * from templates_approvers where template_id='".$_GET['id']."' order by seq");
while($d=mysql_fetch_array($dd)){
	$assigned_t++;
	$c_sel = '';
	$d_sel = '';
	$d_display = '';
	$is_allowed_override = '';
	$xroles = '';

	if($d['can_override'] == 1){
		$is_allowed_override = ' checked';
	}

	if($d['type'] == 'custom'){
		$c_sel = 'selected';
	}
	if($d['type'] == 'dept'){
		$d_sel = 'selected';
		$d_display = 'style="display:none;"';
	}
/*
	if($d['is_role'] > 0){
		$d_display = 'style="display:none;"';
		$xro=mysql_fetch_array(mysql_query("select u.fullName as fname,r.* from template_roles r left join users u on u.ndex=r.user_id where r.ndex='".$d['is_role']."'"));
	
		
		$xroles='<option value="'.$xro['user_id'].'|'.$xro['ndex'].'" selected>'.$xro['role'].' - '.$xro['fname'].'</option>';
	
	}
*/

	$us = mysql_fetch_array(mysql_query("select * from users where ndex='".$d['user_id']."' order by fullName"));

	$assigned.='
		<tr>
			<td><input type="hidden" name="seq'.$assigned_t.'" id="seq'.$assigned_t.'">'.$assigned_t.'</td>
			<td>
				<select name="tayp'.$assigned_t.'" id="tayp'.$assigned_t.'" onchange="tayp_change('.$assigned_t.')">
					<option value="custom" '.$c_sel.'> Custom </option>
					<option value="dept" '.$d_sel.'> Department Head </option>
					
				</select>
			</td>
			<td>
				<select name="dept'.$assigned_t.'" id="dept'.$assigned_t.'">
					<option value="" selected>'.$d['dept'].'</option>	
					'.$deptlist.'					
				</select>
			</td>
			<td>
				<select name="user'.$assigned_t.'" id="user'.$assigned_t.'" '.$d_display.'>
					<option value="'.$us['ndex'].'" selected>'.$us['fullName'].'</option>
					'.$users.'
				</select>
			</td>

			<td align="center">
				<input type="checkbox" name="allow_override'.$assigned_t.'" id="allow_override'.$assigned_t.'" '.$is_allowed_override.'>
			</td>
		</tr>
	';
}
$title = '';
if($_GET['id'] == 1){
	$title = 'E-Clearance';
}
if($_GET['id'] == 2){
	$title = 'Certificate of Employment';
}
if($_GET['id'] == 3){
	$title = 'Resignation / Retirement';
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
    <h2><?php echo $title; ?> Approval Template</h2>   
    <div>
    	<form action="template_approval.php?act=submit&id=<?php echo $_GET['id']?>" method="post">
    	<table width="60%" style="border-spacing: 20px;border-collapse: separate;">
    		<thead>
    			<tr>
    				<th>Seq #</th>
    				<th>Type</th>
    				<th>Dept</th>
    				<th>User</th>
    				<th align="center">Can Overide</th>
    			</tr>
    		</thead>
		<?php
		echo $assigned;
			for($x=($assigned_t + 1); $x<=30; $x++){
				echo '
					<tr>
						<td><input type="hidden" name="seq'.$x.'" id="seq'.$x.'">'.$x.'</td>
						<td>
							<select name="tayp'.$x.'" id="tayp'.$x.'" onchange="tayp_change('.$x.')">
								<option value="custom"> Custom </option>
								<option value="dept"> Department Head </option>								
							</select>
						</td>
						<td>
							<select name="dept'.$x.'" id="dept'.$x.'">
								<option value=""> - Select - </option>	
								'.$deptlist.'					
							</select>
						</td>
						<td>
							<select name="user'.$x.'" id="user'.$x.'">
								<option value=""> - Select - </option>
								'.$users.'
							</select>
						</td>

						<td align="center">
							<input type="checkbox" name="allow_override'.$x.'" id="allow_override'.$x.'">
						</td>
					</tr>
				';
			}
		?>
		</table>
		<br>
		<input type="submit" value="Save Template">
		</form>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
<script src="jquery.js"></script>
<script>
	function tayp_change(x){
		var v = $('#tayp'+x).val();
		
		if(v == 'custom'){
			$('#user'+x).show();			
		}
		else{
			$('#user'+x).hide();
		}
	}
</script>
</body>
</html>


