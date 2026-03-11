<?php
include "../config/db.php";

$name = trim($_POST['area-name'] ?? '');

if (empty($name)) {
    die("Area name is required.");
}

$name = mysqli_real_escape_string($conn, $name);

$sql = "INSERT INTO areas (name) VALUES ('$name')";
$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: /EstateFlow/areas.php");
    exit();
} else {
    die("Error adding area: " . mysqli_error($conn));
}
?>