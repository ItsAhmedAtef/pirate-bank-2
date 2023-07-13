<?php

// ini_set("display_errors", 1); // For Debugging

session_start();
if ( !isset($_SESSION["user"]) || !$_SESSION["authenticated"] ) {
    header("Location: login.php");
    exit();
};

function validate_order_type( $input ) {
    if ( $input === "ASC" || $input === "DESC" ) {
        return true;
    } else {
        return false;
    };
};

$order_type = "";
if ( isset($_GET["order_type"]) ) {
    validate_order_type($_GET["order_type"]);
    $order_type = $_GET["order_type"];
};

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Transactions History - Pirate Bank 2</title>
    <style>
        div {
            overflow: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td, table th {
            padding: 8px;
            border: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table tr:hover {
            background-color: #ddd;
        }
        table th {
            padding-top: 12px;
            padding-bottom: 12px;
            background-color: #04AA6D;
            color: #fff;
        }
        p {
            text-align: center;
        }
        a {
            display: inline-block;
            margin: auto;
            padding: 10px 16px;
            font-size: 17px;
            background-color: #f2f2f2;
            color: #000;
        }
    </style>
</head>
<body>
    <h1>Bank Statement</h1>
    <h3>Here is a list of your transactions</h3>
    <div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    require("db.php");
                    $user = $_SESSION["user"];
                    $query = "SELECT * FROM transactions WHERE from_account='$user' OR to_account='$user' ORDER BY transaction_date $order_type";
                    $result = $db->query($query);
                    if ($result) {
                        while ( $res = $result->fetchArray(SQLITE3_ASSOC) ) {
                        ?>
                        <tr>
                            <td><?php echo $res["transaction_date"]; ?></td>
                            <td><?php echo $res["from_account"]; ?></td>
                            <td><?php echo $res["to_account"]; ?></td>
                            <td>$<?php echo $res["amount"]; ?></td>
                        </tr>
                        <?php
                        };
                    };
                ?>
            </tbody>
        </table>
    </div>
    <p><a href="index.php">Go Home</a></p>
</body>
</html>