<?php
ob_start();

if(isset($_GET['act'])){
move_uploaded_file($_FILES["fileToUpload"]["tmp_name"],$_GET['url']."/".$_FILES["fileToUpload"]["name"]);
header("location:emp_upload_files.php?id=".$_GET['id']);
}
?>
<!DOCTYPE html>
<html>
<body>
<h3>Upload to:</h3> <p><?php echo $_GET['url'];?></p>
<form action="emp_upload_files_specific.php?act=sss&id=<?php echo $_GET['id'];?>&url=<?php echo $_GET['url'];?>" method="post" enctype="multipart/form-data">
    Select zip file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload" name="submit">
</form>

</body>
</html>