<?php

$DatabaseHost = "localhost";
$DatabaseUser = "root"; 
$DatabasePassword = ""; 
$DatabaseName = "socialmusic_db";

try {
    $DatabaseConnection = new PDO("mysql:host=$DatabaseHost;dbname=$DatabaseName", $DatabaseUser, $DatabasePassword);
    $DatabaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ExceptionObject) {
    die("Connection failed: " . $ExceptionObject->getMessage());
}
?>