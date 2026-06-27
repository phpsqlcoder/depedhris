<?php
ob_start();
session_start();
include("bir/config.php");
if(isset($_GET['submitmwe'])){
	$delete = mysql_query("delete from annualization_mwe where year='".$year."'");
	$handle = fopen($_FILES['mwe']['tmp_name'], "r");
	for ($i = 0; $row = fgetcsv($handle ); ++$i) {
		if($i<>0){
			$v = 0;

			$fields = mysql_query("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS  WHERE table_name = 'annualization_mwe'  ORDER BY ordinal_position");
			$sql = "insert into annualization_mwe (";
			$values = " VALUES (";
			while($f=mysql_fetch_array($fields)){
		
				if($f['column_name'] <> 'id'){

					if($f['column_name']=='EmploymentFrom' || $f['column_name']=='EmploymentTo'){
						$values .= "'".date('Y-m-d',strtotime($row[$v]))."',";
					}
					else{						
						$values .= "'".$row[$v]."',";	
					}
						$sql.="`".$f['column_name']."`,";
						$v++;
				}	

			}
			$sql = rtrim($sql,",");
			$values = rtrim($values,",");
			$values.=")";
			$sql .= ")";
			$sql = $sql.$values;

			$execute = mysql_query($conn,$sql);
			//echo $sql."<br>";
		}

	}
}

if(isset($_GET['submitnmwe'])){

	$delete = mysql_query("delete from annualization_nmwe where year='".$year."'");
	$handle = fopen($_FILES['nmwe']['tmp_name'], "r");
	for ($i = 0; $row = fgetcsv($handle ); ++$i) {
		if($i<>0){
			$v = 0;

			$fields = mysql_query("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS  WHERE table_name = 'annualization_nmwe'  ORDER BY ordinal_position");
			$sql = "insert into annualization_nmwe (";
			$values = " VALUES (";
			while($f=mysql_fetch_array($fields)){
		
				if($f['column_name'] <> 'id'){

					if($f['column_name']=='EmploymentFrom' || $f['column_name']=='EmploymentTo' || $f['column_name']=='EmploymentFrom1' || $f['column_name']=='EmploymentTo1'){
						$values .= "'".date('Y-m-d',strtotime($row[$v]))."',";
					}
					else{						
						$values .= "'".$row[$v]."',";	
					}
						$sql.="`".$f['column_name']."`,";
						$v++;
				}	

			}
			$sql = rtrim($sql,",");
			$values = rtrim($values,",");
			$values.=")";
			$sql .= ")";
			$sql = $sql.$values;

			$execute = mysql_query($conn,$sql);
			//echo $sql."<br>";
		}

	}
}

$total_mwe = mysql_fetch_array(mysql_query("select count(id) as total_mwe from annualization_mwe where year='".$year."'"));
$total_nmwe = mysql_fetch_array(mysql_query("select count(id) as total_nmwe from annualization_nmwe where year='".$year."'"));

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
     <h2>BIR 2316 Generator</h2>   
    <div class="clearfix">

		<table width="100%">
			<tr>
				<td width="50%" valign="top">
					<table width="100%" cellpadding="20" cellspacing="10">
						<tr>
							<td><h4>Minimum Wage Earner</h4><br></td>							
						</tr>
						<tr>
							<td><h5>Step 1: Download and fill up the templates</h5></td>							
						</tr>
						<tr>
							<td style="padding-left:50px;"><a href="bir/template/mwe.xlsx">Download Excel Template</a><br><br></td>	
						</tr>
						<tr>
							<td style="padding-left:50px;"><a href="bir/template/raw_mwe.csv">Download RAW Template</a><br><br></td>	
						</tr>
						<tr>
							<td><br><br><h5>Step 2: Upload the template to HRIS</h5></td>							
						</tr>
						<tr>
							<td style="padding-left:50px;">
								<h6>Upload Annualization for <?php echo $year;?></h6>
								<form action="upload.php?submitmwe=go" method="post" enctype="multipart/form-data">
									<input type="file" name="mwe">
									<input type="submit" value="Upload">
								</form>
								<i>Total Records: <?php echo $total_mwe['total_mwe']; ?></i><br>
								<i>Note: This will delete all previously uploaded <?php echo $year; ?> data</i>
							</td>
						</tr>
						<tr>
							<td><br><br><h5>Step 3: Generate the 2316 Files</h5></td>							
						</tr>
						<tr>
							<td style="padding-left:50px;"><a href="bir/pdf_mwe.php" target="_blank">Generate MWE 2316 file</a><br><br></td>
						</tr>
						<tr>
							<td style="padding-left:50px;"><a href="bir/dat.php" target="_blank">Generate DAT file</a></td>
						</tr>

					</table>
				</td>
				<td width="50%" valign="top">
					<table width="100%" cellpadding="20" cellspacing="10">
						<tr>
							<td><h4>Non Minimum Wage Earner</h4><br></td>							
						</tr>
						<tr>
							<td><h5>Step 1: Download and fill up the templates</h5></td>							
						</tr>
						<tr>
							<td style="padding-left:50px;"><a href="bir/template/nmwe.xlsx">Download Excel Template</a><br><br></td>	
						</tr>
						<tr>
							<td style="padding-left:50px;"><a href="bir/template/raw-nmwe.csv">Download RAW Template</a><br><br></td>	
						</tr>
						<tr>
							<td><br><br><h5>Step 2: Upload the template to HRIS</h5></td>							
						</tr>
						<tr>
							<td style="padding-left:50px;">
								<h6>Upload Annualization for <?php echo $year;?></h6>
								<form action="upload.php?submitnmwe=go" method="post" enctype="multipart/form-data">
									<input type="file" name="nmwe">
									<input type="submit" value="Upload">
								</form>
								<i>Total Records: <?php echo $total_nmwe['total_nmwe']; ?></i><br>
								<i>Note: This will delete all previously uploaded <?php echo $year; ?> data</i>
							</td>
						</tr>
						<tr>
							<td><br><br><h5>Step 3: Generate the 2316 Files</h5></td>							
						</tr>
						<tr>
							<td style="padding-left:50px;"><a href="bir/pdf_nmwe.php" target="_blank">Generate NMWE 2316 file</a></td>
						</tr>

					</table>
				</td>
			</tr>
		</table>
	</div> 
	<h2>&nbsp;</h2>
	<?php include "footer.php";?>
  </div>


</body>
</html>