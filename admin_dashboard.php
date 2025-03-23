<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="./src/output.css" rel="stylesheet">
  <title>All bookings - BlueBus</title>
</head>

<body>
  <style>
    .font1 {
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }

    .font2 {
      font-family: 'Courier New', Courier, monospace;
    }

    /* Enhanced Table Styling */
    table {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(8px);
      /* border-radius: 16px; */
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.1);
      width: 400;
      margin-top: 20px;
      animation: slideUp 0.6s ease-out;
    }

    table td,
    table th {
      padding: 20px;
      text-align: left;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    table th {
      background: rgba(13, 43, 71, 0.9);
      color: #ffffff;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    table tr {
      transition: all 0.3s ease;
    }

    table tr:hover {
      background: rgba(255, 255, 255, 0.15);
      transform: translateY(-2px);
    }

    .booknow {
      background: #0D2B47;
      color: white;
      padding: 12px 24px;
      border-radius: 8px;
      transition: all 0.3s ease;
      border: none;
      font-weight: 500;
      width: 160px;
      /* Fixed width */

      text-align: center;
    }

    .booknow:hover {
      background: #1a4b77;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .result-text {
      font-size: 1.2rem;
      margin-bottom: 1rem;
      color: #ffffff;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(-100%);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .animate-slideIn {
      animation: slideIn 0.5s ease-out forwards;
    }
    
    /* Profile dropdown styles */
    .profile-menu {
      position: relative;
      cursor: pointer;
    }

    .profile-content {
      visibility: hidden;
      opacity: 0;
      position: absolute;
      right: 0;
      top: 120%;
      min-width: 250px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 1rem;
      z-index: 1000;
      transition: all 0.3s ease;
      transform: translateY(10px);
    }

    .profile-menu:hover .profile-content {
      visibility: visible;
      opacity: 1;
      transform: translateY(0);
    }

    .user-info-box {
      padding: 12px;
      border-bottom: 1px solid #eee;
      margin-bottom: 8px;
    }

    .profile-link {
      display: block;
      padding: 10px 16px;
      color: #0d2b47;
      text-decoration: none;
      transition: all 0.2s ease;
      border-radius: 6px;
      margin: 4px 0;
    }

    .profile-link:hover {
      background: #f5f5f5;
    }

    .profile-icon {
      font-size: 24px;
    }
  </style>
  <header>
    <div class="z-10 flex justify-around text-black font-serif w-[100vw] fixed bg-white h-25 font1">
      <!-- logo -->
      <div class="flex flex-col justify-center">
        <div class="w-25">
          <img src="./images/logo.png" alt="" />
        </div>
        <div class="font-bold text-xl text-center text-[#0d2b47] jyu">blueBus</div>
      </div>

      <ul class="flex items-center text-[22px] justify-evenly gap-10 text-[#0d2b47]">
        <li class="h-[80%] flex items-center transition-all duration-200 cursor-pointer relative group">
          <a href="index.html" class="flex items-center">
            Home
            <div
              class="w-full h-1 bg-[#0d2b47] transform scale-x-0 group-hover:scale-x-100 absolute bottom-0 top-14 origin-left transition-transform duration-200 rounded-2xl">
            </div>
          </a>
        </li>
        <li class="h-[80%] flex items-center transition-all duration-200 cursor-pointer relative group">
          <a href="about.html" class="flex items-center">
            About
            <div
              class="w-full h-1 bg-[#0d2b47] transform scale-x-0 group-hover:scale-x-100 absolute bottom-0 top-14 origin-left transition-transform duration-200 rounded-2xl">
            </div>
          </a>
        </li>
        
        <li class="h-[80%] flex items-center transition-all duration-200 cursor-pointer relative group">
          <a href="contact.html" class="flex items-center">
            Contact
            <div
              class="w-full h-1 bg-[#0d2b47] transform scale-x-0 group-hover:scale-x-100 absolute bottom-0 top-14 origin-left transition-transform duration-200 rounded-2xl">
            </div>
          </a>
        </li>
       
        <!-- Login item (will be hidden when logged in) -->
        <li id="login-item" class="h-[80%] flex items-center transition-all duration-200 cursor-pointer relative group">
          <a href="login.html" class="flex items-center">
            Login
            <div
              class="w-full h-1 bg-[#0d2b47] transform scale-x-0 group-hover:scale-x-100 absolute bottom-0 top-14 origin-left transition-transform duration-200 rounded-2xl">
            </div>
          </a>
        </li>
        
        <!-- Profile dropdown (will be shown when logged in) -->
        <li id="profile-dropdown" class="h-[80%] hidden items-center transition-all duration-200 cursor-pointer relative group">
          <div class="profile-menu">
            <button class="flex items-center gap-2 text-[#0d2b47] px-4 py-2 rounded-lg">
              <span class="profile-icon">👤</span>
              <span id="user-name-display" class="text-lg"></span>
            </button>
            <div class="profile-content">
              <div class="user-info-box">
                <p id="profile-name" class="font-semibold"></p>
                <p id="profile-email" class="text-sm text-gray-600"></p>
              </div>
              <a href="showbookings.php" class="profile-link">My Bookings</a>
              <a href="#" onclick="logout()" class="profile-link text-red-500">Logout</a>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </header>
  <main class="min-h-screen bg-[#1a4b77] text-2xl text-white ">
    <section class="bg-white h-25"></section>
    <h1 id="availableBusesTitle" class="text-6xl font-bold animate-slideIn" style="padding:20px 40px;">All Bookings</h1>
    <section class="flex items-center" style="padding:20px 40px">
      <div>
        <?php
          // connection
          $servername="localhost";
          $username ="root";
          $password="";
          $database="bluebus";
          $conn=mysqli_connect($servername,$username,$password,$database);
          if(!$conn){
              die("Sorry we failed to connect: ".mysqli_connect_error());
          }

          // display
          $sql = "SELECT * FROM `mybookings` WHERE name!=''";
          $result = mysqli_query($conn, $sql);
          $num = mysqli_num_rows($result);
          
          if ($num > 0) {
              echo "<div class='animate-slideIn'><br> <b>$num</b> bookings found.<br><br></div>";
              echo "<table border='0' cellpadding='8' cellspacing='0' style='border-collapse: collapse;' class=''>";
              echo "<tr>
                      <th>Sno</th>
                      <th>UserName</th>
                      <th>Bus Name</th>
                      <th>Departure Time</th>
                      <th>Arrival Time</th>
                      <th>Fare</th>
                      <th>Travel Date</th>
                      <th>Booking Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>";
          
              $i = 1;
              while ($row = mysqli_fetch_assoc($result)) {
                  // Format the dates for better display
                  $booking_date = !empty($row['booking_date']) ? date('d M Y, h:i A', strtotime($row['booking_date'])) : 'N/A';
                  
                  // Check if travel_date exists and is valid before formatting
                  $travel_date = !empty($row['travel_date']) && $row['travel_date'] != '0000-00-00' 
                      ? date('d M Y', strtotime($row['travel_date'])) 
                      : 'N/A';
                  
                  echo "<tr>
                          <td>{$i}</td>
                          <td>{$row['name']}</td>
                          <td>{$row['bus_name']}</td>
                          <td>{$row['daparture_time']}</td>
                          <td>{$row['arrival_time']}</td>
                          <td>{$row['price']}</td>
                          <td>{$travel_date}</td>
                          <td>{$booking_date}</td>
                          <td>{$row['status']}</td>
                          <td>
                            <form action='cancelBooking.php' method='post'>
                              <input type='hidden' name='booking_id' value='{$row['id']}'>
                              <button type='submit' name='cancel' class='booknow' style='background-color: #e53e3e; width: 120px;'>Cancel</button>
                            </form>
                          </td>
                        </tr>";
                  $i++;
              }
          
              echo "</table>";
          } else {
              echo "<div class='animate-slideIn'>No bookings found.</div>";
          }
          
          mysqli_close($conn);
        ?>
      </div>
    </section>
    
  </main>

  <script src="script.js"></script>
</body>
</html>
