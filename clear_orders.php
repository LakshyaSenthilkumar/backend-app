<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

require 'db.php';

if ($conn->connect_error) {
    die("DB Connection Failed");
}

$sql = "DELETE FROM orders";

if ($conn->query($sql) === TRUE) {
    header("Location: admin.php");
    exit;
} else {
    echo "Error clearing orders";
}

$conn->close();
?>
