<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "config.php";

/* ADD EXPENSE */
if (isset($_POST['add'])) {
    $farm_id = $_POST['farm_id'];
    $expense_type = $_POST['expense_type'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $expense_date = $_POST['expense_date'];

    $next_id = getNextAvailableId($conn, 'expenses', 'expense_id');

    $stmt = $conn->prepare(
        "INSERT INTO expenses (expense_id, farm_id, expense_type, description, amount, expense_date)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("iissds", $next_id, $farm_id, $expense_type, $description, $amount, $expense_date);
    $stmt->execute();
    header("Location: expenses.php");
    exit;
}

/* DELETE EXPENSE */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM expenses WHERE expense_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: expenses.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Expenses</title>
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
<h1>Expenses</h1>

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
<input type="text" name="expense_type" placeholder="Expense Type (e.g. Seed)" required>
<input type="text" name="description" placeholder="Description">
<input type="number" step="0.01" name="amount" placeholder="Amount ($)" required>
<input type="date" name="expense_date" required>
<button type="submit" name="add">Add Expense</button>
</form>

<table>
<tr>
<th>ID</th>
<th>Farm</th>
<th>Type</th>
<th>Description</th>
<th>Amount</th>
<th>Date</th>
<th>Action</th>
</tr>
<?php
$sql = "
SELECT expenses.*, farms.farm_name
FROM expenses
JOIN farms ON expenses.farm_id = farms.farm_id
ORDER BY expenses.expense_id DESC
";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
?>
<tr>
<td><?php echo $row['expense_id']; ?></td>
<td><?php echo htmlspecialchars($row['farm_name']); ?></td>
<td><?php echo htmlspecialchars($row['expense_type']); ?></td>
<td><?php echo htmlspecialchars($row['description']); ?></td>
<td>$<?php echo number_format($row['amount'], 2); ?></td>
<td><?php echo $row['expense_date']; ?></td>
<td>
<a class="delete" href="expenses.php?delete=<?php echo $row['expense_id']; ?>" onclick="return confirm('Delete this expense?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>
</div>
<script src="app.js"></script>
</body>
</html>
