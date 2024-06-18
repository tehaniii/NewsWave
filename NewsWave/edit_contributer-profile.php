<?php
session_start();

// Redirect to login page if user is not logged in or is not a contributor
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'contributer') {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

// Fetch contributor data from the database
$sql = "SELECT first_name, last_name, email, address, nic, dob, contact, username, password FROM contributer WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission for updating contributor data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    // Avoid updating contact number
    $contact = $user['contact']; // Use existing contact number
    $address = $_POST['address'];
    $password = $_POST['password'];
    $nic = $_POST['nic'];
    $dob = $_POST['dob'];

    // Update contributor data in the database
    $sql = "UPDATE contributer SET first_name = ?, last_name = ?, email = ?, address = ?, password = ?, nic = ?, dob = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $firstName, $lastName, $email, $address, $password, $nic, $dob, $username);

    // Execute the update query
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            // Update the user data for immediate display
            $user['first_name'] = $firstName;
            $user['last_name'] = $lastName;
            $user['email'] = $email;
            $user['address'] = $address;
            $user['nic'] = $nic;
            $user['dob'] = $dob;
            $user['password'] = $password;
        } else {
            echo "No rows affected. Make sure you are actually updating the profile.";
        }
    } else {
        echo "Error executing update query: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 0 auto;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .logo img {
            max-width: 150px;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
            margin-top: 10px;
        }
        nav ul li {
            display: inline;
            margin-right: 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }
        .edit-container {
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
        }
        .edit-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-display, .edit-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 20px auto;
        }
        .profile-display h2, .edit-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .profile-table th, .profile-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .profile-table th {
            background-color: #f2f2f2;
        }
        .profile-table button {
            padding: 8px 15px;
            border: none;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
        }
        .profile-table button:hover {
            background-color: #0056b3;
        }
        .edit-form form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .edit-form .form-group {
            width: 100%;
            margin-bottom: 15px;
        }
        .edit-form label {
            display: block;
            margin-bottom: 5px;
        }
        .edit-form input[type="text"],
        .edit-form input[type="email"],
        .edit-form input[type="password"],
        .edit-form input[type="date"] {
            width: calc(100% - 12px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .edit-form input[type="submit"],
        .edit-form button {
            width: 50%;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #dc3545;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .edit-form input[type="submit"]:hover,
        .edit-form button:hover {
            background-color: #45a049;
        }
        @media (max-width: 768px) {
            .container {
                width: 100%;
                padding: 0 10px;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <div class="logo">
            <img src="images/logo.png" alt="NewsWave Logo">
        </div>
        <nav>
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="news_create.php">Create</a></li>
                <li><a href="view.php">View</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container">
    <div class="edit-container">
        <div class="profile-display">
            <h2>Profile Information</h2>
            <table class="profile-table">
                <tr>
                    <th>First Name</th>
                    <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>
                <tr>
                    <th>Contact Number</th>
                    <td><?php echo htmlspecialchars($user['contact']); ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php echo htmlspecialchars($user['address']); ?></td>
                </tr>
                <tr>
                    <th>NIC</th>
                    <td><?php echo htmlspecialchars($user['nic']); ?></td>
                </tr>
                <tr>
                    <th>Date of Birth</th>
                    <td><?php echo htmlspecialchars($user['dob']); ?></td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td><?php echo htmlspecialchars($user['password']); ?></td>
                </tr>
            </table>
            <button onclick="showEditForm()" style="background-color: #dc3545; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; transition: background-color 0.3s ease; margin-top: 20px; margin-right: 100px;">Edit Profile</button>
            </div>

        <div class="edit-form" id="edit-form" style="display: none;">
            <h2>Edit Profile</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="firstName">FirstName:</label>
                    <input type="text" name="firstName" id="firstName" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" name="lastName" id="lastName" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="contact">Contact Number:</label>
                    <input type="text" name="contact" id="contact" value="<?php echo htmlspecialchars($user['contact']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="nic">NIC:</label>
                    <input type="text" name="nic" id="nic" value="<?php echo htmlspecialchars($user['nic']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($user['password']); ?>" required>
                </div>
                <input type="submit" value="Update Profile">
                <button type="button" onclick="hideEditForm()">Cancel</button>
            </form>
        </div>
    </div>
</div>

<script>
    function showEditForm() {
        var editForm = document.getElementById("edit-form");
        editForm.style.display = "block";
        var profileDisplay = document.querySelector(".profile-display");
        profileDisplay.style.display = "none";
    }

    function hideEditForm() {
        var editForm = document.getElementById("edit-form");
        editForm.style.display = "none";
        var profileDisplay = document.querySelector(".profile-display");
        profileDisplay.style.display = "block";
    }
</script>

</body>
</html>

