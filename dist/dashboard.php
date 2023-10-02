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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <header class="bg-gray-800 text-white py-2">

    </header>

    <div class="container max-w-screen-md bg-white rounded-lg shadow-md p-6 my-4 mx-2 text-center">
        <?php
        echo "Hello <b class='text-2xl'>{$user['name']}</b>, welcome to Awa Awa Map";

        ?>

        <a href="logout.php" class="bg-gray-800 text-white px-6 py-2 rounded mt-6 inline-block">Logout</a><br>

        <!-- Add a link/button to navigate to index.map.php -->
        <a href="map_vol3.php" class="bg-blue-500 text-white px-6 py-2 rounded mt-4 inline-block hover:bg-blue-700">Go to Map Page</a>
    </div>

</body>

</html>