<?php
$empid=$_GET['id'];
$emp_dir = 'empfiles/'.$empid;
if (!file_exists($emp_dir)) {
    mkdir($emp_dir, 0777, true);
}

function listFolderFiles($dir){
    $ffs = scandir($dir);
    echo '<ol>';
    foreach($ffs as $ff){
        if($ff != '.' && $ff != '..'){
            echo '<li><a href="'.$dir.'/'.$ff.'" target="_blank">'.$ff.'</a>';
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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