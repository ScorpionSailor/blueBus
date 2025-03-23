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
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate data
if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    header("Location: admin_login.html?signup_error=emptyfields");
    exit();
}

if ($password !== $confirm_password) {
    header("Location: admin_login.html?signup_error=passwordmismatch");
    exit();
}

// First, check if the admins table exists, if not create it
$check_table_query = "SHOW TABLES LIKE 'admins'";
$table_result = $conn->query($check_table_query);

if ($table_result->num_rows == 0) {
    // Table doesn't exist, create it
    $create_table_query = "CREATE TABLE admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (!$conn->query($create_table_query)) {
        die("Error creating table: " . $conn->error);
    }
} else {
    // Table exists, check if email column exists
    $check_column_query = "SHOW COLUMNS FROM admins LIKE 'email'";
    $column_result = $conn->query($check_column_query);
    
    if ($column_result->num_rows == 0) {
        // Email column doesn't exist, add it
        $add_column_query = "ALTER TABLE admins ADD email VARCHAR(100) NOT NULL UNIQUE";
        if (!$conn->query($add_column_query)) {
            die("Error adding email column: " . $conn->error);
        }
    }
}

// Check if username or email already exists
$check_query = "SELECT * FROM admins WHERE username = ? OR email = ?";
$check_stmt = $conn->prepare($check_query);

if ($check_stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$check_stmt->bind_param("ss", $username, $email);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['username'] === $username) {
        header("Location: admin_login.html?signup_error=usernametaken");
        exit();
    } else {
        header("Location: admin_login.html?signup_error=emailtaken");
        exit();
    }
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new admin
$insert_query = "INSERT INTO admins (username, email, password) VALUES (?, ?, ?)";
$insert_stmt = $conn->prepare($insert_query);

if ($insert_stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$insert_stmt->bind_param("sss", $username, $email, $hashed_password);

if ($insert_stmt->execute()) {
    // Redirect to login page with success message
    header("Location: admin_login.html?signup=success");
    exit();
} else {
    header("Location: admin_login.html?signup_error=sqlerror");
    exit();
}

$conn->close();
?>