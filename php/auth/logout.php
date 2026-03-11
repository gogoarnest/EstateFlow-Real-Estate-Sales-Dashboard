<?php
session_start();
session_destroy();

header("Location: /EstateFlow/login.php");
exit();
?>