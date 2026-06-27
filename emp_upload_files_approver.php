<?php
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
            //echo '<li><a href="'.cleand($dir).'/'.cleand($ff).'" target="_blank">'.$ff.'</a>';
		echo '<li><a href="'.$dir.'/'.$ff.'" target="_blank">'.utf8_decode($ff).'</a>';
            if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff);
            echo '</li>';
        }
    }
    echo '</ol>';
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


<?php
echo listFolderFiles($emp_dir);
?>
</body>
</html>