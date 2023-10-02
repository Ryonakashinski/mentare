<?php include('config.php'); ?>
<?php include('firebaseRDB.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>

    <!-- Include the Tailwind CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <?php
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($name == "") {
        echo "Name is required";
    } else if ($email == "") {
        echo "Email is required";
    } else if ($password == "") {
        echo 'Password is required';
    } else {
        $rdb = new firebaseRDB($databaseURL);
        $retrieve = $rdb->retrieve('/user', 'email', 'EQUAL', $email);
        $data = json_decode($retrieve, true);

        if (!empty($data)) {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative' role='alert'>
                <strong class='font-bold'>Email already used!</strong>
                <span class='block sm:inline'> Please use a different email.</span>
              </div>";
        } else {
            $insert = $rdb->insert('/user', [
                "name" => $name,
                "email" => $email,
                "password" => $password
            ]);

            $result = json_decode($insert, true);
            if (isset($result['name'])) {
                echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative' role='alert'>
                    <strong class='font-bold'>Signup success!</strong>
                    <span class='block sm:inline'> Please <a href='login.php' class='text-blue-500 hover:underline'>login</a>.</span>
                  </div>";
            } else {
                echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative' role='alert'>
                    <strong class='font-bold'>Signup failed!</strong>
                    <span class='block sm:inline'> Please try again.</span>
                  </div>";
            }
            echo $insert;
        }
    }
    ?>
</body>

</html>