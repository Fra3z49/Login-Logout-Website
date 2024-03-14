<?php

require_once "../configs/db_connection.php";
require_once "../configs/session.php";

$error = ''; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $fullname = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST["confirm_password"]);
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Prepare a statement for checking existing users
    if ($query = $db->prepare("SELECT * FROM users WHERE email = ?")) {
        $query->bind_param('s', $email);
        $query->execute();
        $query->store_result();

        if ($query->num_rows > 0) {
            $error = '<p class="error">The email address is already registered!</p>';
        } else {
            // Validate password length
            if (strlen($password) < 8) {
                $error .= '<p class="error">Password must have at least 8 characters.</p>';
            }

            // Validate confirm password
            if (empty($confirm_password)) {
                $error .= '<p class="error">Please confirm your password.</p>';
            } elseif ($password != $confirm_password) {
                $error .= '<p class="error">Password did not match.</p>';
            }

            if (empty($error)) {
                $insertQuery = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $insertQuery->bind_param("sss", $fullname, $email, $password_hash);

                if ($insertQuery->execute()) {
                    $error = '<p class="success">Your registration was successful!</p>';
                } else {
                    $error = '<p class="error">Something went wrong!</p>';
                }

                $insertQuery->close(); // Close insertQuery inside its definition scope
            }
        }

        $query->close();
    }

    // Close DB connection
    mysqli_close($db);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <div>
        <h2>Register</h2>
        <p>Please fill this form to create an account.</p>
        <?php 
        if (!empty($error)) {
            echo $error;
        }
        ?>
        <form action="" method="post">
            <div>
                <label for="name">Full Name</label>
                <input type="text" name="name" required>
            </div>
            <div>
                <label for="email">Email Address</label>
                <input type="email" name="email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" required>
            </div>
            <div>
                <button type="submit" name="submit">Register</button>
            </div>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>
