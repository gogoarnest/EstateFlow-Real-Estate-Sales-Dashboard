<?php
include "../config/db.php";

$compound_name = trim($_POST['compound-name'] ?? '');
$area = trim($_POST['area'] ?? '');
$other_area = trim($_POST['other-area'] ?? '');

if (empty($compound_name)) {
    die("Compound name is required.");
}

if ($area === "other") {
    if (empty($other_area)) {
        die("Please enter the new area name.");
    }

    $area = $other_area;

    $checkArea = mysqli_query($conn, "SELECT * FROM areas WHERE name = '" . mysqli_real_escape_string($conn, $area) . "'");
    if (mysqli_num_rows($checkArea) == 0) {
        $insertArea = "INSERT INTO areas (name) VALUES ('" . mysqli_real_escape_string($conn, $area) . "')";
        mysqli_query($conn, $insertArea);
    }
}

if (empty($area)) {
    die("Area is required.");
}

$compound_name = mysqli_real_escape_string($conn, $compound_name);
$area = mysqli_real_escape_string($conn, $area);

$sql = "INSERT INTO compounds (name, area) VALUES ('$compound_name', '$area')";
$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: /EstateFlow/compounds.php");
    exit();
} else {
    die("Error adding compound: " . mysqli_error($conn));
}
?>