<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include "config.php";


/* ADD FARMER */

if (isset($_POST['add'])) {

    $name =
        $_POST['name'];

    $phone =
        $_POST['phone'];

    $address =
        $_POST['address'];


    $next_id = getNextAvailableId($conn, 'farmers', 'farmer_id');

    $stmt = $conn->prepare(
        "INSERT INTO farmers
        (farmer_id, name, phone, address)
        VALUES (?, ?, ?, ?)"
    );


    $stmt->bind_param(
        "isss",
        $next_id,
        $name,
        $phone,
        $address
    );


    $stmt->execute();

    header(
        "Location: farmers.php"
    );

    exit;
}


/* DELETE FARMER */

if (isset($_GET['delete'])) {

    $id =
        intval($_GET['delete']);


    $stmt = $conn->prepare(
        "DELETE FROM farmers
         WHERE farmer_id = ?"
    );


    $stmt->bind_param(
        "i",
        $id
    );


    $stmt->execute();

    header(
        "Location: farmers.php"
    );

    exit;
}

?>


<!DOCTYPE html>

<html>

<head>

<title>Farmers</title>

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
Farmers
</h1>


<div class="container">


<form
    method="POST"
    class="form"
>

<input
    type="text"
    name="name"
    placeholder="Farmer Name"
    required
>


<input
    type="text"
    name="phone"
    placeholder="Phone"
>


<input
    type="text"
    name="address"
    placeholder="Address"
>


<button
    type="submit"
    name="add"
>
Add Farmer
</button>

</form>


<table>

<tr>

<th>
ID
</th>

<th>
Name
</th>

<th>
Phone
</th>

<th>
Address
</th>

<th>
Action
</th>

</tr>


<?php

$result =
    $conn->query(
        "SELECT *
         FROM farmers
         ORDER BY farmer_id DESC"
    );


while (
    $row =
    $result->fetch_assoc()
) {

?>


<tr>

<td>
<?php
echo $row['farmer_id'];
?>
</td>


<td>
<?php
echo htmlspecialchars(
    $row['name']
);
?>
</td>


<td>
<?php
echo htmlspecialchars(
    $row['phone']
);
?>
</td>


<td>
<?php
echo htmlspecialchars(
    $row['address']
);
?>
</td>


<td>

<a
    class="delete"
    href="farmers.php?delete=<?php echo $row['farmer_id']; ?>"
    onclick="return confirm('Delete this farmer?')"
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