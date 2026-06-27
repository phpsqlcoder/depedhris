<?php
ob_start();
session_start();

include("dbcon.php");
include("scripts/scripts.php");
include ("employeefunctions.php");
function number_to_words($number)
{
    if ($number > 999999999)
    {
       throw new Exception("Number is out of range");
    }

    $Gn = floor($number / 1000000);  /* Millions (giga) */
    $number -= $Gn * 1000000;
    $kn = floor($number / 1000);     /* Thousands (kilo) */
    $number -= $kn * 1000;
    $Hn = floor($number / 100);      /* Hundreds (hecto) */
    $number -= $Hn * 100;
    $Dn = floor($number / 10);       /* Tens (deca) */
    $n = $number % 10;               /* Ones */
	$cn = round(($number-floor($number))*100); /* Cents */
    $result = ""; 

    if ($Gn)
    {  $result .= number_to_words($Gn) . " Million";  } 

    if ($kn)
    {  $result .= (empty($result) ? "" : " ") . number_to_words($kn) . " Thousand"; } 

    if ($Hn)
    {  $result .= (empty($result) ? "" : " ") . number_to_words($Hn) . " Hundred";  } 

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
        "Nineteen");
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
        "Seventy", "Eigthy", "Ninety"); 

    if ($Dn || $n)
    {
       if (!empty($result))
       {  $result .= " and ";
       } 

       if ($Dn < 2)
       {  $result .= $ones[$Dn * 10 + $n];
       }
       else
       {  $result .= $tens[$Dn];
          if ($n)
          {  $result .= "-" . $ones[$n];
          }
       }
    }

    if ($cn)
    {
       if (!empty($result))
       {  $result .= ' and ';
       }
       $title = $cn==1 ? 'cent ': 'cents';
       $result .= strtolower(number_to_words($cn)).' '.$title;
    }

    if (empty($result))
    {  $result = "zero"; } 

    return $result;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Corrective Action Memo</title>
	<div id="overDiv" style="position:absolute;right: 220px; top: 200px; visibility:hidden; z-index:1000;"></div>
