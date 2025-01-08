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

// Add Bill functionality (for POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['billtype'])) {
    $billtype = $_POST['billtype'];  // Bill type
    $amount = $_POST['amount'];      // Amount
    $duedate = $_POST['duedate'];    // Due date

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO bill (billtype, amount, duedate) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $billtype, $amount, $duedate);

    // Execute the query
    if ($stmt->execute()) {
        // Get the last inserted bill ID
        $bill_id = $stmt->insert_id;
        // Send success response with bill ID
        echo json_encode(['success' => true, 'bill_id' => $bill_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding bill']);
    }

    // Close statement and connection
    $stmt->close();
}

// Delete Bill functionality (for POST request with bill_id)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bill_id'])) {
    $bill_id = $_POST['bill_id'];

    // Prepare and bind the DELETE query
    $stmt = $conn->prepare("DELETE FROM bill WHERE id = ?");
    $stmt->bind_param("i", $bill_id);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting bill']);
    }

    // Close statement and connection
    $stmt->close();
}

// Close database connection
$conn->close();
?>
