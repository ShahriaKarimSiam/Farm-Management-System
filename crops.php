<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include "config.php";


// =====================================================
// ADD CROP
// =====================================================

if (isset($_POST['add'])) {

    $name = trim($_POST['crop_name']);
    $type = trim($_POST['crop_type']);
    $season = trim($_POST['season']);


    // -------------------------------------------------
    // Find the smallest available ID
    // -------------------------------------------------

    $next_id = getNextAvailableId($conn, 'crops', 'crop_id');


    // -------------------------------------------------
    // Insert crop using the available ID
    // -------------------------------------------------

    $stmt = $conn->prepare(
        "INSERT INTO crops
        (crop_id, crop_name, crop_type, season)
        VALUES (?, ?, ?, ?)"
    );


    $stmt->bind_param(
        "isss",
        $next_id,
        $name,
        $type,
        $season
    );


    $stmt->execute();


    header("Location: crops.php");

    exit;
}



// =====================================================
// DELETE CROP
// =====================================================

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);


    $stmt = $conn->prepare(
        "DELETE FROM crops
         WHERE crop_id = ?"
    );


    $stmt->bind_param(
        "i",
        $id
    );


    $stmt->execute();


    header("Location: crops.php");

    exit;
}

?>


<!DOCTYPE html>

<html>

<head>

<title>Crops</title>

<link
    rel="stylesheet"
    href="style.css"
>

</head>


<body>


<!-- =================================================
     SIDEBAR
================================================= -->

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



<!-- =================================================
     MAIN CONTENT
================================================= -->

<div class="main">


<h1>
Crops
</h1>



<div class="container">


<!-- =================================================
     ADD CROP FORM
================================================= -->

<form
    method="POST"
    class="form"
>


<input
    type="text"
    name="crop_name"
    placeholder="Crop Name"
    required
>


<input
    type="text"
    name="crop_type"
    placeholder="Crop Type"
>


<input
    type="text"
    name="season"
    placeholder="Season"
>


<button
    type="submit"
    name="add"
>
Add Crop
</button>


</form>



<!-- =================================================
     CROP TABLE
================================================= -->

<table>


<tr>

<th>
ID
</th>

<th>
Crop
</th>

<th>
Type
</th>

<th>
Season
</th>

<th>
Action
</th>

</tr>



<?php


$result = $conn->query(
    "SELECT *
     FROM crops
     ORDER BY crop_id DESC"
);


while (
    $row = $result->fetch_assoc()
) {

?>


<tr>


<td>

<?php

echo $row['crop_id'];

?>

</td>



<td>

<?php

echo htmlspecialchars(
    $row['crop_name']
);

?>

</td>



<td>

<?php

echo htmlspecialchars(
    $row['crop_type']
);

?>

</td>



<td>

<?php

echo htmlspecialchars(
    $row['season']
);

?>

</td>



<td>


<a
    class="delete"
    href="crops.php?delete=<?php echo $row['crop_id']; ?>"
    onclick="return confirm('Delete this crop?')"
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