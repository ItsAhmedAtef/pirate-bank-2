<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Pirate Bank 2</title>
    <style>
        body {
            text-align: center;
        }
        p {
            display: inline-block;
            margin: 10px;
            padding: 10px;
            border-radius: 5px;
            background-color: #f2f2f2;
            color: green;
        }
        #error {
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
<?php

// ini_set("display_errors", 1);

session_start();

if ( isset($_POST["user_id"]) && !isset($_POST["pin"]) ) {

    session_unset();
    $_SESSION["reset_user"] = (int)$_POST["user_id"];
    $_SESSION["reset_pin"] = random_int(0, 99999999);
    // send_pin_by_email($pin_code); // Commented temporarily
    echo '<p id="error">Pin code should have been sent.<br>if you did not receive it, you can try again tomorrow</p>';

};

if ( isset($_POST["pin"]) ) {

    $pin_code = (int)$_POST["pin"];
    $id = isset($_SESSION["reset_user"])? (int)$_SESSION["reset_user"]: 0;
    $password = random_int(0, 99999999);
    if (isset($_SESSION["reset_pin"]) && $_SESSION["reset_pin"] === $pin_code) {
        session_unset();
        require("db.php");
        $sql = "UPDATE users SET password='" . md5($password) . "' WHERE id=" . $id;
        if ($db->exec($sql) === TRUE) {
            echo "<p>Password Reset Successfully<br>Your new Password is: $password</p>";
        } else {
            echo '<p id="error">Error reseting password!</p>';
        };
    } else {
        echo '<p id="error">Incorrect Pin</p>';
    };

};

?>
    <h2>Reset your password</h2>
    <form method="POST" action="reset-password.php">
        <label for="user_id">User ID</label>
        <input id="user_id" type="number" name="user_id" placeholder="do you know your user id?" required="required">
        <input type="submit" name="submit" value="Submit">
    </form>

    <h2>Got the code? type it</h2>
    <form method="POST" action="reset-password.php">
        <label for="pin">Pin Code</label>
        <input id="pin" type="number" name="pin" placeholder="Enter the pin code" required="required">
        <input type="submit" name="submit" value="Reset now!">
    </form>
    <a href="index.php">Go Home</a>
</body>
</html>