<?php
$servername = "192.168.2.2";
//$servername = "192.168.7.21";
$username = "root";
$password = "root";

try {
    $conn = new PDO("mysql:host=$servername;dbname=hris", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
?> 