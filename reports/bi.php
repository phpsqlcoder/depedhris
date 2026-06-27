<?php
ob_start();
session_start();
if(!$_SESSION['ndex']){header("location:login.php");}
	include("../dbcon.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
    <div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "../calendar.inc"; ?>
</head>
<body>
<?php
$catqry=mysql_query("SELECT * FROM category WHERE status<>1 order by name");
	$copts="<option value=''> - Select Category -";
while($catrs=mysql_fetch_object($catqry)){
	$copts.="<option value='".$catrs->ndex."'>".$catrs->name."";
}

$scatqry=mysql_query("SELECT * FROM category_sub WHERE status<>1 order by name");
	$scopts="<option value=''> - Select Sub Category -";
while($scatrs=mysql_fetch_object($scatqry)){
	$scopts.="<option value='".$scatrs->ndex."'>".$scatrs->name."";
}
?>

     <h2>User Define Report</h2>
    
		<form name="frmrpt" action="reports/output/bi.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
		<table width="100%">
			<tr><td><strong style="color:maroon;">Title:</strong> <input type="Text" name="rpttitle" size="40"><input type="checkbox" name="eksel"> <strong style="color:maroon;">Result to Excel</strong></td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td> </td></tr>
			<tr>
				<td>
					<table width="70%" style="font-weight:bold;">
						<tr><td>&nbsp;</td></tr>
						<tr style="font-weight:bold;color:maroon;"><td><strong>Display</strong></td><td>Sort By</td><td>Filters</td></tr>
						<tr><td colspan="12"><hr></td></tr>
						<tr><td>&nbsp;</td></tr>
						<tr><td><input type="Checkbox" name="1" checked="checked">ID</td><td><input type="radio" name="ord" value="1"></td><td>&nbsp;</td></tr>
						  <tr><td><input type="Checkbox" name="2" checked="checked">Name</td><td><input type="radio" name="ord" value="2" checked="checked"></td><td><input type="text" name="tname"></td></tr>
						  <tr><td><input type="Checkbox" name="3">Employment Status</td><td><input type="radio" name="ord" value="3"></td><td><select name="tempstatus"><?php echo $optionemploymentstatus;?></select></td></tr>
						<tr><td><input type="Checkbox" name="4">Division</td><td><input type="radio" name="ord" value="4"></td><td><select name="tdivision"><?php echo $optiondivision;?></select></td></tr>
						  <tr><td><input type="Checkbox" name="5">Department</td><td><input type="radio" name="ord" value="5"></td><td><select name="tdept"><?php echo $optiondept;?></select></td></tr>
						  <tr><td><input type="Checkbox" name="6">Unit</td><td><input type="radio" name="ord" value="6"></td><td><select name="tunit"><?php echo $optionunit;?></select></td></tr>
						<tr><td><input type="Checkbox" name="7">Position</td><td><input type="radio" name="ord" value="7"></td><td><select name="tposition"><?php echo $optionposition;?></select></td></tr>
						  <tr><td><input type="Checkbox" name="8">Date Hired</td><td><input type="radio" name="ord" value="8"></td><td><input type="text" name="thired"><a href="javascript:show_calendar('employeefrm.dateHired');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
						  <tr><td><input type="Checkbox" name="9">Bank Account #</td><td><input type="radio" name="ord" value="9"></td><td><input type="text" name="tbank"></td></tr>
						<tr><td><input type="Checkbox" name="10">Locker #</td><td><input type="radio" name="ord" value="10"></td><td><input type="text" name="tlocker"></td></tr>
						  <tr><td><input type="Checkbox" name="11">Hobbies</td><td><input type="radio" name="ord" value="11"></td><td><input type="text" name="thobbies"></td></tr>
						  <tr><td><input type="Checkbox" name="12">Pay Type</td><td><input type="radio" name="ord" value="12"></td><td><select name="tpaytype"><?php echo $optionpaytype;?></select></td></tr>
						<tr><td><input type="Checkbox" name="13">Taxable</td><td><input type="radio" name="ord" value="13"></td><td><select name="ttaxable"><?php echo $optiontaxable;?></select></td></tr>
						  <tr><td><input type="Checkbox" name="14">Nick Name</td><td><input type="radio" name="ord" value="14"></td><td><input type="text" name="tnickname"></td></tr>
						  <tr><td><input type="Checkbox" name="15">Gender</td><td><input type="radio" name="ord" value="15"></td><td><select name="tsex"><?php echo $optionsex;?></select></td></tr>
						<tr><td><input type="Checkbox" name="16">Blood Type</td><td><input type="radio" name="ord" value="16"></td><td><select name="tbloodtype"><?php echo $optionbloodtype;?></select></td></tr>
						  <tr><td><input type="Checkbox" name="17">Civil Status</td><td><input type="radio" name="ord" value="17"></td><td><select name="tcivilstatus"><?php echo $optioncivilstatus;?></select></td></tr>
						  <tr><td><input type="Checkbox" name="18">Present Address</td><td><input type="radio" name="ord" value="18"></td><td><input type="text" name="taddress"></td></tr>
						<tr><td><input type="Checkbox" name="19">Email Address</td><td><input type="radio" name="ord" value="19"></td><td><input type="text" name="temail"></td></tr>
						  <tr><td><input type="Checkbox" name="20">Birth Date</td><td><input type="radio" name="ord" value="20"></td><td><input type="text" name="tbirth"><a href="javascript:show_calendar('employeefrm.dateHired');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
						  <tr><td><input type="Checkbox" name="21">Religion</td><td><input type="radio" name="ord" value="21"></td><td><select name="treligion"><?php echo $optionreligion;?></select></td></tr>
						<tr><td><input type="Checkbox" name="22">Home Phone #</td><td><input type="radio" name="ord" value="22"></td><td><input type="text" name="thomephone"></td></tr>
						  <tr><td><input type="Checkbox" name="23">Birth Place</td><td><input type="radio" name="ord" value="23"></td><td><input type="text" name="tbirthplace"></td></tr>
						  <tr><td><input type="Checkbox" name="24">SSS #</td><td><input type="radio" name="ord" value="24"></td><td><input type="text" name="tsss"></td></tr>
						<tr><td><input type="Checkbox" name="25">Mobile #</td><td><input type="radio" name="ord" value="25"></td><td><input type="text" name="tmobile"></td></tr>
						  <tr><td><input type="Checkbox" name="26">Nationality</td><td><input type="radio" name="ord" value="26"></td><td><select name="tnationality"><?php echo $optionnationality;?></select></td></tr>
						  <tr><td><input type="Checkbox" name="27">PHIC #</td><td><input type="radio" name="ord" value="27"></td><td><input type="text" name="tphic"></td></tr>
						<tr><td><input type="Checkbox" name="28">Pagibig #</td><td><input type="radio" name="ord" value="28"></td><td><input type="text" name="tpagibig"></td></tr>
						  <tr><td><input type="Checkbox" name="29">TIN</td><td><input type="radio" name="ord" value="29"></td><td><input type="text" name="ttin"></td></tr>
						  <tr><td><input type="Checkbox" name="30">Skills</td><td><input type="radio" name="ord" value="30"></td><td><input type="text" name="tskills"></td></tr>
						  <tr><td><input type="Checkbox" name="31">School(Elementary)</td><td><input type="radio" name="ord" value="31"></td><td><input type="text" name="telementary"></td></tr>
						  <tr><td><input type="Checkbox" name="32">School(Secondary)</td><td><input type="radio" name="ord" value="32"></td><td><input type="text" name="tsecondary"></td></tr>
						  <tr><td><input type="Checkbox" name="33">School(College)</td><td><input type="radio" name="ord" value="33"></td><td><input type="text" name="tcollege"></td></tr>
						  <tr><td><input type="Checkbox" name="34">Degree</td><td><input type="radio" name="ord" value="34"></td><td><input type="text" name="tdegree"></td></tr>
						  <tr><td><input type="Checkbox" name="35">Biometric Number</td><td><input type="radio" name="ord" value="35"></td><td><input type="text" name="tbionumber"></td></tr>
						  <tr><td><input type="Checkbox" name="36">Level</td><td><input type="radio" name="ord" value="36"></td><td><input type="text" name="tlevel"></td></tr>
						  <tr><td><input type="Checkbox" name="37">Union Member</td><td><input type="radio" name="ord" value="37"></td><td><select name="tunion"><option value="all">All<option value="1">YES<option value="0">NO</select></td></tr>
						  <tr><td><input type="Checkbox" name="38">Coop Member</td><td><input type="radio" name="ord" value="38"></td><td><select name="tcoop"><option value="all">All<option value="1">YES<option value="0">NO</select></td></tr>
						  
						  <tr><td><input type="Checkbox" name="39">First Name</td><td><input type="radio" name="ord" value="39"></td><td><input type="text" name="tfirst"></td></tr>
						  <tr><td><input type="Checkbox" name="40">Middle Name</td><td><input type="radio" name="ord" value="40"></td><td><input type="text" name="tmiddle"></td></tr>
						  <tr><td><input type="Checkbox" name="41">Last Name</td><td><input type="radio" name="ord" value="41"></td><td><input type="text" name="tlast"></td></tr>
 <tr><td><input type="Checkbox" name="42">Is Resigned</td><td><input type="radio" name="ord" value="42"></td><td><select name="tisresigned"><option value="1" selected="selected">NO<option value="0">YES</select></td></tr>
<tr><td><input type="Checkbox" name="43">Tax Status</td><td><input type="radio" name="ord" value="43"></td><td><input type="text" name="ttaxstatus"></td></tr>
<tr><td><input type="Checkbox" name="44">PRC no.</td><td><input type="radio" name="ord" value="44"></td><td><input type="text" name="ttaxstatus"></td></tr>
<tr><td><input type="Checkbox" name="45">Picture</td><td><input type="radio" name="ord" value="45"></td><td><input type="text" name="tpicture"></td></tr>
<tr><td><input type="Checkbox" name="46">Sub Category</td><td><input type="radio" name="ord" value="46"></td><td><select name="tsubcategory"><?php echo $scopts;?></select></td></tr>
<tr><td><input type="Checkbox" name="47">Category</td><td><input type="radio" name="ord" value="47"></td><td><select name="tcategory"><?php echo $copts;?></select></td></tr>
					</table>
				</td>
			</tr>
			
			<tr><td>&nbsp;</td></tr>
			<tr><td align="center"><input type="Submit" value="Generate"></td></tr>
		</table>
	</form>


</body>
</html>


