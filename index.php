<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "config.php";

$farmers = $conn
    ->query("SELECT COUNT(*) AS total FROM farmers")
    ->fetch_assoc()['total'];

$farms = $conn
    ->query("SELECT COUNT(*) AS total FROM farms")
    ->fetch_assoc()['total'];

$crops = $conn
    ->query("SELECT COUNT(*) AS total FROM crops")
    ->fetch_assoc()['total'];

$livestock = $conn
    ->query("SELECT COUNT(*) AS total FROM livestock")
    ->fetch_assoc()['total'];

$workers = $conn
    ->query("SELECT COUNT(*) AS total FROM workers")
    ->fetch_assoc()['total'];

$expenses = $conn
    ->query(
        "SELECT COALESCE(SUM(amount),0) AS total
         FROM expenses"
    )
    ->fetch_assoc()['total'];

$sales = $conn
    ->query(
        "SELECT COALESCE(SUM(quantity * price),0) AS total
         FROM sales"
    )
    ->fetch_assoc()['total'];

$profit = $sales - $expenses;

?>

<!DOCTYPE html>

<html>

<head>

<title>Farm Management System</title>

<link
    rel="stylesheet"
    href="style.css"
>

</head>

<body>

<div class="sidebar">

<h2 class="logo">
🌾 FarmMS
</h2>

<a href="index.php">
Dashboard
</a>

<a href="farmers.php">
Farmers
</a>

<a href="farms.php">
Farms
</a>

<a href="crops.php">
Crops
</a>

<a href="fields.php">
Fields
</a>

<a href="livestock.php">
Livestock
</a>

<a href="workers.php">
Workers
</a>

<a href="equipment.php">
Equipment
</a>

<a href="expenses.php">
Expenses
</a>

<a href="sales.php">
Sales
</a>

</div>


<div class="main">

<div class="user-info">

Welcome,
<strong>
<?php echo htmlspecialchars($_SESSION['full_name']); ?>
</strong>

<a href="logout.php">
Logout
</a>

</div>

<h1>
Farm Management Dashboard
</h1>


<div class="cards">

<div class="card">

<h3>
Farmers
</h3>

<div class="number">
<?php echo $farmers; ?>
</div>

</div>


<div class="card">

<h3>
Farms
</h3>

<div class="number">
<?php echo $farms; ?>
</div>

</div>


<div class="card">

<h3>
Crops
</h3>

<div class="number">
<?php echo $crops; ?>
</div>

</div>


<div class="card">

<h3>
Livestock
</h3>

<div class="number">
<?php echo $livestock; ?>
</div>

</div>

</div>


<div class="finance">

<div class="finance-card">

<h3>
Total Sales
</h3>

<h2>
$ <?php echo number_format($sales,2); ?>
</h2>

</div>


<div class="finance-card">

<h3>
Total Expenses
</h3>

<h2>
$ <?php echo number_format($expenses,2); ?>
</h2>

</div>


<div class="finance-card">

<h3>
Net Profit
</h3>

<h2>
$ <?php echo number_format($profit,2); ?>
</h2>

</div>

</div>


<br>


<div class="container">

<h2>
Farm Overview
</h2>

<br>

<p>
Total Workers:
<strong>
<?php echo $workers; ?>
</strong>
</p>

</div>

</div>

<script src="app.js"></script>
</body>

</html>