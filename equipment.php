<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "config.php";

/* ADD EQUIPMENT */
if (isset($_POST['add'])) {
    $farm_id = $_POST['farm_id'];
    $equipment_name = $_POST['equipment_name'];
    $purchase_date = !empty($_POST['purchase_date']) ? $_POST['purchase_date'] : null;
    $status = $_POST['status'];
    $purchase_price = !empty($_POST['purchase_price']) ? $_POST['purchase_price'] : null;

    $next_id = getNextAvailableId($conn, 'equipment', 'equipment_id');

    $stmt = $conn->prepare(
        "INSERT INTO equipment (equipment_id, farm_id, equipment_name, purchase_date, status, purchase_price)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("iisssd", $next_id, $farm_id, $equipment_name, $purchase_date, $status, $purchase_price);
    $stmt->execute();
    header("Location: equipment.php");
    exit;
}

/* DELETE EQUIPMENT */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM equipment WHERE equipment_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: equipment.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Equipment</title>
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
<h1>Equipment</h1>

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
<input type="text" name="equipment_name" placeholder="Equipment Name" required>
<input type="date" name="purchase_date" placeholder="Purchase Date">
<select name="status">
<option value="Working">Working</option>
<option value="Maintenance">Maintenance</option>
<option value="Damaged">Damaged</option>
</select>
<input type="number" step="0.01" name="purchase_price" placeholder="Purchase Price">
<button type="submit" name="add">Add Equipment</button>
</form>

<table>
<tr>
<th>ID</th>
<th>Farm</th>
<th>Equipment Name</th>
<th>Purchase Date</th>
<th>Status</th>
<th>Price</th>
<th>Action</th>
</tr>
<?php
$sql = "
SELECT equipment.*, farms.farm_name
FROM equipment
JOIN farms ON equipment.farm_id = farms.farm_id
ORDER BY equipment.equipment_id DESC
";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
?>
<tr>
<td><?php echo $row['equipment_id']; ?></td>
<td><?php echo htmlspecialchars($row['farm_name']); ?></td>
<td><?php echo htmlspecialchars($row['equipment_name']); ?></td>
<td><?php echo $row['purchase_date'] ? $row['purchase_date'] : 'N/A'; ?></td>
<td><?php echo $row['status']; ?></td>
<td>$<?php echo number_format($row['purchase_price'], 2); ?></td>
<td>
<a class="delete" href="equipment.php?delete=<?php echo $row['equipment_id']; ?>" onclick="return confirm('Delete this equipment?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>
</div>
<script src="app.js"></script>
</body>
</html>
