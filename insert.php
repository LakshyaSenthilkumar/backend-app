<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* ✅ Check if request is POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Order Placed Successfully";
    exit;
}

/* ✅ Safely fetch POST values */
$name     = $_POST['name']     ?? '';
$product  = $_POST['product']  ?? '';
$quantity = $_POST['quantity'] ?? '';
$address  = $_POST['address']  ?? '';

/* ✅ Validate input */
if (empty($name) || empty($product) || empty($quantity) || empty($address)) {
    echo "Missing required fields";
    exit;
}

/* ✅ Insert data */
$sql = "INSERT INTO orders (name, product, quantity, address)
        VALUES ('$name', '$product', '$quantity', '$address')";

if ($conn->query($sql) === TRUE) {
    echo "Order placed successfully";
} else {
    echo "Database error";
}

$conn->close();
?>
