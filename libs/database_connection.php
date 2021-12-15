<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "unihorn";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (mysqli_connect_error()) {
    print("sss");
    die("Connection to Server failed");
}

?>
