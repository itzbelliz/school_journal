<?php 
$servername = "localhost";
$user = "root";
$pass = "";
$dbname = "school_journal";

$conn = new mysqli($servername,$user,$pass,$dbname);

if($conn -> connect_error) {
    die("Connection failed: " . $conn -> connect_error);
}