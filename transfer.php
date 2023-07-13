<?php

// ini_set("display_errors", 1); // For Debugging

session_start();
if ( !isset($_SESSION["user"]) || !$_SESSION["authenticated"] ) {
    header("Location: login.php");
    exit();
};

function html_response( $msg ) {
    $html = '<!DOCTYPE html><html lang="en">';
    $html .= '<head>';
    $html .= '<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Transfer - Pirate Bank 2</title>';
    $html .= '<style>body { margin: 0; text-align: center; } div, a { display: inline-block; margin: 10px; padding: 15px 10px; text-align: left; ';
    $html .= 'border-radius: 5px; background-color: #f2f2f2; } a { color: #000; } h3 { margin: 10px; color: red; } h2 { color: green; }</style>';
    $html .= '</head>';
    $html .= '<body><div>' . $msg . '</div><br><a href="index.php">Go Home</a></body>';
    $html .= '</html>';
    return $html;
};

if ( isset($_POST["to_account"]) && isset($_POST["amount"]) ) {

    // Transfer
    require("db.php");

    if ( !filter_var($_POST["to_account"], FILTER_VALIDATE_EMAIL) || preg_match("/^[a-z0-9_@.]+$/", $_POST["to_account"]) !== 1 ) {
        die(html_response("<h3>Please Enter a valid E-mail</h3>"));
    };

    $to_account = stripslashes($_POST["to_account"]);
    $to_account = $db->escapeString($to_account);
    if ( !$db->querySingle("SELECT id FROM users WHERE email='$to_account';") ) {
        die(html_response("<h3>User does not exist!</h3>"));
    };

    $amount = (int)$_POST["amount"];
    if ( $amount < 0 ) {
        die(html_response("<h3>Negative numbers are not allowed</h3>"));
    };

    $balance = $db->querySingle("SELECT balance FROM users WHERE email='" . $_SESSION["user"] . "';");
    // echo gettype($balance);
    if ($amount > $balance) {
        die(html_response("<h3>Insufficient funds!</h3>"));
    };

    $query = "UPDATE users SET balance=balance-" . $amount . " WHERE email='" . $_SESSION["user"] . "';";
    if ( $db->exec($query) === TRUE ) {

        $query = "UPDATE users SET balance=balance+" . $amount . " WHERE email='" . $to_account . "';";
        if ( $db->exec($query) === TRUE ) {

            $query = "INSERT INTO transactions (from_account, to_account, amount, transaction_date) VALUES ('" . $_SESSION["user"] . "', '$to_account', '$amount', '" . date("Y-m-d H:i:s") . "');";
            if ( $db->exec($query) === TRUE ) {
                die(html_response('<h2>Transfer Complete!</h2><p>Amount: $' . $amount . '</p><p>From Account: ' . $_SESSION["user"] . "</p><p>To Account: $to_account</p>"));
            };

        };

    };

    die(html_response("<h3>Error while updating the record!</h3>"));

} else {
    header("Location: index.php");
};
