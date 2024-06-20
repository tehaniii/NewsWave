<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get employeeID from URL
$employeeID = $_GET['employeeID'];

// Delete the request
$sql_delete = "DELETE FROM registration_requests WHERE employeeID = $employeeID";
if ($conn->query($sql_delete) === TRUE) {
    echo "Request deleted successfully";
} else {
    echo "Error deleting request: " . $conn->error;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reject</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">


</head>
<body>
<header>
        <div class="container">
            <div class="logo">
                <img src="images/logo.png" alt="NewsWave Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="#">Categories</a>
                        <ul>
                            <li><a href="politics.php">Politics</a></li>
                            <li><a href="tech.php">Technology</a></li>
                            <li><a href="entertainment.php">Entertainment</a></li>
                            <li><a href="sports.php">Sports</a></li>
                        </ul>
                    </li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="register_home.php">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <section class="banner">
        <div class="container">
            <h1 style="color:#000";>Request deleted successfully</h1>
</div>
</section>
</body>
</html>