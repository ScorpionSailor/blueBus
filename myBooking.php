<?php
session_start();

// Add these at the top of the file
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('Please login to book tickets!');
            window.location.href='login.html';
          </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "bluebus";
    
    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // In your booking processing section
    if(isset($_POST['book'])) {
        // Get booking details
        $busName = mysqli_real_escape_string($conn, $_POST['busName']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $time1 = mysqli_real_escape_string($conn, $_POST['time1']);
        $time2 = mysqli_real_escape_string($conn, $_POST['time2']);
        
        // Explicitly handle the travel date
        $travel_date = isset($_POST['travel_date']) ? mysqli_real_escape_string($conn, $_POST['travel_date']) : date('Y-m-d');
        
        $status = "Confirmed"; // Directly set as confirmed
        $user_id = $_SESSION['user_id'];
        $user_name = $_SESSION['user_name'];
        $user_email = $_SESSION['user_email'];
        $booking_date = date('Y-m-d H:i:s'); // Current date and time
    
        // For debugging (uncomment if needed)
        // echo "Travel date: " . $travel_date; exit;
    
        // Insert the booking into the database with corrected column names
        $sql = "INSERT INTO mybookings (name, email, bus_name, price, arrival_time, daparture_time, booking_date, travel_date, status) 
                VALUES ('$user_name', '$user_email', '$busName', '$price', '$time2', '$time1', '$booking_date', '$travel_date', '$status')";

        if (mysqli_query($conn, $sql)) {
            // Get the booking ID for the confirmation email
            $booking_id = mysqli_insert_id($conn);
            
            // Format dates for email
            $formatted_travel_date = date('d M Y', strtotime($travel_date));
            $formatted_booking_date = date('d M Y, h:i A', strtotime($booking_date));
            
            // Send email using PHPMailer - removed the require and use statements from here
            $mail = new PHPMailer(true);
            
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Replace with your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'studytime.rk25@gmail.com'; // Replace with your email
                $mail->Password = 'swim emnv knaa uttx'; // Replace with your app password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                
                // Recipients
                $mail->setFrom('noreply@bluebus.com', 'BlueBus');
                $mail->addAddress($user_email, $user_name);
                
                // Content
                $mail->isHTML(true);
                $mail->Subject = "BlueBus - Booking Confirmation #$booking_id";
                
                // Email body
                $mail->Body = "
                <html>
                <head>
                    <title>Booking Confirmation</title>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                        .header { background-color: #0d2b47; color: white; padding: 15px; text-align: center; }
                        .content { padding: 20px; border: 1px solid #ddd; }
                        .booking-details { background-color: #f9f9f9; padding: 15px; margin: 15px 0; }
                        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #777; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h1>Booking Confirmation</h1>
                        </div>
                        <div class='content'>
                            <p>Dear $user_name,</p>
                            <p>Thank you for booking with BlueBus. Your booking has been confirmed!</p>
                            
                            <div class='booking-details'>
                                <h2>Booking Details</h2>
                                <p><strong>Booking ID:</strong> #$booking_id</p>
                                <p><strong>Bus Name:</strong> $busName</p>
                                <p><strong>Departure Time:</strong> $time1</p>
                                <p><strong>Arrival Time:</strong> $time2</p>
                                <p><strong>Travel Date:</strong> $formatted_travel_date</p>
                                <p><strong>Fare:</strong> ₹$price</p>
                                <p><strong>Booking Date:</strong> $formatted_booking_date</p>
                                <p><strong>Status:</strong> $status</p>
                            </div>
                            
                            <p>You can view your booking details and manage your bookings by visiting the <a href='http://localhost/blueBus/showbookings.php'>My Bookings</a> section on our website.</p>
                            
                            <p>We wish you a pleasant journey!</p>
                            
                            <p>Best Regards,<br>The BlueBus Team</p>
                        </div>
                        <div class='footer'>
                            <p>This is an automated email. Please do not reply to this message.</p>
                            <p>&copy; 2023 BlueBus. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
                
                $mail->send();
                $email_status = "Booking confirmation email sent.";
            } catch (Exception $e) {
                $email_status = "Email could not be sent. Error: {$mail->ErrorInfo}";
            }
            
            // Just show an alert and redirect to showbookings.php
            echo "<script>
                    alert('Booking successful! $email_status');
                    window.location.href = 'showbookings.php';
                  </script>";
        } else {
            // Show the specific MySQL error
            echo "<script>
                    alert('Error in booking: " . mysqli_error($conn) . "');
                    console.error('MySQL Error: " . mysqli_error($conn) . "');
                    window.location.href = 'fetchBuses.php';
                  </script>";
        }
    }

    mysqli_close($conn);
}
?>