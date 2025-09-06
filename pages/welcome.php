<?php
session_start();
include 'database.php';

// Log the request
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];
$message = "Request logged - Method: $method, Path: $path";
error_log($message);

// Return JSON response
header('Content-Type: application/json');
echo json_encode(['message' => 'Welcome to the DSS Online Crime System!']);
?>
