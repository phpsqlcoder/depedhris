<?php
ob_start();
session_start();
if(!$_SESSION['nym']){echo "".$_SESSION['firstName']." ".$_SESSION['lastName']." You are not allowed to access this page!<br> Your name has been recorded.<br><a href='kiosk/menu.php'>Back to kiosk</a>";die();}
include("../dbcon.php");
include("../scripts/scripts.php");
include ("../myfunctions.php");
include ("payrollfunctions.php");

if($_GET['pageact']=='uploadexcel'){
	//  Include PHPExcel_IOFactory
	//include 'PHPExcel/IOFactory.php';
	require_once '../excel/Classes/PHPExcel.php';
	
	$inputFileName = 'coopexcel/'.$_POST['fyl'].'.xls';
	
	//  Read your Excel workbook
	try {
	    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
	    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	    $objPHPExcel = $objReader->load($inputFileName);
	} catch(Exception $e) {
	    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
	}
	
	//  Get worksheet dimensions
	$sheet = $objPHPExcel->getSheet(0); 
	$highestRow = $sheet->getHighestRow(); 
	$highestColumn = $sheet->getHighestColumn();
	
	//  Loop through each row of the worksheet in turn
	for ($row = 3; $row <= $highestRow; $row++){ 
	    //  Read a row of data into an array
	    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
	                                    NULL,
	                                    TRUE,
	                                    FALSE);
	    //  Insert row data array into your database of choice here
		//print_r($rowData);
		
		foreach($rowData as $r){
				$x=0;
				foreach($r as $rr){
					$x++;
					if($x==2){$i=$rr;}
					if($x==5){
						//echo $i." - ".$rr."<br>";
						$updq=mysql_query("update payroll set d_coopTotal='".$rr."' where pay_period='".$_POST['ucutoffDate']."' and empid='".$i."'");
						$x=0;
					}
					//echo $rr."<br>";
				}		
		}
	
	}
	echo "Successfully process coop deduction!";

}

$rs = mysql_query("SELECT * FROM cutoffdates where isLock=0 ORDER BY payrollDate DESC limit 12",$conn);
while ($dt = mysql_fetch_assoc($rs)){
	$optionSelectPayrollCutoffDate.= "<option value='".$dt['payrollDate']."'>".date('F d, Y',strtotime($dt['payrollDate']))."</option>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- <title>HRIS - Davao Doctors Hospital</title> -->
    <link href="../css/styles.css" rel="stylesheet" type="text/css" />
    <link href="../css/facebox.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include "header.php";?>
<div id="main_content_wrap" class="container_12">
     <h2>Coop Deduction Tools</h2>   
    <div class="clearfix">
    	<h3>Download Excel Form</h3>
			<form method="POST" action="tools_coop_downloadexcel.php?pageact=downloadexcel" margin="0px;" name="myForm">
				<select name="cutoffDate"><?php echo $optionSelectPayrollCutoffDate;?></select>
				<button>Download</button>
			</form>
			
    </div>
    <div class="clearfix">
    	<h3><br /><br />Upload Excel Form</h3>
			<form method="POST" action="tools_coop.php?pageact=uploadexcel" margin="0px;" name="myForm">
				<strong>Cutoff:</strong><select name="ucutoffDate"><?php echo $optionSelectPayrollCutoffDate;?></select>&nbsp;&nbsp;&nbsp;
				<strong>Filename: </strong><input type="text" name="fyl">
				<button>Upload</button>
			</form>
			
    </div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>
</body>
</html>