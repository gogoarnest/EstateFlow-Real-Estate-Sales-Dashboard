<?php
include "../config/db.php";

$name = trim($_POST['user-name'] ?? '');
$email = trim($_POST['user-email'] ?? '');
$password = trim($_POST['user-password'] ?? '');
$role = trim($_POST['user-role'] ?? '');

if (empty($name) || empty($email) || empty($password) || empty($role)) {
    die("All fields are required.");
}

$name = mysqli_real_escape_string($conn, $name);
$email = mysqli_real_escape_string($conn, $email);
$password = mysqli_real_escape_string($conn, $password);
$role = mysqli_real_escape_string($conn, $role);

/* Check if email already exists */
$checkQuery = "SELECT * FROM users WHERE email = '$email'";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    die("This email already exists.");
}

/* Insert user */
$sql = "INSERT INTO users (name, email, password, role)
VALUES ('$name', '$email', '$password', '$role')";

$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: /EstateFlow/users.php");
    exit();
} else {
    die("Error adding user: " . mysqli_error($conn));
}
?>