<?php

$env = trim(@file_get_contents(__DIR__ . '/ENVIRONMENT'));

ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require 'db.php';

if ($conn->connect_error) {
    die("DB connection failed");
}

// Health check
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo $env === 'LIVE'
        ? "LIVE BACKEND (" . gethostname() . ")"
        : "UNDER WORK BACKEND (" . gethostname() . ")";
    exit;
}

// Block orders if under work
if ($env !== 'LIVE') {
    echo "Backend under maintenance. Orders temporarily disabled.";
    exit;
}

// Normal order logic continues here...

/* Only POST allowed for orders */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$name     = $_POST['name'] ?? '';
$product  = $_POST['product'] ?? '';
$quantity = $_POST['quantity'] ?? '';
$address  = $_POST['address'] ?? '';

if (!$name || !$product || !$quantity || !$address) {
    echo "Missing required fields";
    exit;
}

$sql = "INSERT INTO orders (name, product, quantity, address)
        VALUES ('$name', '$product', '$quantity', '$address')";

if ($conn->query($sql)) {
    echo "Order Placed Successfully";
} else {
    echo "Order Failed";
}

$conn->close();
