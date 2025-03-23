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
    <link href="./src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>View Booking - BlueBus Admin</title>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header>
        <div class="z-10 flex justify-around text-black font-serif w-[100vw] fixed bg-white h-25 font1" style="animation: slideDown 0.8s ease-out;">
            <!-- logo -->
            <div class="flex flex-col justify-center">
              <div class="w-25">
                <img src="./images/logo.png" alt="" />
              </div>
              <div class="font-bold text-xl text-center text-[#0d2b47] jyu">blueBus Admin</div>
            </div>
      
            <ul class="flex items-center text-[22px] justify-evenly gap-10 text-[#0d2b47]">
              <li class="h-[80%] flex items-center transition-all duration-200 cursor-pointer relative group">
                <a href="admin_dashboard.php" class="flex items-center">
                  Dashboard
                  <div class="w-full h-1 bg-[#0d2b47] transform scale-x-0 group-hover:scale-x-100 absolute bottom-0 top-14 origin-left transition-transform duration-200 rounded-2xl"></div>
                </a>
              </li>
              <li class="h-[80%] flex items-center transition-all duration-200 cursor-pointer relative group">
                <a href="admin_routes.php" class="flex items-center">
                  Manage Routes
                  <div class="w-full h-1 bg-[#0d2b47] transform scale-x-0 group-hover:scale-x-100 absolute bottom-0 top-14 origin-left transition-transform duration-200 rounded-2xl"></div>
                </a>
              </li>
              <li class="h-[80%] flex items-center transition-all duration-200 cursor-pointer relative group">
                <a href="admin_logout.php" class="flex items-center text-red-500">
                  Logout
                  <div class="w-full h-1 bg-red-500 transform scale-x-0 group-hover:scale-x-100 absolute bottom-0 top-14 origin-left transition-transform duration-200 rounded-2xl"></div>
                </a>
              </li>
            </ul>
          </div>
    </header>

    <main class="pt-32 pb-16 px-8">
        <div class="max-w-3xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-[#0d2b47]">Booking Details</h1>
                <a href="admin_dashboard.php" class="text-[#0d2b47] hover:underline">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-[#0d2b47]">Booking #<?php echo $booking['id']; ?></h2>
                        <p class="text-gray-500">Booked on: <?php echo date('d M Y, h:i A', strtotime($booking['booking_date'])); ?></p>
                    </div>
                    <div>
                        <?php
                        $status_class = '';
                        if ($booking['status'] == 'Confirmed') {
                            $status_class = 'bg-green-100 text-green-800';
                        } elseif ($booking['status'] == 'Cancelled') {
                            $status_class = 'bg-red-100 text-red-800';
                        } else {
                            $status_class = 'bg-yellow-100 text-yellow-800';
                        }
                        ?>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold <?php echo $status_class; ?>">
                            <?php echo $booking['status']; ?>
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Passenger Information</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p><span class="font-semibold">Name:</span> <?php echo $booking['user_name']; ?></p>
                            <p><span class="font-semibold">Email:</span> <?php echo $booking['user_email']; ?></p>
                            <p><span class="font-semibold">Phone:</span> <?php echo $booking['user_phone']; ?></p>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Payment Information</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p><span class="font-semibold">Amount:</span> ₹<?php echo $booking['amount']; ?></p>
                            <p><span class="font-semibold">Payment Method:</span> <?php echo $booking['payment_method']; ?></p>
                            <p><span class="font-semibold">Transaction ID:</span> <?php echo $booking['transaction_id']; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Journey Details</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between mb-4">
                            <div>
                                <p class="text-gray-500">From</p>
                                <p class="text-lg font-semibold"><?php echo $booking['source']; ?></p>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 rounded-full bg-[#0d2b47]"></div>
                                <div class="w-20 h-0.5 bg-[#0d2b47]"></div>
                                <div class="w-2 h-2 rounded-full bg-[#0d2b47]"></div>
                            </div>
                            <div>
                                <p class="text-gray-500">To</p>
                                <p class="text-lg font-semibold"><?php echo $booking['destination']; ?></p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-500">Travel Date</p>
                                <p class="font-semibold"><?php echo date('d M Y', strtotime($booking['travel_date'])); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Bus Type</p>
                                <p class="font-semibold"><?php echo $booking['bus_type']; ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Seats</p>
                                <p class="font-semibold"><?php echo $booking['seats']; ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500">Seat Numbers</p>
                                <p class="font-semibold"><?php echo $booking['seat_numbers']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Passenger Details</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2">Name</th>
                                    <th class="text-left py-2">Age</th>
                                    <th class="text-left py-2">Gender</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Assuming passenger details are stored in JSON format
                                if (isset($booking['passenger_details']) && !empty($booking['passenger_details'])) {
                                    $passengers = json_decode($booking['passenger_details'], true);
                                    if (is_array($passengers)) {
                                        foreach ($passengers as $passenger) {
                                ?>
                                <tr class="border-b">
                                    <td class="py-2"><?php echo $passenger['name']; ?></td>
                                    <td class="py-2"><?php echo $passenger['age']; ?></td>
                                    <td class="py-2"><?php echo $passenger['gender']; ?></td>
                                </tr>
                                <?php
                                        }
                                    }
                                } else {
                                ?>
                                <tr>
                                    <td colspan="3" class="py-2 text-center text-gray-500">No passenger details available</td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4">
                    <a href="admin_dashboard.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Back
                    </a>
                    
                    <?php if ($booking['status'] != 'Cancelled') { ?>
                    <a href="admin_update_booking.php?id=<?php echo $booking['id']; ?>&action=cancel" 
                       class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors"
                       onclick="return confirm('Are you sure you want to cancel this booking?')">
                        Cancel Booking
                    </a>
                    <?php } ?>
                    
                    <a href="admin_print_ticket.php?id=<?php echo $booking['id']; ?>" 
                       class="px-4 py-2 bg-[#0d2b47] text-white rounded-lg hover:bg-[#1a4b77] transition-colors"
                       target="_blank">
                        <i class="fas fa-print mr-2"></i> Print Ticket
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-[#1a4b77] text-white mt-8">
        <div class="flex justify-between mx-auto py-8 px-4 border-t border-gray-400 w-[80%]">
          <p>Ⓒ 2025 blueBus India Pvt Ltd. All rights reserved</p>
          <div class="space-x-6">
            <i class="fa-brands fa-facebook-f text-lg text-white"></i>
            <i class="fa-brands fa-linkedin-in text-lg text-white"></i>
            <i class="fa-brands fa-twitter text-lg text-white"></i>
            <i class="fa-brands fa-instagram text-lg text-white"></i>
          </div>
        </div>
    </footer>

    <style>
        .font1 {
          font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
        
        @keyframes slideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</body>
</html>