<?php
ob_start();
	include("../dbcon.php");
	include("../employeefunctions.php");
	if($_POST['txtid']){
		$qry=mysql_query("select ndex,employeeNo,employmentStatus,password from employee where isActive=1");
		while($r=mysql_fetch_object($qry)){
			$pword=$r->password;
			if(getID($r->employmentStatus,$r->employeeNo)==$_POST['txtid']){
				if($pword==$_POST['txtpword']){
					$v=1;
					$userid=$r->ndex;
				}
				else{$v=2;}
				break;
			}
			else{$v=0;}
		}
		if($v==0){$msg="Employee ID Not Found!";}
		elseif($v==2){$msg="Incorrect Password!";}
		elseif($v==1){
			session_start();
			$_SESSION=mysql_fetch_assoc(mysql_query("SELECT * FROM employee WHERE ndex=".$userid." and isActive=1",$conn));
			header("Location:menu.php");
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>HRIS - Davao Doctors Hospital</title>
<script type="text/javascript">
	function sabmet(){
		if(document.getElementById('txtid').value==''){document.getElementById('txtid').focus();}
		else if (document.getElementById('txtpword').value==''){document.getElementById('txtpword').focus();}
		else{document.frmlog.submit();}
	}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body onload="document.getElementById('txtid').focus()">
<div id="topPan"><img src="../images/kiosk.png" title="Green Solutions" alt="Davao Doctors Hospital" border="0" style="width:304px; height:100px; padding:0 0 0 6px;"/>
<img src="images/hris.png" style="width:704px; height:100px; padding:0 0 0 6px;">
</div>
<div id="bodyPan" style="position:absolute; top:145px; left:200px;">
	<table border="0" width="100%">
	<tr><td height="120" align="center">&nbsp;<font style="color:red;font-size:40px;font-weight:bold;"><?php echo $msg;?></font></td></tr>
	<tr>
		<td align="center">
			<div style=" -khtml-border-radius: 8px; border-radius: 8px;padding: 12px;width:500px;background-color:#FFFC17;">
			<form id="frmlog" name="frmlog" onsubmit="sabmet(); return false;" method="post" action="index.php">
				<table style="font-family:Arial Rounded MT Bold;font-size:35px;color:maroon;">
					<tr><td>ID:</td><td><input type="text" size="10" style="font-size:30px;" name="txtid" id="txtid"></td><td rowspan="2"><img src="../images/logkiosk.png" height="100" width="100" onclick="document.frmlog.submit();"></td></tr>
					<tr><td>Password:</td><td><input type="password" size="10" style="font-size:30px;" name="txtpword" id="txtpword"></td></tr>
					<input type="Submit" style="visibility:hidden;">
				</table>
			</form>
			</div>
		</td>
	</tr>
	<tr><td height="100" colspan="2">&nbsp;</td></tr>
</table>
</div>
</body>
</html>
