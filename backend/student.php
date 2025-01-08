<?php
header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "student";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

// Handle POST request (Add student)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room = $_POST['room'] ?? '';
    $name = $_POST['name'] ?? '';
    $rdate = $_POST['rdate'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $city = $_POST['city'] ?? '';
    $fathername = $_POST['fathername'] ?? '';
    $roomtype = $_POST['roomtype'] ?? '';

    // Validate inputs
    if (empty($room) || empty($name) || empty($rdate) || empty($dob) || empty($phone) || empty($city) || empty($fathername) || empty($roomtype)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if (!is_numeric($room) || strlen($phone) != 10) {
        echo json_encode(["success" => false, "message" => "Invalid input data."]);
        exit;
    }

    // Insert data into the database
    $sql = "INSERT INTO detail (room, name, rdate, dob, phone, city, fathername, roomtype)
            VALUES ('$room', '$name', '$rdate', '$dob', '$phone', '$city', '$fathername', '$roomtype')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode([
            "success" => true,
            "message" => "Student added successfully.",
            "student" => [
                "room" => $room,
                "name" => $name,
                "rdate" => $rdate,
                "dob" => $dob,
                "phone" => $phone,
                "city" => $city,
                "fathername" => $fathername,
                "roomtype" => $roomtype,
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Error adding student: " . $conn->error]);
    }
}

// Handle DELETE request (Delete student)
if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);
    $room = $data['room'] ?? '';

    if (empty($room)) {
        echo json_encode(["success" => false, "message" => "Room number is required."]);
        exit;
    }

    $sql = "DELETE FROM detail WHERE room = '$room'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Student deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting student: " . $conn->error]);
    }
}

$conn->close();
?>
