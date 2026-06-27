<?php

include("dcon.php");
$tbl_name="fquestions2"; // Table name 

// Connect to server and select database.
mysql_connect("$host", "$username", "$password")or die("cannot connect"); 
mysql_select_db("$db_name")or die("cannot select DB");

// get data that sent from form 
$topic=$_POST['topic'];
$detail=$_POST['detail'];
$name=$_POST['name'];
$email=$_POST['email'];

$datetime=date("d/m/y h:i:s"); //create date time

$sql="INSERT INTO $tbl_name(topic, detail, name, email, datetime)VALUES('$topic', '$detail', '$name', '$email', '$datetime')";
$result=mysql_query($sql);
include("newheader.php");
if($result){
echo '<div class="content-page">
              <div class="note note-success">
                <h4 class="block">Successfully Saved your topic</h4>
                <p>
                  <a href=main_forum.php>Back to Forum</a>
                  </p>
              
              </div>  </div>';
echo "";
}
else {
echo "ERROR";
}
mysql_close();


include("newfooter.php");
?>