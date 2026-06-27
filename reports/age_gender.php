<?php
ob_start();
session_start();
?>
     <h2>Report by Age and Gender</h2>
     <form action="reports/output/age_gender.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
  
	 <input type="Hidden" id="cntr" name="cntr" value="1" name="cntr">
	 <font style="color:blue;font-weight:bold;">Age Bracket:</font><br><br>
	 <?php for($a=1;$a<=50;$a++){
	 		$display =  ($a == 1 ? 'block' : 'none');
	 ?>
	 	<div style="font-size:11px;display:<?php echo $display;?>;" id="dev<?php echo $a;?>">
			&nbsp;&nbsp;From: <input type="Text" name="fr<?php echo $a;?>" id="fr<?php echo $a;?>" size='3' style="text-align:right;"; value="0"> 
			&nbsp;&nbsp;To: <input type="Text" name="to<?php echo $a;?>" id="to<?php echo $a;?>" size='3' style="text-align:right;"; value="0"><br>
			<div id="cn<?php echo $a;?>" style="color:blue;font-size:10px;"><a href="#" onclick="addrow('<?php echo ($a+1)."','".$a;?>');" style="text-decoration:none;";>&nbsp;&nbsp;Add Group</a></div>
		</div>
	 <?php }?>
	 <br><br>
    <table width="40%">
	<tr>
	  <td><input type="checkbox" name="grap"> Include graph </td>
	</tr>
	<tr>
	  <td><input type="checkbox" name="dep"> Group by Division/Department </td>
	</tr>
	<tr>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	 </tr>
    </table>
	<h2>&nbsp;</h2>
     </form>




