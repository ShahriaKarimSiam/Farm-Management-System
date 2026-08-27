<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "farm_management_system";

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);

if ($conn->connect_error) {

    die(
        "Database Connection Failed: "
        . $conn->connect_error
    );

}

$conn->set_charset("utf8mb4");

// Returns the smallest available positive ID for a table.
function getNextAvailableId($conn, $table, $column)
{
    $allowed = [
        'farmers' => 'farmer_id',
        'farms' => 'farm_id',
        'crops' => 'crop_id',
        'fields' => 'field_id',
        'livestock' => 'animal_id',
        'workers' => 'worker_id',
        'equipment' => 'equipment_id',
        'expenses' => 'expense_id',
        'sales' => 'sale_id'
    ];

    if (!isset($allowed[$table]) || $allowed[$table] !== $column) {
        throw new Exception('Invalid ID configuration.');
    }

    $result = $conn->query(
        "SELECT `$column` FROM `$table` ORDER BY `$column` ASC"
    );

    $nextId = 1;

    while ($row = $result->fetch_assoc()) {
        $currentId = (int)$row[$column];

        if ($currentId === $nextId) {
            $nextId++;
        } elseif ($currentId > $nextId) {
            break;
        }
    }

    return $nextId;
}

?>