<?php

// host
$host = "localhost";

// database name
$dbname = "auth-sys";

// user
$username = "root";

// password
$password = "";

$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
} else {
  // echo "Connected successfully";
}

?>