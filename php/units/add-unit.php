<?php
include "../config/db.php";

/* Read form data */
$title = trim($_POST['unit-title'] ?? '');
$area = trim($_POST['area'] ?? '');
$compound = trim($_POST['compound'] ?? '');
$type = trim($_POST['unit-type'] ?? '');
$price = trim($_POST['price'] ?? '');
$area_size = trim($_POST['area-size'] ?? '');
$bedrooms = trim($_POST['bedrooms'] ?? '');
$bathrooms = trim($_POST['bathrooms'] ?? '');
$status = trim($_POST['status'] ?? '');
$developer = trim($_POST['developer'] ?? '');
$description = trim($_POST['description'] ?? '');

/* Handle "other" values */
if ($area === "other" && !empty($_POST['other-area'])) {
    $area = trim($_POST['other-area']);
}

if ($compound === "other" && !empty($_POST['other-compound'])) {
    $compound = trim($_POST['other-compound']);
}

if ($type === "other" && !empty($_POST['other-type'])) {
    $type = trim($_POST['other-type']);
}

/* Basic validation */
if (
    empty($title) ||
    empty($area) ||
    empty($compound) ||
    empty($type) ||
    $price === '' ||
    $area_size === '' ||
    $bedrooms === '' ||
    $bathrooms === '' ||
    empty($status)
) {
    die("Please fill in all required fields.");
}

/* Escape data */
$title = mysqli_real_escape_string($conn, $title);
$area = mysqli_real_escape_string($conn, $area);
$compound = mysqli_real_escape_string($conn, $compound);
$type = mysqli_real_escape_string($conn, $type);
$price = mysqli_real_escape_string($conn, $price);
$area_size = mysqli_real_escape_string($conn, $area_size);
$bedrooms = mysqli_real_escape_string($conn, $bedrooms);
$bathrooms = mysqli_real_escape_string($conn, $bathrooms);
$status = mysqli_real_escape_string($conn, $status);
$developer = mysqli_real_escape_string($conn, $developer);
$description = mysqli_real_escape_string($conn, $description);

/* Insert unit */
$sql = "INSERT INTO units 
(title, area, compound, type, price, area_size, bedrooms, bathrooms, status, developer, description)
VALUES
('$title', '$area', '$compound', '$type', '$price', '$area_size', '$bedrooms', '$bathrooms', '$status', '$developer', '$description')";

$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: /EstateFlow/units.php");
    exit();
} else {
    die("Error saving unit: " . mysqli_error($conn));
}
?>