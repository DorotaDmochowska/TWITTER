<?php

$servername = 'localhost';
$username = 'root';
$password = 'coderslab';
$basename = 'twitter_db';

$conn = new mysqli(
        $servername,
        $username,
        $password,
        $basename
        );

if ($conn->connect_error) {
    echo "Connection error: $conn->error.<br>";
} 

?>