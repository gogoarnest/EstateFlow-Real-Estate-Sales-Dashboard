<?php
include "../config/db.php";

$name = trim($_POST['team-name'] ?? '');

if (empty($name)) {
    die("Team name is required.");
}

$name = mysqli_real_escape_string($conn, $name);

$sql = "INSERT INTO teams (name) VALUES ('$name')";
$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: /EstateFlow/teams.php");
    exit();
} else {
    die("Error adding team: " . mysqli_error($conn));
}
?>