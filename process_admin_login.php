<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "bluebus";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$username = $_POST['username'];
$password = $_POST['password'];

// Validate data
if (empty($username) || empty($password)) {
    header("Location: admin_login.html?login_error=emptyfields");
    exit();
}

// Check if username exists and password is correct
$login_query = "SELECT * FROM admins WHERE username = ?";
$login_stmt = $conn->prepare($login_query);
$login_stmt->bind_param("s", $username);
$login_stmt->execute();
$result = $login_stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $password_check = password_verify($password, $row['password']);
    
    if ($password_check) {
        // Set session variables
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_username'] = $row['username'];
        $_SESSION['admin_email'] = $row['email'];
        
        // Redirect to admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        header("Location: admin_login.html?login_error=wrongpassword");
        exit();
    }
} else {
    header("Location: admin_login.html?login_error=nouser");
    exit();
}

$conn->close();
?>