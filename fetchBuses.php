<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="./src/output.css" rel="stylesheet">
  <title>Available Buses - BlueBus</title>
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
  </style>
</head>
<body>
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
        
        <li id="login-item" class="h-[80%] flex items-center transition-all duration-200 cursor-pointer relative group">
          <a href="login.html" class="flex items-center">
            Login
            <div class="w-full h-1 bg-[#0d2b47] transform scale-x-0 group-hover:scale-x-100 absolute bottom-0 top-14 origin-left transition-transform duration-200 rounded-2xl"></div>
          </a>
        </li>

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
  
  <main class="h-screen bg-[#1a4b77] text-2xl text-white ">
    <section class="bg-white h-25"></section>
    <h1 id="availableBusesTitle" class="text-6xl font-bold animate-slideIn" style="padding:20px 40px;">Available Buses</h1>
    <section class="flex items-center" style="padding:20px 40px">
      <div>
        <?php
        if($_SERVER['REQUEST_METHOD']=='POST'){
          // connection
            $servername="localhost";
            $username ="root";
            $password="";
            $database="bluebus";
            $conn=mysqli_connect($servername,$username,$password,$database);
            if(!$conn){
                die("Sorry we failed to connect: ".mysqli_connect_error());
            }
            // else echo "Connection was Successful.<br>";

             // Get and sanitize user input
             $from = mysqli_real_escape_string($conn, $_POST['from']);
             $to = mysqli_real_escape_string($conn, $_POST['to']);

          // display
          $sql = "SELECT * FROM `buslist` WHERE `_from`='$from' AND `_to`='$to'";
          $result = mysqli_query($conn, $sql);
          $num = mysqli_num_rows($result);
          
          if ($num > 0) {
              echo "<div class='animate-slideIn'><br> <b>$num</b> Buses found from <b>$from</b> to <b>$to.</b><br><br></div>";
              echo "<table border='0' cellpadding='8' cellspacing='0' style='border-collapse: collapse;' class=''>";
              echo "<tr>
                      <th>Sno</th>
                      <th>Bus Name</th>
                      <th>Departure Time</th>
                      <th>Arrival Time</th>
                      <th>Fare</th>
                      <th>Status</th>
                    </tr>";
          
              $i = 1;
              while ($row = mysqli_fetch_assoc($result)) {
                  // Make sure you're getting the travel_date from the form submission
                  $travel_date = isset($_POST['travel_date']) ? $_POST['travel_date'] : date('Y-m-d');
                  
                  // When creating the booking form, ensure you're passing the travel_date
                  echo "<tr>
                          <td>{$i}</td>
                          <td>{$row['Name']}</td>
                          <td>{$row['time1']}</td>
                          <td>{$row['time2']}</td>
                          <td>{$row['Price']}</td>
                          <td>
                            <form action='myBooking.php' method='post'>
                                <input type='hidden' name='busName' value='{$row['Name']}'>
                                <input type='hidden' name='price' value='{$row['Price']}'>
                                <input type='hidden' name='time1' value='{$row['time1']}'>
                                <input type='hidden' name='time2' value='{$row['time2']}'>
                                <input type='hidden' name='travel_date' value='{$travel_date}'>
                                <button type='submit' name='book' class='booknow border rounded cursor-pointer' style='padding:10px'>Book Now</button>
                            </form>
                          </td>
                        </tr>";
                  $i++;
              }
          
              echo "</table>";
          } else {
              echo "No buses found.";
          }
  }     
        ?>
      </div>
    </section>
    
    <!-- Removed popup section -->
    
  </main>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      checkLoginStatus();
    });

    function checkLoginStatus() {
      fetch('check_login.php')
        .then(response => response.json())
        .then(data => {
          const loginItem = document.getElementById('login-item');
          const profileDropdown = document.getElementById('profile-dropdown');
          const userNameDisplay = document.getElementById('user-name-display');
          const profileName = document.getElementById('profile-name');
          const profileEmail = document.getElementById('profile-email');

          if (data.loggedIn) {
            loginItem.style.display = 'none';
            profileDropdown.style.display = 'flex';
            userNameDisplay.textContent = data.name.split(' ')[0];
            profileName.textContent = data.name;
            profileEmail.textContent = data.email;
          } else {
            loginItem.style.display = 'flex';
            profileDropdown.style.display = 'none';
          }
        })
        .catch(error => console.error('Error:', error));
    }

    function logout() {
      fetch('logout.php')
        .then(response => response.text())
        .then(() => {
          window.location.href = 'index.html';
        })
        .catch(error => console.error('Error:', error));
    }
  </script>
</body>
</html>
