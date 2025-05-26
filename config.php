<?php
// config.php

$DatabaseHost = "localhost";
$DatabaseUser = "root"; // Default XAMPP MySQL user
$DatabasePassword = ""; // Default XAMPP MySQL password
$DatabaseName = "socialmusic_db";

try {
    $DatabaseConnection = new PDO("mysql:host=$DatabaseHost;dbname=$DatabaseName", $DatabaseUser, $DatabasePassword);
    // Set the PDO error mode to exception
    $DatabaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ExceptionObject) {
    die("Connection failed: " . $ExceptionObject->getMessage());
}
?>