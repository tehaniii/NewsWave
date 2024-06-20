<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get employeeID from URL
$employeeID = $_GET['employeeID'];

// Retrieve details of the approved request
$sql_select = "SELECT * FROM registration_requests WHERE employeeID = '$employeeID'";
$result = $conn->query($sql_select);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Insert into employee table
    $sql_insert_employee = "INSERT INTO employee (employeeID, firstName, lastName, gender, email, address, nic, dob, contact, username, password, role) 
        VALUES ('" . $row['employeeID'] . "','" . $row['firstName'] . "', '" . $row['lastName'] . "', '" . $row['gender'] . "', '" . $row['email'] . "', '" . $row['address'] . "', '" . $row['nic'] . "', '" . $row['dob'] . "', '" . $row['contact'] . "', '" . $row['username'] . "', '" . $row['password'] . "',  '" . $row['role'] . "')";

    if ($conn->query($sql_insert_employee) === TRUE) {
        // Delete the approved request
        $sql_delete_request = "DELETE FROM registration_requests WHERE employeeID = '$employeeID'";
        if ($conn->query($sql_delete_request) === TRUE) {
            echo "Employee registered successfully and request removed.";
        } else {
            echo "Error removing request: " . $conn->error;
        }
    } else {
        echo "Error adding employee: " . $conn->error;
    }
} else {
    echo "Error: Request not found";
}

$conn->close();
?>

