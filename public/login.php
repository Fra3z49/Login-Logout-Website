<?php 
session_start(); // Ensure session start is at the very top to avoid headers already sent error

require_once "../configs/db_connection.php";
// session.php is not needed here since we're starting the session at the top

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate email and password are not empty
    if (empty($email)) {
        $error = '<p class="error">Please enter email.</p>';
    }
    if (empty($password)) {
        $error .= '<p class="error">Please enter your password.</p>'; // Append to any existing error
    }

    // Proceed if there are no errors
    if (empty($error)) {
        if ($query = $db->prepare("SELECT id, email, password FROM users WHERE email = ?")) {
            $query->bind_param('s', $email);
            $query->execute();
            $result = $query->get_result();
            if ($row = $result->fetch_assoc()) {
                if (password_verify($password, $row['password'])) {
                    // Set session variables
                    $_SESSION["userid"] = $row['id'];
                    $_SESSION["useremail"] = $row['email']; // Assuming you want to also keep email in session
                    // Assuming 'name' or a similar field to personalize the welcome message
                    // $_SESSION["name"] = $row['name'];

                    header("location: welcome.php");
                    exit;
                } else {
                    $error = '<p class="error">The password is not valid.</p>';
                }
            } else {
                $error = '<p class="error">No user found with that email address.</p>';
            }
            $query->close();
        }
    }
    mysqli_close($db);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div>
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="" method="post">
            <div>
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <button type="submit" name="submit">Login</button>
            </div>
            <?php if (!empty($error)) echo $error; ?>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
