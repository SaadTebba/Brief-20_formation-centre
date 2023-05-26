<?php
  
try {
    $servername = "localhost";
    $dbname = "brief-20: amina";
    $username = "root";
    $password = "";
 
    $conn = new PDO("mysql:host=$servername; dbname=brief-20", $username, $password);
     
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     
} catch(PDOException $e) {
    echo "Connection failed: "
        . $e->getMessage();
}