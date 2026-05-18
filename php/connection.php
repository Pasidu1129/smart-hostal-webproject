<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "smart_hostal";

// 1. Create connection
$connection = new mysqli($servername, $username, $password);

// 2. Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// 3. Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($connection->query($sql) === TRUE) {
    //echo "Database created successfully or already exists";
} else {
    //echo "Error creating database: " . $conn->error;
}

// 4. Select the database to use it for future queries
$connection->select_db($dbname);

// Close connection
//$connection->close();
?>
