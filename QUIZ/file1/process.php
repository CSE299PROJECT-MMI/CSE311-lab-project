<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'register') {
        $name = $_POST['name'];
        $class = $_POST['class'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $phone_number = $_POST['phone_number'];
        $birthday = $_POST['birthday'];
        $address = $_POST['address'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Email already exists.'); window.history.back();</script>";
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO users (name, class, email, password, phone_number, birthday, address) VALUES (:name, :class, :email, :password, :phone_number, :birthday, :address)");
        $stmt->execute([
            'name' => $name,
            'class' => $class,
            'email' => $email,
            'password' => $password,
            'phone_number' => $phone_number,
            'birthday' => $birthday,
            'address' => $address
        ]);

        echo "<script>alert('Registration successful!'); window.location.href = 'login.php';</script>";
    }

    elseif ($action === 'userLogin') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            echo "<script>alert('User login successful!'); window.location.href = 'user-dashboard.html';</script>";
        } else {
            echo "<script>alert('Invalid email or password.'); window.history.back();</script>";
        }
    }

    elseif ($action === 'adminLogin') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($email === 'Admin420' && $password === 'NSUstudent420') {
            $_SESSION['admin'] = true;
            echo "<script>alert('Admin login successful!'); window.location.href = 'admin-dashboard.html';</script>";
        } else {
            echo "<script>alert('Invalid admin credentials.'); window.history.back();</script>";
        }
    }
}
?>
