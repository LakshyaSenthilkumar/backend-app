echo "Backend CICD working";
exit;
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require 'db.php';

if ($conn->connect_error) {
    die("Connection failed");
}

$name = $_POST['name'];
$product = $_POST['product'];
$quantity = $_POST['quantity'];
$address = $_POST['address'];

$sql = "INSERT INTO orders (name, product, quantity, address)
        VALUES ('$name', '$product', '$quantity', '$address')";

if ($conn->query($sql) === TRUE) {
    echo "Order placed successfully";
} else {
    echo "Error";
}

$conn->close();
?>

