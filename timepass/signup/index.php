<?php

$servername = "localhost";
$username = "root";
$password = "Aditya#123";
$database = "firstproject";

// Connection 
$conn = new mysqli($servername,$username, $password, $database);

if ($conn->connect_error) {
  die("Connection failed: " 
      . $conn->connect_error);
}
// echo "Connected successfully";
if($_SERVER['REQUEST_METHOD']=="POST"){
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $cpassword = $_POST['cpassword'];

  if ($password !== $cpassword) {
      echo "Passwords do not match.";
      exit;
  }
  
    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "❌ This email is already registered.";
        exit;
    }
    $checkStmt->close();

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");


    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

  
    $stmt->bind_param("sss", $username, $email, $hashedPassword);


    // Execute
    if ($stmt->execute()) {
        header("Location: dashboard.php");  // Note: no space after "Location:"
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}


  // if($result1){
  //   echo " record inserted succesfully";
  // }else{
  //   echo " record inserted failed";
  // }

 




?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up Form</title>
  <link rel="stylesheet" href="./stylesigup.css">
</head>
<body>
  <div class="box">
    <div class="login">
      <form class="loginBx" method="POST">
        <h2>
          <i class="fa-solid fa-user-plus"></i>
          Sign Up
        </h2>
        <input name="username" type="text" placeholder="Username">
        <input name="email" type="email" placeholder="Email">
        <input name="password" type="password" placeholder="Password">
        <input name="cpassword" type="password" placeholder="Confirm Password">
        <input type="submit" value="Register" />
        <div class="group">
          <a href="#">Already have an account?</a>
          <a href="http://localhost/timepass">Login</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
