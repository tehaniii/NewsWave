<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'newswave');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare SQL statement to check login credentials for contributors
        $sql = "SELECT * FROM contributer WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if login is successful
        if ($result->num_rows == 1) {
            // Set session variables
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = 'contributer';

            // Redirect to the contributor profile edit page
            header("Location: edit_contributer-profile.php");
            exit();
        } else {
            echo "Login failed. Please check your username and password.";
        }

        // Close statement and connection
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NewsWave</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f3f3f3;
        }
        .banner {
            background-image: url('images/banner.jpg');
            background-size: cover;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }
        form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: orange;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #e68a00;
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
                <li><a href="categories.php">Explore News</a></li>
                <li><a href="register_home.php">Register</a></li>
                <li><a href="login.php">Login</a>
                    <ul>
                        <li><a href="adminlogin.php">Admin</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</header>
<section class="banner">
    <div class="container">
        <h1>Login here</h1>
        <p>Don't have an account? <a href="register_home.php">Register here</a></p>
    </div>
</section>
<br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <label for="user_type">User Type:</label><br>
    <select id="user_type" name="user_type">
        <option value="contributer">Contributor</option>
    </select><br><br>

    <input type="submit" value="Login">
</form>
</body>
</html>
