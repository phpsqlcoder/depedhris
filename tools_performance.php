<?php
ob_start();
session_start();
include("dbcon.php");
include("myfunctions.php");
include("employeefunctions.php");
$updatedb=mysql_query("ALTER TABLE  `performance` CHANGE  `first`  `first` DECIMAL( 8, 4 ) NOT NULL ,
CHANGE  `second`  `second` DECIMAL( 8, 4 ) NOT NULL ;
");
$sql="";
if($_GET['yer']){

if(isset($_GET['act'])){
	
	$asq=mysql_query("select * from employee where isActive=1  order by lastName,firstName");
	while($as=mysql_fetch_object($asq)){
	//echo $_POST['f'.$as->ndex]."a<br>";
	//echo $_POST['s'.$as->ndex]."a<br>";
		$uds=mysql_query("update performance set first='".$_POST['f'.$as->ndex]."',second='".$_POST['s'.$as->ndex]."' where employeeId='".$as->ndex."' and yer='".$_GET['yer']."'");
		
	}
}

$sq=mysql_query("select * from employee where isActive=1");
while($ss=mysql_fetch_object($sq)){
	$ck=mysql_num_rows(mysql_query("select * from performance where yer='".$_GET['yer']."' and employeeId='".$ss->ndex."'"));
	if($ck==0){
		$insq=mysql_query("INSERT INTO `performance`( `employeeId`, `employmentStatus`, `first`, `second`, `yer`) VALUES ('".$ss->ndex."','".$ss->employmentStatus."','0.00','0.00','".$_GET['yer']."')");		
	}
}

	$qry=mysql_query("SELECT e.ndex as em,
		e.level,e.lastName,e.firstName,e.middleName,e.employmentStatus,e.employeeNo,e.ndex,p.employmentStatus as emps,p.*
			from performance p
			left join employee e on e.ndex=p.employeeId			
		WHERE e.ndex<>'' and p.yer='".$_GET['yer']."' order by e.lastName,e.firstName");


$var=0;
	while($r=mysql_fetch_object($qry)){
		 $ctr1s++;
		 $var++;
		     if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F8F8AC';}
		 if($r->emps=='Regular'){
		     $data.="<tr bgcolor='".$bgclr1s."'>			 		
				   <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
				   <td>".$r->lastName.", ".$r->firstName." ".$r->middleName."</td>
				   <td>&nbsp;</td>
				   <td>&nbsp;</td>
				   <td><input type='text' name='f".$r->em."' value='".$r->first."' style='text-align:right;'></td>			      
				   <td><input type='text' name='s".$r->em."' value='".$r->second."' style='text-align:right;'></td>			      
		     </tr>";
		}
		else{
			$data.="<tr bgcolor='".$bgclr1s."'>			 		
				   <td>".getID($r->employmentStatus,$r->employeeNo)."</td>
				   <td>".$r->lastName.", ".$r->firstName." ".$r->middleName."</td>
				   <td><input type='text' name='f".$r->em."' value='".$r->first."' style='text-align:right;'></td>			      
				   <td><input type='text' name='s".$r->em."' value='".$r->second."' style='text-align:right;'></td>		
				   <td>&nbsp;</td>
				   <td>&nbsp;</td>		      
		     </tr>";
		}
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
     <h2>Performance Record</h2>   
    <div class="clearfix">
	<form method="post" action="tools_performance.php?yer=<?php echo $_GET['yer'];?>&act=add">
     <table width="100%" style="font-family:Arial;font-size:12px;">
	
	 <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td colspan="10" align="center" style="font-size:14px;font-weight:bold;">Performance History Record<br><?php echo $_GET['yer']?></td>
	  </tr>
	  <tr><td colspan="6" style="color:red;">NOTE: Input percentage in decimal format (ex. <strong>50.75%</strong> should be encoded as <strong>0.5075</strong>)</td></tr>
	  <tr><td><input type="submit" value="UPDATE"></td></tr>
	  <tr style="font-weight:bold;">	      
		   <td>ID</td>
		   <td>Name</td>
		   <td>Probationary<br>3rd month</td>
		   <td>Probationary<br>5th month</td>
		   <td>Regular<br>1st Half</td>
		   <td>Regular<br>2nd Half</td>
	      
	  </tr>
	  <tr><td colspan="10"><hr></td></tr>
	  
	  
	  <?php echo $data;?>
	  <tr><td><input type="submit" value="UPDATE"></td></tr>
	 
      </table>
	   </form>



  </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>
<?php } else { ?>
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
     <h2>Performance Record</h2>   
    <div class="clearfix">
     <table width="100%" style="font-family:Arial;font-size:12px;">
     	<?php if($_SESSION['ndex']!=17) {  $viewing="alert('You are not allowed for this operation!');return(false);";  } ?>
	<form action="tools_performance.php" method="get" onsubmit="<?php echo $viewing;?>">
	 <tr><td>&nbsp;</td></tr>
	  <tr>
	       <td  align="center" style="font-size:14px;font-weight:bold;">Select Year:<select name='yer'>
		   	<option value="2011">2011
			<option value="2012">2012
			<option value="2013">2013
			<option value="2014">2014
			<option value="2015">2015
			<option value="2016">2016
			<option value="2017">2017
			<option value="2018">2018
			<option value="2019">2019
		   </select><input type="submit" value='GO'></td>
	  </tr>
	  <tr><td>&nbsp;</td></tr>
	  <tr style="font-weight:bold;">
	</form>
      </table>
	  



  </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>
<?php } ?>

