<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['employeeID'])) {
    $employeeID = $_GET['employeeID'];
    $sql = "DELETE FROM employee WHERE employeeID = $employeeID";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error deleting employee: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
