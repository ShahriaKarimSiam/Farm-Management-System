<?php

include "config.php";

$username = "admin";
$password = "admin123";
$full_name = "Farm Administrator";

$hashed_password =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );

$stmt = $conn->prepare(
    "INSERT INTO users
    (username, password, full_name)
    VALUES (?, ?, ?)"
);

$stmt->bind_param(
    "sss",
    $username,
    $hashed_password,
    $full_name
);

if ($stmt->execute()) {

    echo "Admin account created successfully.<br><br>";

    echo "Username: admin<br>";
    echo "Password: admin123<br><br>";

    echo "Delete create_admin.php after this.";

} else {

    echo "Error: " . $conn->error;

}

?>