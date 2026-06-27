<?php
session_start();

ob_start();
include("dbcon.php");
include("myfunctions.php");
include("scripts/scripts.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
		function addrow(ndex,ndexadd){
			
				$(window['dev' + ndex]).style.display='block';
				$(window['cn' + ndexadd]).style.display='none';
				$(cntr).value=parseInt($(cntr).value)+1;
		}
</script>  
<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
</head>

<body>
<?php include "headerperdept.php";?>
<form id="frmrpt" name="frmrpt"></form>
<div id="main_content_wrap" class="container_12">
<div id="rcont">
  <h2>Reports</h2>
<table width="100%">
  <tr>
  	<td style="width:50px;">&nbsp;</td>
    <td>
      <table style="color:maroon;font-weight:bold;font-size:12px;">
	
	<tr style="height:30px"><td><a href="#" onclick="report('late_monthlydept');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Late Report</a></td></tr>

  <tr style="height:30px"><td><a href="#" onclick="window.open('tools_reportshiftingperdept.php','displayWindow','toolbar=no,scrollbars=yes,width=1500,height=600');" style="text-decoration:none;"><img src="images/usercontract.png" height="15" width="15">&nbsp;Schedule Summary</a></td></tr>
      </table>
    </td>
  
  </tr>
</table> 
  <h2>&nbsp;</h2>
</div>
          <?php include "footer.php";?>
    
  </div>



</body>
</html>


