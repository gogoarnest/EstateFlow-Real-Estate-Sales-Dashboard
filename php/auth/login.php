<?php
session_start();
include "../config/db.php";

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);

    if ($password == $user['password']) {
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        header("Location: /EstateFlow/dashboard.php");
        exit();
    } else {
        header("Location: /EstateFlow/login.php?error=1");
        exit();
    }
} else {
    header("Location: /EstateFlow/login.php?error=1");
    exit();
}
?>