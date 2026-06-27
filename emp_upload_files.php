<?php
ob_start();
session_start();
if($_SESSION['ndex']!=16 && $_SESSION['ndex']!=114 && $_SESSION['ndex']!=451 && $_SESSION['ndex']!=452 && $_SESSION['ndex']!=453 && $_SESSION['ndex']!=17 && $_SESSION['ndex']!=99  && $_SESSION['ndex']!=21  && $_SESSION['ndex']!=222) {  echo "Restricted Access!";die();}
if(isset($_GET['del'])){
	$delete = unlink($_GET['del']);
header("location:emp_upload_files.php?id=".$_GET['id']);
}


$empid=$_GET['id'];
$emp_dir = 'empfiles/'.$empid;
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
	//$ff=cleand($ff);
        if($ff != '.' && $ff != '..'){

$addbtn='';
if(is_dir($dir.'/'.$ff)) {
$addbtn='&nbsp;|&nbsp;<a href="emp_upload_files_specific.php?spec=go&id='.$_GET['id'].'&url='.$dir.'/'.$ff.'" style="font-family:arial;color:red;font-size:10px;">ADD</a>';
/*	
$addbtn='<form action="emp_upload_files.php?spec=go&id='.$_GET['id'].'&url='.$dir.'/'.$ff.'">
	<input type="file" name="fileToUpload" id="fileToUpload">
    	<input type="submit" value="Upload" name="submit"></form>';
*/
}
		echo '<li> <a href="'.$dir.'/'.$ff.'" target="_blank">'.utf8_decode($ff).'</a>&nbsp;&nbsp;&nbsp;<a href="emp_upload_files.php?id='.$_GET['id'].'&del='.$dir.'/'.$ff.'" style="font-family:arial;color:red;font-size:10px;" title="Delete this file">X</a>&nbsp;'.$addbtn;
            if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff);
            echo '</li>';
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

/*
function CopyFolderFiles($dir){
    global $emp_dir;
    $ffs = scandir($dir);
    $newdir=str_replace("\\","/",$dir);
    $to_dir=$emp_dir."/".$newdir;
    echo $to_dir."<br>";
    if (!file_exists($to_dir)) {
        mkdir($to_dir, 0777, true);
    }
    echo '<ol>';
    foreach($ffs as $ff){
        if($ff != '.' && $ff != '..'){
            echo '<li>'.$ff;
            if(is_dir($dir.'/'.$ff)) {                
                CopyFolderFiles($dir.'/'.$ff);
            }
            else{

            }
            echo '</li>';
        }
    }
    echo '</ol>';
}
$str='empfiles/1/F/cis';
$s=explode("/",$str);
echo count($s);*/

//CopyFolderFiles('F:\cis');
//listFolderFiles('F:\cis');

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