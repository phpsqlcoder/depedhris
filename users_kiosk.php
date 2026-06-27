<?php
session_start();
//if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
//if($_SESSION['deptId']!=0){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.";die();}
//if($_SESSION['ndex']!=17){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.";die();}
ob_start();
include("dbcon.php");
include("scripts/scripts.php");
if($_GET['processqry']){
$qp=mysql_query("
  ALTER TABLE `users` ADD `isViewingaccess` INT NOT NULL;
	");

}
$sql=mysql_query("select u.*,d.name as dept from users u left join dept d on d.ndex=u.deptId");
while($r=mysql_fetch_object($sql)){
	$deptdata='';
	$dex=explode(",", $r->deptId);
	foreach($dex as $dx){
		$deptq=mysql_fetch_array(mysql_query("select * from dept where ndex='".$dx."'"));
		if($deptq['ndex']>0){
			$deptdata.=$deptq['name'].'<br>';
		}
	}
	
$ctr1s++;
if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
if($r->isApprovingOfficer==1){$ok='Authorizer';}else{$ok='Maker';}
  $data.="<tr style='background-color:".$bgclr1s."'>
	    <td>".$r->nym."</td>
	    <td>".$r->fullName."</td>
		<td>".$deptdata."</td>
	    <td><a href='#' onclick=\"window.location='users.php?act=pwordchange&id=".$r->ndex."'\"><img src='images/edit.png' height='15'></a>
		<a href='#' title='User Access' onclick=\"window.open('useraccess.php?id=".$r->ndex."','displayWindow','toolbar=no,scrollbars=yes,width=400,height=700')\";><img src=\"images/usercontract.png\" height='15' width='15'></a>&nbsp;&nbsp;
		</td>
  </tr>";
}
if($_GET['act']=='pwordchange'){
  $u=mysql_fetch_object(mysql_query("select u.*,d.name as dept from users u left join dept d on d.ndex=u.deptId where u.ndex=".$_GET['id'].""));
}
elseif($_GET['act']=='upd'){
 $dsave=mysql_query("select * from dept order by name");
  $depsaves="";
  while($ds=mysql_fetch_object($dsave)){
  	if($_POST[$ds->ndex]=='on'){$depsaves.=$ds->ndex.",";}
  }
  $depsaves=rtrim($depsaves,","); 
 // echo $depsaves."sssss";
 	//echo $_POST['approving'];
	if($_POST['approving']=='on'){$ap=1;}else{$ap=0;}
	if($_POST['viewing']=='on'){$apv=1;}else{$apv=0;}
	//echo $ap;
  $up=mysql_query("update users set email='".$_POST['emailadd']."',nym='".$_POST['uname']."',fullName='".$_POST['fname']."',pword='".$_POST['pword']."',deptId='".$depsaves."',isApprovingOfficer='".$ap."',isViewingaccess='".$apv."' where ndex='".$_POST['id']."'");
}
elseif($_GET['act']=='adnew'){
  $dsave=mysql_query("select * from dept order by name");
  $depsave="";
  while($ds=mysql_fetch_object($dsave)){
  	if($_POST[$ds->ndex]=='on'){$depsave.=$ds->ndex.",";}
  }
  $depsave=rtrim($depsave,","); 
  if($_POST['approvinga']=='on'){$ap=1;}else{$ap==0;}
   if($_POST['viewinga']=='on'){$apv=1;}else{$apv==0;}
  $addnew=mysql_query("insert into users (nym,fullName,pword,deptId,isApprovingOfficer,isViewingaccess,email)VALUES('".$_POST['unamea']."','".$_POST['fnamea']."','".$_POST['pworda']."','".$depsave."','".$ap."','".$apv."','".$_POST['emailadda']."')");
  header("location:users.php");
}
$dpqry=mysql_query("select * from dept order by name");
while($d=mysql_fetch_object($dpqry)){
	if($_GET['act']=='pwordchange'){
		$depar=explode(",",$u->deptId);
		//foreach($depar as $de){echo $de."<br>";}
		if(in_array($d->ndex,$depar)){$chkdefault="checked='checked'";}else{$chkdefault="";}
	}
	$chkbox.="<input type='checkBox' name='".$d->ndex."' ".$chkdefault.">".$d->name."<br>";
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

<div id="rcont">
  <h2><strong style="color:blue;">Users</strong></h2><?php if($_SESSION['nym']=='jhang'){?> <a href="users_payroll.php">Payroll Users</a><?php } ?>
  <table width="120%">
  	<tr>
		<td width='60%'>
			  <table width="100%" align="center">
			    <tr style="color:blue;font-weight:bold;">
			      <td>Username</td>
			      <td>Full Name</td>
			      <td>Dept</td>
				  <td>Access</td>
			      <td>Action</td>
			    </tr>
			    <tr><td colspan=4><hr></td></tr>
				  <?php echo $data;?>
			  </table>
		</td>
		<td width='40%'>
			
    <?php if($_GET['act']=='pwordchange'){?>
    <form action="users.php?act=upd" method="post">
      <table width="100%"  style="color:maroon;">
	<tr>
	  <td colspan="2"><h1>Update User</h1></td>
	</tr>
	<tr>
	  <td>Username:<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"></td>
	  <td><input type="text" name="uname" value="<?php echo $u->nym;?>"></td>
	</tr>
	<tr>
	  <td>Full name:</td>
	  <td><input type="text" name="fname" value="<?php echo $u->fullName;?>"></td>
	</tr>
	<tr>
	  <td>Email:</td>
	  <td><input type="email" name="emailadd" value="<?php echo $u->email;?>"></td>
	</tr>
	<tr>
	  <td>Password:</td>
	  <td><input type="password" name="pword" value="<?php echo $u->pword;?>"></td>
	</tr>
	<tr>
	  <td>Viewing Access Only:</td>
	  <td><input type="Checkbox" name="viewing" <?php if ($u->isViewingaccess==1){echo "checked='checked'";}else{echo "";}?>></td>
	</tr>
	<tr>
	  <td>Approving Officer:</td>
	  <td><input type="Checkbox" name="approving" <?php if ($u->isApprovingOfficer==1){echo "checked='checked'";}else{echo "";}?>></td>
	</tr>
	<tr>
	  <td>Dept Access:</td>
	  <td>
	  	<?php echo $chkbox;?>
	  </td>
	</tr>
	
	
	<tr><td colspan=2 align="center"><input type="submit" value="UPDATE"></td></tr>
	<tr><td>&nbsp;</td></tr>
      </table>
    </form> 
    <?php }
    else{?>
    <form action="users.php?act=adnew" method="post">
      <table width="100%"  style="color:maroon;" border="1">
	<tr>
	  <td colspan="2"><h1>Add new user</h1></td>
	</tr>
	<tr>
	  <td>Username:</td>
	  <td><input type="text" name="unamea"></td>
	</tr>
	<tr>
	  <td>Full name:</td>
	  <td><input type="text" name="fnamea"></td>
	</tr>
	<tr>
	  <td>Email:</td>
	  <td><input type="email" name="emailadda"></td>
	</tr>
	<tr>
	  <td>Password:</td>
	  <td><input type="password" name="pworda"></td>
	</tr>
	<tr>
	  <td>Viewing Access Only:</td>
	  <td><input type="Checkbox" name="viewinga"></td>
	</tr>
	<tr>
	  <td>Approving Officer:</td>
	  <td><input type="Checkbox" name="approvinga"></td>
	</tr>
	<tr>
	  <td>Dept Access:</td>
	  <td><?php echo $chkbox;?></td>
	</tr>
	
	<tr><td colspan=2 align="center"><input type="submit" value="SAVE"></td></tr>
	<tr><td>&nbsp;</td></tr>
      </table>
    </form> 
    <?php }?>
		</td>
	</tr>
  </table>
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>


