<?php

// ini_set("display_errors", 1); // For Debugging
$errors = array();

if ( isset($_POST["submit"]) ) {
    // If form submitted, insert values into the database.
    require("db.php");

    if ( !isset($_POST["email"]) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) || preg_match("/^[a-z0-9_@.]+$/", $_POST["email"]) !== 1 ) {
        $errors["email"] = "Please Enter a valid E-mail";
    };

    if ( !isset($_POST["password"]) || !is_string($_POST["password"]) || strlen($_POST["password"]) < 8 ) {
        $errors["password"] = "Please Enter a valid Password";
    } else if ( !isset($_POST["c_password"]) || $_POST["c_password"] !== $_POST["password"] ) {
        $errors["c_password"] = "Confirm Password must be valid and same as the password";
    };

    if ( empty($errors) ) {
        // remove backslashes
        // escape special characters in a string
        $email = stripslashes($_POST["email"]);
        $email = $db->escapeString($email);
        $password = stripslashes($_POST["password"]);
        $password = $db->escapeString($password);
        try {
            $result = $db->exec("INSERT INTO users (email, password) VALUES ('$email', '" . md5($password) . "');");
            if ($result) {
                header("Location: login.php?status=success");
                exit();
            } else {
                $errors["failed"] = "Error Creating User! Maybe email already exists, Try to use another email";
            };
        } catch (Exception $e) {
            // echo $e;
            $errors["failed"] = "Error Creating User!";
        };
    };

};

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pirate Bank 2</title>
    <style>
        body {
            margin: 0;
            text-align: center;
        }
        p {
            color: red;
        }
        form {
            width: 400px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            background-color: #f2f2f2;
        }
        input {
            display: inline-block;
            box-sizing: border-box;
            width: 100%;
            margin: 8px 0;
            padding: 12px 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type=submit] {
            width: 100%;
            padding: 14px 20px;
            border: none;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        a {
            display: inline-block;
            margin-bottom: 10px;
            padding: 10px 16px;
            font-size: 17px;
            background-color: #f2f2f2;
            color: #000;
        }
        @media only screen and (max-width: 600px) {
            form {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <div>
        <h1>Pirate Bank 2</h1>
        <h2>Register</h2>
        <?php
            foreach ( $errors as $error ) {
                echo "<p>" . $error . "</p>";
            };
        ?>
        <form method="POST" action="register.php">
            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" placeholder="Enter your email" required="required">

            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Enter your Password" minlength="8" required="required">

            <label for="c_password">Confirm Password</label>
            <input id="c_password" type="password" name="c_password" placeholder="Enter your Password again" minlength="8" required="required">

            <input type="submit" name="submit" value="Submit">
        </form>
        <a href="login.php">Already have an account?</a>
    </div>
</body>
</html>