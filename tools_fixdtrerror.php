<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
	include("dbcon.php");
	include("scripts/scripts.php");
	include ("employeefunctions.php");
//echo strlen('yyyy-mm-dd hh:mm:ss');die();
//echo substr('yyyy-mm-dd hh:mm:ss',0,10);die();
if($_GET['act']=='sabmet'){
	for($q=1;$q<=$_POST['valtot'];$q++){
		if($_POST['sel'.$q]!=$_POST['tm'.$q]){
			//echo $_POST['hid'.$q];
			$upd=mysql_query("update hrinterface set in_out='".$_POST['sel'.$q]."' where hrint_id='".$_POST['hid'.$q]."'");
		}
		if(strlen($_POST['tym'.$q])==19 && substr($_POST['tym'.$q],0,4)=='2012'){
			$inse=mysql_query("insert into hrinterface (`dtrid`, `datelog`, `log`, `in_out`, `isProcessed`)
			VALUES('".$_POST['dt'.$q]."','".substr($_POST['tym'.$q],0,10)."','".$_POST['tym'.$q]."','".$_POST['sel2'.$q]."','0')");
		}
	}
}

$empqry=mysql_query("select * from employee where employmentStatus not in ('Reliever','Project Based') ORDER BY lastName,firstName");
while($em=mysql_fetch_object($empqry)){
	$emplist.="<option value='".$em->biometricNo."'>".$em->lastName.", ".$em->firstName."";
}



$qr="";
//if($_POST['startdate']){$qr.=" and datelog>='".$_POST['startdate']."'";}else{$qr.=" and datelog>='2012-08-09'";}
//if($_POST['enddate']){$qr.=" and datelog<='".$_POST['enddate']."'";}else{$qr.=" and datelog<='2012-08-23'";}
if($_GET['act']=='go'){
$interface=mysql_query("select * from hrinterface where dtrid<>'' and datelog>='".$_POST['startdate']."' and datelog<='".$_POST['enddate']."' ORDER BY dtrid,log");
$a=0;
$v=0;
while($r=mysql_fetch_object($interface)){
$e=mysql_fetch_object(mysql_query("select * from employee where biometricNo='".$r->dtrid."'"));	
	$a++;
	$shiftsked=mysql_fetch_object(mysql_query("select e.*,s.name as shft from employee_shifting e left join shifting s on s.ndex=e.shiftingId where e.employeeId='".$e->ndex."' and e.approvedDate<>'0000-00-00 00:00:00' and '".$r->datelog."' between e.startDate and e.endDate"));
	$typ[$a]=$r->in_out;
	$nym[$a]=$e->lastName.",".$e->firstName."&nbsp;".$e->middleName;
	$dyt[$a]=$r->datelog;
	$dytlog[$a]=$r->log;
	$hrint[$a]=$r->hrint_id;
	$inout[$a]=$r->in_out;
	$shfts[$a]=$shiftsked->shft;
	//echo $r->log." - ".$typ[$a]." - ".$typ[$a - 1]."<br>";
	if($e->employmentStatus!='Reliever' &&  $e->employmentStatus!='Project Based' && $nym[$a]==$nym[$a - 1]){
		if($typ[$a]==$typ[$a - 1]){
			$v++;
			if($r->in_out==0){$lin="selected='selected'";$lout="";}else{$lin="";$lout="selected='selected'";}
			if($inout[$a - 1]==0){$lin2="Time In";}else{$lin2="Time Out";}
			$data.="<tr>
						<td>&nbsp;</td>
						<td>".$nym[$a - 1]."</td>
						<td>".$dyt[$a - 1]."</td>
						<td>".$dytlog[$a - 1]."</td>
						<td>".$shfts[$a - 1]."</td>
						<td>".$lin2."</td>
					</tr>";
			$data.="<tr>
						<td>".$v."</td>
						<td>".$e->lastName.",".$e->firstName."&nbsp;".$e->middleName."</td>
						<td>".$r->datelog."&nbsp;&nbsp;</td>
						<td>".$r->log."<input type='hidden' value='".$r->hrint_id."' name='hid".$v."'><input type='hidden' value='".$r->in_out."' name='tm".$v."'></td>
						<td>".$shiftsked->shft."</td>
						<td><select name='sel".$v."'>
							<option value='0' ".$lin.">Time In
							<option value='1' ".$lout.">Time Out
						</select><br><br></td>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td><input type='text' name='tym".$v."' value='yyyy-mm-dd hh:mm:00'>
						<input type='hidden' value='".$r->dtrid."' name='dt".$v."'>
						</td>
						<td><select name='sel2".$v."'>
							<option value='0'>Time In
							<option value='1'>Time Out</td>
			</tr>";
		}
	}
}
}
/*
$emp=mysql_query("select e.lastName,e.firstName,e.middleName,d.* from dailytimesummary d left join employee e on e.ndex=d.employeeId where d.isError>0 ".$qr." order by e.lastName,e.firstName,e.middleName,d.date");
$var=0;
while($r=mysql_fetch_object($emp)){
$var++;
	$ctr1s++;
	if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
	if($r->isError==1){$er='Duplicate Logs';}
	elseif($r->isError==2){$er='No TimeOut';}
	elseif($r->isError==3){$er='No Shifting';}
	$d.="<tr style='font-size:12px;background-color:".$bgclr1s."'>
			<td>".$var."</td>
			<td>".$r->lastName.",".$r->firstName."&nbsp;".$r->middleName."</td>
			<td>".$r->date."</td>
			<td>".$er."</td>
	</tr>";
}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HRIS - Davao Doctors Hospital</title>
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href="css/facebox.css" rel="stylesheet" type="text/css" />
    <div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
	<?php include "calendar.inc"; ?>
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>DTR Error</h2>   
    <div class="clearfix">
	<form name="frmempee" action="tools_fixdtrerror.php?act=go" method="post">
	<table width="100%" style="font-size:11px;">			
			<tr style="color:blue;font-size:11px;font-weight:bold;">
	<td>Start Date:&nbsp;&nbsp;<input type="Text" name="startdate" id="startdate" size="15"><a href="javascript:show_calendar('frmempee.startdate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
			<td>End Date:&nbsp;&nbsp;<input type="Text" name="enddate" id="enddate" size="15"><a href="javascript:show_calendar('frmempee.enddate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td>
			<td><input type="Submit" value="GO"><br><br></td>
		</tr></table>
	</form>
	<form name="frmemp" action="tools_fixdtrerror.php?act=sabmet" method="post">
		<table width="100%" style="font-size:11px;">			
			<tr style="color:blue;font-size:11px;font-weight:bold;">
				<td>Seq#</td>
				<td>Name</td>
				<td>Date</td>
				<td colspan="2">Time Log</td>
				<td>Type</td>
			</tr>
			<tr><td colspan='6'><hr></td></tr>
			
			<?php echo $data;?>
			<tr><td><input type="hidden" value="<?php echo $v;?>" name="valtot"><input type="Submit" value="SUBMIT"></td></tr>
		</table>
	</form>
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>
