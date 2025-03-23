<?php
session_start();

if(isset($_POST['cancel'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "bluebus";
    
    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $booking_id = mysqli_real_escape_string($conn, $_POST['booking_id']);
    
    $sql = "DELETE FROM mybookings WHERE id = '$booking_id'";
    
    if(mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Booking cancelled successfully!');
                window.location.href='showbookings.php';
              </script>";
    } else {
        echo "<script>
                alert('Error cancelling booking: " . mysqli_error($conn) . "');
                window.location.href='showbookings.php';
              </script>";
    }

    mysqli_close($conn);
}
?>