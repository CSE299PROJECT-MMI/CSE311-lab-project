<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

if (!$user) {
   
    echo "User not found!";
    exit();
}


$exams_stmt = $conn->prepare("SELECT * FROM exams WHERE is_active = 1");
$exams_stmt->execute();
$exams = $exams_stmt->fetchAll();


$results_stmt = $conn->prepare("SELECT * FROM exam_results WHERE user_id = :user_id");
$results_stmt->execute(['user_id' => $user_id]);
$results = $results_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
       
        <div class="jumbotron text-center">
            <h1 class="display-4">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
            <p class="lead">Your Dashboard</p>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Profile Overview</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Birthday:</strong> <?php echo htmlspecialchars($user['birthday']); ?></p>
                <p><strong>Class:</strong> <?php echo htmlspecialchars($user['class']); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                <a href="profile_update.php" class="btn btn-primary mt-3">Update Profile</a>
                
        </div>

        
        <div class="card mb-4">
            <div class="card-header">
                <h5>Available Exams</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($exams)): ?>
                    <ul class="list-group">
                        <?php foreach ($exams as $exam): ?>
                            <li class="list-group-item">
                                <strong><?php echo htmlspecialchars($exam['title']); ?></strong>
                                <p><?php echo htmlspecialchars($exam['description']); ?></p>
                                <a href="take_exam.php?exam_id=<?php echo $exam['id']; ?>" class="btn btn-primary">Take Exam</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No active exams available.</p>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card mb-4">
            <div class="card-header">
                <h5>Your Exam Results</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($results)): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Exam</th>
                                <th>Score</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $result): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($result['exam_title']); ?></td>
                                    <td><?php echo htmlspecialchars($result['score']); ?></td>
                                    <td><?php echo htmlspecialchars($result['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No exam results available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
