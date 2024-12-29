<?php
require 'connection.php';

// Fungsi validasi
function validateName($name) {
    return strlen($name) >= 4;
}

function validateEmail($email) {
    return preg_match("/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z]{2,6})$/", $email);
}

function validatePasswords($pass1, $pass2) {
    return $pass1 == $pass2 && strlen($pass1) > 5 && strpos($pass1, ' ') === false;
}

// Logika registrasi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if (validateName($name) && validateEmail($email) && validatePasswords($password, $password2)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "<div id='success'>Registration successful! You can now <a href='login.php'>login</a>.</div>";
        } else {
            echo "<div id='error'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div id='error'>Invalid data. Please try again.</div>";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="masuk.css">
</head> 
<body>
    <div id="container">
        <h1>Registration</h1>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])): ?>
            <?php if (!validateName($_POST['name']) || !validateEmail($_POST['email']) || !validatePasswords($_POST['password'], $_POST['password2'])): ?>
            <div id="error">
                <ul>
                <?php if (!validateName($_POST['name'])): ?>
                    <li><strong>Invalid Name:</strong> We want names with more than 3 letters!</li>
                <?php endif ?>
                <?php if (!validateEmail($_POST['email'])): ?>
                    <li><strong>Invalid E-mail:</strong> Stop cowboy! Type a valid e-mail please :P</li>
                <?php endif ?>
                <?php if (!validatePasswords($_POST['password'], $_POST['password2'])): ?>
                    <li><strong>Passwords are invalid:</strong> Passwords doesn't match or are invalid!</li>
                <?php endif ?>
                </ul>
            </div>
            <?php endif ?>
        <?php endif ?>

        <form method="post" id="customForm" action="">
            <div>
            <label for="name">Name</label>
            <input id="name" name="name" type="text" />
            <span id="nameInfo">What's your name?</span>
            </div>
            <div>
            <label for="email">E-mail</label>
            <input id="email" name="email" type="text" />
            <span id="emailInfo">Valid E-mail please, you will need it to log in!</span>
            </div>
            <div>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" />
            <span id="passwordInfo">At least 5 characters: letters, numbers and '_'</span>
            </div>
            <div>
            <label for="password2">Confirm Password</label>
            <input id="password2" name="password2" type="password" />
            <span id="password2Info">Confirm password</span>
            </div>
            <div>
            <input id="send" name="send" type="submit" value="Send" />
            </div>
        </form>
        <p>do you have an account? <a href="login.php">Login here</a>.</p>
        </div>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script src="js/validation.js"></script>
    </body>
</html>