<?php
session_start();

// Hardcoded admin credentials
$admin_email = "admin";
$admin_password = "admin";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['is_admin'] = true;
        header('Location: daftar_karyawan.php');
        exit();
    } else {
        $error_message = "Invalid admin. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="masuk.css">
</head>
<body>
    <div id="container">
        <h1>Admin Login</h1>
        <form method="POST" action="">
            <div>
                <label for="email">Username</label>
                <input id="email" type="text" name="email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>
            <div>
                <input id="login" type="submit" name="login" value="Login">
            </div>
        </form>
        <?php if (isset($error_message)) : ?>
            <div id="error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
