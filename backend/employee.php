<?php
header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

// Handle POST request (Add Employee)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';

    if (empty($name) || empty($role)) {
        echo json_encode(["success" => false, "message" => "Name and role are required."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO employee (name, role) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $role);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "employee" => ["id" => $conn->insert_id, "name" => $name, "role" => $role]]);
    } else {
        echo json_encode(["success" => false, "message" => "Error adding employee: " . $stmt->error]);
    }
    $stmt->close();
}

// Handle DELETE request (Delete Employee)
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = isset($data['id']) ? intval($data['id']) : 0;

    if ($id <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid employee ID."]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM employee WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Employee deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error deleting employee: " . $stmt->error]);
    }
    $stmt->close();
}

// Handle GET request (Fetch Employees)
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $result = $conn->query("SELECT id, name, role FROM employee");

    if ($result->num_rows > 0) {
        $employees = [];
        while ($row = $result->fetch_assoc()) {
            $employees[] = $row;
        }
        echo json_encode(["success" => true, "employees" => $employees]);
    } else {
        echo json_encode(["success" => false, "message" => "No employees found."]);
    }
}

$conn->close();
?>
