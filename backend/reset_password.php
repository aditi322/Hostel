<?php
header('Content-Type: application/json');
include 'db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$newPassword = password_hash($data['password'], PASSWORD_BCRYPT);

$sql = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true, 'message' => 'Password reset successful!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Password reset failed.']);
}

mysqli_close($conn);
?>
