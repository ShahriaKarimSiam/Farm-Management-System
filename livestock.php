<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "config.php";

/* ADD LIVESTOCK */
if (isset($_POST['add'])) {
    $farm_id = $_POST['farm_id'];
    $animal_type = $_POST['animal_type'];
    $breed = $_POST['breed'];
    $dob = !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null;
    $gender = $_POST['gender'];
    $health_status = $_POST['health_status'];
    $quantity = intval($_POST['quantity']);

    $next_id = getNextAvailableId($conn, 'livestock', 'animal_id');

    $stmt = $conn->prepare(
        "INSERT INTO livestock (animal_id, farm_id, animal_type, breed, date_of_birth, gender, health_status, quantity)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("iisssssi", $next_id, $farm_id, $animal_type, $breed, $dob, $gender, $health_status, $quantity);
    $stmt->execute();
    header("Location: livestock.php");
    exit;
}

/* DELETE LIVESTOCK */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM livestock WHERE animal_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: livestock.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Livestock</title>
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
<h1>Livestock</h1>

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
<input type="text" name="animal_type" placeholder="Animal Type (e.g. Cow)" required>
<input type="text" name="breed" placeholder="Breed">
<input type="date" name="date_of_birth" placeholder="Date of Birth">
<select name="gender">
<option value="Female">Female</option>
<option value="Male">Male</option>
</select>
<select name="health_status">
<option value="Healthy">Healthy</option>
<option value="Sick">Sick</option>
<option value="Under Treatment">Under Treatment</option>
<option value="Recovered">Recovered</option>
</select>
<input type="number" name="quantity" placeholder="Quantity" value="1" min="1" required>
<button type="submit" name="add">Add Livestock</button>
</form>

<table>
<tr>
<th>ID</th>
<th>Farm</th>
<th>Animal Type</th>
<th>Breed</th>
<th>Date of Birth</th>
<th>Gender</th>
<th>Health</th>
<th>Qty</th>
<th>Action</th>
</tr>
<?php
$sql = "
SELECT livestock.*, farms.farm_name
FROM livestock
JOIN farms ON livestock.farm_id = farms.farm_id
ORDER BY livestock.animal_id DESC
";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
?>
<tr>
<td><?php echo $row['animal_id']; ?></td>
<td><?php echo htmlspecialchars($row['farm_name']); ?></td>
<td><?php echo htmlspecialchars($row['animal_type']); ?></td>
<td><?php echo htmlspecialchars($row['breed']); ?></td>
<td><?php echo $row['date_of_birth'] ? $row['date_of_birth'] : 'N/A'; ?></td>
<td><?php echo $row['gender']; ?></td>
<td><?php echo $row['health_status']; ?></td>
<td><?php echo $row['quantity']; ?></td>
<td>
<a class="delete" href="livestock.php?delete=<?php echo $row['animal_id']; ?>" onclick="return confirm('Delete this livestock record?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>
</div>
<script src="app.js"></script>
</body>
</html>
