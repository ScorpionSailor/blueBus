<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html");
    exit();
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$booking_id = $_GET['id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "bluebus";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch booking details
$booking_query = "SELECT b.*, u.name as user_name, u.email as user_email, u.phone as user_phone 
                 FROM bookings b 
                 JOIN users u ON b.user_id = u.id 
                 WHERE b.id = $booking_id";
$booking_result = $conn->query($booking_query);

if ($booking_result->num_rows == 0) {
    header("Location: admin_dashboard.php");
    exit();
}

$booking = $booking_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Ticket - BlueBus</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .ticket {
            border: 2px solid #0d2b47;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0d2b47;
        }
        
        .ticket-number {
            font-size: 14px;
            color: #666;
        }
        
        .journey-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .location {
            text-align: center;
            flex: 1;
        }
        
        .location h3 {
            margin-bottom: 5px;
            font-size: 18px;
        }
        
        .journey-line {
            display: flex;
            align-items: center;
            flex: 2;
            justify-content: center;
        }
        
        .dot {
            width: 10px;
            height: 10px;
            background-color: #0d2b47;
            border-radius: 50%;
        }
        
        .line {
            height: 2px;
            background-color: #0d2b47;
            flex-grow: 1;
            margin: 0 5px;
        }
        
        .passenger-details, .booking-details {
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        table, th, td {
            border: 1px solid #ddd;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
        }
        
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .barcode {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #ddd;
        }
        
        .print-button {
            text-align: center;
            margin-top: 20px;
        }
        
        .print-button button {
            background-color: #0d2b47;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        @media print {
            .print-button {
                display: none;
            }
            
            body {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <div class="logo">blueBus</div>
            <div class="ticket-number">
                <div>Booking ID: #<?php echo $booking['id']; ?></div>
                <div>Date: <?php echo date('d M Y', strtotime($booking['booking_date'])); ?></div>
            </div>
        </div>
        
        <div class="journey-details">
            <div class="location">
                <p>From</p>
                <h3><?php echo $booking['source']; ?></h3>
            </div>
            
            <div class="journey-line">
                <div class="dot"></div>
                <div class="line"></div>
                <div class="dot"></div>
            </div>
            
            <div class="location">
                <p>To</p>
                <h3><?php echo $booking['destination']; ?></h3>
            </div>
        </div>
        
        <div class="booking-details">
            <h3>Journey Details</h3>
            <table>
                <tr>
                    <th>Travel Date</th>
                    <td><?php echo date('d M Y', strtotime($booking['travel_date'])); ?></td>
                    <th>Bus Type</th>
                    <td><?php echo $booking['bus_type']; ?></td>
                </tr>
                <tr>
                    <th>Seats</th>
                    <td><?php echo $booking['seats']; ?></td>
                    <th>Seat Numbers</th>
                    <td><?php echo $booking['seat_numbers']; ?></td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td>₹<?php echo $booking['amount']; ?></td>
                    <th>Status</th>
                    <td>
                        <?php
                        $status_class = '';
                        if ($booking['status'] == 'Confirmed') {
                            $status_class = 'confirmed';
                        } elseif ($booking['status'] == 'Cancelled') {
                            $status_class = 'cancelled';
                        } else {
                            $status_class = 'pending';
                        }
                        ?>
                        <span class="status <?php echo $status_class; ?>"><?php echo $booking['status']; ?></span>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="passenger-details">
            <h3>Passenger Information</h3>
            <table>
                <tr>
                    <th>Name</th>
                    <td><?php echo $booking['user_name']; ?></td>
                    <th>Email</th>
                    <td><?php echo $booking['user_email']; ?></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><?php echo $booking['user_phone']; ?></td>
                    <th>Payment Method</th>
                    <td><?php echo $booking['payment_method']; ?></td>
                </tr>
            </table>
            
            <?php if (isset($booking['passenger_details']) && !empty($booking['passenger_details'])) { ?>
            <h3>Passenger List</h3>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $passengers = json_decode($booking['passenger_details'], true);
                    if (is_array($passengers)) {
                        foreach ($passengers as $passenger) {
                    ?>
                    <tr>
                        <td><?php echo $passenger['name']; ?></td>
                        <td><?php echo $passenger['age']; ?></td>
                        <td><?php echo $passenger['gender']; ?></td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
        
        <div class="barcode">
            <p>Scan the QR code at the boarding point</p>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=BLUEBUS-TICKET-<?php echo $booking['id']; ?>" alt="QR Code">
            <p>Thank you for choosing blueBus!</p>
        </div>
    </div>
    
    <div class="print-button">
        <button onclick="window.print()">Print Ticket</button>
    </div>
</body>
</html>