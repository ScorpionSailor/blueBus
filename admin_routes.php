<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "bluebus";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding new route
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_route'])) {
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $bus_type = $_POST['bus_type'];
    $fare = $_POST['fare'];
    $total_seats = $_POST['total_seats'];
    
    $insert_query = "INSERT INTO buses (source, destination, departure_time, arrival_time, bus_type, fare, total_seats) 
                    VALUES ('$source', '$destination', '$departure_time', '$arrival_time', '$bus_type', '$fare', '$total_seats')";
    
    if ($conn->query($insert_query) === TRUE) {
        $success_message = "New route added successfully!";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

// Handle route deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    $delete_query = "DELETE FROM buses WHERE id = $delete_id";
    
    if ($conn->query($delete_query) === TRUE) {
        $success_message = "Route deleted successfully!";
    } else {
        $error_message = "Error deleting route: " . $conn->error;
    }
}

// Fetch all routes
$routes_query = "SELECT * FROM buses ORDER BY source, destination";
$routes_result = $conn->query($routes_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Manage Routes - BlueBus Admin</title>
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
                  <div class="w-full h-1 bg-[#0d2b47] transform scale-x-100 absolute bottom-0 top-14 origin-left transition-transform duration-200 rounded-2xl"></div>
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
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-[#0d2b47]">Manage Bus Routes</h1>
                <button id="add-route-btn" class="bg-[#0d2b47] text-white px-4 py-2 rounded-lg hover:bg-[#1a4b77] transition-colors">
                    <i class="fas fa-plus mr-2"></i> Add New Route
                </button>
            </div>
            
            <?php if (isset($success_message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <!-- Add Route Form (Hidden by default) -->
            <div id="add-route-form" class="bg-white rounded-lg shadow-md p-6 mb-8 hidden">
                <h2 class="text-xl font-bold text-[#0d2b47] mb-4">Add New Route</h2>
                
                <form action="" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="source" class="block text-gray-700 mb-2">Source</label>
                        <input type="text" id="source" name="source" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2b47]" required>
                    </div>
                    
                    <div>
                        <label for="destination" class="block text-gray-700 mb-2">Destination</label>
                        <input type="text" id="destination" name="destination" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2b47]" required>
                    </div>
                    
                    <div>
                        <label for="departure_time" class="block text-gray-700 mb-2">Departure Time</label>
                        <input type="time" id="departure_time" name="departure_time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2b47]" required>
                    </div>
                    
                    <div>
                        <label for="arrival_time" class="block text-gray-700 mb-2">Arrival Time</label>
                        <input type="time" id="arrival_time" name="arrival_time" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2b47]" required>
                    </div>
                    
                    <div>
                        <label for="bus_type" class="block text-gray-700 mb-2">Bus Type</label>
                        <select id="bus_type" name="bus_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2b47]" required>
                            <option value="AC Sleeper">AC Sleeper</option>
                            <option value="Non-AC Sleeper">Non-AC Sleeper</option>
                            <option value="AC Seater">AC Seater</option>
                            <option value="Non-AC Seater">Non-AC Seater</option>
                            <option value="Volvo">Volvo</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="fare" class="block text-gray-700 mb-2">Fare (₹)</label>
                        <input type="number" id="fare" name="fare" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2b47]" required>
                    </div>
                    
                    <div>
                        <label for="total_seats" class="block text-gray-700 mb-2">Total Seats</label>
                        <input type="number" id="total_seats" name="total_seats" min="1" max="60" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2b47]" required>
                    </div>
                    
                    <div class="md:col-span-2 flex justify-end">
                        <button type="button" id="cancel-btn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors mr-4">
                            Cancel
                        </button>
                        <button type="submit" name="add_route" class="bg-[#0d2b47] text-white px-4 py-2 rounded-lg hover:bg-[#1a4b77] transition-colors">
                            Add Route
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Routes Table -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-[#0d2b47] mb-4">All Routes</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-[#0d2b47] text-white">
                                <th class="py-3 px-4 text-left">ID</th>
                                <th class="py-3 px-4 text-left">Source</th>
                                <th class="py-3 px-4 text-left">Destination</th>
                                <th class="py-3 px-4 text-left">Departure</th>
                                <th class="py-3 px-4 text-left">Arrival</th>
                                <th class="py-3 px-4 text-left">Bus Type</th>
                                <th class="py-3 px-4 text-left">Fare (₹)</th>
                                <th class="py-3 px-4 text-left">Seats</th>
                                <th class="py-3 px-4 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($routes_result->num_rows > 0) {
                                while ($row = $routes_result->fetch_assoc()) {
                            ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4"><?php echo $row['id']; ?></td>
                                    <td class="py-3 px-4"><?php echo $row['source']; ?></td>
                                    <td class="py-3 px-4"><?php echo $row['destination']; ?></td>
                                    <td class="py-3 px-4"><?php echo $row['departure_time']; ?></td>
                                    <td class="py-3 px-4"><?php echo $row['arrival_time']; ?></td>
                                    <td class="py-3 px-4"><?php echo $row['bus_type']; ?></td>
                                    <td class="py-3 px-4"><?php echo $row['fare']; ?></td>
                                    <td class="py-3 px-4"><?php echo $row['total_seats']; ?></td>
                                    <td class="py-3 px-4">
                                        <a href="admin_edit_route.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:text-blue-700 mr-3">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="admin_routes.php?delete_id=<?php echo $row['id']; ?>" 
                                           class="text-red-500 hover:text-red-700"
                                           onclick="return confirm('Are you sure you want to delete this route?')">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="9" class="py-4 px-4 text-center text-gray-500">No routes found</td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
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

    <script>
        // Toggle add route form
        document.getElementById('add-route-btn').addEventListener('click', function() {
            document.getElementById('add-route-form').classList.remove('hidden');
        });
        
        // Cancel button
        document.getElementById('cancel-btn').addEventListener('click', function() {
            document.getElementById('add-route-form').classList.add('hidden');
        });
    </script>
</body>
</html>