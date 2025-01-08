<?php
header('Content-Type: application/json');
include 'db_connection.php'; // Include database connection

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$email = $data['email'];
$phone = $data['phone'];
$password = password_hash($data['password'], PASSWORD_BCRYPT); // Hash the password

$sql = "INSERT INTO users (username, email, phone, password) VALUES ('$username', '$email', '$phone', '$password')";
if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true, 'message' => 'Signup successful!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Signup failed. User might already exist.']);
}

mysqli_close($conn);
?>
