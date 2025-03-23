<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "bluebus";

    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email']; // Add this line
            echo "<script>
                    alert('Login successful!');
                    window.location.href='index.html';
                  </script>";
        } else {
            echo "<script>
                    alert('Invalid password!');
                    window.location.href='login.html';
                  </script>";
        }
    } else {
        echo "<script>
                alert('User not found!');
                window.location.href='login.html';
              </script>";
    }

    mysqli_close($conn);
}
?>