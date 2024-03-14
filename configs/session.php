<?php
// Check if the session is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// If the user is already logged in, redirect to welcome page
if (isset($_SESSION["userid"]) && $_SESSION["userid"] === true) {
    header("location: public/welcome.php");
    die();
}
