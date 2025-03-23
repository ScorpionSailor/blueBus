<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html");
    exit();
}

// Check if ID and action are provided
if (!isset($_GET['id']) || !isset($_GET['action'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$booking_id = $_GET['id'];
$action = $_GET['action'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "bluebus";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update booking status based on action
if ($action == 'cancel') {
    $update_query = "UPDATE bookings SET status = 'Cancelled' WHERE id = $booking_id";
    
    if ($conn->query($update_query) === TRUE) {
        // Success
        header("Location: admin_dashboard.php?success=Booking cancelled successfully");
    } else {
        // Error
        header("Location: admin_dashboard.php?error=Error updating booking: " . $conn->error);
    }
} else {
    header("Location: admin_dashboard.php");
}

$conn->close();
?>