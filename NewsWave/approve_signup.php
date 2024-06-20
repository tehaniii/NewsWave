<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get action from URL (approve or reject)
$action = $_GET['action'];
$id = $_GET['id'];

if ($action === 'approve') {
    // Retrieve details of the request
    $sql_select = "SELECT * FROM signup_requests WHERE id = '$id'";
    $result = $conn->query($sql_select);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Insert into contributor table
        $sql_insert_contributor = "INSERT INTO contributer (id, first_name, last_name, gender, email, address, nic, dob, contact, username, password) 
            VALUES ('" . $row['id'] . "','" . $row['first_name'] . "', '" . $row['last_name'] . "', '" . $row['gender'] . "', '" . $row['email'] . "', '" . $row['address'] . "', '" . $row['nic'] . "', '" . $row['dob'] . "', '" . $row['contact'] . "', '" . $row['username'] . "', '" . $row['password'] . "')";

        if ($conn->query($sql_insert_contributor) === TRUE) {
            // Delete the approved request
            $sql_delete_request = "DELETE FROM signup_requests WHERE id = '$id'";
            if ($conn->query($sql_delete_request) === TRUE) {
                echo "Contributor registered successfully and request removed.";
            } else {
                echo "Error removing request: " . $conn->error;
            }
        } else {
            echo "Error adding contributor: " . $conn->error;
        }
    } else {
        echo "Error: Request not found";
    }
} elseif ($action === 'reject') {
    // Delete the rejected request
    $sql_delete_request = "DELETE FROM signup_requests WHERE id = '$id'";
    if ($conn->query($sql_delete_request) === TRUE) {
        echo "Request rejected and removed successfully.";
    } else {
        echo "Error rejecting request: " . $conn->error;
    }
} else {
    echo "Invalid action.";
}

$conn->close();
?>
