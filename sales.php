<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "config.php";

/* ADD SALE */
if (isset($_POST['add'])) {
    $farm_id = $_POST['farm_id'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $unit = !empty($_POST['unit']) ? $_POST['unit'] : 'kg';
    $price = $_POST['price'];
    $sale_date = $_POST['sale_date'];
    $customer_name = $_POST['customer_name'];

    $next_id = getNextAvailableId($conn, 'sales', 'sale_id');

    $stmt = $conn->prepare(
        "INSERT INTO sales (sale_id, farm_id, product_name, quantity, unit, price, sale_date, customer_name)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("iisdsdss", $next_id, $farm_id, $product_name, $quantity, $unit, $price, $sale_date, $customer_name);
    $stmt->execute();
    header("Location: sales.php");
    exit;
}

/* DELETE SALE */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM sales WHERE sale_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: sales.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Sales</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
<h2 class="logo">🌾 FarmMS</h2>
<a href="index.php">Dashboard</a>
<a href="farmers.php">Farmers</a>
<a href="farms.php">Farms</a>
<a href="crops.php">Crops</a>
<a href="fields.php">Fields</a>
<a href="livestock.php">Livestock</a>
<a href="workers.php">Workers</a>
<a href="equipment.php">Equipment</a>
<a href="expenses.php">Expenses</a>
<a href="sales.php">Sales</a>
</div>

<div class="main">
<h1>Sales</h1>

<div class="container">
<form method="POST" class="form">
<select name="farm_id" required>
<option value="">Select Farm</option>
<?php
$result = $conn->query("SELECT * FROM farms");
while ($farm = $result->fetch_assoc()) {
    echo "<option value='{$farm['farm_id']}'>" . htmlspecialchars($farm['farm_name']) . "</option>";
}
?>
</select>
<input type="text" name="product_name" placeholder="Product Name (e.g. Rice)" required>
<input type="number" step="0.01" name="quantity" placeholder="Quantity" required>
<input type="text" name="unit" placeholder="Unit (default: kg)" value="kg">
<input type="number" step="0.01" name="price" placeholder="Price per unit ($)" required>
<input type="date" name="sale_date" required>
<input type="text" name="customer_name" placeholder="Customer Name">
<button type="submit" name="add">Add Sale</button>
</form>

<table>
<tr>
<th>ID</th>
<th>Farm</th>
<th>Product</th>
<th>Quantity</th>
<th>Price/Unit</th>
<th>Total</th>
<th>Date</th>
<th>Customer</th>
<th>Action</th>
</tr>
<?php
$sql = "
SELECT sales.*, farms.farm_name
FROM sales
JOIN farms ON sales.farm_id = farms.farm_id
ORDER BY sales.sale_id DESC
";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $total = $row['quantity'] * $row['price'];
?>
<tr>
<td><?php echo $row['sale_id']; ?></td>
<td><?php echo htmlspecialchars($row['farm_name']); ?></td>
<td><?php echo htmlspecialchars($row['product_name']); ?></td>
<td><?php echo $row['quantity']; ?> <?php echo htmlspecialchars($row['unit']); ?></td>
<td>$<?php echo number_format($row['price'], 2); ?></td>
<td>$<?php echo number_format($total, 2); ?></td>
<td><?php echo $row['sale_date']; ?></td>
<td><?php echo htmlspecialchars($row['customer_name']); ?></td>
<td>
<a class="delete" href="sales.php?delete=<?php echo $row['sale_id']; ?>" onclick="return confirm('Delete this sale record?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>
</div>
<script src="app.js"></script>
</body>
</html>
