<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "config.php";

/* ADD WORKER */
if (isset($_POST['add'])) {
    $farm_id = $_POST['farm_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $salary = !empty($_POST['salary']) ? $_POST['salary'] : null;
    $hire_date = !empty($_POST['hire_date']) ? $_POST['hire_date'] : null;

    $next_id = getNextAvailableId($conn, 'workers', 'worker_id');

    $stmt = $conn->prepare(
        "INSERT INTO workers (worker_id, farm_id, name, phone, position, salary, hire_date)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("iisssds", $next_id, $farm_id, $name, $phone, $position, $salary, $hire_date);
    $stmt->execute();
    header("Location: workers.php");
    exit;
}

/* DELETE WORKER */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM workers WHERE worker_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: workers.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Workers</title>
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
<h1>Workers</h1>

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
<input type="text" name="name" placeholder="Worker Name" required>
<input type="text" name="phone" placeholder="Phone">
<input type="text" name="position" placeholder="Position (e.g. Manager)">
<input type="number" step="0.01" name="salary" placeholder="Salary">
<input type="date" name="hire_date" placeholder="Hire Date">
<button type="submit" name="add">Add Worker</button>
</form>

<table>
<tr>
<th>ID</th>
<th>Farm</th>
<th>Name</th>
<th>Phone</th>
<th>Position</th>
<th>Salary</th>
<th>Hire Date</th>
<th>Action</th>
</tr>
<?php
$sql = "
SELECT workers.*, farms.farm_name
FROM workers
JOIN farms ON workers.farm_id = farms.farm_id
ORDER BY workers.worker_id DESC
";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
?>
<tr>
<td><?php echo $row['worker_id']; ?></td>
<td><?php echo htmlspecialchars($row['farm_name']); ?></td>
<td><?php echo htmlspecialchars($row['name']); ?></td>
<td><?php echo htmlspecialchars($row['phone']); ?></td>
<td><?php echo htmlspecialchars($row['position']); ?></td>
<td>$<?php echo number_format($row['salary'], 2); ?></td>
<td><?php echo $row['hire_date'] ? $row['hire_date'] : 'N/A'; ?></td>
<td>
<a class="delete" href="workers.php?delete=<?php echo $row['worker_id']; ?>" onclick="return confirm('Delete this worker?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>
</div>
<script src="app.js"></script>
</body>
</html>
