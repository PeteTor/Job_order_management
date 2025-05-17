<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "job_orders_db";

$conn = new mysqli($host, $user, $password, $database);
$pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);

if ($conn->connect_error) {
    die("Connection failed " . $conn->connect_error);
}
