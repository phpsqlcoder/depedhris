<?php
include "dbcon.php";

//employees
$sql = "SELECT ndex, lastName as lname, firstName as fname FROM employee WHERE isActive=1 AND lastName<>'' AND ndex='".$_GET['id']."' ORDER BY lastName";
$rs =  mysql_query($sql,$conn);
while ($dt = mysql_fetch_object($rs)){
	$eelist .= "<option value='".$dt->ndex."'>".$dt->lname.", ".$dt->fname."</option>";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>View Biometric Logs</title>
	<link rel=stylesheet href="./mycssnew.css" type="text/css"> 
	<link rel=stylesheet href="./datepickercontrol.css" type="text/css"> 
	
	<script type="text/javascript" src="./scripts/lib/prototype.js"></script>
	<script type="text/javascript" src="./scripts/lib/datepickercontrol.js"></script>
	<script language="javascript">
			if (navigator.platform.toString().toLowerCase().indexOf("linux") != -1){
		 		document.write('<link type="text/css" rel="stylesheet" href="datepickercontrol_lnx.css">');
		 	}
		 	else{
		 		document.write('<link type="text/css" rel="stylesheet" href="datepickercontrol.css">');
		 	}
			function getHTML(fileNam,f,plchldr){
				var pars = '';
				if (f != 'displayFirst'){ 
					pars = $(f).serialize();} else { pars ='';	
				}
				pars = pars + '&ajaxAct=' + plchldr;
				var url = fileNam;
				var myAjax = new Ajax.Updater( plchldr,
																			 url, 
																			 { method: 'get', 
																			 					 parameters: pars,
																		 	 }
																		 );
			}
	</script>	
</head>
<body>
	<div class="main-container">
		<div class='menu-container'>
			<form method="post" id="myform">
				<table>
					<tr>
						<td>Employee Name</td>
						<td><select name="employeeID">	
								<?php echo $eelist;?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Date Start</td>
						<td><input type="text" name="dateStart" id="dateStart" size="14" datepicker="true" datepicker_format="YYYY-MM-DD" value="<?php echo date('Y-m-d',strtotime('-13 day',strtotime(date('Y-m-d'))));?>"></td>
					</tr>
					<tr>
						<td>Date End</td>
						<td><input type="text" name="dateEnd" id="dateEnd" size="14" datepicker="true" datepicker_format="YYYY-MM-DD" value="<?php echo date('Y-m-d');?>"></td>
					</tr>
					<tr><td colspan='2'><input type="button" value="View Biometric Logs" onClick="getHTML('bmlogs.php',this.form,'display-container');" onkeypress="getHTML('bmlogs.php',this.form,'display-container');"></td></tr>
				</table>
			</form>
			<br /><br /><br /><br />
			<fieldset>
		    <legend><i>about</i></legend>
		    In this page you can view all the logs downloaded from Biometric Machine <br /><br />
		  </fieldset>
		</div>
		<div class='display-container' id="display-container">
			<img src="images/ajax-loaderbar.gif" id="indicator" style="display:none"/>
		</div>
	</div>
</body>
</html>