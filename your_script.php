<?php
// Database connection
$host = 'localhost';
$db   = 'u663791915_testchqtttttt';
$user = 'u663791915_testchqtttttt';
$pass = 'Old@12345';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

// Get the posted email and password
$email = $_POST['email'];
$password = $_POST['password'];

// Check if user exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    // User exists, check password
    if ($user['password'] == $password) {
        // Password is correct, login the user and redirect to dashboard
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard/deposit.html');
        exit();
    } else {
        echo "Incorrect password.";
    }
} else {
    // User doesn't exist, register the user
    $stmt = $pdo->prepare("INSERT INTO users (email, password, time_added) VALUES (?, ?, ?)");
    $stmt->execute([$email, $password, time()]);

    // Log in the new user and redirect to dashboard
    $_SESSION['user_id'] = $pdo->lastInsertId();
    header('Location: dashboard/deposit.html');
    exit();
}
?>
