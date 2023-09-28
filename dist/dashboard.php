<?php
// dashboard.php

include('config.php');
include('firebaseRDB.php');

if (!isset($_SESSION['user'])) {
    header('location: login.php');
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firebase Authentication</title>
    <link rel="stylesheet" type="text/css" href="../css/signup.css" />
</head>
<body>
    <?php
    echo "Hello <b>{$user['name']}</b>, welcome to KajaCasa";
    ?>

    <a href="logout.php">Logout</a><br>
    
    <!-- Add a link/button to navigate to index.map.php -->
    <a href="map.php">Go to Map Page</a>

   
</body>
</html>
