<?php
session_start();

require 'connection.php';

// Logika login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mendapatkan password yang cocok dengan email
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $hashedPassword);
        $stmt->fetch();

        // Verifikasi password
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['id'] = $userId;
            $_SESSION['email'] = $email;

            echo "<div id='success'>Login successful! Welcome back.</div>";
            
            header('Location: list_wisata.php');
            exit();
        } else {
            echo "<div id='error'>Invalid credentials. Please try again.</div>";
        }
    } else {
        echo "<div id='error'>No account found with this email address.</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="masuk.css">
</head> 
<body>
    <div id="container">
        <h1>Login</h1>
        <form method="post" action="">
            <div>
                <label for="email">E-mail</label>
                <input id="email" name="email" type="text" required />
                <span id="emailInfo">Valid E-mail please, you will need it to log in!</span>
            </div>
            <div>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required />
                <span id="passwordInfo">At least 5 characters: letters, numbers and '_'</span>
            </div>
            <div>
                <input id="login" name="login" type="submit" value="Login" />
            </div>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>
