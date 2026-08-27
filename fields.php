<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "config.php";

/* ADD FIELD */
if (isset($_POST['add'])) {
    $farm_id = $_POST['farm_id'];
    $field_name = $_POST['field_name'];
    $soil_type = $_POST['soil_type'];
    $field_size = $_POST['field_size'];

    $next_id = getNextAvailableId($conn, 'fields', 'field_id');

    $stmt = $conn->prepare(
        "INSERT INTO fields (field_id, farm_id, field_name, soil_type, field_size)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("iissd", $next_id, $farm_id, $field_name, $soil_type, $field_size);
    $stmt->execute();
    header("Location: fields.php");
    exit;
}

/* DELETE FIELD */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM fields WHERE field_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: fields.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Fields</title>
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
<h1>Fields</h1>

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
<input type="text" name="field_name" placeholder="Field Name" required>
<input type="text" name="soil_type" placeholder="Soil Type">
<input type="number" step="0.01" name="field_size" placeholder="Size (acres)" required>
<button type="submit" name="add">Add Field</button>
</form>

<table>
<tr>
<th>ID</th>
<th>Farm</th>
<th>Field</th>
<th>Soil Type</th>
<th>Size</th>
<th>Action</th>
</tr>
<?php
$sql = "
SELECT fields.*, farms.farm_name
FROM fields
JOIN farms ON fields.farm_id = farms.farm_id
ORDER BY fields.field_id DESC
";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
?>
<tr>
<td><?php echo $row['field_id']; ?></td>
<td><?php echo htmlspecialchars($row['farm_name']); ?></td>
<td><?php echo htmlspecialchars($row['field_name']); ?></td>
<td><?php echo htmlspecialchars($row['soil_type']); ?></td>
<td><?php echo $row['field_size']; ?> acres</td>
<td>
<a class="delete" href="fields.php?delete=<?php echo $row['field_id']; ?>" onclick="return confirm('Delete this field?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>
</div>
<script src="app.js"></script>
</body>
</html>
