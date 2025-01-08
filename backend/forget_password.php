<?php
header('Content-Type: application/json');
include 'db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$emailOrPhone = $data['email_or_phone'];

// Verify if the user exists
$sql = "SELECT * FROM users WHERE email = '$emailOrPhone' OR phone = '$emailOrPhone'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $otp = rand(100000, 999999); // Generate OTP
    echo json_encode(['success' => true, 'otp' => $otp, 'message' => 'OTP sent successfully!']);
    // Store OTP in session or send via email/SMS (Not implemented here)
} else {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
}

mysqli_close($conn);
?>
