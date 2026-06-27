<?php
ob_start();
session_start();
include("../dbcon.php");

$catqry=mysql_query("SELECT * FROM category WHERE status<>1 order by name");

while($catrs=mysql_fetch_object($catqry)){
	$copts.="<option value='".$catrs->ndex."'>".$catrs->name."";
}

$scatqry=mysql_query("SELECT * FROM category_sub WHERE status<>1 order by name");
	
while($scatrs=mysql_fetch_object($scatqry)){
	$scopts.="<option value='".$scatrs->ndex."'>".$scatrs->name."";
}
?>
     <h2>Report by Category</h2>
     <form name="frmrpt" action="reports/output/category.php" method="post" target="foo" onsubmit="window.open('', 'foo', 'width=1000,height=600,status=yes,resizable=yes,scrollbars=yes');">
     <table width="50%">
	  <tr>
	  	<td>Select Category:</td>
		<td><select name="catd"><option value="all"> - All Category -<?php echo $copts;?></select></td>
	</tr>
	<tr>
	  	<td>Select Sub Category:</td>
		<td><select name="subcatd"><option value="all"> - All Sub Category -<?php echo $scopts;?></select></td>
	</tr>
	<tr>
	  <td><input type="checkbox" name="eksel"> Result to Excel </td>
	  <td><input type="submit" value="Submit"></td>
	  </tr>
      </table>
	<h2>&nbsp;</h2>
     </form>




