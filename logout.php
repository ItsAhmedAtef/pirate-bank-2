<?php

// ini_set("display_errors", 1); // For Debugging

session_start();
session_unset();
session_destroy();

header("Location: login.php");
