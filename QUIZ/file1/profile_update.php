<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];
    $class = $_POST['class'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    // Update the user's profile in the database
    $update_stmt = $conn->prepare("UPDATE users SET name = :name, email = :email, birthday = :birthday, class = :class, phone_number = :phone_number, address = :address WHERE id = :id");
    $update_stmt->execute([
        'name' => $name,
        'email' => $email,
        'birthday' => $birthday,
        'class' => $class,
        'phone_number' => $phone_number,
        'address' => $address,
        'id' => $user_id
    ]);

    // Redirect to the dashboard
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Update Profile</h2>
        <form action="profile_update.php" method="post">
            
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>

            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

           
            <div class="form-group">
                <label for="birthday">Birthday:</label>
                <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo htmlspecialchars($user['birthday']); ?>" required>
            </div>

            
            <div class="form-group">
                <label for="class">Class Name:</label>
                <input type="text" class="form-control" id="class" name="class" value="<?php echo htmlspecialchars($user['class']); ?>" required>
            </div>

            
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="tel" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
            </div>

           
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>

           
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
