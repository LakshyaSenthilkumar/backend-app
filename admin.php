<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require 'db.php';

if ($conn->connect_error) {
    die("DB Connection Failed");
}

$result = $conn->query("SELECT * FROM orders ORDER BY order_time DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<style>
body { font-family: Arial; background: #f4f4f4; padding: 20px; }
table { width: 100%; border-collapse: collapse; background: white; }
th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
th { background: #333; color: white; }
</style>
</head>
<body>

<h2>Restaurant Orders Dashboard</h2>

<form action="clear_orders.php" method="POST" onsubmit="return confirm('Clear all orders?');">
  <button style="background:red;color:white;padding:10px;border:none;">
    Clear All Orders
  </button>
</form>

<table>
<tr>
  <th>ID</th>
  <th>Name</th>
  <th>Product</th>
  <th>Quantity</th>
  <th>Address</th>
  <th>Time</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= $row['name'] ?></td>
  <td><?= $row['product'] ?></td>
  <td><?= $row['quantity'] ?></td>
  <td><?= $row['address'] ?></td>
  <td><?= $row['order_time'] ?></td>
</tr>
<?php } ?>

</table>
</body>
</html>
