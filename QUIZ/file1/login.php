<?php
session_start();
include 'config.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    
    if (empty($email) || empty($password)) {
        echo "Please fill out both fields!";
        exit();
    }

    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

       
        header("Location: user-dashboard.php");
        exit();
    } else {
        
        echo "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles2.css">
</head>
<body>
    <div class="container">
        <h2>Student Login</h2>

        
        <form method="POST" action="login.php">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button><br>
        </form>

        
        <p>Don't have an account? <a href="register-login.php">Register here</a></p>
    </div>
</body>

</html>
