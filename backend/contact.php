<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if data is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Prepare the SQL query to insert data into the table
    $sql = "INSERT INTO contact (name, email, subject, phone, message) VALUES ('$name', '$email', '$subject', '$phone', '$message')";

    if ($conn->query($sql) === TRUE) {
        // Return success response
        echo json_encode(["status" => "success"]);
    } else {
        // Return failure response
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }

    // Close the database connection
    $conn->close();
}
?>
