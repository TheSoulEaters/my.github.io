<?php
// Database connection
$servername = "localhost";
$username = "root";  // Default username for XAMPP
$password = "";      // Default password for XAMPP (empty string)
$dbname = "user_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$signupMessage = "";
$loginMessage = "";

// Handle Signup
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $signupMessage = "Signup successful!";
    } else {
        $signupMessage = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle Login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $loginMessage = "Login successful!";
        } else {
            $loginMessage = "Invalid password.";
        }
    } else {
        $loginMessage = "No user found with that username.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Sign Up</title>
    <style>
        /* CSS Styles for the form layout */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f0f0f0;
        }

        .container {
            width: 800px;
            display: flex;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            width: 50%;
            padding: 40px;
            background-color: #fff;
        }

        .form-container h2 {
            font-size: 24px;
            margin-bottom: 10px;
            position: relative;
        }

        .form-container h2::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 50px;
            height: 3px;
            background-color: #000;
        }

        .form-container label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .form-container .toggle-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }

        .welcome-container {
            width: 50%;
            background-color: #000;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .welcome-container h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .welcome-container p {
            font-size: 14px;
            text-align: center;
            line-height: 1.5;
        }
        
        .message {
            color: green;
            text-align: center;
            margin-top: 10px;
        }
        
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Welcome Section -->
    <div class="welcome-container">
        <h1>WELCOME BACK!</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti, rem?</p>
    </div>

    <!-- Login Form -->
    <div class="form-container" id="loginForm">
        <h2>Login</h2>
        <form method="POST" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password</label>
            <input type="password" id="loginPassword" name="password" required>
            <input type="checkbox" onclick="togglePassword('loginPassword')"> Show Password

            <button type="submit" name="login">Login</button>
        </form>
        <?php if ($loginMessage): ?>
            <p><?php echo $loginMessage; ?></p>
        <?php endif; ?>
        <a href="#signupForm" onclick="toggleForm()">Don't have an account? Sign up</a>
    </div>

    <!-- Sign-Up Form -->
    <div class="form-container" id="signupForm" style="display: none;">
        <h2>Sign Up</h2>
        <form method="POST" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="signupPassword" name="password" required>
            <input type="checkbox" onclick="togglePassword('signupPassword')" style=""> Show Password

            <button type="submit" name="signup">Sign Up</button>
        </form>
        <?php if ($signupMessage): ?>
            <p><?php echo $signupMessage; ?></p>
        <?php endif; ?>
        <a href="#loginForm" onclick="toggleForm()">Already have an account? Login</a>
    </div>
</div>

<script>
    // JavaScript to toggle between login and sign-up forms
    function toggleForm() {
        const loginForm = document.getElementById("loginForm");
        const signupForm = document.getElementById("signupForm");

        if (loginForm.style.display === "none") {
            loginForm.style.display = "block";
            signupForm.style.display = "none";
        } else {
            loginForm.style.display = "none";
            signupForm.style.display = "block";
        }
    }

    // JavaScript function to show/hide password
    function togglePassword(passwordFieldId) {
        const passwordField = document.getElementById(passwordFieldId);
        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
</script>

</body>
</html>