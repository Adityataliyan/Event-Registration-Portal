<?php
session_start(); // Required to use $_SESSION

$servername = "localhost";
$username = "root";
$password = "Aditya#123";
$database = "firstproject";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the form actually sent these fields
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

           if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

               
                header("Location: http://localhost/timepass/signup/dashboard.php");
                exit();
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "User not found.";
        }

        $stmt->close();
    } else {
        echo "Please fill in all fields.";
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Modern Animated Login Form</title>
  <link rel="stylesheet" href="./styleindex.css">

</head>
<body>
<!-- partial:index.partial.html -->
<div class="box">
  <div class="login">
    <form class="loginBx" method="POST" action="">
  <h2>
    <i class="fa-solid fa-right-to-bracket"></i>
    Login
    <i class="fa-solid fa-heart"></i>
  </h2>
  <input name="email" type="email" placeholder="Email" required>
  <input name="password" type="password" placeholder="Password" required>
  <input type="submit" value="Sign in" />
  <div class="group">
    <a href="#">Forgot Password</a>
    <a href="http://localhost/timepass/signup/">Sign up</a>
  </div>
</form>

  </div>
</div>
<!-- partial -->
  
</body>
</html>
