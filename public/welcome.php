<?php
session_start();

// Check if the user is not logged in, then redirect to login page
if (!isset($_SESSION["userid"])) {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <div>
        <h1>Hello, <strong><?php echo htmlspecialchars($_SESSION["useremail"]); ?></strong>. Welcome to our site. You are successfully logged in.</h1>
        <p><a href="logout.php">Log Out</a></p>
    </div>
</body>
</html>