<?php include "calendar.inc"; ?>
</head>
<body style="font-family:Arial;font-size:15px;text-align:center;">
<?php 
if($_GET['act']){

	$e=mysql_fetch_object(mysql_query("select e.*,d.head as head from employee e left join dept d on d.ndex=e.deptId where e.ndex='".$_POST['id']."'"));
	if($_POST['tayp']=='Tardiness'){
		
?>	
<table width="100%" style="font-family:Arial;font-size:12px;">
	<tr><td colspan="2" style="color:gray;">Memorandum<br><br></td></tr>
	<tr><td>TO:</td><td><?php echo $e->lastName.", ".$e->firstName." ".$e->middleName;?><br><br></td></tr>
	<tr><td>FROM:</td><td>OFFICE OF THE DISCIPLINARY COMMITTEE<br><br></td></tr>
	<tr><td>DATE:</td><td><?php echo date('F m, Y')?><br><br></td></tr>
	<tr><td>SUBJECT:</td><td>NOTICE OF DISCIPLINARY ACTION<br><br></td></tr>
	<tr><td colspan="2"><hr><br></td></tr>
	<tr><td colspan="2">
		<p  style="width:100%;text-align: justify;text-justify: inter-word;">
		This memorandum is in relation to your alleged habitual tardiness (<?php echo $_POST['offense'];?> offense) which the discipline committee conducted an administrative hearing dated <?php echo date('F m, Y',strtotime($_POST['hearingdate']));?>.<br><br>

		We acknowledge receipt of your explanation letter dated <?php echo date('F m, Y',strtotime($_POST['expdate']));?>. In summary you have explained that <?php echo $_POST['exp']?>. After considering you explanation/s it has been established that you have incurred tardiness.<br><br>

		In view of the above mentioned, you are hereby given this STERN WARNING, Please be reminded that being punctual in going to work is one of the values being embraced by the Hospital. Repetition of any similar act determined of shall be dealt more severely.<br><br>
		 </p>

		Please be guided accordingly.<br><br><br><br>


		Prepared by:<br><br><br><br>

		Dustin V. Betonio
		Employee and Industrial Relations Specialist<br><br><br><br>

		Noted by:<br><br><br><br>

		Mirasol B. Tiu
		Human Resource Director<br><br>

		CC: Head<br>
		      &nbsp;&nbsp;&nbsp;<?php echo $e->head;?><br>
		      &nbsp;&nbsp;&nbsp;PATRA-DDH

	</td></tr>
</table>
<?php } 
else{?>
	<table width="100%" style="font-family:Arial;font-size:12px;">
		<tr><td colspan="2" style="color:gray;">Memorandum<br><br></td></tr>
	<tr><td>TO:</td><td><?php echo $e->lastName.", ".$e->firstName." ".$e->middleName;?><br><br></td></tr>
	<tr><td>DATE:</td><td><?php echo date('F m, Y')?><br><br></td></tr>
	<tr><td>SUBJECT:</td><td>NOTICE OF DISCIPLINARY ACTION<br><br></td></tr>
	<tr><td colspan="2"><hr><br></td></tr>
	<tr><td colspan="2">
		<p  style="width:100%;text-align: justify;text-justify: inter-word;">
		This memorandum is in relation to your tardiness (<?php echo $_POST['offense'];?> offense) please see attached time logs.<br><br>

		We acknowledge receipt of your explanation letter dated <?php echo date('F m, Y',strtotime($_POST['expdate']));?>. In summary you have explained that <?php echo $_POST['exp']?>. After considering your explanation, it does not exempt you from coming to work early.<br><br>

		In view of the above mentioned, you are hereby given this Suspension, Please be reminded that being punctual in going to work is one of the values being embraced by the Hospital. Repetition of any similar act determined of shall be dealt more severely. Your Suspension date/s will be scheduled on <?php echo date('F m, Y',strtotime($_POST['suspensiondate']));?>.<br><br>
	</p>
		 

		Please be guided accordingly.<br><br><br><br>


		Prepared by:<br><br><br><br>

		Name of Unit Manager/Head/Supervisor<br><br><br><br>

		CC: Head<br>
		      &nbsp;&nbsp;&nbsp;<?php echo $e->head;?><br>
		      &nbsp;&nbsp;&nbsp;PATRA-DDH

	</td></tr>
</table>
<?php }
}else{ ?>



<form name="frmcompo" method="post" action="tools_printcod.php?act=print">
<table cellpadding="0" cellspacing="0" style='font-family:Arial;font-size:13px;margin-left:20px;'>
	<tr><td>Employee:<select name="id"><?php echo $optionemployee;?></select> </td></tr>
	<tr><td>Type of memo<select name="tayp"><option value="Tardiness">Tardiness<option value="Suspension">Suspension</select>: </td></tr>
	<tr><td>Hearing Date: <input type="Text" name="hearingdate" id="hearingdate" size="15"><a href="javascript:show_calendar('frmcompo.hearingdate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
	<tr><td>Suspension Date: <input type="Text" name="suspensiondate" id="suspensiondate" size="15"><a href="javascript:show_calendar('frmcompo.suspensiondate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
	<tr><td>Explaination Letter Date: <input type="Text" name="expdate" id="expdate" size="15"><a href="javascript:show_calendar('frmcompo.expdate');" onMouseOver="window.status='Date Picker'; overlib(''); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="b_calendar.png" width=19 border=0></a></td></tr>
	
	<tr><td>Explaination: <textarea name="exp" cols="30" rows="10"></textarea></td></tr>
	<tr><td>No of offense:<input type="text" name="offense"> </td></tr>
	<tr><td><input type="submit" value="Print" style="font-size:25px;"></u><br><br></td></tr>
	
</table>
<?php } ?>
</body>
</html>
<?php ob_end_flush();?>