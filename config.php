<?php
$servername = 'localhost';
$username = 'm02';
$password = 'nevxej-6vewre-gyRqak';
$database = 'm02';

$conn = new mysqli($servername, $username, $password, $database);

if (!conn) {
    die('Connection failed: ' . mysqli_connect_error());
}
?>