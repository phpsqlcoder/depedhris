<?php
ob_start();
session_start();
include("../../employeefunctions.php");
include("../../dbcon.php");
$qry="SELECT * from kiosk_visit_login order by id desc";
//if($_POST['sex']=='on'){$cond.="'MALE'";}
//elseif($_POST['sex']=='on'){$cond.="'FEMALE'";}
     //$cond=rtrim($cond,",");
$exec=mysql_query($qry);
$var=0;
while($r=mysql_fetch_array($exec)){
     $var++;
     $ctr1s++;
     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
     $data.="<tr bgcolor='".$bgclr1s."'>
	       <td>".$var."</td>
		   <td>".$r['ip']."</td>
	       <td>".$r['datetime']."</td>
	       <td>".$r['user']."</td>
		  
     </tr>";
}
?>
     <?php include("../rptheader.php");?>
     <table width="80%" style="font-family:Arial;font-size:12px;">
	  <tr>
	       <td colspan="6" align="center" style="font-size:14px;font-weight:bold;">Kiosk Login Logs</td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td>Seq</td>
		   <td>IP</td>
	       <td>Datetime</td>
	       <td>User</td>
		   
	  </tr>
	  <tr><td colspan="3"><hr></td></tr>
	  <?php echo $data;?>
      </table>
	  <?php include("../rptfooter.php");?>




