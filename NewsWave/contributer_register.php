<?php

// Database connection
$conn = new mysqli('localhost', 'root', '', 'newswave');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $nic = $_POST['nic'];
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "INSERT INTO signup_requests (first_name, last_name, gender, email, nic, dob, contact, address, username, password) 
        VALUES ('$firstName', '$lastName', '$gender', '$email',  '$nic', '$dob', '$contact', '$address', '$username', '$password')";

if ($conn->query($sql) === TRUE) {

    // Display success message to user
    echo "<script>alert('Registration successful. Please wait for admin approval.');</script>";
} else {
    // Display error message if registration fails
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();
}   ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <style>

     body {
            margin: 0;
            padding: 0;
            background-image: url('images/background_register.jpg'); 
            background-size: cover;
            background-repeat: no-repeat;
        }

        h2 {
            color:white;
            text-align: center;
            margin-top: 50px;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="radio"] {
            margin-right: 5px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .form-group {
            margin-bottom: 20px;
        }
        footer {
    background-color: #333;
    color: #fff;
    text-align: center;
    padding: 20px 0;
}
.logo img {
    width: 100px;
    margin-left: 20px;
}

nav ul {
    list-style: none;
}

nav ul li {
    display: inline-block;
    margin-right: 20px;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
}

nav ul ul {
    display: none;
    position: absolute;
    background-color: #333;
    padding: 10px;
}

nav ul ul li {
    display: block;
}

nav ul li:hover > ul {
    display: block;
}
header {
    background-color: #333;
    color: #fff;
    padding: 20px 0;
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
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="#">Categories</a>
                        <ul>
                            <li><a href="politics.php">Politics</a></li>
                            <li><a href="tech.php">Technology</a></li>
                            <li><a href="entertainment.php">Entertainment</a></li>
                            <li><a href="sports.php">Sports</a></li>
                        </ul>
                    </li>
                    <li><a href="register_home.php">Register</a></li>
                    <li><a href="login.php">Login</a></li>

                </ul>
            </nav>
        </div>
    </header>
    <h2>Sign Up As a Contributer</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
       
    
        <div class="form-group">
            <label for="firstName">First Name:</label>
            <input type="text" name="firstName" id="firstName" required>
        </div>
        <div class="form-group">
            <label for="lastName">Last Name:</label>
            <input type="text" name="lastName" id="lastName" required>
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <input type="radio" name="gender" id="male" value="m" required>
            <label for="male">Male</label>
            <input type="radio" name="gender" id="female" value="f" required>
            <label for="female">Female</label>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
       
        <div class="form-group">
            <label for="nic">NIC:</label>
            <input type="text" name="nic" id="nic" required>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" id="dob" required>
        </div>
        <div class="form-group">
            <label for="contact">Contact Number:</label>
            <input type="text" name="contact" id="contact" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" name="address" id="address" required>
        </div>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <input type="submit" value="Register">
        <button type="reset">Cancel</button>

    </form>
</body>
</html>
