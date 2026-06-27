<?php

ob_start();
if(isset($_GET['del'])){
	$delete = unlink($_GET['del']);
header("location:emp_upload_files.php?id=".$_GET['id']);
}
$empid=$_GET['id'];
$emp_dir = 'empfiles';
if (!file_exists($emp_dir)) {
    mkdir($emp_dir, 0777, true);
}

function cleand($string) {
   //$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '?', $string); // Removes special chars.
}

function listFolderFiles($dir){
    $ffs = scandir($dir);
    echo '<ol>';
    foreach($ffs as $ff){
        if($ff != '.' && $ff != '..'){
		$vl = $dir.'/'.$ff;
//$vl = (string)$vl;
$pos = strpos($vl,"&");
$newf = str_replace("&","AND",$vl);
if($vl<>$newf){
	$rename = rename($vl,$newf);
}
		 //if($pos>=2){ 			
			echo '<li> <a href="'.$dir.'/'.$ff.'" target="_blank">'.utf8_decode($ff).'</a>&nbsp;&nbsp;';
            		if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff);
            		echo '</li>';
		//}
        }
    }
    echo '</ol>';
}

if(isset($_GET['act'])){
    $zip = new ZipArchive;
    if ($zip->open($_FILES['fileToUpload']['tmp_name']) === TRUE) {
        $zip->extractTo($emp_dir.'/');
        $zip->close();
    } else {
        // failed to extract.
    }
    header("location:emp_upload_files.php?id=".$_GET['id']."");
}




?>
<!DOCTYPE html>
<html>
<body>

<form action="emp_upload_files.php?act=sss&id=<?php echo $_GET['id'];?>" method="post" enctype="multipart/form-data">
    Select zip file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload" name="submit">
</form>
<?php
echo listFolderFiles($emp_dir);
?>
</body>
</html>