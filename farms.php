<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "config.php";


if (isset($_POST['add'])) {

    $farmer_id =
        $_POST['farmer_id'];

    $farm_name =
        $_POST['farm_name'];

    $location =
        $_POST['location'];

    $size =
        $_POST['farm_size'];


    $next_id = getNextAvailableId($conn, 'farms', 'farm_id');

    $stmt = $conn->prepare(
        "INSERT INTO farms
        (farm_id, farmer_id, farm_name, location, farm_size)
        VALUES (?, ?, ?, ?, ?)"
    );


    $stmt->bind_param(
        "iissd",
        $next_id,
        $farmer_id,
        $farm_name,
        $location,
        $size
    );


    $stmt->execute();

    header("Location: farms.php");

    exit;
}


if (isset($_GET['delete'])) {

    $id =
        intval($_GET['delete']);


    $stmt =
        $conn->prepare(
            "DELETE FROM farms
             WHERE farm_id = ?"
        );


    $stmt->bind_param(
        "i",
        $id
    );


    $stmt->execute();

    header("Location: farms.php");

    exit;
}

?>


<!DOCTYPE html>

<html>

<head>

<title>Farms</title>

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

<h1>
Farms
</h1>


<div class="container">


<form
    method="POST"
    class="form"
>


<select
    name="farmer_id"
    required
>

<option value="">
Select Farmer
</option>


<?php

$result =
    $conn->query(
        "SELECT *
         FROM farmers"
    );


while (
    $farmer =
    $result->fetch_assoc()
) {

?>

<option
    value="<?php echo $farmer['farmer_id']; ?>"
>

<?php
echo htmlspecialchars(
    $farmer['name']
);
?>

</option>

<?php

}

?>

</select>


<input
    type="text"
    name="farm_name"
    placeholder="Farm Name"
    required
>


<input
    type="text"
    name="location"
    placeholder="Location"
>


<input
    type="number"
    step="0.01"
    name="farm_size"
    placeholder="Size (acres)"
>


<button name="add">
Add Farm
</button>

</form>


<table>

<tr>

<th>ID</th>

<th>Farmer</th>

<th>Farm</th>

<th>Location</th>

<th>Size</th>

<th>Action</th>

</tr>


<?php

$sql = "
SELECT
    farms.*,
    farmers.name AS farmer_name
FROM farms
JOIN farmers
ON farms.farmer_id =
   farmers.farmer_id
ORDER BY farms.farm_id DESC
";


$result =
    $conn->query($sql);


while (
    $row =
    $result->fetch_assoc()
) {

?>

<tr>

<td>
<?php echo $row['farm_id']; ?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row['farmer_name']
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row['farm_name']
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row['location']
);
?>
</td>

<td>
<?php
echo $row['farm_size']; ?>
 acres
</td>

<td>

<a
    class="delete"
    href="farms.php?delete=<?php echo $row['farm_id']; ?>"
    onclick="return confirm('Delete this farm?')"
>
Delete
</a>

</td>

</tr>

<?php

}

?>

</table>

</div>

</div>

<script src="app.js"></script>
</body>

</html>