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

// Check if ID is provided
if (!isset($_GET['id'])) {
    header("Location: admin_routes.php");
    exit();
}

$route_id = $_GET['id'];

// Handle form submission for updating route
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_route'])) {
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $bus_type = $_POST['bus_type'];
    $fare = $_POST['fare'];
    $total_seats = $_POST['total_seats'];
    
    $update_query = "UPDATE buses SET 
                    source = '$source', 
                    destination = '$destination', 
                    departure_time = '$departure_time', 
                    arrival_time = '$arrival_time', 
                    bus_type = '$bus_type', 
                    fare = '$fare', 
                    total_seats = '$total_seats' 
                    WHERE id = $route_id";
    
    if ($conn->query($update_query) === TRUE) {
        $success_message = "Route updated successfully!";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

// Fetch route details
$route_query = "SELECT * FROM buses WHERE id = $route_id";
$route_result = $conn->query($route_query);

if ($route_result->num_rows == 0) {
    header("Location: admin_routes.php");
    exit();
}

$route = $route_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Edit Route - BlueBus Admin</title>
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
                <h1 class="text-3xl font-bold text-[#0d2b47]">Edit Route</h1>
                <a href="admin_routes.php" class="text-[#0d2b47] hover:underline">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Routes
                </a>
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
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="source" class="block text-gray-700 mb-2">Source</label>
                        <input type="text" id="source" name="source" value="<?php echo $route['source']; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2b47]" required>
                    </div>
                    
                    <div>
                        <label for="destination" class="block text-gray-700 mb-2">Destination</label>
                        <input type="text" id="destination" name="destination" value="<?php echo $route['destination']; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2b47]" required>
                    </div>
                    
                    <div>
                        <label for="departure_time" class="block text-gray-700 mb-2">Departure Time</label>
                        <input type="time" id="departure_time" name="departure_time" value="<?php echo $route['departure_time']; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2b47]" required>
                    </div>
                    
                    <div>
                        <label for="arrival_time" class="block text-gray-700 mb-2">Arrival Time</label>
                        <input type="time" id="arrival_time" name="arrival_time" value="<?php echo $route['arrival_time']; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2b47]" required>
                    </div>
                    
                    <div>
                        <label for="bus_type" class="block text-gray-700 mb-2">Bus Type</label>
                        <select id="bus_type" name="bus_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2b47]" required>
                            <option value="AC Sleeper" <?php echo ($route['bus_type'] == 'AC Sleeper') ? 'selected' : ''; ?>>AC Sleeper</option>
                            <option value="Non-AC Sleeper" <?php echo ($route['bus_type'] == 'Non-AC Sleeper') ? 'selected' : ''; ?>>Non-AC Sleeper</option>
                            <option value="AC Seater" <?php echo ($route['bus_type'] == 'AC Seater') ? 'selected' : ''; ?>>AC Seater</option>
                            <option value="Non-AC Seater" <?php echo ($route['bus_type'] == 'Non-AC Seater') ? 'selected' : ''; ?>>Non-AC Seater</option>
                            <option value="Volvo" <?php echo ($route['bus_type'] == 'Volvo') ? 'selected' : ''; ?>>Volvo</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="fare" class="block text-gray-700 mb-2">Fare (₹)</label>
                        <input type="number" id="fare" name="fare" value="<?php echo $route['fare']; ?>" min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2b47]" required>
                    </div>
                    
                    <div>
                        <label for="total_seats" class="block text-gray-700 mb-2">Total Seats</label>
                        <input type="number" id="total_seats" name="total_seats" value="<?php echo $route['total_seats']; ?>" min="1" max="60" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2b47]" required>
                    </div>
                    
                    <div class="md:col-span-2 flex justify-end">
                        <a href="admin_routes.php" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors mr-4">
                            Cancel
                        </a>
                        <button type="submit" name="update_route" class="bg-[#0d2b47] text-white px-4 py-2 rounded-lg hover:bg-[#1a4b77] transition-colors">
                            Update Route
                        </button>
                    </div>
                </form>
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