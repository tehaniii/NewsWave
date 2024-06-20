<?php
// Start session
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle approve/reject requests for employee registration
if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    if ($_GET['action'] == 'approve_employee') {
        // Approve employee - move from registration_requests to employee table
        $sql_approve = "INSERT INTO employee (firstName, lastName, gender, email, employeeID, role, nic, dob, contact, address, username, password) 
                        SELECT firstName, lastName, gender, email, employeeID, role, nic, dob, contact, address, username, password 
                        FROM registration_requests WHERE employeeID = ?";
        $stmt = $conn->prepare($sql_approve);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Remove from registration_requests
        $sql_delete_request = "DELETE FROM registration_requests WHERE employeeID = ?";
        $stmt = $conn->prepare($sql_delete_request);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($_GET['action'] == 'reject_employee') {
        // Reject employee - simply remove from registration_requests
        $sql_delete_request = "DELETE FROM registration_requests WHERE employeeID = ?";
        $stmt = $conn->prepare($sql_delete_request);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: admin.php"); // Redirect back to admin page after action
    exit();
}

// Handle delete employee requests
if (isset($_GET['delete_employee_id'])) {
    $id = intval($_GET['delete_employee_id']);
    $sql_delete_employee = "DELETE FROM employee WHERE employeeID = ?";
    $stmt = $conn->prepare($sql_delete_employee);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin.php"); // Redirect back to admin page after deletion
    exit();
}

// Query registration requests for employees
$sql_registration_employee = "SELECT * FROM registration_requests WHERE role = 'employee'";
$result_registration_employee = $conn->query($sql_registration_employee);

// Query employees
$sql_employees = "SELECT * FROM employee";
$result_employees = $conn->query($sql_employees);

?>
