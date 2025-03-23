<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "bluebus";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get form data and sanitize
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert data into contact table
    $sql = "INSERT INTO `contact_us` (`name`, `email`, `subject`, `message`) VALUES ('$name', '$email', '$subject', '$message')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Thank you for contacting us! We will get back to you soon.');
                window.location.href='contact.html';
              </script>";
    } else {
        // Add error logging to see what's wrong
        echo "<script>
                alert('Error: " . mysqli_error($conn) . "');
                window.location.href='contact.html';
              </script>";
    }

    mysqli_close($conn);
}
?>