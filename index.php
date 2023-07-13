<?php

// ini_set("display_errors", 1); // For Debugging

session_start();
if ( !isset($_SESSION["user"]) || !$_SESSION["authenticated"] ) {
    header("Location: login.php");
    exit();
};

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Pirate Bank 2</title>
    <style>
        body {
            margin: 0;
            text-align: center;
        }
        .topnav {
            display: flex;
            overflow: hidden;
            justify-content: space-between;
            background-color: #333;
        }
        .topnav p {
            padding: 0 16px;
            font-size: 17px;
            color: green;
        }
        a {
            display: inline-block;
            margin-bottom: 10px;
            padding: 14px 16px;
            font-size: 17px;
            background-color: #f2f2f2;
            color: #000;
        }
        .topnav a {
            margin-bottom: 0;
            text-decoration: none;
            background-color: unset;
            color: #f2f2f2;
        }
        h3 {
            color: green;
        }
        form, #balance {
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
        @media only screen and (max-width: 600px) {
            form, #balance {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <div class="topnav">
        <p>Pirate Bank 2</p>
        <a href="logout.php">Logout</a>
    </div>
    <div>
        <?php
            require("db.php");
            $query = "SELECT balance FROM users WHERE email='" . $_SESSION["user"] . "';";
            $balance = $db->querySingle($query);
            $vip_msg = "";
            if ( $balance >= 1000000 ) {
                $vip_msg = "<h3>Congratulations! You're a millionaire!</h3>";
            };

            echo '<div id="balance">' . $vip_msg . '<p>Your balance is: $' . $balance . '</p></div>';

        ?>
        <form method="POST" action="transfer.php">
            <h2>Transfer Money</h2>

            <label for="to_account">To</label>
            <input id="to_account" type="email" name="to_account" placeholder="Enter receiver's email" required="required">

            <label for="amount">Amount</label>
            <input id="amount" type="number" name="amount" required="required">

            <input type="submit" name="submit" value="Send">
        </form>
        <a href="transactions.php">My Transactions History</a>
    </div>
</body>
</html>